<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:almacenero']);
    }

    public function dashboard()
    {
        $user = auth()->user();
        $warehouse = $user->managedWarehouses()->first();

        if (!$warehouse) {
            return redirect()->route('home')->with('error', 'You are not assigned to any warehouse.');
        }

        $stats = [
            'total_products' => $warehouse->inventory()->count(),
            'pending_orders' => Order::where('warehouse_id', $warehouse->id)
                ->where('status', 'pending')
                ->count(),
            'total_inventory_value' => $warehouse->inventory()
                ->selectRaw('SUM(quantity * cost_price) as total')
                ->value('total') ?? 0,
        ];

        return view('warehouse.dashboard', compact('warehouse', 'stats'));
    }

    public function inventory()
    {
        $user = auth()->user();
        $warehouse = $user->managedWarehouses()->first();

        if (!$warehouse) {
            return redirect()->route('home')->with('error', 'You are not assigned to any warehouse.');
        }

        $inventory = $warehouse->inventory()
            ->with('product')
            ->paginate(15);

        return view('warehouse.inventory', compact('warehouse', 'inventory'));
    }

    public function orders()
    {
        $user = auth()->user();
        $warehouse = $user->managedWarehouses()->first();

        if (!$warehouse) {
            return redirect()->route('home')->with('error', 'You are not assigned to any warehouse.');
        }

        $orders = Order::where('warehouse_id', $warehouse->id)
            ->with(['fromUser', 'toUser', 'salesPoint', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('warehouse.orders.index', compact('warehouse', 'orders'));
    }

    public function showOrder(Order $order)
    {
        $user = auth()->user();
        $warehouse = $user->managedWarehouses()->first();

        if (!$warehouse || $order->warehouse_id !== $warehouse->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['fromUser', 'toUser', 'salesPoint', 'items.product', 'approvedBy']);
        return view('warehouse.orders.show', compact('order', 'warehouse'));
    }

    public function approveOrder(Request $request, Order $order, OrderService $orderService)
    {
        $user = auth()->user();
        $warehouse = $user->managedWarehouses()->first();

        if (!$warehouse || $order->warehouse_id !== $warehouse->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Order is not in pending status.');
        }

        try {
            $orderService->approveOrder($order, $user);
            return redirect()->back()->with('success', 'Order approved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error approving order: ' . $e->getMessage());
        }
    }

    public function rejectOrder(Request $request, Order $order, OrderService $orderService)
    {
        $user = auth()->user();
        $warehouse = $user->managedWarehouses()->first();

        if (!$warehouse || $order->warehouse_id !== $warehouse->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Order is not in pending status.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            $orderService->rejectOrder($order, $user, $request->rejection_reason);
            return redirect()->back()->with('success', 'Order rejected successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error rejecting order: ' . $e->getMessage());
        }
    }

    // Transfer to sales points
    public function createTransfer()
    {
        $user = auth()->user();
        $warehouse = $user->managedWarehouses()->first();

        if (!$warehouse) {
            return redirect()->route('home')->with('error', 'You are not assigned to any warehouse.');
        }

        $salesPoints = $warehouse->salesPoints()->where('is_active', true)->get();
        $inventory = $warehouse->inventory()
            ->with('product')
            ->where('quantity', '>', 0)
            ->get();

        return view('warehouse.transfers.create', compact('warehouse', 'salesPoints', 'inventory'));
    }

    public function storeTransfer(Request $request, OrderService $orderService)
    {
        $user = auth()->user();
        $warehouse = $user->managedWarehouses()->first();

        if (!$warehouse) {
            return redirect()->route('home')->with('error', 'You are not assigned to any warehouse.');
        }

        $request->validate([
            'sales_point_id' => 'required|exists:sales_points,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:0.01',
            'products.*.sale_price' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        $salesPoint = SalesPoint::findOrFail($request->sales_point_id);

        // Verify sales point belongs to this warehouse
        if ($salesPoint->warehouse_id !== $warehouse->id) {
            return redirect()->back()->with('error', 'Sales point does not belong to your warehouse.');
        }

        // Verify inventory availability
        foreach ($request->products as $productData) {
            $inventory = $warehouse->inventory()
                ->where('product_id', $productData['product_id'])
                ->first();
            
            if (!$inventory || $inventory->quantity < $productData['quantity']) {
                $product = Product::find($productData['product_id']);
                return redirect()->back()
                    ->with('error', "Insufficient inventory for product: {$product->name}");
            }
        }

        try {
            $order = $orderService->createTransferOrder(
                $user,
                $warehouse,
                $salesPoint,
                $request->products,
                $request->notes
            );

            return redirect()->route('warehouse.orders.show', $order)
                ->with('success', 'Transfer order created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating transfer: ' . $e->getMessage());
        }
    }
}