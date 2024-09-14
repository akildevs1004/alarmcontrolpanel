<?php

namespace App\Models\Customers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketResponses extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ["customer", "operator", "technician", "attachments"];

    public function customer()
    {
        return $this->belongsTo(Customers::class, "customer_id", "id");
    }
    public function attachments()
    {
        return $this->hasMany(TicketResponsesAttachments::class, "ticket_response_id", "id");
    }
    public function operator()
    {
        return $this->belongsTo(SecurityLogin::class, "operator_id", "id");
    }
    public function technician()
    {
        return $this->belongsTo(TechnicianLogins::class, "technician_id", "id");
    }
}
