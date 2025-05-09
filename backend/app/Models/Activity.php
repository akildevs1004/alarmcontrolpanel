<?php

namespace App\Models;

use App\Models\Customers\Customers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['date_time'];

    /**
     * Get the user that owns the Activity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->user()->with("employee");
    }
    public function company()
    {
        return $this->hasOne(Company::class, "id", "company_id");
    }
    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
    }
    public function role()
    {
        return $this->hasOne(Role::class, "id", "role_id");
    }
    public function customer()
    {
        return $this->belongsTo(Customers::class, "customer_id", "id");
    }


    public function getDateTimeAttribute()
    {
        return date("d M Y  H:i", strtotime($this->created_at));
    }
}
