<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'sell_user_id',
        'name',
        'image_path',
        'brand',
        'price',
        'explanation',
        'condition_id'
    ];
    public function categories()
    {
        return $this->belongsToMany(Category::class,'category_product');
    }
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'sell_user_id');
    }
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }
    public function usersWhoLiked()
    {
        return $this->belongsToMany(User::class, 'likes', 'product_id', 'user_id');
    }
}
