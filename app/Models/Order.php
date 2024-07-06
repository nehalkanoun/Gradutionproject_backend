<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable=[
        'product_ID',
        'amount',
        'cart_ID'
        
            ];
            public function cart()
            {
                return $this->belongsTo(Cart::class, 'cart_ID', 'id');
            }
            public function product(){
                return $this->belongsTo(Product::class, 'product_ID');
        
            }
}
