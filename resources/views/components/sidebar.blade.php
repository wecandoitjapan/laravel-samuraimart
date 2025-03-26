<div class="container">
    <!-- 大分類 -->
    @foreach ($major_categories as $major_category)
    <h2>{{ $major_category->name }}</h2>
       <!-- 大分類に属するカテゴリー -->
       @foreach ($categories as $category)
            @if ($category->major_category_id === $major_category->id)
           <!-- カテゴリーの表示 -->
                <label class="samuraimart-sidebar-category-label"><a href="{{ route('products.index', ['category' => $category->id]) }}">{{ $category->name }}</a></label> 
           @endif
       @endforeach
   @endforeach
</div>