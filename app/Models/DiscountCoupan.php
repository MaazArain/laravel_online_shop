<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCoupan extends Model
{
    use HasFactory;


    protected $table = 'discount_coupans';

    protected $fillable = [
        'code',
        'name',
        'description',
        'max_uses',
        'max_uses_user',
        'type',
        'discount_amount',
        'min_amount',
        'usage_count',
        'starts_at',
        'expires_at',
    ];
}
