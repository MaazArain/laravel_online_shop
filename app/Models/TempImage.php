<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempImage extends Model
{
    use HasFactory;

    protected $table = 'temp_images';  // Ensure table name is correct

    protected $fillable = ['name']; // Add other fields if necessary
}
