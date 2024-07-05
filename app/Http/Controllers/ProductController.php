<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Xendit\Configuration;
use Xendit\Customer\CustomerApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;
use Xendit\XenditSdkException;

class ProductController extends Controller
{
    public function index(Request $request) {
        // $products = Product::all();

        // return view('products.index', compact('products'));
        
        try {
            $products = Product::query();
    
            if ($request->has('sort') and $request->input('sort') != 'default') {
                $sort = $request->input('sort');
    
                // split the sort
                $keywords = preg_split('/-/', $sort);
    
                $desc = $keywords[1] == 'Asc' ? false : true;
    
                if ($desc) {
                    $products = $products->orderByDesc(strtolower($keywords[0]));
                } else {
                    $products = $products->orderBy(strtolower($keywords[0]));
                }
            }

            if ($request->has('search')) {
                $keyword = $request->input('search');

                $products = $products
                    ->where('name', 'like', "%$keyword%")
                    ->orWhere('description', 'like', "%$keyword%");
                // dd($products);
            }

            $products = $products->paginate(10);
    
            return view('products.index', compact('products'));
        } catch (\Throwable $th) {
            // dd($th);
            return redirect('products');
        }
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
        Configuration::setXenditKey(env('XNDT_KY'));

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
        $createXenditInvoice = $this->createXenditInvoice($order->no_invoice, $total, $user);

        return redirect()->away($createXenditInvoice['invoice_url']);
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

    public function createXenditInvoice($noInvoice, $total, $user) {
        $apiInstance = new InvoiceApi();
        $invoiceRequest = new CreateInvoiceRequest([
            'external_id' => $noInvoice,
            'amount' => $total,
            'invoice_duration' => 172800,
            'description'=>"TEST INVOICE",
            'currency' => 'IDR',
            'reminder_time' => 1,
            'customer' => [
                'id' => $user->xendit_customer_id,
                'given_name' => $user->name,
            ]
        ]);

        try {
            $result = $apiInstance->createInvoice($invoiceRequest);

            return $result;
        } catch (XenditSdkException $e) {
            echo 'Exception when calling InvoiceApi->createInvoice: ', $e->getMessage(), PHP_EOL;
            echo 'Full Error: ', json_encode($e->getFullError()), PHP_EOL;
        }
    }
}
