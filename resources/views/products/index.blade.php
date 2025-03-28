@extends('layouts.app')

@section('content')
<div class="row">
    <!-- カテゴリー表示 -->
<div class="col-2">
@component('components.sidebar', ['categories' => $categories, 'major_categories' => $major_categories])
        @endcomponent
    </div>
   <div class="col-9">
   <!-- 絞り込みができた時の表示 -->
   <div class="container">
            @if ($category !== null)
            <a href="{{ route('products.index') }}">トップ</a> > <a href="#">{{ $major_category->name }}</a> > {{ $category->name }}
                <!-- 絞り込んでいるカテゴリー名を表示 -->
                <h1>{{ $category->name }}の商品一覧{{$total_count}}件</h1>
                <!-- パンくずリストと検索ワードを表示 -->
            @elseif ($keyword !== null)
                <a href="{{ route('products.index') }}">トップ</a> > 商品一覧
                <h1>"{{ $keyword }}"の検索結果{{$total_count}}件</h1>
            @endif
        </div>
        <!-- ソート機能 -->
        <div>
            Sort By
            @sortablelink('id', 'ID')
            @sortablelink('price', 'Price')
            @sortablelink('created_at', 'Create_At')
        </div>
       <div class="container mt-4">
           <div class="row w-100">
               @foreach($products as $product)
               <div class="col-3">
                   <a href="{{route('products.show', $product)}}">
                    <!-- adminで登録した画像を表示する -->
                   @if ($product->image !== "")
                        <img src="{{ asset($product->image) }}" class="img-thumbnail">
                        @else
                        <img src="{{ asset('img/dummy.png')}}" class="img-thumbnail">
                        @endif
                   </a>
                   <div class="row">
                       <div class="col-12">
                           <p class="samuraimart-product-label mt-2">
                               {{$product->name}}<br>
                               <!-- 星評価 -->
                               @if ($product->reviews()->exists())
                            <span class="samuraimart-star-rating" data-rate="{{ round($product->reviews->avg('score') * 2) / 2 }}"></span>
                            {{ round($product->reviews->avg('score'), 1) }}<br>
                                @endif
                               <label>￥{{$product->price}}</label>
                           </p>
                       </div>
                   </div>
               </div>
               @endforeach
           </div>
       </div>
       <!-- カテゴリーで絞り込んだ条件を保持してページング -->
       {{ $products->appends(request()->query())->links() }}
   </div>
</div>
@endsection