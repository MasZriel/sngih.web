<?php

namespace App\Http\Controllers;

use App\Models\StockNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockNotificationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'email' => 'nullable|email|required_without:user_id',
        ]);

        $data = ['product_id' => $request->product_id];

        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        } else {
            $data['email'] = $request->email;
        }

        StockNotification::firstOrCreate($data);

        return back()->with('success', 'Terima kasih! Anda akan diberi tahu saat produk tersedia kembali.');
    }
}