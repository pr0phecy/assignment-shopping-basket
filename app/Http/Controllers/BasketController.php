<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use JsonException;

class BasketController extends Controller
{
    /**
     * Display the basket page.
     *
     * @throws \JsonException
     */
    public function index(Request $request): Factory|View
    {
        $total = 0;

        if (Auth::check()) {
            $basket = Basket::firstOrCreate(
                ['user_id'      => Auth::id(),
                    'completed' => false]
            );

            $basketItems = $basket->items;
            $products = Product::whereIn('id', $basketItems->pluck('product_id'))->get();
            $basketData = $basketItems->pluck('quantity', 'product_id')->toArray();
        } else {
            $basketData = json_decode($request->cookie('basket', '[]'), true, 512, JSON_THROW_ON_ERROR);
            $products = Product::whereIn('id', array_keys($basketData))->get();
        }

        foreach ($products as $product) {
            $total += $basketData[$product->id] * $product->price;
        }

        return view('basket.index', compact('products', 'basketData', 'total'));
    }

    /**
     * Add a product to the basket.
     *
     * @throws \JsonException
     */
    public function add(Request $request, Product $product): RedirectResponse
    {
        $quantity = $request->input('quantity', 1);

        if (Auth::check()) {
            $basket = Basket::firstOrCreate(
                ['user_id'      => Auth::id(),
                    'completed' => false]
            );

            $basketItem = $basket->items()->firstOrNew(['product_id' => $product->id]);
            $basketItem->quantity = ($basketItem->quantity ?? 0) + $quantity;
            $basketItem->save();
        } else {
            $basket = json_decode($request->cookie('basket', '[]'), true, 512, JSON_THROW_ON_ERROR);
            $basket[$product->id] = ($basket[$product->id] ?? 0) + $quantity;

            return redirect()->route('basket.index')->withCookie(cookie()->forever('basket', json_encode($basket, JSON_THROW_ON_ERROR)));
        }

        return redirect()->route('basket.index');
    }

    /**
     * Remove a product from the basket.
     *
     * @throws JsonException
     */
    public function remove(Request $request, Product $product): RedirectResponse
    {
        if (Auth::check()) {
            $basket = Basket::firstOrCreate(
                ['user_id' => Auth::id()],
                ['completed' => false]
            );

            $basket->items()->where('product_id', $product->id)->delete();
        } else {
            $basket = json_decode($request->cookie('basket', '[]'), true, 512, JSON_THROW_ON_ERROR);
            unset($basket[$product->id]);

            return redirect()->route('basket.index')->withCookie(cookie()->forever('basket', json_encode($basket, JSON_THROW_ON_ERROR)));
        }

        return redirect()->route('basket.index');
    }

    /**
     * Clear the basket.
     *
     * @throws JsonException
     */
    public function clear(): RedirectResponse
    {
        if (Auth::check()) {
            $basket = Basket::firstOrCreate(
                ['user_id' => Auth::id()],
                ['completed' => false]
            );

            $basket->items()->delete();
        } else {
            return redirect()->route('basket.index')->withCookie(cookie()->forever('basket', json_encode([], JSON_THROW_ON_ERROR)));
        }

        return redirect()->route('basket.index');
    }

    /**
     * @throws JsonException
     * @returns int
     */
    public static function getBasketCount(): int
    {
        if (Auth::check()) {
            $basket = Basket::where('user_id', Auth::id())
                ->where('completed', false)
                ->first();

            if ($basket) {
                return $basket->items->sum('quantity');
            }

            return 0;
        }

        $basketData = json_decode(request()->cookie('basket', '[]'), true, 512, JSON_THROW_ON_ERROR);

        return array_sum($basketData);
    }
}
