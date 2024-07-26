<?php

namespace App\Console;

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\SDKController;
use App\Models\AccessControlTimeSlot;
use App\Models\Company;
use App\Models\DeviceActivesettings;
use App\Models\PayrollSetting;
use App\Models\ReportNotification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule
            ->command('task:alarm_device_sensor_logs_csv')
            ->everyMinute()
            ->appendOutputTo(storage_path("kernal_logs/" . date("d-M-y") . "-alarm-device-sensor-logs-csv.log")); // 

        $schedule
            ->command('task:alarm_device_sensor_check_hearbeat_minutes')
            ->hourly()
            ->appendOutputTo(storage_path("kernal_logs/" . date("d-M-y") . "-alarm-device-sensor--heartbeat-logs-csv.log")); // 

        // $schedule
        //     ->command('task:sync_alarm_logs_update_start_end_time')
        //     ->everyMinute()
        //     ->appendOutputTo(storage_path("kernal_logs/" . date("d-M-y") . "-alarm-process-logs.log")); // 

        $monthYear = date("M-Y");

        $schedule
            ->command("task:files-delete-old-log-files")

            ->dailyAt('23:30')
            //->withoutOverlapping()
            ->appendOutputTo(storage_path("kernal_logs/$monthYear-delete-old-logs.log"))
            ->runInBackground(); //->emailOutputOnFailure(env("ADMIN_MAIL_RECEIVERS"));  

        $schedule->call(function () {
            $count = Company::where("is_offline_device_notificaiton_sent", true)->update(["is_offline_device_notificaiton_sent" => false, "offline_notification_last_sent_at" => date('Y-m-d H:i:s')]);
            info($count . "companies has been updated");
        })->dailyAt('00:00');



        $schedule
            ->command('task:check_device_health')
            ->hourly()
            ->between('7:00', '23:59')
            //->withoutOverlapping()
            ->appendOutputTo(storage_path("kernal_logs/$monthYear-devices-health.log")); //->emailOutputOnFailure(env("ADMIN_MAIL_RECEIVERS"));



    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
