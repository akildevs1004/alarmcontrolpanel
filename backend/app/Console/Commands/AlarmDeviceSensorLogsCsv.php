<?php

namespace App\Console\Commands;

use App\Http\Controllers\Customers\Api\ApiAlarmDeviceSensorLogsController;
use App\Http\Controllers\SDKController;
use App\Models\AccessControlTimeSlot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AlarmDeviceSensorLogsCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:alarm_device_sensor_logs_csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'AlarmDeviceSensorLogsCsv';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $result = (new ApiAlarmDeviceSensorLogsController)->readCSVLogFile();

        echo  json_encode($result);
    }
}
