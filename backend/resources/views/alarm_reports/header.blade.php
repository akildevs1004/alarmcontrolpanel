<table style="margin-top:  0px !important; padding-bottom:5px; ;width:100%;border:0px solid red;">

    <tr>
        <td style="border: nonse; ;height:90px">
            <div style="text-align:left;    ;margin:auto;">

                @if (env('APP_ENV') !== 'local')
                <img src="{{ $company->logo }}" style=" margin:auto;width:100px;max-width:150px;max-height:40px ">
                @else
                <img src="{{ getcwd() .   '/'.$company->logo_raw }}" style="margin:auto; width:100px;max-width:150px; ;max-height:40px  ">
                @endif

                <div style=" font-size:10px;padding-top:10px"> {{$company->name}}</div>

            </div>
        </td>
        <td style="text-align: center;width: 30%; border :0px solid red;padding-left:0px;margin:0px   ">
            <table style="width:100%">
                <tr>
                    <td style="text-align:center;font-size:14px">
                        {{$title1}}
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center">
                        @if ($title2)
                        {{$title2 }}
                        @endif

                    </td>

                </tr>
            </table>

        </td>
        <td style=" text-align: right; width:35% ;margin:auto">

            Generated Date: <br />
            {{date("Y-m-d ")}}


        </td>
    </tr>
    <!-- <tr>
        <td colspan="100%" style="border-top:1px solid black"></td>
    </tr> -->
    <!-- <tr>
        <td colspan="100%" style="background-color:brown;height:10px"></td>
    </tr> -->

</table>
@php
function changeDateformatTime($date)

{
if($date=='') return ['---','---'];
$date = new DateTime($date);

// Format the date to the desired format
return $date->format('M j, Y').' '.$date->format('H:i') ;
}
@endphp