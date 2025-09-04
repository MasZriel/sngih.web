<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->with('product') // Eager load the product details
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlist'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->product_id;
        $userId = Auth::id();

        // Check if the item is already in the wishlist
        $existing = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($existing) {
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Produk ini sudah ada di wishlist Anda.', 'is_in_wishlist' => true]);
            }
            return back()->with('info', 'Produk ini sudah ada di wishlist Anda.');
        }

        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Produk telah ditambahkan ke wishlist!', 'is_in_wishlist' => true]);
        }

        return back()->with('success', 'Produk telah ditambahkan ke wishlist!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($productId)
    {
        $wishlistItem = Wishlist::where('user_id', Auth::id())->where('product_id', $productId)->firstOrFail();

        $this->authorize('delete', $wishlistItem);

        $wishlistItem->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Produk telah dihapus dari wishlist.', 'is_in_wishlist' => false]);
        }

        return back()->with('success', 'Produk telah dihapus dari wishlist.');
    }
}