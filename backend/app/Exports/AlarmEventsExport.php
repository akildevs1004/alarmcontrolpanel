<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class AlarmEventsExport implements FromCollection, WithHeadings,  ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            "Event Id ",
            "Customer",
            "Property",
            "Address",
            "City",
            "Type",
            "Event Time",
            "Closed Time",
            "Priority",
            "Resolved Time(HH:MM)",

        ];
    }

    // public function map($row): array
    // {
    //     return [
    //         $row['date'],
    //         (string)$row['employee']["employee_id"] ?? "---",
    //         $row['employee']["full_name"] ?? $row['employee']["first_name"] . " " . $row['employee']["last_name"],
    //         $row["in1"] ?? "---",
    //         $row["out1"] ?? "---",
    //         $row["in2"] ?? "---",
    //         $row["out2"] ?? "---",
    //         $row["in3"] ?? "---",
    //         $row["out3"] ?? "---",
    //         $row["in4"] ?? "---",
    //         $row["out4"] ?? "---",
    //         $row["in5"] ?? "---",
    //         $row["out5"] ?? "---",
    //         $row["in6"] ?? "---",
    //         $row["out6"] ?? "---",
    //         $row["in7"] ?? "---",
    //         $row["out7"] ?? "---",
    //         $row["total_hrs"] ?? "---",
    //         $row["ot"] ?? "---",
    //         $row["status"] ?? "---",
    //     ];
    // }

    // public function styles($sheet)
    // {
    //     return [
    //         // Apply text format to the 'Email' column
    //         'C' => ['numberFormat' => NumberFormat::FORMAT_TEXT],
    //     ];
    // }
}
