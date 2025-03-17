<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
// レビュー　レビューと商品
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
// レビュー レビューとユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
