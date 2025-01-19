@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="my-4">Checkout</h1>

        <table class="table">
            <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($products as $item)
                <tr>
                    <td>{{ $item['product']->name }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>${{ number_format($item['total'], 2) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2"><strong>Subtotal</strong></td>
                <td>${{ number_format($subTotal, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>VAT (21%)</strong></td>
                <td>${{ number_format($vat, 2) }}</td>
            </tr>
            @if ($discountCode)
                <tr>
                    <td colspan="2">
                        <form action="{{ route('checkout.index') }}" method="GET" class="d-inline">
                            <input type="checkbox" id="apply_discount" name="apply_discount" value="1"
                                   {{ $applyDiscount ? 'checked' : '' }}
                                   onchange="this.form.submit()">
                            <label for="apply_discount">Apply Discount Code (€{{ number_format($discountCode->amount, 2) }})</label>
                        </form>
                    </td>
                    <td>-${{ number_format($discount, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td colspan="2"><strong>Total</strong></td>
                <td>${{ number_format($total, 2) }}</td>
            </tr>
            </tfoot>
        </table>

        <form action="{{ route('checkout.complete') }}" method="POST">
            @csrf
            <input type="hidden" name="apply_discount" value="{{ $applyDiscount ? '1' : '0' }}">
            <button type="submit" class="btn btn-success btn-lg">Complete Checkout</button>
        </form>
    </div>
@endsection
