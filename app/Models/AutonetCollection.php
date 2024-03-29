<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutonetCollection extends Model
{
    use HasFactory;

    protected $fillable = ['week_id', 'product_id', 'autonet_id', 'bv'];

}
