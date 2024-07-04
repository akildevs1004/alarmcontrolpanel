<?php

namespace App\Models\Customers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerContacts extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function getProfilePictureAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return asset('customers/contacts/' . $value);
    }
}
