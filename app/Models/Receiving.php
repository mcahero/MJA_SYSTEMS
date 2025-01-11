<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Receiving extends Model
{
    use HasFactory;

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
        return $this->hasMany(Product::class, 'sku_id', 'product_sku');
    }
    
}
