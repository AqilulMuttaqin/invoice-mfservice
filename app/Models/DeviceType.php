<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceType extends Model
{
    use SoftDeletes;

    protected $table = 'device_types';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
