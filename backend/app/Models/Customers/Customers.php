<?php

namespace App\Models\Customers;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function devices()
    {
        return $this->hasMany(Device::class, "customer_id", "id");
    }
    public function contacts()
    {
        return $this->hasMany(CustomerContacts::class, "customer_id", "id")->where("display_order", "!=", 0)->orderBy("display_order", "asc");
    }
    public function primary_contact()
    {
        return $this->hasOne(CustomerContacts::class, "customer_id", "id")->where("address_type", "primary");
    }
    public function secondary_contact()
    {
        return $this->hasOne(CustomerContacts::class, "customer_id", "id")->where("address_type", "secondary");
    }
    public function getProfilePictureAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return asset('customers/building/' . $value);
    }
}
