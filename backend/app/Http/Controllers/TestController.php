<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Department;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\ReportController;
use App\Models\AlarmEvents;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;




class TestController extends Controller
{
    public function pdfMergeTest()
    {
        $oMerger = PDFMerger::init();

        // $oMerger->addPDF(public_path("pdf_one.pdf"), [2]);
        // $oMerger->addPDF(public_path("pdf_one.pdf"), 'all');
        $oMerger->addPDF("C:\\akil\\ideaHRMS\\ideahrms\\backend\\storage\\app\\public\\temp_pdf\\1001.pdf", 'all');

        $oMerger->addPDF("C:\\akil\\ideaHRMS\\ideahrms\\backend\\storage\\app\\public\\temp_pdf\\353.pdf", 'all');

        $oMerger->addPDF("C:\\akil\\ideaHRMS\\ideahrms\\backend\\storage\\app\\public\\temp_pdf\\5656.pdf", 'all');



        $oMerger->merge();
        return $oMerger->save('merged_result.pdf');
    }
    public function index()
    {
    }

    public function test_week()
    {
        $pdf = App::make('dompdf.wrapper');

        $str = '

        <!DOCTYPE html>
        <html>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <head>
        <style>
            table { font-family: arial, sans-serif; border-collapse: collapse; border: none; width: 100%; }
            td, th { border: 1px solid #eeeeee; text-align: left; }

            th { font-size: 9px; }
            td { font-size: 7px; }

            .page-break { page-break-after: always; }
            .main-table {
                padding-right: 15px;
                padding-left: 15px;
            }
            hr {
                position: relative;
                border: none;
                height: 2px;
                background: #c5c2c2;
                padding: 0px
            }
            .title-font {
                font-family: Arial, Helvetica, sans-serif !important;
                font-size: 14px;
                font-weight: bold
            }

            .summary-header th {
                font-size: 10px
            }

            .summary-table td {
                font-size: 9px
            }
            footer {
                bottom: 0px;
                position: absolute;
                width: 100%;
            }
        </style>
        </head>
        <body>

        <table style="margin-top: -20px !important;backgroundd-color:blue;padding-bottom:0px ">
        <tr>
            <td style="text-align: left;width: 300px; border :none; padding:15px;   backgrozund-color: red">
                <div style="img">
               <img src="' . getcwd() . '/upload/app-logo.jpeg" height="70px" width="200">
                </div>
            </td>
            <td style="text-align: left;width: 333px; border :none; padding:15px; backgrozusnd-color:blue">
                <div>
                    <table style="text-align: left; border :none;  ">
                        <tr style="text-align: left; border :none;">
                            <td style="text-align: center; border :none">
                                <span class="title-font">
                                Weekly Attendance Summary Report
                                </span>
                                <hr style="width: 230px">
                            </td>
                        </tr>
                        <tr style="text-align: left; border :none;">
                            <td style="text-align: center; border :none">
                                <span style="font-size: 11px">
                                ' . date('d M Y')  . ' - ' .  date('d M Y')  . ' <br>
                                   <small> Department : All </small>
                                </span>
                                <hr style="width: 230px">
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td style="text-align: right;width: 300px; border :none; backgrounsd-color: red">


                <table class="summary-table"
                style="border:none; padding:0px 50px; margin-left:35px;margin-top:20px;margin-bottom:0px">
                <tr style="text-align: left; border :none;">
                    <td style="text-align: right; border :none;font-size:10px">
                        <b>
                        Akil
                        </b>
                        <br>
                    </td>
                </tr>
                <tr style="text-align: left; border :none;">
                    <td style="text-align: right; border :none;font-size:10px">
                        <span style="margin-right: 3px"> P.O.Box:  12568 </span>
                        <br>
                    </td>
                </tr>
                <tr style="text-align: left; border :none;">
                    <td style="text-align: right; border :none;font-size:10px">
                        <span style="margin-right: 3px"> UAE </span>
                        <br>
                    </td>
                </tr>
                <tr style="text-align: left; border :none;">
                    <td style="text-align: right; border :none;font-size:10px">
                        <span style="margin-right: 3px"> Tel:075236986 </span>
                        <br>
                    </td>
                </tr>
            </table>

                <br>
            </td>
            </td>
        </tr>
    </table>
    <hr style="margin:0px;padding:0">




            <div class="page-breaks">
            <table class="main-table" style="margin-top: 10px !important;">
            <tr style="text-align: left; border :1px solid black; width:120px;">
                <td style="text-align:left;width:120px"><b>Name</b>:fahath</td>
                <td style="text-align:left;width:120px"><b>EID</b>:fahath</td>
                <td style="text-align:left;width:120px"><b>Total</b>:fahath</td>
                <td style="text-align:left;width:120px"><b>OT</b>:fahath</td>
                <td style="text-align:left;width:120px"><b>Present</b>:fahath</td>
                <td style="text-align:left;width:120px"><b>Absent</b>:fahath</td>
                <td style="text-align:left;width:120px"><b>Missing</b>:fahath</td>
                <td style="text-align:left;width:120px"><b>Manual</b>:fahath</td>
            </tr>


        ' . $this->weekDays() . '

        </div>

        </table>

        <hr style=" bottom: 0px; position: absolute; width: 100%; margin-bottom:20px">
        <footer style="padding-top: 0px!important">
        <table class="main-table">
            <tr style="border :none">
                <td style="text-align: left;border :none"><b>Device</b>: Main Entrance = MED, Back Entrance = BED</td>
                <td style="text-align: left;border :none"><b>Shift Type</b>: Manual = MA, Auto = AU, NO = NO</td>
                <td style="text-align: left;border :none"><b>Shift</b>: Morning = Mor, Evening = Eve, Evening2 = Eve2
                </td>
                <td style="text-align: right;border :none;">
                    <b>Powered by</b>: <span style="color:blue"> www.ideahrms.com</span>
                </td>
                <td style="text-align: right;border :none">
                    Printed on :  ' . date("d-M-Y ") . '
                </td>
            </tr>
        </table>
    </footer>
    </body>
</html>

        ';

        return $pdf->loadHTML($str)->stream();
    }

    public function weekDays()
    {
        $status = '';


        for ($a = 1; $a < 5; $a++) {
            $dates = '<tr"><td style="text-align:left;width:100px"><b>Dates</b></td>';
            $days = '<tr"><td><b>Days</b></td>';
            $in = '<tr"><td><b>In</b></td>';
            $out = '<tr"><td><b>Out</b></td>';
            $work = '<tr"><td><b>Work</b></td>';
            $ot = '<tr"><td><b>OT</b></td>';
            $shift = '<tr"><td><b>Shift</b></td>';
            $shift_type = '<tr "><td><b>Shift Type</b></td>';
            $din = '<tr"><td><b>Device In</b></td>';
            $dout = '<tr"><td><b>Device Out</b></td>';
            $status_tr = '<tr"><td><b>Status</b></td>';

            $totdays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sat', 'Sat'];
            $times = ['10:25', '08:12', '13:00', '09:00', '11:00', '08:00', '09:00', '09:00', '09:00'];
            $shifts = ['mrg', 'evg', 'night', 'mitnight', 'regular', 'regular', 'regular', 'regular', 'regular', 'regular', 'regular'];
            $shiftTypes = ['FILO', 'Auto', 'Manual'];

            for ($i = 1; $i <= 7; $i++) {
                $td = array_rand($totdays);
                $ti = array_rand($times);
                $s = array_rand($shifts);
                $st = array_rand($shiftTypes);

                $dates .= '<td style="text-align: center;">' . $i . ' </td>';
                $days .= '<td style="text-align: center;"> ' .  $totdays[$td] . ' </td>';
                $in .= '<td style="text-align: center;"> ' . $times[$ti] . '   </td>';
                $out .= '<td style="text-align: center;">' . $times[$ti] . '  </td>';
                $work .= '<td style="text-align: center;">' . $times[$ti] . '  </td>';
                $ot .= '<td style="text-align: center;">' . $times[$ti] . '  </td>';
                $shift .= '<td style="text-align: center;">' . $shifts[$s] . '  </td>';
                $shift_type .= '<td style="text-align: center;"> ' . $shiftTypes[$st] . '  </td>';
                $din .= '<td style="text-align: center;">' . $times[$ti] . '  </td>';
                $dout .= '<td style="text-align: center;">' . $times[$ti] . '  </td>';
                $status .= '<td style="text-align: center;">' . $times[$ti] . '  </td>';
                $status_tr .= '<td style="text-align: center; ">' . $times[$ti] . '  </td>';
            }

            $dates .= '</tr>';
            $days .= '</tr>';
            $in .= '</tr>';
            $out .= '</tr>';
            $work .= '</tr>';
            $ot .= '</tr>';
            $shift .= '</tr>';
            $shift_type .= '</tr>';
            $din .= '</tr>';
            $dout .= '</tr>';
            $status_tr .= '</tr>';
        }
        $str =   $dates . $days . $in . $out . $work . $ot . $shift . $shift_type . $din . $dout . $status_tr;

        return $str;
    }


    public function mimo(Request $request)
    {


        $arr = [
            '10:10',
            '10:34',
            '11:04',
            '12:07',
            '10:10',
            '11:02',
            '10:30',
            '12:05',
            '14:30',
            '16:25',
        ];








        // $company = Company::whereId($request->company_id)->with('contact')->first(["logo", "name", "company_code", "location", "p_o_box_no", "id"]);
        // $model = new ReportController;
        // $deptName = '';
        // $totEmployees = '';
        // if ($request->department_id && $request->department_id == -1) {
        //     $deptName = 'All';
        //     $totEmployees = Employee::whereCompanyId($request->company_id)->whereDate("created_at", "<", date("Y-m-d"))->count();
        // } else {
        //     $deptName = DB::table('departments')->whereId($request->department_id)->first(["name"])->name ?? '';
        //     $totEmployees = Employee::where("department_id", $request->department_id)->count();
        // }

        // $info = (object) [
        //     'department_name' => $deptName,
        //     'total_employee' => $totEmployees,
        //     'total_absent' => $model->report($request)->where('status', 'A')->count(),
        //     'total_present' => $model->report($request)->where('status', 'P')->count(),
        //     'total_missing' => $model->report($request)->where('status', '---')->count(),
        //     'total_early' => $model->report($request)->where('early_going', '!=', '---')->count(),
        //     'total_late' => $model->report($request)->where('late_coming', '!=', '---')->count(),
        //     'total_leave' => 0,
        //     'department' => $request->department_id == -1 ? 'All' :  Department::find($request->department_id)->name,
        //     "daily_date" => $request->daily_date,
        //     "report_type" => $this->getStatusText($request->status)
        // ];

        // $data = $model->report($request)
        //     ->with('AttendanceLogs', function ($q) use ($request) {
        //         $q->whereDate('LogTime', $request->daily_date);
        //     })
        //     ->withCount('AttendanceLogs')
        //     ->get();
        // ld($data);
        // return Pdf::loadView('pdf.mimo', compact("company", "info", "data"))->stream();
    }

    public function AlarmEvent(Request $request)
    {
        $data = $request->validate([
            "company_id" => "required|integer",
            "serial_number" => "required",
            "alarm_start_datetime" => "nullable",
            "customer_id" => "required|integer",
            "sensor_id" => "required|integer",
            "area" => "nullable",
            "alarm_type" => "nullable",
            "alarm_status" => "required|integer",
        ]);

        $data = [
            "company_id" => $data["company_id"],
            "serial_number" => $data["serial_number"],
            "alarm_start_datetime" => date("Y-m-d H:i:s"),
            "customer_id" => $data["customer_id"],
            "zone" => $data["sensor_id"],
            "area" => "test",
            "alarm_type" => "---",
            "alarm_status" => 1,
        ];

        try {
            return AlarmEvents::create($data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
