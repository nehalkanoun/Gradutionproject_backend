<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'price',
        'seller_ID'
    
        
            ];

            public function order(){
                return $this->hasMany(Order::class);
        
            }
            public function seller(){
                return $this->belongsTo(Seller::class, 'seller_ID');
        
            }
}
