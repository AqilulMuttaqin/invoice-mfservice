<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_code',
        'device_type_id',
        'device_name',
        'imei_serial_number',
        'customer_name',
        'customer_phone',
        'complaint',
        'physical_condition',
        'technician_notes',
        'received_date',
        'estimated_finish_day',
        'completed_date',
        'picked_up_date',
        'status',
        'remaining_warranty_claim',
        'warranty_days',
        'grand_total',
    ];

    protected $casts = [
        'received_date' => 'date',
        'estimated_finish_day' => 'decimal:30,0',
        'completed_date' => 'date',
        'picked_up_date' => 'date',
        'remaining_warranty_claim' => 'decimal:10,0',
        'warranty_days' => 'decimal:10,0',
        'grand_total' => 'decimal:12,2',
    ];

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
