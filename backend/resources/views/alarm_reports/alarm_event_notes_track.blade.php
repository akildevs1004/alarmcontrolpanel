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


        .table-border1 table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-border1 tr {
            /* border-top: 1px solid black; */

            border-top: 1px solid #313131;
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

        .table-border-top {
            border-collapse: collapse;
            width: 100%;
        }



        .table-border-top th,
        .table-border-top td {
            padding: 5px;

        }
    </style>



</head>

<body>
    <!-- Header -->
    @php
    $title1='Alarm Event Notes Report';

    @endphp
    <header>

        @include('alarm_reports.header', [

        'title1' => $title1,
        'company' => $alarm['device']['company'],
        'title2' => '',

        ])



    </header>

    <!-- Footer -->
    <footer>

        @include('alarm_reports.footer', [

        'company' => $alarm['device']['company']
        ])

    </footer>
    @php

    $customerLogo=getcwd() .'/no-business_profile.png';
    $companyLogo=getcwd() .'/no-business_profile.png';
    $securityLogo=getcwd() .'/no-profile-image.jpg';
    if (env('APP_ENV') !== 'local')
    {

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


        <table style="width:100%;font-size:12px">



            <tr>
                <td>
                    <table class="table-border-top" style="width:100%">
                        <tr>
                            <td colspan="5" style="background-color:#8f8f8f;height:20px;color:#FFF;font-size:16px">Customer Details</td>
                        </tr>
                        <tr style="border: 1px solid #8f8f8f;border-top:0px">
                            <td rowspan="4" style="width:100px;text-align:center"> <img
                                    style="border-radius: 50%;height: 70px;min-height: 70px;  "
                                    src="{{ $customerLogo }}" /> </td>

                            <td style="font-weight:bold">Customer/Building Name</td>
                            <td>: {{ $alarm['device']['customer']['building_name'] }}</td>

                            <td style="font-weight:bold">Type</td>
                            <td>:{{$alarm['device']['customer']['buildingtype']['name']}}</td>


                        </tr>
                        <tr style="border: 1px solid #8f8f8f;">

                            <td style="font-weight:bold">Address</td>
                            <td colspan="3">: <span>{{$alarm->device->customer->house_number ?? "---"}}, {{$alarm->device->customer->street_number ?? "---"}}</span>
                                <span>{{$alarm->device->customer->area ?? "---"}}, {{$alarm->device->customer->city ?? "---"}} </span> <span>{{$alarm->device->customer->state ?? "---"}}, {{$alarm->device->customer->country ?? "---"}} </span>
                            </td>


                        </tr>

                        <tr style="border: 1px solid #8f8f8f;">

                            <td style="font-weight:bold">Contact</td>

                            <td colspan=3>: {{$alarm->device->customer?->primary_contact?->first_name ?? "---"}} {{$alarm->device->customer?->primary_contact?->last_name ?? "---"}} </td>


                        </tr>
                        <tr style="border: 1px solid #8f8f8f;">

                            <td style="font-weight:bold">Phone Number</td>

                            <td>: {{$alarm->device->customer?->primary_contact?->phone1 ?? "---"}}</td>
                            <td style="font-weight:bold">Email</td>

                            <td>: {{$alarm->device->customer?->primary_contact?->email ?? "---"}}</td>

                        </tr>

                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <table class="table-border-top" style="width:100%">
                        <tr>
                            <td colspan="5" style="background-color:#8f8f8f;height:20px;color:#FFF;font-size:16px">Event Details</td>
                        </tr>
                        <tr style="border: 1px solid #8f8f8f;border-top:0px">
                            <td rowspan="4" style="width:100px;text-align:center"> <img
                                    style=" border-radius: 50%; width: 40px;  max-width: 40px;  "
                                    src="{{env('BASE_PUBLIC_URL')}}{{$icons['alarm']}}" /></td>

                            <td style="font-weight:bold">Event Id</td>
                            <td style="color:red">#{{ $alarm->id   }}</td>

                            <td style="font-weight:bold">Status</td>

                            <td>: @if($alarm->forwarded ==true && $alarm->alarm_status==1)
                                Forwarded
                                @elseif($alarm->alarm_status==1)
                                Open
                                @else
                                Closed
                                @endif</td>


                        </tr>
                        <tr style="border: 1px solid #8f8f8f;">

                            <td style="font-weight:bold">Sensor Name</td>
                            <td>: {{ $alarm->zoneData->sensor_type ?? "---" }}</td>

                            <td style="font-weight:bold">Alarm Zone</td>
                            <td>: {{ $alarm->zoneData->sensor_name ?? "---" }}</td>


                        </tr>

                        <tr style="border: 1px solid #8f8f8f;">
                            <td style="font-weight:bold">Alarm Type</td>
                            <td>: {{ $alarm->alarm_type ?? "---" }}</td>

                            <td style="font-weight:bold">Alarm Location</td>
                            <td>: {{ $alarm->zoneData->location ?? "---"   }}</td>


                        </tr>
                        <tr style="border: 1px solid #8f8f8f;">



                            <td style="font-weight:bold">Start</td>
                            <td>: {{changeDateformat($alarm->alarm_start_datetime)}}</td>

                            <td style="font-weight:bold">End</td>
                            <td>: {{changeDateformat($alarm->alarm_end_datetime)}}</td>
                        </tr>

                    </table>
                </td>
            </tr>

            <!-- <tr>
                <td style="width:100%">
                    @include('alarm_reports.include_alarm_event_notes_track_customer1', [
                    'alarm' => $alarm
                    ])

                </td>
            </tr> -->

            <!-- <tr>
                <td colspan="100%">
                    <hr style="color:#ddd;margin-top:0px " />
                </td>
            </tr> -->

            <tr>

                <td>

                    @if (count($alarm->notes)==0)
                    <div style="width:100%;height:50px;margin:auto;font-size:12px;text-align:center">
                        <div style="margin:auto">
                            Operator Notes are not available
                        </div>

                    </div>
                    @endif
                </td>
            </tr>


            @foreach ($alarm->notes as $note )
            <tr>

                <td>@if($note->event_status== 'Forwarded')
                    @include('alarm_reports.include_alarm_event_notes_track_forward1', [
                    'note' => $note
                    ])

                    @elseif($note->event_status != 'Forwarded')


                    @include('alarm_reports.include_alarm_event_notes_track_operator_notes', [
                    'note' => $note
                    ])


                    @endif



                </td>
            </tr>

            @endforeach
            <tr>

                <td colspan="100%">






                    @if($alarm->alarm_end_datetime != '')
                    <!-- Closed Alarm  -->

                    @include('alarm_reports.include_alarm_event_notes_track_closed', [

                    'alarm' => $alarm
                    ])

                    @endif

                </td>
            </tr>
        </table>
    </main>


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