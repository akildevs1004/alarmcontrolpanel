<?php

namespace App\Http\Controllers\Alarm\Api;



use App\Http\Controllers\Controller;
use App\Http\Controllers\WhatsappController;
use App\Mail\DbBackupMail;
use App\Mail\EmailContentDefault;
use App\Mail\ReportNotificationMail;
use App\Models\AlarmDeviceTemperatureLogs;
use App\Models\AlarmLogs;
use Illuminate\Http\Request;
use App\Models\Community\AttendanceLog;
use App\Models\Company;
use App\Models\Device;
use App\Models\ReportNotification;
use App\Models\ReportNotificationLogs;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ApiAlarmDeviceTemperatureLogsController extends Controller
{
    public function AlarmLogs(Request $request)
    {
        Storage::append("logs/alarm/api-alarm-device-" . date('Y-m-d') . ".txt", date("Y-m-d H:i:s") .  " : "    . json_encode($request->all()));

        $log_time = date('Y-m-d H:i:s');

        $device_serial_number = $request->serial_number;
        $alarm_status = 0;
        $alarm_type = '';

        $area = '';
        $zone = '';


        if ($device_serial_number != '' && $request->filled("alarm_type")) {
            $logs = [];
            $logs['serial_number'] = $request->serial_number;
            $logs['log_time'] = $log_time;
            if ($request->filled("alarm_status")) {
                $logs['alarm_status'] = $request->alarm_status;
            }
            if ($request->filled("alarm_type")) {
                $logs['alarm_type'] = $request->alarm_type;
            }
            if ($request->filled("area")) {
                $logs['area'] = $request->area;
            }
            if ($request->filled("zone")) {
                $logs['zone'] = $request->zone;
            }


            $insertedRecord = AlarmLogs::create($logs);

            $deviceModel = Device::where("serial_number", $device_serial_number)->first();
            if ($deviceModel) {
                $company_id = $deviceModel->company_id;
                $device_time_zone = $deviceModel->utc_time_zone;

                $log_time = new DateTime($log_time);
                $log_time->setTimezone(new DateTimeZone($device_time_zone));

                $data = ["company_id" => $company_id, "log_time" => $log_time->format('Y-m-d H:i:s')];
                AlarmDeviceTemperatureLogs::where("id", $insertedRecord["id"])->update($data);
                return $this->response('Alarm Logs are created', null, true);
            }
        } else {
            return $this->response('Device Serial Number or Alarm Type is empty', null, false);
        }
    }

    public function ApiTemperatureLogs(Request $request)
    {



        $message = [];

        //try {
        Storage::append("logs/alarm/api-requests-device-" . date('Y-m-d') . ".txt", date("Y-m-d H:i:s") .  " : "    . json_encode($request->all()));

        $temperature = -1;
        $humidity = -1;


        $log_time = date('Y-m-d H:i:s');

        $max_temperature = '';
        $max_humidity = '';



        $device_serial_number = $request->serial_number;


        if ($device_serial_number != '') {



            if ($request->filled("temperature")) {
                $temperature = $request->temperature == 'NaN' ? 0 : $request->temperature;
                //$temperature = (float) $request->temperature;

            }
            if ($request->filled("humidity")) {
                $humidity = $request->humidity == 'NaN' ? 0 : $request->humidity;
            }


            if ($temperature == "NaN") {
                $temperature = 0;
            }
            if ($temperature == "nan") {
                $temperature = 0;
            }
            if (strtolower($temperature) == "nan") {
                $temperature = 0;
            }
            if (strtolower($humidity) == "nan") {
                $temperature = 0;
            }
            if ($humidity == "NaN") {
                $humidity = 0;
            }




            $logs["serial_number"] = $device_serial_number;
            $logs["temperature"] = $temperature;

            $logs["humidity"] = $humidity;

            $logs["log_time"] = $log_time;

            $insertedRecord = AlarmDeviceTemperatureLogs::create($logs);

            //(new AlarmDeviceTemperatureLogsController)->updateCompanyIds();

            $deviceModel = Device::where("serial_number", $device_serial_number);

            $deviceObj = $deviceModel->clone()->get();
            //update live status 
            $deviceModel->clone()->update(["status_id" => 1, "last_live_datetime" => date("Y-m-d H:i:s")]);
            if (count($deviceObj) == 0) {
                return $this->response('Device Information is not available', null, false);
            }
            $name = $deviceModel->clone()->first()->name;
            $deviceObj = $deviceObj[0];

            try {
                //update company_id;
                AlarmDeviceTemperatureLogs::where("id", $insertedRecord["id"])->update(["company_id" => $deviceObj["company_id"]]);
            } catch (\Exception $e) {
            }


            //notifications
            if ($deviceObj['threshold_temperature'] > 0 && $temperature != 'nan') {

                $temperature = floatval($temperature);

                if ($temperature >= $deviceObj['threshold_temperature']) {

                    $ignore15Minutes = false;


                    if ($deviceObj['temperature_alarm_status'] == 0) {
                        $row = [];
                        $row["temperature_alarm_status"] = 1;
                        $row["temperature_alarm_start_datetime"] = $log_time;
                        $row["temperature_alarm_end_datetime"] = null;
                        $deviceModel->clone()->update($row);
                        $ignore15Minutes = true;
                    }

                    $message[] =  $this->SendWhatsappNotification(
                        $name . " -   Temperature Alarm is  ON",
                        $name,
                        $deviceModel->clone()->first(),
                        $log_time,

                        $ignore15Minutes,
                        ["temperature" => $temperature, "max_temperature" => $deviceObj['threshold_temperature']]
                    );
                } else {



                    if ($deviceObj['temperature_alarm_status'] == 1) {
                        $ignore15Minutes = true;
                        $message[] =  $this->SendWhatsappNotification($name . " -  Temperature Alarm is  OFF",   $name, $deviceModel->clone()->first(), $log_time, $ignore15Minutes, ["temperature" => $temperature, "max_temperature" => $deviceObj['threshold_temperature']]);
                        $row = [];
                        $row["temperature_alarm_status"] = 0;

                        $row["temperature_alarm_end_datetime"] = $log_time;
                        $deviceModel->clone()->update($row);
                    }
                }
            }

            $row = [];



            return $this->response('Successfully Updated', null, true);
        } else {
            return $this->response('Device Serial Number is empty', null, false);
        }
        // } catch (\Exception $e) {
        //     Storage::append("logs/alarm_error/api-requests-device-" . date('Y-m-d') . ".txt", date("Y-m-d H:i:s") .  " : "    . json_encode($request->all()) . ' \n' . json_encode($e));

        //     return  $e->getMessage();
        // }

        return $this->response('Data error', null, false);
    }

    public function SendWhatsappNotification($issue, $room_name, $model1, $date,  $ignore15Minutes, $tempArray = [])
    {
        $company_id = $model1->company_id;
        $branch_id = $model1->branch_id;

        //$reports = ReportNotification::where("company_id", $model1->company_id)->where("branch_id", $model1->branch_id)->get();

        $reports = ReportNotification::with(["managers.branch",  "company.company_mail_content"])


            ->with("managers", function ($query) use ($company_id, $branch_id) {
                $query->where("company_id", $company_id);
                $query->where("branch_id", $branch_id);
            })->get();

        foreach ($reports as $model) {
            $id = $model["id"];

            $script_name = "ReportNotificationCrons";

            // $date = date("Y-m-d H:i:s");

            // try {


            // $model = ReportNotification::with(["managers.branch",  "company.company_mail_content"])->where("id", $id)


            //     ->with("managers", function ($query) use ($company_id, $branch_id) {
            //         $query->where("company_id", $company_id);
            //         $query->where("branch_id", $branch_id);
            //     })

            //     ->first();

            if ($model)
                if (in_array("Email", $model->mediums)) {



                    foreach ($model->managers as $key => $value) {
                        $minutesDifference = 1000;

                        //wait 5 minutes to send notification
                        $notificationSentLogs = ReportNotificationLogs::where("notification_id", $value->notification_id)
                            ->where("notification_manager_id", $value->id)
                            ->where("email", $value->email)
                            ->where("subject", $issue)

                            ->orderBy("created_at", "DESC")->first();

                        if ($notificationSentLogs) {
                            $datetime1 = new DateTime(date("Y-m-d H:i"));
                            $datetime2 = new DateTime($notificationSentLogs["created_at"]);

                            $interval = $datetime1->diff($datetime2);
                            $minutesDifference =  $interval->i + ($interval->h * 60) + ($interval->days * 1440);
                        }


                        if ($minutesDifference >=   15 || $ignore15Minutes) { // 




                            $branch_name = $value->branch->branch_name;

                            $body_content1 = "📊 *{$issue} Notification <br/>";

                            $body_content1 = " Hello, {$value->name} <br/>";
                            $body_content1 .= " Company:  {$model->company->name}<br/>";
                            $body_content1 .= "This is Notifing you about {$issue} status <br/>";

                            if (isset($tempArray["temperature"])) {
                                if ($tempArray["temperature"] != 'nan°C') {
                                    $body_content1 .= "Temperature:  {$tempArray["temperature"]}°C<br/>";
                                }
                            }
                            if (isset($tempArray["max_temperature"])) {
                                $body_content1 .= "Threshold:  {$tempArray["max_temperature"]}<br/>";
                            }

                            $body_content1 .= "Date:  $date<br/>";
                            $body_content1 .= "Room Name: {$room_name}<br/>";
                            $body_content1 .= "Branch: {$branch_name}<br/><br/><br/><br/>";
                            $body_content1 .= "*Xtreme Guard*<br/>";

                            $data = [
                                'subject' => "{$issue} Notification",
                                'body' => $body_content1,
                            ];


                            $body_content1 = new EmailContentDefault($data);

                            if ($value->email != '') {
                                Mail::to($value->email)
                                    ->send($body_content1);


                                $data = ["company_id" => $value->company_id, "branch_id" => $value->branch_id, "notification_id" => $value->notification_id, "notification_manager_id" => $value->id, "email" => $value->email, "subject" => $issue];



                                ReportNotificationLogs::create($data);
                            }
                        }
                    }
                } else {
                    echo "[" . $date . "] Cron: $script_name. No emails are configured";
                }

            //wahtsapp with attachments
            if (in_array("Whatsapp", $model->mediums)) {

                foreach ($model->managers as $key => $manager) {
                    $minutesDifference = 1000; //minutes
                    //wait 5 minutes to send notification
                    $notificationSentLogs = ReportNotificationLogs::where("notification_id", $manager->notification_id)
                        ->where("notification_manager_id", $manager->id)
                        ->where("subject", $issue)
                        ->where("whatsapp_number", $manager->whatsapp_number)
                        ->orderBy("created_at", "DESC")->first();
                    $minutesDifference = 1000; //minutes
                    if ($notificationSentLogs) {
                        $datetime1 = new DateTime(date("Y-m-d H:i"));
                        $datetime2 = new DateTime($notificationSentLogs["created_at"]);
                        $interval = $datetime1->diff($datetime2);
                        $minutesDifference =  $interval->i + ($interval->h * 60) + ($interval->days * 1440);
                    }



                    if ($minutesDifference >=   15   || $ignore15Minutes) { // 

                        if ($manager->whatsapp_number != '') {

                            $branch_name = $manager->branch->branch_name;

                            $body_content1 = "📊 *{$issue}* Notification  📊\n\n";

                            $body_content1 .= "Hello, *{$manager->name}*\n\n";
                            $body_content1 .= "Company:  {$model->company->name}\n\n";
                            $body_content1 .= "This is Notifing you about *{$issue}* status \n\n";
                            if (isset($tempArray["temperature"])) {
                                if ($tempArray["temperature"] != 'nan°C') {
                                    $body_content1 .= "Temperature:  {$tempArray["temperature"]}°C\n\n";
                                }
                            }
                            if (isset($tempArray["max_temperature"])) {
                                $body_content1 .= "*Threshold:  {$tempArray["max_temperature"]}°C*\n\n";
                            }
                            $body_content1 .= "Date:  $date\n";
                            $body_content1 .= "Room Name:  {$room_name}\n";

                            $body_content1 .= "Branch:  {$branch_name}\n";
                            $body_content1 .= "*Xtreme Guard*\n";




                            if (count($model->company->company_whatsapp_content))
                                $body_content1 .= $model->company->company_whatsapp_content[0]->content;

                            (new WhatsappController())->sendWhatsappNotification($model->company, $body_content1, $manager->whatsapp_number, []);

                            $data = [
                                "company_id" => $model->company->id,
                                "branch_id" => $manager->branch_id,
                                "notification_id" => $manager->notification_id,
                                "notification_manager_id" => $manager->id,
                                "whatsapp_number" => $manager->whatsapp_number,
                                "subject" => $issue
                            ];

                            ReportNotificationLogs::create($data);
                        }
                    }
                }
            }
        }
    }
}
