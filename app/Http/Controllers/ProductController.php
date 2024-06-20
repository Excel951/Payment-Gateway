<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
