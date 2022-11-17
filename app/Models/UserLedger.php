<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLedger extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'product_id', 'network_user_id', 'debit', 'credit', 'balance', 'bv','week_id'];

}
