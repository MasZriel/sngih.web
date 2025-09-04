<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:pending,processing,shipped,delivered,cancelled'],
        ]);

        $newStatus = $request->status;
        $oldStatus = $order->status;

        if ($newStatus === $oldStatus) {
            return redirect()->route('admin.orders.show', $order->id)->with('info', 'Status pesanan tidak berubah.');
        }

        DB::beginTransaction();
        try {
            $updateData = ['status' => $newStatus];
            if ($newStatus === 'cancelled') {
                $updateData['cancellation_reason'] = $request->cancellation_reason;
            }
            $order->update($updateData);

            // Logic for cancellation
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                        // Decrement total_sold only if the order was previously considered a sale
                        if (in_array($oldStatus, ['delivered', 'shipped'])) { // Assuming 'delivered' or 'shipped' count as a sale
                            $product->decrement('total_sold', $item->quantity);
                        }
                    }
                }
                // Notify user of cancellation
                $order->user->notify(new \App\Notifications\OrderCancelledNotification($order));
            }
            // Logic for marking an order as a completed sale
            else if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        // Increment total_sold as the sale is now complete
                        $product->increment('total_sold', $item->quantity);
                    }
                }
            }
            // Add other status transition logic here if needed

            DB::commit();

            return redirect()->route('admin.orders.show', $order->id)->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            // It's good practice to log the actual error for debugging
            // Log::error('Order update failed: ' . $e->getMessage());
            return redirect()->route('admin.orders.show', $order->id)->with('error', 'Gagal memperbarui status pesanan. Silakan coba lagi.');
        }
    }
}
