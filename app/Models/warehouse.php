<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = 'warehouse';

    protected $fillable = [
        'sku_id',
        'transaction_number',
        'pcs',
        'checker',
        'expiry_date',
        'remarks',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'sku_id');
    }
}
