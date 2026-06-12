<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'product_id',
        'payment',
        'address_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'buy_user_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
