<?php

namespace App\Http\Controllers\Customers\Reports;

use App\Exports\AlarmEventsExport;
use App\Exports\DeviceArmedExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Customers\Alarm\AlarmNotificationController;
use App\Http\Controllers\Customers\Alarm\DeviceArmedLogsController;
use App\Http\Controllers\Customers\CustomerAlarmEventsController;
use App\Models\AlarmEvents;
use App\Models\AlarmLogs;
use App\Models\AttendanceLog;
use App\Models\Company;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;

class AlarmReportsController extends Controller
{
    public function alarmEventsExportCsv(Request $request)
    {
        $model =   (new CustomerAlarmEventsController())->filter($request);
        $model->orderBy("alarm_start_datetime", "asc");
        $reports = $model->get();

        $file_name =  'Alarm Events from ' . $request->date_from . ' to ' . $request->date_to . ' .xlsx';

        return Excel::download((new AlarmEventsExport($reports)), $file_name);
    }
    public function alarmEventsDownloadPdf(Request $request)
    {
        $file_name =  'Alarm Events from ' . $request->date_from . ' to ' . $request->date_to . ' .pdf';

        $model =   (new CustomerAlarmEventsController())->filter($request);
        $model->orderBy("alarm_start_datetime", "asc");
        $reports = $model->get();
        $company = Company::whereId($request->company_id)->with('contact:id,company_id,number')->first();


        $pdf = Pdf::loadView('alarm_reports/alarm_events_list',  ['reports' => $reports, 'company' => $company, "request" => $request])->setPaper('A4', 'potrait');
        return $pdf->download($file_name);
    }
    public function alarmEventsPrintPdf(Request $request)
    {


        $model =   (new CustomerAlarmEventsController())->filter($request);
        $model->orderBy("alarm_start_datetime", "asc");
        $reports = $model->get();
        $company = Company::whereId($request->company_id)->with('contact:id,company_id,number')->first();


        $pdf = Pdf::loadView('alarm_reports/alarm_events_list',  ['reports' => $reports, 'company' => $company, "request" => $request])->setPaper('A4', 'potrait');
        return $pdf->stream('report.pdf');
    }
    //----------------------------------------DEVICE ARMED REPORTS 
    public function deviceArmedLogsPrintPdf(Request $request)
    {
        $model =   (new DeviceArmedLogsController())->filter($request);
        $model->orderBy("armed_datetime", "asc");
        $reports = $model->get();
        $company = Company::whereId($request->company_id)->with('contact:id,company_id,number')->first();


        $pdf = Pdf::loadView('alarm_reports/device_armed_list',  ['reports' => $reports, 'company' => $company, "request" => $request])->setPaper('A4', 'potrait');
        return $pdf->stream('report.pdf');
    }
    public function deviceArmedLogsDownloadPdf(Request $request)
    {
        $model =   (new DeviceArmedLogsController())->filter($request);
        $model->orderBy("armed_datetime", "asc");
        $reports = $model->get();
        $company = Company::whereId($request->company_id)->with('contact:id,company_id,number')->first();
        $file_name =  'Device Armed Reports from ' . $request->date_from . ' to ' . $request->date_to . ' .pdf';

        $pdf = Pdf::loadView('alarm_reports/device_armed_list',  ['reports' => $reports, 'company' => $company, "request" => $request])->setPaper('A4', 'potrait');
        return $pdf->download($file_name);
    }
    public function deviceArmedLogsExportExcel(Request $request)
    {
        $model =   (new DeviceArmedLogsController())->filter($request);
        $model->orderBy("armed_datetime", "asc");
        $reports = $model->get();

        $file_name =  'Device Armed Reports from ' . $request->date_from . ' to ' . $request->date_to . ' .xlsx';

        return Excel::download((new DeviceArmedExport($reports)), $file_name);
    }
    public function alarmEventsNotesDownload(Request $request)
    {

        if (!$request->filled('alarm_id')) return [];
        $alarm =   AlarmEvents::with([
            "device.customer.primary_contact",
            "device.customer.secondary_contact",
            "device.company.user",
            "notes.contact",
            "category",
            "device.customer.buildingtype",
            "zoneData",
            "security.user",
            "pinverifiedby"

        ])->where("id", $request->alarm_id)->first();

        $icons = (new AlarmNotificationController())->getGoogleMapIcons();


        $pdf = Pdf::loadView('alarm_reports/alarm_event_notes_track', compact('alarm', 'icons'))->setPaper('A4', 'potrait');
        return $pdf->download($request->alarm_id . "_event_track_notes.pdf");
    }
    public function alarmEventsNotesPrintPdf(Request $request)
    {

        if (!$request->filled('alarm_id')) return [];
        $alarm =   AlarmEvents::with([
            "device.customer.primary_contact",
            "device.customer.secondary_contact",
            "device.company.user",
            "notes.contact",
            "category",
            "device.customer.buildingtype",
            "zoneData",
            "security.user",
            "pinverifiedby"

        ])->where("id", $request->alarm_id)->first();

        $icons = (new AlarmNotificationController())->getGoogleMapIcons();


        $pdf = Pdf::loadView('alarm_reports/alarm_event_notes_track', compact('alarm', 'icons'))->setPaper('A4', 'potrait');
        return $pdf->stream($request->alarm_id . "_event_track_notes.pdf");
    }

    //----------------------------------------------------------------

    public function sample_pdf_pagenumbers()
    {
        $reports =   AttendanceLog::where("UserID", "1001")->get();
        $pdf =  App::make('dompdf.wrapper');

        //$pdf->getDomPDF()->set_option("enable_php", true);//do not delete this line 
        $pdf->loadView('alarm_reports/sample_with_page_numbers', compact('reports'))->setPaper('A4', 'potrait');
        return $pdf->stream('invoice.pdf');
    }
}
