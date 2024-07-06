<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;

class Admin extends Model implements Authenticatable
{
    use HasFactory,HasApiTokens,AuthenticatableTrait;

    protected $fillable=[
        'username',
        'password',
            ];
            protected $hidden =[
                'password',
            ];
            protected $casts=[
                'password'=>'hashed',
            ];
}
