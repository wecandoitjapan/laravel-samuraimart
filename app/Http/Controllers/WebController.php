<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\MajorCategory;
use App\Models\Product;

class WebController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $major_categories = MajorCategory::all();

        // 商品の登録日時（created_at）でソートして、新しい順に4つ取得してビューに渡す
        $recently_products = Product::orderBy('created_at', 'desc')->take(4)->get();

        // おすすめフラグがONの商品を3つ取得してビューに渡す
        $recommend_products = Product::where('recommend_flag', true)->take(3)->get();

        // 注目商品の追加
        // 各商品の平均評価を算出し、その平均評価が高い順に並べ替え
        $featured_products = Product::withAvg('reviews', 'score')->orderBy('reviews_avg_score', 'desc')->take(4)->get();

        return view('web.index', compact('major_categories', 'categories', 'recently_products', 'recommend_products','featured_products'));
    }
}
