<?php

namespace App;

use App\Models\Product;
use App\Models\UsersCart;

class Cart {
    public static function showCart($userID, $forEdit = false) {
        $cart = UsersCart::where('telegram_user_id', $userID)->first();
        if (!$cart) {
            $cart = UsersCart::create([
                'telegram_user_id' => $userID,
                'cart' => serialize([]),
            ]);
        }
        $cartProducts = [];
        $cartData = unserialize($cart->cart);
        if ($cart && count($cartData) > 0) {
            $txt = "Сейчас в вашей козине:\n\n";
            $total = 0;
            foreach ($cartData as $productID => $count) {
                $product = Product::find($productID);
                if ($forEdit) {
                    $cartProducts[$productID] = $product;
                }
                $txt .= "$product->name:{$product->price} руб. x $count\n";
                $total += $product->price;
            }
            $txt .= "\nСумма без доставки: $total руб.";

        } else {
            $txt = "Ваша корзина пуста";
        }
        return compact('cart', 'txt', 'cartProducts');
    }

    public static function addToCart($user_id, $product_id) {
        $cart = UsersCart::where('telegram_user_id', $user_id)->first();

        if (!$cart) {
            $cart = UsersCart::create([
                'telegram_user_id' => $user_id,
                'cart' => serialize([$product_id => 1]),
            ]);
        } else {
            $cartInfo = unserialize($cart->cart);
            if (isset($cartInfo[$product_id])) {
                $cartInfo[$product_id] = $cartInfo[$product_id] + 1;
            } else {
                $cartInfo[$product_id] = 1;
            }
            $cart->cart = serialize($cartInfo);
            $cart->save();
        }
    }

    public static function removeFromCart($user_id, $product_id) {
        $cart = UsersCart::where('telegram_user_id', $user_id)->first();

        if ($cart) {
            $cart_data = unserialize($cart->cart);
            if (isset($cart_data[$product_id])) {
                unset($cart_data[$product_id]);
            }
            $cart->cart = serialize($cart_data);
            $cart->save();
        }
    }

    public static function increaseInCart($user_id, $product_id) {
        $cart = UsersCart::where('telegram_user_id', $user_id)->first();

        if (!$cart) {
            $cart = UsersCart::create([
                'telegram_user_id' => $user_id,
                'cart' => serialize([$product_id => 1]),
            ]);
            return;
        }

        $cartData = unserialize($cart->cart);

        if (isset($cartData[$product_id])) {
            $cartData[$product_id] = (int)$cartData[$product_id] + 1;
        } else {
            $cartData[$product_id] = 1;
        }

        $cart->cart = serialize($cartData);
        $cart->save();
    }

    public static function decreaseInCart($userID, $productID) {
        $cart = UsersCart::where('telegram_user_id', $userID)->first();

        if (!$cart) {
            return;
        }

        $cartData = unserialize($cart->cart);

        if (isset($cartData[$productID]) && $cartData[$productID] >= 1) {
            $count = (int)$cartData[$productID] - 1;
            if ($count === 0) {
                unset($cartData[$productID]);
            } else {
                $cartData[$productID] = $count;
            }
        }
        $cart->cart = serialize($cartData);
        $cart->save();
    }

    public static function clearCart($chatID) {
        UsersCart::where('telegram_user_id', $chatID)->update(['cart' => serialize([])]);
    }
}
