<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShippingController extends Controller
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('shipping.rajaongkir.api_key');
        $this->baseUrl = config('shipping.rajaongkir.base_url');
    }

    public function getProvinces()
    {
        $provinces = Province::all();
        return response()->json($provinces);
    }

    public function getCities(Request $request)
    {
        $provinceId = $request->query('province_id');
        $cities = City::where('province_id', $provinceId)->get();
        return response()->json($cities);
    }

    public function getCost(Request $request)
    {
        $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string',
        ]);

        // If API key is not set, return a dummy response or handle as needed
        if (empty($this->apiKey)) {
            Log::warning('RajaOngkir API key is not set. Returning empty shipping cost.');
            // You might want to return a default shipping cost or an error
            return response()->json(['error' => 'Shipping cost calculation is currently unavailable.'], 503);
        }

        $response = Http::withHeaders([
            'key' => $this->apiKey,
        ])->post($this->baseUrl . '/cost', [
            'origin' => $request->origin,
            'destination' => $request->destination,
            'weight' => $request->weight,
            'courier' => $request->courier,
        ]);

        if ($response->failed()) {
            Log::error('RajaOngkir API request failed for shipping cost', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            return response()->json(['error' => 'Failed to fetch shipping cost.'], 500);
        }

        return response()->json($response->json()['rajaongkir']['results']);
    }
}