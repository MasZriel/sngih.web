<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Notification as MidtransNotification;
use App\Models\User;
use App\Notifications\OrderCancelledForAdmin;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Step 1: Validate stock levels before creating the order
            foreach ($cart as $id => $details) {
                $product = Product::find($id);
                if (!$product || $product->stock < $details['quantity']) {
                    // If stock is insufficient, rollback and redirect back with an error
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', 'Produk "' . ($product->name ?? 'N/A') . '" tidak memiliki stok yang cukup.');
                }
            }

            // Step 2: Create the order
            $totalAmount = 0;
            foreach ($cart as $id => $details) {
                $totalAmount += $details['price'] * $details['quantity'];
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'shipping_deadline' => now()->addDays(2),
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->shipping_address, // Assuming billing is same as shipping
            ]);

            // Step 3: Create order items and decrement stock
            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);

                // Decrement the product stock
                $product = Product::find($id);
                $product->decrement('stock', $details['quantity']);
            }

            // If everything is successful, commit the transaction
            DB::commit();

            // Clear the cart and redirect to success page
            session()->forget('cart');
            return redirect()->route('order.success')->with('success', 'Pesanan Anda telah berhasil dibuat!');

        } catch (\Exception $e) {
            // If any error occurs, rollback the transaction
            DB::rollBack();

            // Log the error and redirect back with a generic error message
            // Log::error($e->getMessage()); // Consider logging the error for debugging
            return redirect()->route('cart.index')->with('error', 'Terjadi kesalahan saat memproses pesanan Anda. Silakan coba lagi.');
        }
    }

    public function success()
    {
        return view('orders.success');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Keranjang Anda kosong. Silakan belanja dulu.');
        }

        $total = 0;
        $totalWeight = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
            $product = Product::find($id);
            if($product) {
                $totalWeight += $product->weight * $details['quantity'];
            }
        }

        return view('checkout.index', compact('cart', 'total', 'totalWeight'));
    }

    public function show(Order $order)
    {
        // Authorize that the user owns the order
        if (Auth::id() !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    public function notificationHandler(Request $request)
    {
        // Set your Merchant Server Key
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');

        try {
            $notif = new MidtransNotification();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid notification'], 400);
        }

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        $order = Order::findOrFail($orderId);
        $oldStatus = $order->status; // Get status before change

        if ($transaction == 'capture') {
            // For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $order->status = 'challenge';
                } else {
                    $order->status = 'paid';
                    if ($oldStatus !== 'paid') {
                        foreach ($order->items as $item) {
                            Product::find($item->product_id)->increment('total_sold', $item->quantity);
                        }
                    }
                }
            }
        } else if ($transaction == 'settlement') {
            $order->status = 'paid';
            if ($oldStatus !== 'paid') {
                foreach ($order->items as $item) {
                    Product::find($item->product_id)->increment('total_sold', $item->quantity);
                }
            }
        } else if ($transaction == 'pending') {
            $order->status = 'pending';
        } else if ($transaction == 'deny') {
            $order->status = 'denied';
            $this->restoreStock($order, $oldStatus);
        } else if ($transaction == 'expire') {
            $order->status = 'expired';
            $this->restoreStock($order, $oldStatus);
        } else if ($transaction == 'cancel') {
            $order->status = 'cancelled';
            $this->restoreStock($order, $oldStatus);
        }

        $order->save();

        return response()->json(['message' => 'Notification handled']);
    }

    protected function restoreStock(Order $order, $oldStatus = null)
    {
        DB::transaction(function () use ($order, $oldStatus) {
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                    // Decrement total_sold only if the order was previously paid
                    if ($oldStatus === 'paid') {
                        $product->decrement('total_sold', $item->quantity);
                    }
                }
            }
        });
    }

    public function cancel(Order $order)
    {
        // Authorize that the user owns the order
        if (Auth::id() !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the order can be cancelled
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        $oldStatus = $order->status; // Should be 'pending'
        
        // Update order status
        $order->status = 'cancelled';
        $order->save();

        // Restore stock
        $this->restoreStock($order, $oldStatus);

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new OrderCancelledForAdmin($order));

        return redirect()->route('orders.show', $order)->with('success', 'Pesanan telah berhasil dibatalkan.');
    }
}
