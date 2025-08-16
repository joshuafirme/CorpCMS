<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Utils;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $info['app_name'] = isset($data->app_name) ? $data->app_name : '[app_name]';
        $info['app_version'] = isset($data->app_version) ? $data->app_version : '1.0';
        $info['logo'] = isset($data->logo) ? $data->logo : 'assets/img/favicon.png';
        $info['primary_color'] = isset($data->primary_color) ? $data->primary_color : '';
        $info['secondary_color'] = isset($data->secondary_color) ? $data->secondary_color : '';
        $info['meta_description'] = isset($data->meta_description) ? $data->meta_description : '';
        $info['facebook'] = isset($data->facebook) ? $data->facebook : '';
        $info['instagram'] = isset($data->instagram) ? $data->instagram : '';
        $info['linkedin'] = isset($data->linkedin) ? $data->linkedin : '';
        $info['twitter'] = isset($data->twitter) ? $data->twitter : '';
        $info['tiktok'] = isset($data->tiktok) ? $data->tiktok : '';

        $data = Utils::arrayToObject($info);
        $data = [
            'data' => $data,
            'product' => Product::all()
        ];

        return view('admin.product.main', $data);
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
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_slug' => 'required|string|max:255|unique:products,product_slug',
            'product_description' => 'required|string',
            'product_price' => 'required',
            'qty' => 'required|integer|min:1',
            'status' => 'required',
            'product_img' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Store image
        if ($request->hasFile('product_img')) {
            $path = $request->file('product_img')->store('products', 'public');
            $validated['product_img'] = '/storage/' . $path;
        }

        Log::info($validated);
        // return $validated;
        Product::create($validated);

        return response()->json(['message' => 'Product created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
