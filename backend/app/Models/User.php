<?php

namespace App\Models;

use App\Models\Customers\Customers;
use App\Models\Customers\SecurityLogin;
use App\Models\Customers\TechnicianLogins;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'user_type',
    //     'name',
    //     'email',
    //     'password',
    //     'role_id',
    //     'company_id',
    //     'branch_id',
    //     'is_master',
    //     'first_login',
    //     'reset_password_code',
    //     'employee_role_id',
    //     'email_verified_at',
    //     'enable_whatsapp_otp',
    //     'web_login_access',

    // ]; 
    protected $appends = ['profile_picture'];
    protected $guarded = [];
    protected $with = ['assigned_permissions'];

    public function assigned_permissions()
    {
        return $this->hasMany(RolePermissions::class, 'role_id', 'role_id');
    }
    public function getProfilePictureAttribute($value)
    {
        return asset('users/' . $this->picture);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime:d-M-y',
    ];

    // public function company()
    // {
    //     return $this->hasOne(Company::class);
    // }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function branchLogin()
    {
        return $this->hasOne(CompanyBranch::class, 'user_id');
    }

    public function employeeData()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'id');
    }
    public function employee()
    {
        return $this->hasOne(Employee::class,  "user_id", "id");
    }


    public function role()
    {
        return $this->belongsTo(Role::class, "role_id")->withDefault([
            "name" => "---",
        ]);
    }

    public function employee_role()
    {
        return $this->belongsTo(Role::class, "employee_role_id");
    }

    // public function role()
    // {
    //     return $this->belongsTo(Role::class);
    // }

    protected static function boot()
    {
        parent::boot();

        // Order by name DESC
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class, "id", "user_id");
    }
    public function security()
    {
        return $this->belongsTo(SecurityLogin::class, "id", "user_id");
    }
    public function technician()
    {
        return $this->belongsTo(TechnicianLogins::class, "id", "user_id");
    }
}
