<?php

namespace App\Models;

use App\Models\Customers\AlarmCateogories;
use App\Models\Customers\CustomerAlarmNotes;
use App\Models\Customers\Customers;
use App\Models\Deivices\DeviceZones;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlarmEvents extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $with = ["category"];


    public function customer()
    {
        return $this->belongsTo(Customers::class, "customer_id", "id");
    }
    public function category()
    {
        return $this->belongsTo(AlarmCateogories::class, "alarm_category", "id");
    }
    public function device()
    {
        return $this->belongsTo(Device::class, "serial_number", "serial_number");
    }
    public function notes()
    {
        return $this->hasMany(CustomerAlarmNotes::class, "alarm_id", "id");
    }
    // public function zoneData()
    // {
    //     return $this->belongsTo(Device::class, "serial_number", "serial_number");
    // }
    public function zoneData()
    {
        return  $this->belongsTo(DeviceZones::class, "sensor_zone_id", "id")

            // ->where("area_code", $this->area)

            //->where("area_code", $this->area_code)
        ;
    }
}
