<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // storeアクションはお気に入りに登録する処理
    public function store($product_id)
    {
        Auth::user()->favorite_products()->attach($product_id);

        return back();
    }
    // destroyアクションはお気に入りを解除
    public function destroy($product_id)
    {
        Auth::user()->favorite_products()->detach($product_id);

        return back();
    }
}
