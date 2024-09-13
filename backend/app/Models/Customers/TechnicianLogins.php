<?php

namespace App\Models\Customers;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicianLogins extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $appends = ['profile_picture'];
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }


    public function getProfilePictureAttribute($value)
    {
        return asset('technicians/' . $this->picture);
    }
}
