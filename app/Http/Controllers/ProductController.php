<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Xendit\Configuration;
use Xendit\Customer\CustomerApi;

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

    public function checkout(Request $request) {
        Configuration::setXenditKey(env('XENDIT_API_KEY'));

        $user = auth()->user();

        $userCarts = $user->carts;

        $total = $userCarts->sum(function ($cart) {
            return $cart->price * $cart->qty;
        });

        // buat orders
        $order = Order::create([
            'user_id' => $user->id,
            'no_invoice' => date('Ymdhis'),
            'total' => $total,
        ]);

        foreach ($userCarts as $userCart) {
            $order->orderDetails()->create([
                'product_id' => $userCart->product_id,
                'qty' => $userCart->qty,
                'price' => $userCart->price,
            ]);
        }

        // buat xendit customer
        if(! $user->xendit_customer_id) {
            $this->createCustomerXendit($user);
        }

        // buat xendit invoice

    }

    public function createCustomerXendit($user) {
        $apiInstance = new CustomerApi();
        $customerRequest = new \Xendit\Customer\CustomerRequest([
            'client_name' => $user->name,
            'reference_id' => $user->id . '',
            'individual_detail' => [
                'given_names' => $user->name,
            ],
        ]);

        try {
            $result = $apiInstance->createCustomer(null, null, $customerRequest);
            $user->xendit_customer_id = $result['id'];
            $user->save();
        } catch (\Xendit\XenditSdkException $e) {
            echo 'Exception when calling CustomerApi->createCustomer: ', $e->getMessage(), PHP_EOL;
            echo 'Full Error: ', json_encode($e->getFullError()), PHP_EOL;
        }
    }
}
