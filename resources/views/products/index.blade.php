<head>
    <link rel="stylesheet" href="{{ asset('css/product-list.css') }}">
</head>
<div class="container">
    <h1 class="text-center my-5">Products</h1>

    @if ($products->isEmpty())
        <p class="text-center">No products available at the moment.</p>
    @else
        <div class="product-grid">
            @foreach ($products as $product)
                <div class="product-card">
                    <h2>{{ $product->name }}</h2>
                    <p>{{ $product->description }}</p>
                    <p><strong>Price:</strong> €{{ number_format($product->price, 2) }}</p>
                    <p><strong>Stock:</strong> {{ $product->stock }}</p>

                    @if ($product->stock > 0)
                        <form action="{{ route('basket.add', $product->id) }}" method="POST">
                            @csrf
                            <label for="quantity_{{ $product->id }}">Quantity:</label>
                            <select id="quantity_{{ $product->id }}" name="quantity">
                                @for ($i = 1; $i <= $product->stock; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <button type="submit" class="btn">Add to Basket</button>
                        </form>
                    @else
                        <button class="btn disabled">Out of Stock</button>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

