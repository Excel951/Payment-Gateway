<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;
use App\Models\User;
use Faker\Extension\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Xendit\Configuration;
use Xendit\Customer\CustomerApi;
use Xendit\Customer\CustomerRequest;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
    });

    Route::prefix('casheers')->group(function () {
        Route::get('/', function(Request $request) {
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
                }
    
                $products = $products->paginate(10);
        
                return view('casheer.index', compact('products'));
            } catch (\Throwable $th) {
                // dd($th);
                return redirect('casheer.index');
            }
        })->name('casheer.index');

        Route::get('/invoice', function (Request $request) {
            Configuration::setXenditKey(env('XNDT_KY'));

            $userLogIn = Auth::user();
            // Cek 
            if (!$userLogIn->xendit_customer_id) {        
                Configuration::setXenditKey(env('XNDT_KY'));
        
                $user = User::find($userLogIn->id);
        
                // Create customer
                $customerAPI=new CustomerApi();
                $customerRequest=new CustomerRequest([
                    'individual_detail'=>[
                        // 'given_names'=>$data['name'],
                        'given_names'=>$userLogIn->name,
                    ],
                    // 'client_name'=>$data['name'],
                    'client_name'=>$userLogIn->name,
                    'reference_id'=>"{$request->name_product} - {$userLogIn->id}",
                    'email'=>$userLogIn->email,
                    'mobile_number'=>'+6285184920283',
                ]);
        
                try {
                    $customer=$customerAPI->createCustomer(customer_request:$customerRequest);
                    $user->xendit_customer_id = $customer['id'];
                    $user->save();

                    // return redirect(route('invoice.create', compact($user, $customer)));
                } catch (\Xendit\XenditSdkException $e) {
                    Log::error("XenditService@createXenditCustomer: {$e->getMessage()}");
        
                    echo 'Exception when calling CustomerApi->createCustomer.', $e->getMessage(), PHP_EOL;
                    echo 'Full Error.', json_encode($e->getFullError()), PHP_EOL;
                }
            }

            $invoiceApi = new InvoiceApi();
            $invoiceRequest = new CreateInvoiceRequest([
                'external_id' => strval(random_int(100, 1000000)),
                'amount' => $request->price,
                'invoice_duration' => 172800,
                'description'=>"{$request->name} {$request->name}",
                'currency' => 'IDR',
                'reminder_time' => 1,
                'customer' => [
                    'id' => $userLogIn->xendit_customer_id,
                //     'mobile_number' => Helper::formatPhoneNumber($data['customer_mobile_number']),
                //     'given_names' => $data['customer_given_names'],
                //     'email' => $data['customer_email'],
                //     'customer_id' => $data['customer_customer_id'],
                ],
                // 'success_redirect_url' => $data['success_redirect_url'],
            ]);
            // $for_user_id = $userLogIn->xendit_customer_id;

            try {
                $result = $invoiceApi->createInvoice($invoiceRequest);

                return redirect(url($result['invoice_url']));
            } catch (\Xendit\XenditSdkException $e) {
                echo 'Exception when calling InvoiceApi->createInvoice: ', $e->getMessage(), PHP_EOL;
                echo 'Full Error: ', json_encode($e->getFullError()), PHP_EOL;
            }
        })->name('invoice.create');
    });

    Route::prefix('customers')->group(function () {
        Route::post('/', function (Request $request) {
            $user = Auth::user();
    
            Configuration::setXenditKey(env('XNDT_KY'));
    
            $userLogIn = Auth::user();
            $user = User::find($userLogIn->id);
    
            // Create customer
            $customerAPI=new CustomerApi();
            $customerRequest=new CustomerRequest([
                'individual_detail'=>[
                    // 'given_names'=>$data['name'],
                    'given_names'=>$userLogIn->name,
                ],
                // 'client_name'=>$data['name'],
                'client_name'=>$userLogIn->name,
                'reference_id'=>"{$request->name_product} - {$userLogIn->id}",
                'email'=>$userLogIn->email,
                'mobile_number'=>'+6285184920283',
            ]);
    
            try {
                $customer=$customerAPI->createCustomer(customer_request:$customerRequest);
                $user->xendit_customer_id = $customer['id'];
                $user->save();

                return redirect(route('invoice.create', compact($user, $customer)));
            } catch (\Xendit\XenditSdkException $e) {
                Log::error("XenditService@createXenditCustomer: {$e->getMessage()}");
    
                echo 'Exception when calling CustomerApi->createCustomer.', $e->getMessage(), PHP_EOL;
                echo 'Full Error.', json_encode($e->getFullError()), PHP_EOL;
            }
        })->name('customer.create');
    
        Route::get('/', function() {
            // Configuration::setXenditKey(env('XNDT_KY'));
    
            // $userLogIn = Auth::user();
            // $user = User::find($userLogIn->id);
    
            // // Create customer
            // $customerAPI=new CustomerApi();
            // $customerRequest=new CustomerRequest([
            //     'individual_detail'=>[
            //         // 'given_names'=>$data['name'],
            //         'given_names'=>$userLogIn->name,
            //     ],
            //     // 'client_name'=>$data['name'],
            //     'client_name'=>$userLogIn->name,
            //     'reference_id'=>"Product 1 - 0",
            //     'email'=>$userLogIn->email,
            //     'mobile_number'=>'+6285184920283',
            // ]);
    
            // try {
            //     $customer=$customerAPI->createCustomer(customer_request:$customerRequest);
            //     $user->xendit_customer_id = $customer['id'];
            //     $user->save();
            // } catch (\Xendit\XenditSdkException $e) {
            //     Log::error("XenditService@createXenditCustomer: {$e->getMessage()}");
    
            //     echo 'Exception when calling CustomerApi->createCustomer.', $e->getMessage(), PHP_EOL;
            //     echo 'Full Error.', json_encode($e->getFullError()), PHP_EOL;
            // }
        })->name('customer');
    });
});


require __DIR__.'/auth.php';
