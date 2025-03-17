<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // レビュー機能　商品とレビュー
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
