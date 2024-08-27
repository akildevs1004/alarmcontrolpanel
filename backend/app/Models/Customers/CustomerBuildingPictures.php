<?php

namespace App\Models\Customers;

use App\Models\Plotting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBuildingPictures extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getPictureAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return asset('customers/building/' . $value);
    }

    public function photoPlottings()
    {
        return $this->hasMany(Plotting::class, "customer_building_picture_id", "id");
    }
}
