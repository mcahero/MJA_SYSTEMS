<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Receiving extends Model
{
    protected $table = 'receivinglist';

    protected $fillable = [
        'sku_id',
        'transaction_number',
        'pcs',
        'checker',
        'expiry_date',
        'remarks',
    ];
    public function sku()
    {
        return $this->belongsTo(Product::class, 'sku_id', 'id');
    }

}
