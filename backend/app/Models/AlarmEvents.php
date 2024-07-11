<?php

namespace App\Models;


use App\Models\Customers\CustomerAlarmNotes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlarmEvents extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function device()
    {
        return $this->belongsTo(Device::class, "serial_number", "serial_number");
    }
    public function notes()
    {
        return $this->hasMany(CustomerAlarmNotes::class, "alarm_id", "id");
    }
}
