<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Basket;
use App\Mail\PurchaseEmail;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    public const VAT = 0.21;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): RedirectResponse|View
    {
        /** @var User $user */
        $user = Auth::user();

        $basket = Basket::where('user_id', $user->id)->where('completed', false)->first();

        if (! $basket) {
            return redirect()->route('basket.index')->with('error', 'Your basket is empty.');
        }

        $products = $basket->items()->with('product')->get()->map(function ($item) {
            return [
                'product'  => $item->product,
                'quantity' => $item->quantity,
                'total'    => $item->quantity * $item->product->price,
            ];
        });

        $subTotal = $products->sum('total');
        $vat = $subTotal * self::VAT;

        $discountCode = DiscountCode::where('user_id', $user->id)
            ->where('is_used', false)
            ->first();

        $applyDiscount = $request->boolean('apply_discount');
        $discount = ($applyDiscount && $discountCode) ? min($discountCode->amount, $subTotal) : 0;
        $total = $subTotal + $vat - $discount;

        return view('checkout.index', compact('products', 'subTotal', 'vat', 'discount', 'applyDiscount', 'total', 'discountCode'));
    }

    public function complete(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $basket = Basket::where('user_id', $user->id)->where('completed', false)->first();

        if (! $basket) {
            return redirect()->route('basket.index')->with('error', 'Your basket is empty.');
        }

        $basket->update(['completed' => true]);

        self::applyDiscountlogic($user, $request);

        Mail::to($user->email)->later(now()->addMinutes(15), new PurchaseEmail());

        return redirect()->route('home')->with('success', 'Checkout completed successfully!');
    }

    private static function applyDiscountlogic(User $user, Request $request): void
    {
        if ($request->boolean('apply_discount')) {
            $discountCode = DiscountCode::where('user_id', $user->id)
                ->where('is_used', false)
                ->first();

            if ($discountCode) {
                $discountCode->update(['is_used' => true]);
            }
        }

        $hasUnusedDiscountCodes = DiscountCode::where('user_id', $user->id)
            ->where('is_used', false)
            ->exists();

        if (! $hasUnusedDiscountCodes) {
            DiscountCode::create([
                'user_id' => $user->id,
                'amount'  => 5,
                'is_used' => false,
            ]);
        }
    }
}
