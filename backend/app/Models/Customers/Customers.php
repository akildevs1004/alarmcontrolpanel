<?php

namespace App\Models\Customers;

use App\Models\AlarmEvents;
use App\Models\CustomersBuildingTypes;
use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['devices', 'buildingtype',  'primary_contact', 'secondary_contact', "profilePictures"];


    public function devices()
    {
        return $this->hasMany(Device::class, "customer_id", "id");
    }
    public function buildingtype()
    {
        return $this->belongsTo(CustomersBuildingTypes::class, "building_type_id", "id");
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

    public function alarm_events()
    {
        return $this->hasMany(AlarmEvents::class, 'customer_id', 'id')->where("alarm_status", 1);
    }

    public function latest_alarm_event()
    {
        return $this->hasOne(AlarmEvents::class, 'customer_id', 'id')->where("alarm_status", 1)->latest();
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function photos()
    {
        return $this->hasmany(CustomerBuildingPictures::class, 'customer_id', 'id');
    }

    public function profilePictures()
    {
        return $this->hasMany(CustomerBuildingPhotos::class, "customer_id", 'id');
    }
    public function getProfilePictureAttribute()
    {
        // Get the first profile picture from the related table
        $profilePicture = $this->profilePictures()->first();

        if (!$profilePicture || !$profilePicture->picture) {
            return null;
        }
        return $profilePicture->picture;


        // return asset('customers/building/' . $profilePicture->picture);
    }
    // public function getProfilePictureAttribute($value)
    // {
    //     if (!$value) {
    //         return null;
    //     }
    //     return asset('customers/building/' . $value);
    // }
}
