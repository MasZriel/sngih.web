<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'search' => 'nullable|string|max:100',
            'variant' => 'nullable|string|max:50',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gte:min_price',
            'sort_by' => 'nullable|string|in:newest,popular,price_low_high,price_high_low',
        ]);

        $search = $validated['search'] ?? null;
        $variant = $validated['variant'] ?? null;
        $minPrice = $validated['min_price'] ?? null;
        $maxPrice = $validated['max_price'] ?? null;
        $sortBy = $validated['sort_by'] ?? 'newest';

        $variants = Product::select('variant')->distinct()->pluck('variant');

        $productsQuery = Product::query()
            ->when($search, function ($q, $search) {
                $searchTerms = array_filter(explode(' ', $search));

                if (empty($searchTerms)) {
                    return $q;
                }

                return $q->where(function ($query) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $query->where(function ($subQuery) use ($term) {
                            $subQuery->where('name', 'LIKE', '%' . $term . '%')
                                     ->orWhere('description', 'LIKE', '%' . $term . '%')
                                     ->orWhere('variant', 'LIKE', '%' . $term . '%');

                            if (is_numeric($term)) {
                                $subQuery->orWhere('price', '=', $term)
                                         ->orWhere('harga_diskon', '=', $term);
                            }
                        });
                    }
                });
            })
            ->when($variant, function ($q, $variant) {
                return $q->where('variant', $variant);
            })
            ->when($minPrice, function ($q, $minPrice) {
                return $q->whereRaw('(CASE WHEN harga_diskon IS NOT NULL AND harga_diskon > 0 THEN harga_diskon ELSE price END) >= ?', [$minPrice]);
            })
            ->when($maxPrice, function ($q, $maxPrice) {
                return $q->whereRaw('(CASE WHEN harga_diskon IS NOT NULL AND harga_diskon > 0 THEN harga_diskon ELSE price END) <= ?', [$maxPrice]);
            });

        // Apply sorting
        $priceColumn = 'CASE WHEN harga_diskon IS NOT NULL AND harga_diskon > 0 THEN harga_diskon ELSE price END';
        switch ($sortBy) {
            case 'popular':
                $productsQuery->orderBy('total_sold', 'desc');
                break;
            case 'price_low_high':
                $productsQuery->orderByRaw($priceColumn . ' ASC');
                break;
            case 'price_high_low':
                $productsQuery->orderByRaw($priceColumn . ' DESC');
                break;
            default: // 'newest'
                $productsQuery->latest();
                break;
        }

        $products = $productsQuery->paginate(12)->withQueryString();

        $wishlistedProductIds = [];
        if (auth()->check()) {
            $productIds = $products->pluck('id')->toArray();
            $wishlistedProductIds = auth()->user()->wishlist()->whereIn('product_id', $productIds)->pluck('product_id')->toArray();
        }

        return view('products.index', compact(
            'products',
            'search',
            'variant',
            'variants',
            'minPrice',
            'maxPrice',
            'sortBy',
            'wishlistedProductIds'
        ));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $reviews = $product->reviews()->whereNull('parent_id')->with('user', 'replies.user')->latest()->get();
        $isInWishlist = false;

        if (auth()->check()) {
            $isInWishlist = auth()->user()->wishlist()->where('product_id', $product->id)->exists();
        }

        return view('products.show', compact('product', 'reviews', 'isInWishlist'));
    }

    public function showJson($id)
    {
        $product = Product::findOrFail($id);
        $productData = $product->toArray();
        $productData['image_url'] = asset($product->image);

        return response()->json($productData);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        if (!$query) {
            return response()->json([]);
        }

        $searchTerms = explode(' ', $query);

        $productsQuery = Product::query();

        foreach ($searchTerms as $term) {
            $productsQuery->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%{$term}%"])
                  ->orWhereRaw('LOWER(category) LIKE ?', ["%{$term}%"])
                  ->orWhereRaw('LOWER(variant) LIKE ?', ["%{$term}%"]);
            });
        }

        $products = $productsQuery->get();

        return response()->json($products);
    }

    public function promo()
    {
        $products = Product::whereNotNull('harga_diskon')
                            ->where('harga_diskon', '>', 0)
                            ->latest()
                            ->paginate(12);

        return view('promo', compact('products'));
    }
}