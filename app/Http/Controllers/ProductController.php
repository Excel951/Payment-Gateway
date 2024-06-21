<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        $products = Product::all();

        return view('products.index', compact('products'));
    }

    public function addToCart(Request $request) {
        $product = Product::findOrFail($request->product_id);

        $userId = auth()->id();
        $productCart = $product->carts()->where('user_id', $userId);

        // cek jika ada produknya
        if ($productCart->count()) {
            // jika ada, update qty nya saja
            $cart = $productCart->first();
            $qty = $cart->qty + 1;
            $cart->update([
                'qty' => $qty,
            ]);
        } else {
            // jika tidak, tambahkan produk ke keranjang
            $product->carts()
                ->create([
                    'user_id' => $userId,
                    'qty' => 1,
                    'price' => $product->price,
                ]);
        }

        return redirect()->route('products.carts')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function carts() {
        $userId = auth()->id();
        $userCarts = Cart::where('user_id', $userId)
            ->get();

        return view('products.cart', compact('userCarts'));
    }
}
