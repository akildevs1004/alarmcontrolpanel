<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesQuotations extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function BuildingType()
    {
        return $this->belongsTo(CustomersBuildingTypes::class, "business_type_id", "id");
    }

    public function ProductService()
    {
        return $this->belongsTo(DeviceProductServices::class, "device_product_service_id", "id");
    }
}
