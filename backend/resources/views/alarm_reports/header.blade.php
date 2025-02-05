<table style="margin-top:  0px !important; padding-bottom:5px; ;width:100%;border:0px solid red">

    <tr>
        <td style="border: nonse;width:30%">
            <div style="text-align:left;    ;margin:auto;">

                @if (env('APP_ENV') !== 'local')
                <img src="{{ $company->logo }}" style=" margin:auto;width:100px;max-width:150px;max-height:40px ">
                @else
                <img src="{{ getcwd() .   '/'.$company->logo_raw }}" style="margin:auto; width:100px;max-width:150px; ;max-height:40px  ">
                @endif
            </div>
        </td>
        <td style="text-align: center;width: 35%; border :0px solid red;padding-left:0px;margin:0px   ">
            <table style="width:100%">
                <tr>
                    <td style="text-align:center;font-size:14px">
                        Alarm Events Report
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center">
                        @php
                        // Check if the request has 'date_from' and 'date_to' parameters
                        if($request->date_from && $request->date_to) {
                        // Assuming changeDateformat returns an array, so accessing the first element
                        $fromDate = changeDateformat($request->date_from)[0];
                        $toDate = changeDateformat($request->date_to)[0];
                        echo "From $fromDate to $toDate";
                        }
                        @endphp
                    </td>

                </tr>
            </table>

        </td>
        <td style=" text-align: right; width:35% ;margin:auto">
            <table class="table-border-header" style="width:100%">
                <tr>
                    <td style="color:#FF0000">SOS</td>
                    <td>{{ $counts->soscount}}</td>

                    <td style="color:#df3079">Critical</td>
                    <td>


                        {{ $counts->criticalcount}}



                    </td>

                </tr>
                <tr>
                    <td style="color:#007BFF">Technical</td>
                    <td> {{ $counts->technicalcount}}
                    </td>

                    <td style="color:black">Event</td>
                    <td> {{ $counts->eventscount}}
                    </td>

                </tr>
                <tr>
                    <td style="color:#28A745">Medical</td>
                    <td> {{ $counts->medicalcount}}
                    </td>

                    <td style="color:#FF8C00">Fire</td>
                    <td> {{ $counts->firecount}}
                    </td>

                </tr>
                <tr>
                    <td style="color:#20C997">Water</td>
                    <td> {{ $counts->watercount}}
                    </td>

                    <td style="color:#DC3545">Temperature</td>
                    <td> {{ $counts->temperaturecount}}
                    </td>

                </tr>
            </table>
            <!-- <table style="padding:0px;margin:0px;border-left :1px solid #DDD; ">
                          <tr style="text-align: left; border :none;padding:0px 0px">
                            <td style="text-align: left; border :none;font-size:12px;padding:0 0 5px 0px;">
                                <b style="padding:0px;margin:0px">
                                    {{ $company->name }}
                                </b>
                                <br>
                            </td>
                        </tr>  
                        <tr style="text-align: left; border :none;padding:0px 0px">
                            <td style="text-align: left; border :none;font-size:10px;padding:0 0 5px 0px;">
                                <span style="margin-left: 3px">P.O.Box
                                    {{ $company->p_o_box_no == 'null' ? '---' : $company->p_o_box_no }}</span>
                                <br>
                            </td>
                        </tr>
                        <tr style="text-align: left; border :none;padding:0px 0px">
                            <td style="text-align: left; border :none;font-size:10px;padding:0 0 5px 0px;">
                                <span style="margin-left: 3px">{{ $company->location }}</span>
                                <br>
                            </td>
                        </tr>
                        <tr style="text-align: left; border :none;padding:0px 0px">
                            <td style="text-align: left; border :none;font-size:10px;padding:0 0 5px 0px;">
                                <span style="margin-left: 3px">{{ $company->contact->number ?? '---' }}</span>
                                <br>
                            </td>
                        </tr>

                    </table> -->

        </td>
    </tr>

    <tr>
        <td colspan="100%" style="background-color:brown;height:10px"></td>
    </tr>

</table>