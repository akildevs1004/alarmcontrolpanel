<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Device;
use App\Models\Employee;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class DeviceCameraModel2Controller extends Controller
{
    public  $camera_sdk_url = '';
    public  $sxdmToken = '7VOarATI4IfbqFWLF38VdWoAbHUYlpAY';
    public  $sxdmSn = '';


    public function __construct($camera_sdk_url, $sxdmSn = '')
    {
        $this->camera_sdk_url = $camera_sdk_url;

        $url = $this->camera_sdk_url ?? gethostbyname(gethostname()) . ':8888';
        $this->camera_sdk_url = "$url";
        $this->sxdmSn = $sxdmSn;
    }




    public function getPersonDetails($system_user_id)
    {

        $data = [];
        $json = '{
            "cmd": "person_list_query",

            "limit": 10,
            "offset": 0,
            "sort": "asc",
            "query_string": "' . $system_user_id . '"
          }';
        $response = $this->postCURL('/api/persons/query', $json);

        foreach ($response['data'] as $key => $personList) {



            $person = $this->getCURL('/api/persons/item/' . $personList['id']);

            if (isset($person['picture_data'])) {

                $picture_data = $person['picture_data'];
                $picture_data = str_replace("data:image/jpg;base64,", "", $picture_data);

                $data = ["name" => $person["person_name"], "userCode" => $system_user_id, "expiry" => '---',  "faceImage" => $picture_data, "timeGroup" => "0"];
            }
        }

        return $data;
    }



    public function openDoor($device)
    {

        $this->openDoorAlways($device);
        // $this->resetDoorStatus($device);
        // $this->sxdmSn = $device->device_id;
        // $json = '{
        //     "tips": {
        //         "text": "Door Open",
        //         "person_type": "admin-software"
        //     }
        // }';
        // $response = $this->postCURL('/api/devices/io', $json);
    }
    public function closeDoor($device)
    {
        $this->resetDoorStatus($device);
        //reset the always open door settings and then close the door automatically after 1 sec

        $this->sxdmSn = $device->device_id;
        $json = '{
            "tips": {
                "text": "Door Closed",
                "person_type": "admin-software"
            }
        }';
        $response = $this->postCURL('/api/devices/io', $json);
    }
    public function openDoorAlways($device)
    {
        //$this->resetDoorStatus($device);

        $this->sxdmSn = $device->device_id;
        $json = '{
                "door_open_stat": "open"

        }';
        $response = $this->putCURL('/api/devices/door', $json);

        $this->sxdmSn = $device->device_id;
        $json = '{
            "tips": {
                "text": "Door Open",
                "person_type": "admin-software"
            }
        }';
        $response = $this->postCURL('/api/devices/io', $json);
    }

    public function resetDoorStatus($device)
    {
        $this->sxdmSn = $device->device_id;
        $json = '{
                "door_open_stat": "none"


        }';
        //     $json = '{
        //         "door_open_stat": "close" //it will close permanent


        // }';
        $response = $this->putCURL('/api/devices/door', $json);
    }
    public function updateSettings($request)
    {
        $this->sxdmSn = $request->deviceSettings['device_id'];
        $json = '{
                "voice_volume": ' . round($request->deviceSettings['voice_volume']) . '   }';
        $response = $this->putCURL('/api/devices/profile', $json);
        //---------------------------


        $data["verification_mode"] = $request->deviceSettings['verification_mode'];
        $data["open_duration"] = $request->deviceSettings['open_duration']  * 1000;

        $response1  = $this->putCURL('/api/devices/door', json_encode($data));
        //---------------------------
        $json = '{
            "recognition_mode": "' .  ($request->deviceSettings['recognition_mode']) . '"}';
        $response = $this->putCURL('/api/devices/recognition', $json);

        return $response1;
    }

    public function updateAttendanceSDKData($device_id, $json)
    {
        $this->sxdmSn = $device_id;

        $response = $this->putCURL('/api/custom/attendance', $json);
    }

    public function updateSDKData($device_id, $json)
    {
        $this->sxdmSn = $device_id;

        $response = $this->putCURL('/api/devices/profile', $json);
    }

    public function TestLive($device)
    {
        $this->sxdmSn = $device->device_id;

        return array(
            CURLOPT_URL => $this->camera_sdk_url . '/api/auth/login/challenge?username=admin',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'sxdmToken:' . $this->sxdmToken, //get from Device manufacturer
                'sxdmSn:' . $this->sxdmSn //get from Device serial number
            )
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->camera_sdk_url . '/api/auth/login/challenge?username=admin',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'sxdmToken:' . $this->sxdmToken, //get from Device manufacturer
                'sxdmSn:' . $this->sxdmSn //get from Device serial number
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response, true);
        if (isset($response["session_id"])) {
            $sessionId = $response["session_id"];
        } else {
            $sessionId = '';

            return "Session ID is empty.";
        }
        //////$sessionId = $this->getActiveSessionId();

        //return $this->camera_sdk_url . $serviceCall;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->camera_sdk_url .  '/api/devices/status',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: sessionID=' . $sessionId,
                'sxdmToken: ' . $this->sxdmToken, //get from Device manufacturer
                'sxdmSn:  ' . $this->sxdmSn //get from Device serial number
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return  $response = json_decode($response, true);
        //return $status = $this->getCURL('/api/devices/status');
    }
    public function getSettings($device)
    {
        $row = [];
        try {
            $this->sxdmSn = $device->device_id;
            $status = $this->getCURL('/api/devices/status');
            $profile = $this->getCURL('/api/devices/profile');
            $time = $this->getCURL('/api/devices/time');
            $door = $this->getCURL('/api/devices/door');
            $network = $this->getCURL('/api/devices/network');
            $server = $this->getCURL('/api/devices/server');
            $recognition = $this->getCURL('/api/devices/recognition');

            $json = '{
                "cmd": "person_list_query",

                "limit": 100,
                "offset": 0,
                "sort": "asc",

              }';
            $persons = $this->postCURL('/api/groups/query', $json);

            $row['model_spec'] = $status['model_spec'];
            $row['voice_volume'] = $profile['voice_volume'];
            $row['local_time'] = $time['local_time'];
            $row['door_open_stat'] = $door['door_open_stat'];
            $row['wifi_ip'] = $network['wifi']['ip'];
            $row['lan_ip'] = $network['lan']['ip'];
            $row['ipaddr'] = $server['ipaddr'];
            $row['open_duration'] =   $door['open_duration'] / 1000;
            $row['verification_mode'] = $door['verification_mode'];
            $row['recognition_mode'] = $recognition['recognition_mode'];

            $persons_count = 0;
            foreach ($persons['data'] as $key => $value) {
                $persons_count = $persons_count + $value['person_count'];
            }
            $row['persons_count'] =  ($persons_count);


            $inputDateString = $row['local_time'];
            $inputDateTime = new DateTime($inputDateString);
            $row['local_time'] = $inputDateTime->format("Y-m-d H:i P");
        } catch (\Exception $e) {
        }

        return  $row;
    }

    public function pushUserToCameraDevice($name,  $system_user_id, $base65Image, $device_id, $persons = null)
    {
        $card_number = "";
        if ($persons) {
            if (isset($persons['cardData'])) {
                $card_number = $persons['cardData'];
            }
        }
        $password = "";
        if ($persons) {
            if (isset($persons['password'])) {
                $password = $persons['password'];
            }
        }
        //

        try {
            if ($this->sxdmSn == '')
                $this->sxdmSn = $device_id;
            $sessionId = $this->getActiveSessionId();
            if ($sessionId != '') {

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->camera_sdk_url . '/api/persons/item',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{
                        "person_code": ' . $system_user_id . ',
                        "visit_begin_time": "",
                        "visit_end_time": "",
                        "recognition_type": "staff",
                        "person_name":  "' . $name . '",
                        "person_id": "",
                        "id":  ' . $system_user_id . ',
                        "card_number": ' . $card_number . ',
                        "id_number": "",
                        "pass": "",
                        "password": "' . $password . '",
                        "phone_num": "",
                        "is_admin": false,
                        "enabled": false,
                        "group_list": [
                          "1"
                        ],
                        "face_list": [
                          {
                            "idx": 0,
                            "data":  "' . $base65Image . ' "
                          }
                        ]
                      }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Cookie: sessionID=' . $sessionId,
                        'sxdmToken: ' . $this->sxdmToken, //get from Device manufacturer
                        'sxdmSn:  ' . $this->sxdmSn //get from Device serial number
                    ),
                ));

                // CURLOPT_POSTFIELDS => '{
                //     "recognition_type": "staff",
                //     "is_admin": false,
                //     "person_name": "' . $name . '",
                //     "id": ' . $system_user_id . ',
                //     "password": "123456",
                //     "card_number": ' . $system_user_id . ',
                //     "person_code":' . $system_user_id . ',
                //     "visit_begin_time": "' . date('Y-m-d 00:00:00') . '",
                //     "visit_end_time": "' .  date('Y-m-d 00:00:00', strtotime(date("Y-m-d 23:00:00") . " + 365 day")) . '",
                //     "phone_num":"18686868686",
                //     "group_list": [
                //       1
                //     ],
                //     "feature_version":"8903",
                //     "face_list": [
                //       {
                //         "idx": 3,
                //         "data": "' . $base65Image . '"
                //       }
                //     ]
                //   }',

                $response = curl_exec($curl);

                curl_close($curl);


                $this->devLog("camera-megeye-info", "Successfully Added ID:" . $system_user_id . ", Name :  " . $name);

                return $response;
            } else {



                $this->devLog("camera-megeye-error", "Unable to Generate session");

                return "Unable to Generate session";
            }
        } catch (\Throwable $th) {
            //throw $th;
            $this->devLog("camera-megeye-error", "Exception - Unable to Generate session" . $th);
        }
    }
    public function updateTimeZone($device)

    {
        $this->sxdmSn = $device->device_id;
        $timeZone = $device?->utc_time_zone ?: 'Asia/Dubai';
        $utc_time_zone  = $timeZone;
        if ($utc_time_zone != '') {

            $timezone = new DateTimeZone($utc_time_zone);
            $utcOffset = $timezone->getOffset(new DateTime());
            $offsetHours = $utcOffset / 3600;
            $offsetMinutes = abs(($utcOffset % 3600) / 60);
            $utcOffsetString = sprintf('GMT%+03d:%02d:00', $offsetHours, $offsetMinutes);

            //
            $dateObj = new DateTime("now", $timezone);
            $output_time_zone = new DateTimeZone($utc_time_zone);

            $dateObj->setTimezone($output_time_zone);
            $output_format = 'Y-m-d\TH:i:sP'; // "2024-01-26T11:59:00+04:00"
            $currentTime = $dateObj->format($output_format);
        }


        $json = '{
            "local_time": "' . $currentTime . '",
            "ntp": {
                "mode": false,
                "time_zone": "' . $utcOffsetString . '",
                "server_port": 123,
                "sync_interval": 60,
                "server_address": "cn.pool.ntp.org"
            }
        }';


        return   $response = $this->putCURL('/api/devices/time', $json);
    }
    public function getCameraDeviceLiveStatus($company_id)
    {
        //139.59.69.241:8888
        $online_devices_count = 0;
        $devices = Device::where('company_id', $company_id)->where('model_number', "OX-900"); //OX-900

        $devices->clone()->update(["status_id" => 2]);



        foreach ($devices->get() as $device) {

            $this->sxdmSn = $device->device_id;
            $url = $device->camera_sdk_url ?? gethostbyname(gethostname()) . ':8888';
            $this->camera_sdk_url = "$url";


            $response = $this->getCURL('/api/devices/status');

            if (isset($response["serial_no"])) {

                Device::where("device_id", $response["serial_no"])->update(["status_id" => 1, "last_live_datetime" => date("Y-m-d H:i:s")]);
                $online_devices_count++;
            }
        }

        return  $online_devices_count;
    }

    public function getCURL($serviceCall)
    {
        $sessionId = $this->getActiveSessionId();

        //return $this->camera_sdk_url . $serviceCall;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->camera_sdk_url . $serviceCall,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: sessionID=' . $sessionId,
                'sxdmToken: ' . $this->sxdmToken, //get from Device manufacturer
                'sxdmSn:  ' . $this->sxdmSn //get from Device serial number
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return  $response = json_decode($response, true);
    }
    public function putCURL($serviceCall, $post_json)
    {


        $sessionId = $this->getActiveSessionId();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->camera_sdk_url . $serviceCall,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS =>  $post_json,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: sessionID=' . $sessionId,
                'sxdmToken: ' . $this->sxdmToken, //get from Device manufacturer
                'sxdmSn:  ' . $this->sxdmSn //get from Device serial number
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return  $response = json_decode($response, true);
    }
    public function postCURL($serviceCall, $post_json)
    {


        $sessionId = $this->getActiveSessionId();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->camera_sdk_url . $serviceCall,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $post_json,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: sessionID=' . $sessionId,
                'sxdmToken: ' . $this->sxdmToken, //get from Device manufacturer
                'sxdmSn:  ' . $this->sxdmSn //get from Device serial number
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return  $response = json_decode($response, true);
    }

    public function getActiveSessionId()
    {
        // return array(
        //     'sxdmToken: ' . $this->sxdmToken, //get from Device manufacturer
        //     'sxdmSn:  ' . $this->sxdmSn //get from Device serial number
        // );

        // if ($this->sxdmSn == '') {
        //     return "Device Serial Number is empty";
        // }
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->camera_sdk_url . '/api/auth/login/challenge?username=admin',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'sxdmToken:' . $this->sxdmToken, //get from Device manufacturer
                'sxdmSn:' . $this->sxdmSn //get from Device serial number
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response, true);
        if (isset($response["session_id"])) {
            return $response["session_id"];
        } else {
            return '';
        }
    }
}
