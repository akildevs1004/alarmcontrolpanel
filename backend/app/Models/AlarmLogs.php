<?php

namespace App\Models;

use App\Models\Deivices\DeviceZones;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AlarmLogs extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:d-M-y',
    ];
    public function device()
    {
        return $this->belongsTo(Device::class, "serial_number", "serial_number");
    }
    // public function sensorzones()
    // {
    //     return $this->belongsTo(DeviceZones::class, "id", "zone_id");
    // }

    protected static function boot()
    {
        parent::boot();

        // Order by name ASC
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }
}
