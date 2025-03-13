<!-- 商品データの新規登録ページへのリンクを作成 -->
<a href="{{ route('products.create') }}"> Create New Product</a>

<table>
   <tr>
       <th>Name</th>
       <th>Description</th>
       <th>Price</th>
       <th>Category ID</th>
       <th >Action</th>
   </tr>
   <!-- コントローラから受け取った変数$productsに格納されている商品データを1つずつ$productに渡 -->
   @foreach ($products as $product)
   <tr>
       <td>{{ $product->name }}</td>
       <td>{{ $product->description }}</td>
       <td>{{ $product->price }}</td>
       <td>{{ $product->category_id }}</td>
       <td>
        <!-- 削除フォーム -->
       <form action="{{ route('products.destroy',$product->id) }}" method="POST">
        <!-- 商品の個別ページへのリンク -->
           <a href="{{ route('products.show',$product->id) }}">Show</a>
           <!-- 商品情報の編集ページへのリンク -->
           <a href="{{ route('products.edit',$product->id) }}">Edit</a>
           @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
       </td>
   </tr>
   @endforeach
</table>