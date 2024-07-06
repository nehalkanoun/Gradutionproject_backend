<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Seller extends Model implements Authenticatable
{
    use HasFactory,AuthenticatableTrait,HasApiTokens;
    protected $fillable=[
        'username',
        'password',
        'phonenumber',
        'location',
        'details',
        
            ];
            protected $hidden =[
                'password',
            ];
            protected $casts=[
                'password'=>'hashed',
            ];

            public function product(){
                return $this->hasMany(Product::class);
        
            }


}
