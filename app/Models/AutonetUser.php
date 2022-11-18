<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutonetUser extends Model
{
    use HasFactory;
    protected $fillable = ['autonet_id', 'user_id', 'week_id'];

}
