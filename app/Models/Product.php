<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Product extends Model
{
    use HasFactory, Sortable;


    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image',
        'recommend_flag',
        'carriage_flag',
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // レビュー機能　商品とレビュー
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    // withTimestamps()メソッドをつなげることで、中間テーブルの場合もcreated_atカラムやupdated_atカラムの値が自動的に更新される
    public function favorited_users() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
