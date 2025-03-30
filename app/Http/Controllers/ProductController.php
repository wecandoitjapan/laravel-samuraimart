<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\MajorCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index(Request $request)

    {
        $keyword = $request->keyword;

        $sorts = [
            '新着順' => 'created_at desc',
            '価格が安い順' => 'price asc',
        ];

        $sort_query = [];
        $sorted = "created_at desc";

        if ($request->has('select_sort')) {
            $slices = explode(' ', $request->input('select_sort'));
            $sort_query[$slices[0]] = $slices[1];
            $sorted = $request->input('select_sort');
        }

        if ($request->category !== null) {
            $products = Product::where('category_id', $request->category)->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(12);
            $total_count = Product::where('category_id', $request->category)->count();
            $category = Category::find($request->category);
            $major_category = MajorCategory::find($category->major_category_id);
        // HTTPリクエストにkeywordパラメータが存在する場合（検索ボタンがクリックされた場合）は、その値を使って商品を検索する処理
        } elseif ($keyword !== null) {
            $products = Product::where('name', 'like', "%{$keyword}%")->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(12);
            $total_count = $products->total();
            $category = null;
            $major_category = null;
        } else {
            $products = Product::sortable($sort_query)->orderBy('created_at', 'desc')->paginate(12);
            $total_count = $products->total();
            $category = null;
            $major_category = null;
        }
        // カテゴリーを表示
        $categories = Category::all();
        $major_categories = MajorCategory::all();

        return view('products.index', compact('products', 'category', 'major_category', 'categories', 'major_categories', 'total_count', 'keyword', 'sorts', 'sorted'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    // $categoriesにすべてのカテゴリを保存し、ビューへと渡
    public function create()
    {
        $categories = Category::all();
 
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // データベースへの保存
        $product = new Product();
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->category_id = $request->input('category_id');
        $product->save();

        // データが保存がされた後、リダイレクトを行っている
        return to_route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $reviews = $product->reviews()->paginate(5);
 
        return view('products.show', compact('product', 'reviews'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */

    //  カテゴリの全データが入っている$categories変数をビューに渡
    public function edit(Product $product)
    {
        $categories = Category::all();
 
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->category_id = $request->input('category_id');
        // 更新
        $product->update();

        return to_route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
 
        return to_route('products.index');
    }
}
