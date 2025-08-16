<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Utils;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
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
        // var_dump($data);
        // return;
        $data = [
            'data' => $data,
            'orders' => Order::all()
        ];
        return view('admin.order.main', $data);
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
            'name' => 'required|string',
            'email' => 'required|email',
            'book' => 'required|string',
            'address' => 'nullable|string',
            'note' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

            $filePath = $file->storeAs('public/payment_proofs', $filename);
            $validated['payment_proof_path'] = 'storage/payment_proofs/' . $filename;
        }

        $order = Order::create($validated);

        return response()->json(['success' => true, 'order' => $order]);
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
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
