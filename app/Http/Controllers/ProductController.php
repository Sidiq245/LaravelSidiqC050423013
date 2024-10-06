<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        //get all products
        $products = Product::latest()->paginate(10);

        //render view with products
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product
     * 
     * @return View
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * Store a newly created product in storage
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        //validate form with custom error messages
        $request->validate([
            'image'                 => 'required|image|mimes:jpeg,jpg,png|max:9048',
            'title'                 => 'required|min:5',  
            'description'           => 'required|min:10',
            'price'                 => 'required|numeric',
            'stock'                 => 'required|numeric'
        ], [
            'image.required'       => 'Image is required',
            'title.required'       => 'Title is required and must be at least 5 characters',
            'description.required' => 'Description is required and must be at least 10 characters',
            'price.required'       => 'Price is required and must be a number',
            'stock.required'       => 'Stock is required and must be a number',
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        //create product
        Product::create([
            'image'         => $image->hashName(),
            'title'         => $request->title,
            'description'   => $request->description,
            'price'         => $request->price,
            'stock'         => $request->stock
        ]);

        //redirect to index with success message
        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }
}
