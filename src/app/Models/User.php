<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address_id',
        'img_url'
    ];
    public function address()
    {
        return $this->belongsTo(Address::class,'address_id');
    }
    public function likedProducts()
    {
        return $this->belongsToMany(Product::class,'likes','user_id','product_id');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function purchasedProducts()
    {
        return $this->belongsToMany(Product::class,'purchases','user_id','product_id')
        ->withTimestamps();
    }
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }
}
