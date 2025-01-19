<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\Basket;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Cookie;
use JsonException;

class MergeGuestBasket
{
    /**
     * Handle the event.
     *
     * @throws JsonException
     */
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $guestBasket = json_decode(Cookie::get('basket', '[]'), true, 512, JSON_THROW_ON_ERROR);

        if (! empty($guestBasket)) {
            $userBasket = Basket::firstOrCreate(
                ['user_id' => $user->id, 'completed' => false]
            );

            foreach ($guestBasket as $productId => $quantity) {
                $basketItem = $userBasket->items()->firstOrNew(['product_id' => $productId]);
                $basketItem->quantity = ($basketItem->quantity ?? 0) + $quantity;
                $basketItem->save();
            }

            Cookie::queue(Cookie::forget('basket'));
        }
    }
}
