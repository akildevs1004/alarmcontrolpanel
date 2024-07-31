<?php

namespace App\Models\Customers;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityLogin extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
    public function getPictureAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return asset('security/' . $value);
    }
}
