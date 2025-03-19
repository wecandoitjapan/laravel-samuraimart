<div class="container">
    <!-- 大分類 -->
   @foreach ($major_category_names as $major_category_name)
       <h2>{{ $major_category_name }}</h2>
       <!-- 大分類に属するカテゴリー -->
       @foreach ($categories as $category)
           @if ($category->major_category_name === $major_category_name)
           <!-- カテゴリーの表示 -->
           <label class="samuraimart-sidebar-category-label"><a href="{{ route('products.index', ['category' => $category->id]) }}">{{ $category->name }}</a></label> 
           @endif
       @endforeach
   @endforeach
</div>