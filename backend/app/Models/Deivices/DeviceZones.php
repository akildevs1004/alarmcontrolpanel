<?php

namespace App\Models\Deivices;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceZones extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'device_sensor_zones';

    public function device()
    {

        return $this->hasOne(Device::class, "id", "device_id");
    }
}
