<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'reference_no',
        'reference_id',
        'is_free_user',
        'is_banned',
        'role_id',
        'phone_no',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $sortable = [
        'id',
        'name',
        'email',
        'password',
        'reference_no',
        'reference_id',
        'is_free_user',
        'is_banned',
        'role_id',
        'phone_no',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function referedBy(){
        return $this->belongsTo(User::class,'reference_id');
    }
}
