<?php

namespace App\Models\Customers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPayments extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function customer()
    {
        return $this->belongsTo(Customers::class, "customer_id", "id");
    }
}
