<div id="email" style="width:700px;margin:auto;background:white;">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10px;
            border: 1px solid #DDD;
        }

        h1,
        h2 {
            margin: 0;
            padding: 0;
            font-size: 20px;
        }

        td {
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>

    @php
    $event_backgroundcolor="red";
    if($alarm["alarm_category_id"]==2) {
    $event_backgroundcolor="orange";
    } else if($alarm["alarm_category_id"]==3) {
    $event_backgroundcolor="#00A4BD";
    }
    @endphp

    <table role="presentation" border="1" cellspacing="0" cellpadding="0" style=" width:100%">
        <tr>
            <td bgcolor="{{$event_backgroundcolor}}" align="center" style="color: white;">
                <h2>#{{ $alarm["id"] }} {{ $alarm["alarm_type"] }} Notification at {{ $alarm["device"]["customer"]["building_name"] }}. Time {{ $alarm['alarm_start_datetime'] }}</h2>
            </td>
        </tr>
    </table>

    <table role="presentation" border="0" cellspacing="0" cellpadding="0" style=" width:100%">
        <tr>
            <td style="padding:  0px;">
                <h2>Hello {{$name}},</h2>
                <table role="presentation" border="1" style=" width:100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td style="font-weight:bold">Event ID</td>
                                    <td>: #{{ $alarm["id"] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Priority</td>
                                    <td>: {{ $alarm["category"]["name"] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Event Type</td>
                                    <td>: {{ $alarm["alarm_type"] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Event Time</td>
                                    <td>: {{ $alarm["alarm_start_datetime"] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Closed Time</td>
                                    <td>: {{ $alarm["alarm_end_datetime"] ?? '---' }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Resolved Time</td>
                                    <td>: {{ $alarm["response_minutes"] ?? '---' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table role="presentation" border="1" style=" width:100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td style="font-weight:bold">Building Name</td>
                                    <td>{{ $alarm["customer"]["building_name"] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Property Type</td>
                                    <td>{{ $alarm["customer"]["buildingtype"]["name"] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Address</td>
                                    <td>{{ $alarm["customer"]["house_number"] }}, {{ $alarm["customer"]["street_number"] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Area</td>
                                    <td>{{ $alarm["customer"]["area"] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">City</td>
                                    <td>{{ $alarm["customer"]["city"] }}, {{ $alarm["customer"]["state"] }}, {{ $alarm["customer"]["country"] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Landmark</td>
                                    <td>{{ $alarm["customer"]["landmark"] }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold">Google Map Link</td>
                                    <td>
                                        <!-- <a href="https://maps.google.com/?q={{ $alarm["customer"]["latitude"] }},{{ $alarm["customer"]["longitude"] }}">Map Location</a> -->

                                        https://maps.google.com/?q={{ $alarm["customer"]["latitude"] }},{{ $alarm["customer"]["longitude"] }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <img src="{{  $alarm['customer']['profile_picture'] }}" style="margin:auto; max-width:100%;" alt="Building Image">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br />
    <table role="presentation" border="0" cellspacing="0" cellpadding="0" style=" width:100%">
        <tr>
            <td style=" width:50%;padding:  0px;">
                <table role="presentation" border="1" style=" width:100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="font-weight:bold">Primary Contact</td>
                    </tr>
                    <tr>
                        <td>{{ $alarm["customer"]["primary_contact"]["first_name"] }} {{ $alarm["customer"]["primary_contact"]["last_name"] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $alarm["customer"]["primary_contact"]["phone1"] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $alarm["customer"]["primary_contact"]["phone2"] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $alarm["customer"]["primary_contact"]["office_phone"] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $alarm["customer"]["primary_contact"]["email"] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $alarm["customer"]["primary_contact"]["whatsapp"] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $alarm["customer"]["primary_contact"]["distance"] }}</td>
                    </tr>
                </table>
            </td>

            <td style=" width:50%;padding:  0px;">
                <table role="presentation" border="1" cellspacing="0" cellpadding="0" style=" width:100%">
                    <tr>
                        <td style="font-weight:bold">Secondary Contact</td>
                    </tr>
                    <tr>
                        <td>{{ $alarm["customer"]["secondary_contact"]["first_name"] }} {{ $alarm["customer"]["secondary_contact"]["last_name"] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $alarm["customer"]["secondary_contact"]["phone1"] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $alarm["customer"]["secondary_contact"]["phone2"] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $alarm["customer"]["secondary_contact"]["office_phone"] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $alarm["customer"]["secondary_contact"]["email"] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $alarm["customer"]["secondary_contact"]["whatsapp"] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $alarm["customer"]["secondary_contact"]["distance"] }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <table role="presentation" border="0" cellspacing="0" cellpadding="0" style="width:100%">
        <tr>
            <td bgcolor="#F5F8FA" style="padding: 30px;text-align:left;;width:50%">
                @if (env('APP_ENV') !== 'local')
                <img src="{{ $alarm['device']['company']['logo'] }}" style="width:100px; max-width:150px; max-height:60px;" alt="Company Logo">
                @else
                <img src="{{ getcwd() . '/' . $alarm['device']['company']['logo_raw'] }}" style="width:100px; max-width:150px; max-height:60px;" alt="Company Logo">
                @endif
            </td>
            <td bgcolor="#F5F8FA" style="padding: 30px;text-align:right;;width:50%">
                <p style="margin:0; font-size:16px; line-height:24px; color: #99ACC2;">Alarm Control Panel</p>
            </td>
        </tr>
    </table>
</div>