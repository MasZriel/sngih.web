<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity', 1);

        // Stock validation
        if ($product->stock < $quantity) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi. Sisa stok: ' . $product->stock], 422);
            }
            return redirect()->back()->with('error', 'Stok tidak mencukupi. Sisa stok: ' . $product->stock);
        }

        $cart = session()->get('cart', []);

        // Check if product is already in cart
        if (isset($cart[$id])) {
            $newQuantity = $cart[$id]['quantity'] + $quantity;
            if ($product->stock < $newQuantity) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi. Sisa stok: ' . $product->stock], 422);
                }
                return redirect()->back()->with('error', 'Stok tidak mencukupi. Sisa stok: ' . $product->stock);
            }
            $cart[$id]['quantity'] = $newQuantity;
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'quantity' => (int)$quantity,
                'price' => $product->harga_diskon > 0 ? $product->harga_diskon : $product->price,
                'image' => $product->image,
                'product_id' => $product->id
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'cart_count' => count(session()->get('cart', []))
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $orders = [];

        if (auth()->check()) {
            $orders = auth()->user()->orders()->latest()->paginate(5);
        }

        return view('cart.index', compact('cart', 'orders'));
    }

    private function getCartTotal($cart)
    {
        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }
        return $total;
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity');

        if (isset($cart[$id])) {
            if ($product->stock < $quantity) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi. Sisa stok: ' . $product->stock], 422);
                }
                return redirect()->back()->with('error', 'Stok tidak mencukupi. Sisa stok: ' . $product->stock);
            }

            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);

            if ($request->wantsJson()) {
                $newTotal = $this->getCartTotal($cart);
                return response()->json([
                    'success' => true,
                    'message' => 'Keranjang berhasil diperbarui!',
                    'new_total_formatted' => 'Rp ' . number_format($newTotal, 0, ',', '.'),
                    'item_subtotal_formatted' => 'Rp ' . number_format($cart[$id]['price'] * $quantity, 0, ',', '.')
                ]);
            }

            return redirect()->back()->with('success', 'Keranjang berhasil diperbarui!');
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan di keranjang.'], 404);
        }
        return redirect()->back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);

            if ($request->wantsJson()) {
                $newTotal = $this->getCartTotal($cart);
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil dihapus dari keranjang!',
                    'new_total_formatted' => 'Rp ' . number_format($newTotal, 0, ',', '.'),
                    'cart_is_empty' => count($cart) === 0
                ]);
            }

            return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan di keranjang.'], 404);
        }
        return redirect()->back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }
}
