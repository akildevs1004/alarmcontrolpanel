<?php

namespace App\Http\Controllers\Customers\Api;


use Illuminate\Support\Facades\Log as Logger;
use App\Http\Controllers\Controller;
use App\Http\Controllers\WhatsappController;
use App\Mail\DbBackupMail;
use App\Mail\EmailContentDefault;
use App\Mail\ReportNotificationMail;
use App\Models\AlarmDeviceTemperatureLogs;
use App\Models\AlarmEvents;
use App\Models\AlarmLogs;
use Illuminate\Http\Request;
use App\Models\Community\AttendanceLog;
use App\Models\Company;
use App\Models\Deivices\DeviceZones;
use App\Models\Device;
use App\Models\DeviceNotificationsManagers;
use App\Models\ReportNotification;
use App\Models\ReportNotificationLogs;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ApiAlarmDeviceSensorLogsController extends Controller
{

    public function verifyHeartbeat()
    {
        $datetime = date("Y-m-d H:i:s", strtotime("-60 minutes"));
        $query1 = Device::query()

            ->where(function ($query) use ($datetime) {

                $query->where('last_live_datetime', '<=', $datetime);
                $query->orWhere('last_live_datetime',    null);
            })
            ->update(["status_id" => 2]);
    }
    public function getCSVFileLines($date)
    {


        $csvPath = "app/alarm-sensors/sensor-logs-$date.csv"; // The path to the file relative to the "Storage" folder

        $fullPath = storage_path($csvPath);

        if (!file_exists($fullPath)) {
            return ["error" => true, "message" => "File doest not exist on $date."];
        }

        $file = fopen($fullPath, 'r');

        $data = file($fullPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!count($data)) {
            return ["error" => true, "message" => 'File is empty.'];
        }



        $previoulyAddedLineNumbers =  Storage::get(("alarm-sensors/sensor-logs-count-$date.txt")) ?? 0;

        // return $this->getMeta("Sync Attenance Logs", $previoulyAddedLineNumbers . "\n");

        $totalLines = count($data);

        $currentLength = 0;

        if ($previoulyAddedLineNumbers == $totalLines) {
            return ["error" => true, "message" => 'No new data found.'];
        } else if ($previoulyAddedLineNumbers > 0 && $totalLines > 0) {
            $currentLength = $previoulyAddedLineNumbers;
        }

        fclose($file);

        $result = [
            "date" => $date,
            "totalLines" => $totalLines,
            "data" => array_slice($data, $currentLength)

        ];

        if (array_key_exists("error", $result)) {
            return $this->getMeta("Sync Attenance Logs", $result["message"] . "\n");
        }

        $result["data"] = array_values(array_unique($result["data"]));

        return $result;
    }
    public function readCSVLogFile()
    {
        $date = date("d-m-Y");
        $results = $this->getCSVFileLines($date);

        $records = [];

        $message = [];
        if (isset($results["data"])) {
            foreach ($results["data"] as $row) {


                $columns = explode(',', $row);


                $serial_number = $columns[0];
                $event = $columns[1];
                $log_time = $columns[2];

                // $unique_code = $columns[3];
                // $unique_code = $columns[3];

                $zone = '';
                $area = '';
                if (isset($columns[4])) {

                    $area =  $columns[4];
                    $zone =   $columns[5];
                }




                $alarm_type = '';

                //3401 00 000 / HOME 


                //-----------Alarm Control panel - Wifi Model 

                if ($event == 'HEARTBEAT') {
                    Device::where("serial_number", $serial_number)->update(
                        ["status_id" => 1, "last_live_datetime" => $log_time]
                    );
                    $message[] = $this->getMeta("Device HeartBeat", $log_time . "\n");
                } else if ($event == '1407' || $event == '1401') //disarm button  // 1401,000=device //1407=remote
                {
                    Device::where("serial_number", $serial_number)->update(["alarm_status" => 0, "alarm_end_datetime" => $log_time, "armed_status" => 0, "armed_datetime" => $log_time]);
                    $this->endAllAlarmsBySerialNumber($serial_number, $log_time);
                    $message[] = $this->getMeta("Device Disarmed", $log_time . "\n");


                    $device = Device::where("serial_number", $serial_number)->first();
                    (new ApiAlarmDeviceTemperatureLogsController)->createAlarmEventsJsonFile($device->company_id);
                } else if ($event == '3407' || $event == '3401') //armed button   //device=3401,000 //3407,001=remote
                {
                    Device::where("serial_number", $serial_number)->update(["armed_status" => 1, "armed_datetime" => $log_time]);

                    $message[] = $this->getMeta("Device Armed", $log_time . "\n");
                } else if ($zone != '')   //zone verification button
                {
                    //$zone = substr($event, 1);

                    $devices = DeviceZones::with(['device'])
                        ->whereHas('device', function ($query) use ($serial_number) {
                            $query->where('serial_number', $serial_number);
                        })
                        ->where("zone_code", $zone)
                        ->where("area_code", $area)
                        ->first();

                    $alarm_type = $devices->sensor_name ?? '';
                    //$area =   $devices->area_code ?? '';


                    $count = AlarmLogs::where("serial_number", $serial_number)->where("log_time", $log_time)->where("zone", $zone)->where("area", $area)->count();
                    if ($count == 0) {
                        $records  = [
                            "serial_number" => $serial_number,
                            "log_time" => $log_time,
                            "alarm_status" => 1,
                            "alarm_type" => $alarm_type,
                            "area" => $area,
                            "zone" => $zone,
                        ];

                        $insertedRecord = AlarmLogs::create($records);
                        $message[] =  $this->getMeta("New Alarm Log Is interted", $log_time . "\n");
                        $this->updateCompanyIds($insertedRecord, $serial_number, $log_time);
                    }
                    try {
                        (new ApiAlarmDeviceTemperatureLogsController)->updateAlarmResponseTime();
                    } catch (\Exception $e) {
                    }
                }
            }

            // try {
            Storage::put("alarm-sensors/sensor-logs-count-" . $date . ".txt", $results['totalLines']);
            return $this->getMeta("Sync Attenance Logs", count($message) . json_encode($message));
            //    // } catch (\Throwable $th) {

            //         Storage::append("alarm-sensors/error-" . date('Y-m-d') . ".txt", date("Y-m-d H:i:s") .  " : "  . "\n" . json_encode($th)  . json_encode($th));

            //         return $this->getMeta("Sync Attenance Logs", " Error occured." . "\n");
            //     }

        } else {
            return $this->getMeta("Sync Alarm Sensor Logs", " 0 records found " . "\n");
        }
    }
    public function endAllAlarmsBySerialNumber($serial_number, $log_end_datetime)
    {

        $alarmActiveEvents = AlarmEvents::where("serial_number", $serial_number)->where("alarm_end_datetime", null)->get();
        //turn off all alarms 
        foreach ($alarmActiveEvents  as $key => $event) {
            $datetime1 = new DateTime($log_end_datetime);
            $datetime2 = new DateTime($event["alarm_start_datetime"]);

            $interval = $datetime1->diff($datetime2);

            $minutesDifference = $interval->i + ($interval->h * 60) + ($interval->days * 1440); // i represents the minutes part of the interval 

            AlarmEvents::where("id",  $event["id"])
                ->update([
                    "alarm_end_datetime" => $log_end_datetime,
                    "response_minutes" => $minutesDifference,
                    "alarm_status" => 0
                ]);
        }
    }
    public function updateCompanyIds($insertedRecord, $serial_number, $log_time)
    {
        $deviceModel = Device::where("serial_number", $serial_number)->first();
        if ($deviceModel) {
            $company_id = $deviceModel->company_id;
            $customer_id = $deviceModel->customer_id;
            $device_time_zone = $deviceModel->utc_time_zone;

            $log_time = new DateTime($log_time);
            $log_time->setTimezone(new DateTimeZone($device_time_zone));

            $data = [
                "company_id" => $company_id,
                "customer_id" => $customer_id,
                "log_time" => $log_time->format('Y-m-d H:i:s')
            ];
            AlarmLogs::where("id", $insertedRecord["id"])->update($data);


            // $data = [
            //     "company_id" => $company_id,
            //     "serial_number" => $serial_number,
            //     "alarm_start_datetime" => $log_time,
            //     "customer_id" => $customer_id,
            //     "zone" => $insertedRecord['zone'],
            //     "area" => $insertedRecord['area'],
            //     "alarm_type" => $insertedRecord['alarm_type'],

            // ];

            // AlarmEvents::create($data);

            //create alarm 


            return $this->response('Alarm Logs are created', null, true);
        }
    }
}
