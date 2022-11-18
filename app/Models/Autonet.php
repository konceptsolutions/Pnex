<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autonet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'percentage', 'bv', 'network_bv','updated_by'];

}
