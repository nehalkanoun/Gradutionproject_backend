<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable=[
        'customer_ID',
        
        
        
            ];
            public function customer(){
                return $this->belongsTo(Customer::class, 'customer_ID');
        
            }
            public function orders()
            {
                return $this->hasMany(Order::class, 'cart_ID', 'id');
            }
}
