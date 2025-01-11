<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $table = 'productlist';

    protected $fillable = [
        'product_fullname',
        'product_shortname',
        'jda_systemname',
        'product_sku',
        'product_barcode',
        'product_type',
        'product_warehouse',
        'product_entryperson',
        'product_remarks'
    ];
    protected $casts = [
        'created_at' => 'datetime:m/d/Y',
        'updated_at' => 'datetime:m/d/Y',
    ];
    public function receiving()
{
    return $this->hasMany(Receiving::class, 'sku_id', 'id');
}
}


