<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;
    protected $table = 'customer_addresses';
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'country_id',
        'address',
        'apartment',
        'city',
        'state',
        'zip'
    ];


    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }
}

