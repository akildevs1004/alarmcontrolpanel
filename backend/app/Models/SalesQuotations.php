<?php

namespace App\Models;

use App\Models\Customers\CustomerPayments;
use App\Models\Customers\Customers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesQuotations extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function Company()
    {
        return $this->belongsTo(Company::class, "company_id", "id");
    }
    public function BuildingType()
    {
        return $this->belongsTo(CustomersBuildingTypes::class, "building_type_id", "id");
    }
    public function invoice()
    {
        return $this->belongsTo(CustomerPayments::class, "invoice_id", "id");;
    }
    public function ProductService()
    {
        return $this->belongsTo(DeviceProductServices::class, "device_product_service_id", "id");
    }
}
