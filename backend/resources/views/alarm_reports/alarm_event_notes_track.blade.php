<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alarm Events Report</title>
    <style>
        /* body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        @page {
            margin: 100px 25px;
            
        }

        header {
            position: fixed;
            top: -80px;
            left: 0px;
            right: 0px;
            height: 60px;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }

        footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
            height: 30px;
            text-align: center;
            font-size: 12px;
        }

        .pagenum:before {
            content: counter(page) " of " counter(pages);
        } */


        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10px;
        }

        @page {
            margin: 100px 25px 100px 25px;

        }

        @page :first {
            @bottom-center {
                content: "Company Name | Page {PAGE_NUM} of {PAGE_COUNT}";
            }
        }




        header {
            position: fixed;
            top: -100px;

            left: 0;
            right: 0;
            height: 100px;
            text-align: center;

            padding-bottom: 10px;

            border: 0px solid red;

        }

        footer {
            position: fixed;
            bottom: -50px;
            height: 100px;
            left: 0;
            right: 0;
            height: 30px;
            border-top: 1px solid #ddd;
            padding-top: 0px;
            text-align: center;
            font-size: 10px;
            border: 0px solid red;
        }

        .page-number {
            text-align: right;
            position: absolute;
            right: 0;
        }

        main {
            margin-top: 10px;
        }

        .table-border table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-border tr {
            /* border-top: 1px solid black; */

            border-top: 1px solid #313131;
        }

        .table-border tr:first-child {
            border-top: none !important;
        }

        .table-border tr:first-child {
            border-bottom: 1px solid #313131;
        }

        .table-border th,
        .table-border td {
            padding: 8px;
            text-align: left;
        }

        .table-border-stats tr {

            border-top: 1px solid red !important;

        }

        .table-border-stats td {

            padding: 8px;

        }


        .table-border-header {
            border-collapse: collapse;
            width: 100%;
        }

        .table-border-header tr {
            border-bottom: 1px solid #313131;

        }

        .table-border-header th,
        .table-border-header td {
            padding: 5px;

        }
    </style>



</head>

<body>
    <!-- Header -->
    <header>

        <table style="margin-top:  0px !important; padding-bottom:5px; ;width:100%;border:0px solid red; ">

            <tr>
                <td style="border: nonse;width:30%;vertical-align:middle;height:90px">
                    <div style="text-align:left;    ;margin:auto;">

                        @if (env('APP_ENV') !== 'local')
                        <img src="{{ $company->logo }}" style=" margin:auto;width:100px;max-width:150px;max-height:40px ">
                        @else
                        <img src="{{ getcwd() .   '/'.$company->logo_raw }}" style="margin:auto; width:100px;max-width:150px; ;max-height:40px  ">
                        @endif
                    </div>
                </td>
                <td style="text-align: center;width: 55%; border :0px solid red;padding-left:0px;margin:0px   ">
                    <table style="width:100%">
                        <tr>
                            <td style="text-align:center;font-size:14px">
                                {{$company->name}}
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:center">

                            </td>

                        </tr>
                    </table>

                </td>
                <td style=" text-align: right; width:10% ;margin:auto">

                </td>
            </tr>

            <tr>
                <td colspan="100%" style="background-color:brown;height:10px"></td>
            </tr>

        </table>

    </header>
    @php

    $customerLogo=getcwd() .'/no-business_profile.png';
    $companyLogo=getcwd() .'/no-business_profile.png';
    $securityLogo=getcwd() .'/no-profile-image.jpg';

    if($alarm['device']&&$alarm['device']['customer']&&$alarm['device']['customer']['profile_picture']!='')
    {
    $customerLogo=$alarm['device']['customer']['profile_picture'];
    }
    if($alarm['device']&&$alarm['device']['company']&&$alarm['device']['company']['logo']!='')
    {
    $companyLogo=$alarm['device']['company']['logo'];
    }
    if($alarm['security']&&$alarm['security']['picture']&&$alarm['security']['picture'] !='')
    {
    $securityLogo=$alarm['security']['picture'];
    }





    @endphp

    @php

    function changeDateformat($date)

    {
    if($date=='') return '---';
    $date = new DateTime($date);

    // Format the date to the desired format
    return $date->format('M j, Y').' '.$date->format('H:i') ;
    }
    function minutesToTime($totalMinutes)
    {
    if($totalMinutes==0) return '00:00';
    if($totalMinutes==null) return '---';
    // Calculate hours and minutes
    $hours = intdiv($totalMinutes, 60); // Integer division to get hours
    $minutes = $totalMinutes % 60; // Remainder to get minutes

    // Format hours and minutes to HH:MM
    return $formattedTime = sprintf('%02d:%02d', $hours, $minutes);

    }
    @endphp
    <main>
        <table style="width:100%">
            <tr>
                <td style="width:40%">
                    <table style="width:100%">
                        <tr>
                            <td style="width: 80px">
                                <img
                                    style="
                    border-radius: 50%;
                    height: 80px;
                    min-height: 80px;
                    width: 80px;
                    max-width: 80px;
                  "
                                    src="{{ $customerLogo }}" />
                            </td>
                            <td>
                                <div style="line-height:14px;padding-left:10px">


                                    <div style="font-weight: bold">
                                        <span>{{ $alarm['device']['customer']['building_name']   }}</span>
                                        <span>({{$alarm['device']['customer']['buildingtype']['name']}})</span>
                                    </div>
                                    <div>{{$alarm->device->customer->house_number ?? "---"}}, {{$alarm->device->customer->street_number ?? "---"}}</div>
                                    <div>{{$alarm->device->customer->area ?? "---"}}, {{$alarm->device->customer->city ?? "---"}}</div>
                                    <div>


                                        <table>
                                            <tr>
                                                <td><span><img style="margin :auto" src="{{env('BASE_PUBLIC_URL')}}icons/email.png" width="15" style="padding-top:5px"></span>

                                                </td>
                                                <td><span>{{$alarm->device->customer->user->email ?? "---"}}</span></td>
                                            </tr>
                                        </table>

                                    </div>
                                    <div>
                                        <table>
                                            <tr>
                                                <td><span><img style="margin :auto" src="{{env('BASE_PUBLIC_URL')}}icons/phone.png" width="15"></span>

                                                </td>
                                                <td><span>{{$alarm->device->customer->contact_number ?? "---"}}</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="border-left:1px solid black;width:30%">



                    <table style="width:100%">
                        <tr>
                            <td>
                                <img
                                    style="
                    border-radius: 50%;

                    width: 40px;
                    max-width: 40px;
                  "
                                    src="{{env('BASE_URL')}}{{$icons['alarm']}}" />
                            </td>
                            <td>
                                <table style="width: 100%; border-collapse: collapse;line-height:20px">
                                    <tr style="border-bottom: 1px solid rgb(143, 141, 141)">
                                        <td>Alarm Type</td>
                                        <td>{{ $alarm->alarm_type ?? "---" }}</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid rgb(143, 141, 141)">
                                        <td>Alarm Location</td>
                                        <td> {{ $alarm->zoneData->location ?? "---"   }}</td>
                                    </tr>
                                    <tr style="border-bottom:1px solid rgb(143, 141, 141)">
                                        <td>Sensor Name</td>
                                        <td>{{ $alarm->zoneData->sensor_type ?? "---" }}</td>
                                    </tr>
                                    <tr style="border-bottom:0px solid rgb(143, 141, 141)">
                                        <td>Alarm Zone</td>
                                        <td>{{ $alarm->zoneData->sensor_name ?? "---" }}

                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>


                </td>
                <td style="border-left:1px solid black;width:30%">

                    <table style="width: 100%; border-collapse: collapse;line-height:16px">
                        <tr style="border-bottom: 1px solid rgb(143, 141, 141)">
                            <td>Event Id</td>
                            <td style="color:red">#{{ $alarm->id   }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid rgb(143, 141, 141)">
                            <td>Status</td>

                            <td> @if($alarm->forwarded ==true && $alarm->alarm_status==1)
                                Forwarded
                                @elseif($alarm->alarm_status==1)
                                Open
                                @else
                                Closed
                                @endif</td>
                        </tr>
                        <tr style="border-bottom: 1px solid rgb(143, 141, 141)">
                            <td>Category</td>
                            <td>@if($alarm->alarm_category==1 )
                                Critical
                                @elseif ($alarm->alarm_category==2)
                                Medium
                                @else
                                Low
                                @endif

                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid rgb(143, 141, 141)">
                            <td>Start</td>
                            <td>{{changeDateformat($alarm->alarm_start_datetime)}}</td>
                        </tr>
                        <tr style="border-bottom: 0px solid rgb(143, 141, 141)">
                            <td>End</td>
                            <td>{{changeDateformat($alarm->alarm_end_datetime)}}</td>
                        </tr>
                    </table>

                </td>
            </tr>

            <tr>
                <td colspan="100%">
                    <hr style="color:#ddd;margin-top:0px " />
                </td>
            </tr>

            <tr>

                <td colspan="100%">
                    @if (count($alarm->notes)==0)
                    <div style="width:100%;height:50px;margin:auto;font-size:12px;text-align:center">
                        <div style="margin:auto">
                            Operator Notes are not available
                        </div>

                    </div>
                    @endif



                    <table cellpadding="0" cellspacing="0">
                        @foreach ($alarm->notes as $note )
                        <!-- Forward -->
                        @if($note->event_status== 'Forwarded')
                        <tr cellpadding="0" cellspacing="0">
                            <td cellpadding="0" cellspacing="0">
                                <table cellpadding="0" cellspacing="0">
                                    <td
                                        style="
                      vertical-align: middle;
                      text-align: center;
                      font-size: 10px;width:180px
                    ">
                                        {{changeDateformat($note->created_datetime)}}
                                    </td>
                                    <td
                                        style="
                      border-left: 1px solid #ddd;
                      vertical-align: middle;
                      text-align: center;width:70px
                    ">
                                        <div
                                            style="
                        position: relative;
                        left: -24px;
                        width: 1px;
                        height: 1px;
                        border-radius: 50%;
                        padding: 20px;
                        border: 1px solid #000;
                        background-color: #fff;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                      ">
                                            <div style="text-align: center">
                                                <img
                                                    style="
                            border-radius: 50%;

                            width: 35px;
                            max-width: 35px;
                            margin-top:-16px
                          "
                                                    src="https://alarmbackend.xtremeguard.org/forwardicon.png" />
                                            </div>
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <div style="position: relative; top: 50px; left: -15px">
                                            <img
                                                style="width: 15px"
                                                src="https://alarmbackend.xtremeguard.org/alarm-notes-left-arrow.png?1=2" />
                                        </div>
                                        <div style="  margin:auto; ">
                                            <div
                                                style="
                                        min-height:80px;
                        height:auto;
                        border: 1px solid #ddd;
                        border-radius: 6px;
                        padding-left: 20px;
                        padding-top: 20px;
                        margin-top: -10px;
                      ">
                                                <div style="font-weight: bold; font-size: 14px">

                                                    Forwarded



                                                </div>
                                                <div style="padding-top: 10px; font-size: 10px">
                                                    <span class="bold">Notes: </span>{{ $note->notes }}
                                                </div>

                                            </div>
                                        </div>

                                    </td>
                                </table>
                            </td>
                        </tr>

                        @elseif($note->event_status != 'Forwarded') <!-- Contact -->
                        <tr style=" border-collapse: collapse;">
                            <td>
                                <table cellpadding="0" cellspacing="0" style="height:140px">
                                    <td
                                        style="
                      vertical-align: middle;
                      text-align: left;
                      font-size: 10px;width:180px
                    ">
                                        <div style="width:100%;text-align:center;font-weight:bold">
                                            {{changeDateformat($note->created_datetime)}}
                                        </div>

                                        @if($note->contact)


                                        <div style="text-align:left;font-size:8px;padding-left:50px;margin-top:10px;">
                                            <div style="font-weight:bold">
                                                {{ $note->contact?->first_name }}
                                                {{ $note->contact?->last_name }}
                                            </div>
                                            <div>
                                                {{ $note->contact->phone1 }}
                                            </div>
                                            <div>
                                                {{ $note->contact->phone2 }}
                                            </div>
                                            <div>
                                                {{ $note->contact->email }}
                                            </div>
                                            <div>
                                                {{ $note->contact->whatsapp }}
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                    <td
                                        style="
                      border-left: 1px solid #ddd;
                      vertical-align: middle;
                      text-align: center;width:70px
                    ">
                                        <div
                                            style="
                        position: relative;
                        left: -24px;
                        width: 1px;
                        height: 1px;
                        border-radius: 50%;
                        padding: 20px;
                        border: 1px solid #000;
                        background-color: #fff;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                      ">
                                            <div style="text-align: center">

                                                <img
                                                    style="
                            border-radius: 50%;

                            width: 35px;
                            max-width: 35px;
                            margin-top:-16px
                          "
                                                    src="{{ $note->contact?->profile_picture ?: 'https://alarmbackend.xtremeguard.org/unknown-person-icon.png' }}" />


                                            </div>
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle;height:140px; ">
                                        <div style="position: relative; top: 50px; left: -15px">
                                            <img
                                                style="width: 15px"
                                                src="https://alarmbackend.xtremeguard.org/alarm-notes-left-arrow.png?1=2" />
                                        </div>
                                        <div style="  margin:auto; ">

                                            <div
                                                style="
                         
                        border: 1px solid #ddd;
                        border-radius: 6px;
                        padding-left: 20px;
                        padding-top: 20px;padding-right: 20px;
                        margin-top: -10px;
                        height:auto
                      ">
                                                <div style="font-weight: bold; font-size: 14px">
                                                    {{$note->contact->address_type}}
                                                </div>
                                                <div style="padding-top: 10px; font-size: 10px">
                                                    <span class="bold">Notes: </span>{{ $note->notes }}
                                                </div>

                                                <hr style="color:#ddd;margin-top:10px " />
                                                <table style=font-size:10px;>
                                                    <tr>
                                                        <td style="font-weight:bold;width:60px;">Call Status
                                                        </td>
                                                        <td>: {{ $note->call_status }}

                                                        </td>
                                                        <td style="font-weight:bold;width:70px;">Call Response

                                                        </td>
                                                        <td>: {{ $note->response }}

                                                        </td>
                                                        <td style="font-weight:bold;width:70px;">Event Status

                                                        </td>
                                                        <td>: {{ $note->event_status }}

                                                        </td>
                                                    </tr>
                                                </table>

                                                <div style="height:20px"> </div>
                                            </div>


                                        </div>
                                    </td>
                                </table>
                            </td>
                        </tr> <!-- Contact -->

                        @endif





                        @endforeach

                        @if($alarm->alarm_end_datetime != '')
                        <!-- Closed Alarm  -->
                        <tr style=" border-collapse: collapse;">
                            <td>
                                <table cellpadding="0" cellspacing="0" style="height:150px">
                                    <td
                                        style="
                      vertical-align: middle;
                      text-align: center;
                      font-size: 10px;width:180px;color:red
                    ">
                                        {{changeDateformat($alarm->alarm_end_datetime)}}
                                    </td>
                                    <td
                                        style="
                      border-left: 1px solid #ddd;
                      vertical-align: middle;
                      text-align: center;width:70px
                    ">
                                        <div
                                            style="
                        position: relative;
                        left: -24px;
                        width: 1px;
                        height: 1px;
                        border-radius: 50%;
                        padding: 18px;
                        border: 1px solid #000;
                        background-color: #fff;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                      ">
                                            <div style="text-align: center">
                                                <div
                                                    style="
                        position: relative;
                         
                        width: 1px;
                        height: 1px;
                        border-radius: 50%;
                        padding: 12px;
                        border: 1px solid #FFF;
                        background-color:  red;
                        display: flex;
                        justify-content: center;
                        align-items: center; 

                            
                            
                            margin-top:-13px;
                            margin-left:-13px;
                      ">

                                                </div>
                                            </div>
                                    </td>
                                    <td style="vertical-align: middle;height:140px; ">
                                        <div style="position: relative; top: 50px; left: -15px">
                                            <img
                                                style="width: 15px"
                                                src="https://alarmbackend.xtremeguard.org/alarm-notes-left-arrow.png?1=2" />
                                        </div>
                                        <div style="  margin:auto; ">

                                            <div
                                                style="
                         
                        border: 1px solid #ddd;
                        border-radius: 6px;
                        padding-left: 20px;
                        padding-top: 20px;padding-right: 20px;
                        margin-top: -10px;
                        height:auto
                      ">
                                                <div style="font-weight: bold; font-size: 14px">
                                                    Alarm Event Closed at {{changeDateformat($alarm->alarm_end_datetime)}}
                                                </div>
                                                <div style="padding-top: 10px; font-size: 10px">
                                                    @if($alarm->alarm_end_manually == 1)
                                                    <div>
                                                        Operator Verified PIN with {{ $alarm->pin_verified_by }} -

                                                        {{
                        $alarm->pinverifiedby
                          ? $alarm->pinverifiedby->first_name +
                            " " +
                            $alarm->pinverifiedby->last_name
                          : "---"
                      }}
                                                    </div>
                                                    @else

                                                    Auto Closed by Disarm Event
                                                    @endif
                                                </div>

                                                <div style="height:30px"> </div>
                                            </div>


                                        </div>
                                    </td>
                                </table>
                            </td>
                        </tr>

                        @endif
                    </table>
                </td>
            </tr>
        </table>
    </main>

    <!-- Footer -->
    <footer>
        <table style="width:100%;text-align:center;border:0px solid black">

            <tr>
                <td colspan="100%" style="background-color:black;height:5px"></td>
            </tr>
            <tr>

                <td style="width:33%;vertical-align: middle;height:40px;;text-align:left">


                    <div style="font-weight:bold">{{ $company->name }}</div>

                    Phone1: {{ $company->contact->number ?? '---' }}
                </td>
                <td style="width:33%;vertical-align: middle;height:50px;padding-top:10px;border:0px solid red;margin:auto">
                    <table>
                        <tr>
                            <td>
                                <img style="margin :auto" src="{{env('BASE_PUBLIC_URL')}}icons/website.png" width="15">
                            </td>
                            <td>
                                Email: {{ $company->user->email ?? '---' }}
                            </td>

                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                Website:---
                            </td>
                        </tr>
                    </table>





                </td>
                <td style="width:33%;vertical-align: middle;height:40px;text-align:left;padding-left:50px;"><span style=" margin-left: 3px">P.O.Box
                        {{ $company->p_o_box_no == 'null' ? '---' : $company->p_o_box_no }}</span>

                    <br />
                    <span style="margin-left: 3px">{{ $company->location }}</span>

                </td>
            </tr>
        </table>
    </footer>
    <script type="text/php">
        if (isset($pdf)) {
        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
        $font = $fontMetrics->getFont("Verdana");
        $size = 6;
        $x = $pdf->get_width() - 50; 
        $y = $pdf->get_height() - 30;
        $pdf->page_text($x, $y, $text, $font, $size);
    }

     
</script>
</body>

</html>