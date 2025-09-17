<?php

namespace App\Livewire\Website\Cart;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class CartIcon extends Component
{

    public function removeItemFromCart($id)
    {
        $authBoolean = auth('web')->check();
        if ($authBoolean) {
            $cartItem = auth('web')->user()->cart->items()->where('id', $id)->first();
            $cartItem->delete();
            $this->dispatch('updateCart');

            // delete coupon if the last item is deleted
            if (auth('web')->user()->cart->items->count() == 0) {
                auth('web')->user()->cart->update(['coupon' => null]);
            }
        }

        // new code : make event to refresh checkout component in case opened
        $this->dispatch('orderSummaryRefresh');
    }

    #[On('refreshCartIcon')]
    public function render()
    {
        $authBoolean    = auth('web')->check();
        $cartItemsCount = $authBoolean ?  auth('web')->user()->cart->items->count() : 0;
        $cartItems      = $authBoolean ?  auth('web')->user()->cart->items : [];
        return view('livewire.website.cart.cart-icon', [
            'cartItems' => $cartItems,
            'cartItemsCount' => $cartItemsCount,
        ]);
    }
}
