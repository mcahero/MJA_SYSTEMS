<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Receiving extends Model
{
    // Table associated with this model
    protected $table = 'receivinglist';

    // Mass assignable fields
    protected $fillable = [
        'sku_id',
        'transaction_number',
        'pcs',
        'checker',
        'expiry_date',
        'remarks',
    ];

    // Define the relationship with the Product model
    public function product()
    {
        return $this->belongsTo(Product::class, 'sku_id', 'id');
    }
}
