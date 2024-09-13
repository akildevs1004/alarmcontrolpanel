<?php

namespace App\Models;

use App\Models\Customers\Customers;
use App\Models\Customers\SecurityLogin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ["customer", "operator"];

    public function customer()
    {
        return $this->belongsTo(Customers::class, "customer_id", "id");
    }

    public function operator()
    {
        return $this->belongsTo(SecurityLogin::class, "operator_id", "id");
    }
}
