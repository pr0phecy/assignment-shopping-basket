@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="my-4">Your Basket</h1>

        @if ($products->isEmpty())
            <p>Your basket is empty.</p>
        @else
            <table class="table">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $basketData[$product->id] }}</td>
                        <td>€{{ number_format($product->price, 2) }}</td>
                        <td>€{{ number_format($basketData[$product->id] * $product->price, 2) }}</td>
                        <td>
                            <form action="{{ route('basket.remove', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td>€{{ number_format($total, 2) }}</td>
                    <td>
                        <form action="{{ route('basket.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">Clear Basket</button>
                        </form>
                    </td>
                </tr>
                </tfoot>
            </table>

            <div class="text-end">
                <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg">Checkout</a>
            </div>
        @endif
    </div>
@endsection
