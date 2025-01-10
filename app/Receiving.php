<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receiving extends Model
{
    use HasFactory;

    protected $table = 'receivinglist';

    protected $fillable = [
        'sku',
        'transaction_number',
        'pcs',
        'checker',
        'expiry_date',
        'remarks',
    ];
}