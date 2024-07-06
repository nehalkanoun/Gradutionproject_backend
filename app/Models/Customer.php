<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Customer extends Model implements Authenticatable
{
    use HasFactory,HasApiTokens,AuthenticatableTrait;
    protected $fillable=[
'username',
'password',
'phonenumber'

    ];
    protected $hidden =[
        'password',
    ];
    protected $casts=[
        'password'=>'hashed',
    ];

    public function cart(){
        return $this->hasMany(Cart::class);

    }

}
