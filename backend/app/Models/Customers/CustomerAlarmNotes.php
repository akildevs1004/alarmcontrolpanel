<?php

namespace App\Models\Customers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAlarmNotes extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['picture_raw'];
    public function getPictureAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return asset('customers/notes/' . $value);
    }
    public function getPictureRawAttribute($value)
    {
        $arr = explode('customers/notes/', $this->picture);
        return    $arr[1] ?? '';
    }
}
