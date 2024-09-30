<?php

namespace App\Http\Controllers\Customers\Alarm;

use App\Http\Controllers\Controller;
use App\Http\Controllers\WhatsappController;
use App\Mail\EmailAlarmForwardMail;
use App\Mail\EmailContentDefault;
use App\Models\AlarmEvents;
use App\Models\Customers\CustomerAlarmNotes;
use App\Models\ReportNotificationLogs;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AlarmNotificationController extends Controller
{

    public function getAlarmNotificationIcons()
    {
        return   [
            "Temperature" => "temperature.png",
            "Burglary" => "burglary.png",
            "Medical" => "medical.png",
            "Water" => "water.png",
            "Fire" => "fire.png",
            "Humidity" => "humidity.png",
            "Intruder" => "intruder.png",
        ];
    }
    public function sendAlarmForwardNotification(Request $request)
    {

        $response = '';
        $alarm_id = $request->get('alarm_id');
        $contacts = $request->get('contacts');
        $external_cc_email = $request->get('external_cc_email');
        if ($alarm_id > 0) {

            $alarm = AlarmEvents::with(["device.customer", "device.company"])->whereId($alarm_id)->first();

            if ($alarm) {

                foreach ($contacts as   $contact) {
                    //return $contact;

                    //$contact = json_decode($contact, true);

                    $email = $contact['email'];
                    $name = $contact['name'];
                    $whatsapp_number = $contact['whatsapp_number'];
                    AlarmEvents::where("id", $alarm_id)->update(["forwarded" => true]);
                    if ($email != '') {
                        try {


                            $response = $response . $this->sendMail($name, $alarm, $email, $alarm_id, $external_cc_email);
                        } catch (\Exception $e) {
                            $response = $response . $email . ' - Email  Not sent ' . $e;
                        }
                    }

                    if ($whatsapp_number != '') {
                        try {

                            $this->sendWhatsappMessage($name, $alarm, $whatsapp_number, $alarm_id);
                        } catch (\Exception $e) {
                            $response = $response . $whatsapp_number . ' - Whatsapp  Not sent  ';
                        }
                    }
                }
            } else {
                $response = $response . 'Alarm Details are not available';
            }
        } else {
            $response = $response . 'Alarm ID is not available';
        }

        if ($response == '') {
            return $this->response('Alarm details are sent successfully', null, true);
        } else {
            return $this->response($response, null, false);
        }
    }
    public function sendMail($name, $alarm, $email, $alarm_id, $external_cc_email)
    {


        $cc_emails =  [];

        if ($external_cc_email != '') {
            $emailTesting = explode('@', $external_cc_email);
            if (count($emailTesting) > 1) {
                $cc_emails[] = $external_cc_email;
            }
        }


        $company_id = $alarm['company_id'];
        $customer_id = $alarm['customer_id'];
        $building_name = $alarm['device']['customer']['building_name'];
        $property_type = $alarm['device']['customer']['buildingtype']['name'];
        $address = $alarm['device']['customer']['area'];
        $city = $alarm['device']['customer']['city'];
        $location = $alarm['device']['customer']['latitude'] . ',' . $alarm['device']['customer']['longitude'];

        $alarm_type = $alarm['alarm_type'];
        $priority = $alarm['category']['name'];
        $event_datetime = $this->changeDateformat($alarm['alarm_start_datetime']);
        $company_name = $alarm['device']['company']['name'];

        $primary_contact = 'User';
        $primary_contact = 'User';
        if ($alarm['device']['customer']['primary_contact']) {


            $primary_contact = $alarm['device']['customer']['primary_contact']["first_name"] . ' ' . $alarm['device']['customer']['primary_contact']["flast_name"];;;;



            if ($alarm['device']['customer']['primary_contact']['email']) {
                $emailTesting = explode('@', $alarm['device']['customer']['primary_contact']['email']);
                if (count($emailTesting) > 1) {
                    $cc_emails[] = $alarm['device']['customer']['primary_contact']['email'];
                }
            }
        }
        if ($alarm['device']['customer']['secondary_contact']) {
            $primary_contact = $alarm['device']['customer']['secondary_contact']["first_name"] . ' ' . $alarm['device']['customer']['secondary_contact']["flast_name"];;;;
            if ($alarm['device']['customer']['secondary_contact']['email']) {
                $emailTesting = explode('@', $alarm['device']['customer']['secondary_contact']['email']);
                if (count($emailTesting) > 1) {
                    $cc_emails[] = $alarm['device']['customer']['secondary_contact']['email'];
                }
            }
        }



        $body_content1 = "Hello, <strong> {$name}</strong> <br/>";
        $body_content1 .= "Company:  {$company_name}<br/><br/>";
        $body_content1 .= "This is Notifing you about Alarm {$alarm_type}   <br/><br/>";


        $body_content1 .= "<strong>Event Id:  #{$alarm_id}</strong><br/>";
        $body_content1 .= "Type: {$alarm_type}<br/>";
        $body_content1 .= "Event Time:  {$event_datetime}<br/>";
        $body_content1 .= "Priority:  {$priority}<br/><br/>";

        $body_content1 .= "Primary Contact: {$primary_contact}<br/>";
        $body_content1 .= "Phone1: {$alarm['device']['customer']['primary_contact']}<br/>";
        $body_content1 .= "Primary Contact: {$primary_contact}<br/>";

        $body_content1 .= "Property: {$property_type}<br/>";
        $body_content1 .= "Address: {$address}<br/>";
        $body_content1 .= "City: {$city}<br/><br/> ";
        $body_content1 .= "Google Map Link : https://maps.google.com/?q={$location}  <br/><br/><br/><br/>";


        $body_content1 .= "Thanks<br/>Xtreme Guard<br/>";

        $data = [
            'subject' =>  "#" . $alarm_id . "   '{$alarm_type}' Notification at {$building_name} Time  " .  $event_datetime,

            'body' => $body_content1,
            'alarm' => $alarm,
            'name' => $name,

        ];


        $body_content1 = new EmailAlarmForwardMail($data); {
            Mail::to($email)
                ->cc($cc_emails)

                ->send($body_content1);
            $data = [
                "company_id" => $company_id,
                "customer_id" => $customer_id,
                "alarm_id" => $alarm_id,
                "email" => $email,
                "notes" => "Event Forwarded to " . $email,
                "event_status" => "Forwaded",
                "created_datetime" => date("Y-m-d H:i:s")
            ];

            CustomerAlarmNotes::create($data);
        }
        // return Pdf::loadView("emails.AlarmForwardEmail", $data)->setPaper("A4", "potrait")->stream();
    }

    public function sendWhatsappMessage($name, $alarm, $whatsapp_number, $alarm_id)
    {
        $company_id = $alarm['company_id'];
        $customer_id = $alarm['customer_id'];
        $building_name = $alarm['device']['customer']['building_name'];
        $property_type = $alarm['device']['customer']['buildingtype']['name'];
        $address = $alarm['device']['customer']['area'];
        $city = $alarm['device']['customer']['city'];
        $alarm_type = $alarm['alarm_type'];
        $priority = $alarm['category']['name'];
        $event_datetime = $this->changeDateformat($alarm['alarm_start_datetime']);
        $company_name = $alarm['device']['company']['name'];
        $location = $alarm['device']['customer']['latitude'] . ',' . $alarm['device']['customer']['longitude'];

        $primary_contact = 'User';
        if ($alarm['device']['customer']['primary_contact'])
            $primary_contact = $alarm['device']['customer']['primary_contact']["first_name"] . ' ' . $alarm['device']['customer']['primary_contact']["flast_name"];;;;

        $body_content1 = "Hello, * {$name}* \n";
        $body_content1 .= "Company:  {$company_name}\n\n";
        $body_content1 .= "This is Notifing you about Alarm {$alarm_type}   \n\n";


        $body_content1 .= "*Event Id:  *#{$alarm_id}*\n";
        $body_content1 .= "Type: {$alarm_type}\n";
        $body_content1 .= "Event Time:  {$event_datetime}\n";
        $body_content1 .= "Priority:  {$priority}\n\n";
        $body_content1 .= "Customer: {$primary_contact}\n";
        $body_content1 .= "Property: {$property_type}\n";
        $body_content1 .= "Address: {$address}\n";
        $body_content1 .= "City: {$city}\n\n\n";
        $body_content1 .= "Google Map Link : https://maps.google.com/?q={$location}  <br/><br/><br/><br/>";

        $body_content1 .= "Thanks *Xtreme Guard*";
        (new WhatsappController())->sendWhatsappNotification($alarm['company'], $body_content1, $whatsapp_number, []);

        if ($alarm['device']['customer']['primary_contact']['whatsapp'] != '') (new WhatsappController())->sendWhatsappNotification($alarm['company'], '--------Copy Message--------\n' . $body_content1, $alarm['device']['customer']['primary_contact']['whatsapp'], []);

        if ($alarm['device']['customer']['secondary_contact']['whatsapp'] != '') (new WhatsappController())->sendWhatsappNotification($alarm['company'], '--------Copy Message--------\n' . $body_content1, $alarm['device']['customer']['secondary_contact']['whatsapp'], []);

        $data = [
            "company_id" => $company_id,
            "customer_id" => $customer_id,
            "alarm_id" => $alarm_id,
            "whatsapp_number" => $whatsapp_number,
            "notes" => "Event Forwarded to " . $whatsapp_number,
            "event_status" => "Forwaded",
            "created_datetime" => date("Y-m-d H:i:s")
        ];

        CustomerAlarmNotes::create($data);
    }
    function changeDateformat($date)

    {
        if ($date == '') return ['---', '---'];
        $date = new DateTime($date);

        // Format the date to the desired format
        return   $date->format('M j, Y H:i');
    }
}
