<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'image',
        'status',
        'showHome',
    ];

    public function sub_categories()
    {
        return $this->hasMany(SubCategory::class, 'category_id', 'id'); 
    }
}
