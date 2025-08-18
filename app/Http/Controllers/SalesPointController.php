<?php

namespace App\Http\Controllers;

use App\Models\SalesPoint;
use App\Models\Product;
use App\Models\Order;
use App\Models\DailySale;
use App\Services\OrderService;
use App\Services\SalesService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesPointController extends Controller
{


    public function dashboard()
    {
        $user = auth()->user();
        $salesPoint = $user->managedSalesPoints()->first();

        if (!$salesPoint) {
            return redirect()->route('home')->with('error', 'You are not assigned to any sales point.');
        }

        $today = now()->toDateString();
        $todaySales = DailySale::where([
            'sales_point_id' => $salesPoint->id,
            'sale_date' => $today,
        ])->first();

        $stats = [
            'total_products' => $salesPoint->inventory()->count(),
            'pending_orders' => Order::where('sales_point_id', $salesPoint->id)
                ->where('status', 'pending')
                ->count(),
            'today_sales' => $todaySales?->total_amount ?? 0,
            'today_transactions' => $todaySales?->total_transactions ?? 0,
            'total_inventory_value' => $salesPoint->inventory()
                ->selectRaw('SUM(quantity * sale_price) as total')
                ->value('total') ?? 0,
        ];

        return view('sales-point.dashboard', compact('salesPoint', 'stats'));
    }

    public function inventory()
    {
        $user = auth()->user();
        $salesPoint = $user->managedSalesPoints()->first();

        if (!$salesPoint) {
            return redirect()->route('home')->with('error', 'You are not assigned to any sales point.');
        }

        $inventory = $salesPoint->inventory()
            ->with('product')
            ->paginate(15);

        return view('sales-point.inventory', compact('salesPoint', 'inventory'));
    }

    public function orders()
    {
        $user = auth()->user();
        $salesPoint = $user->managedSalesPoints()->first();

        if (!$salesPoint) {
            return redirect()->route('home')->with('error', 'You are not assigned to any sales point.');
        }

        $orders = Order::where('sales_point_id', $salesPoint->id)
            ->with(['fromUser', 'warehouse', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('sales-point.orders.index', compact('salesPoint', 'orders'));
    }

    public function showOrder(Order $order)
    {
        $user = auth()->user();
        $salesPoint = $user->managedSalesPoints()->first();

        if (!$salesPoint || $order->sales_point_id !== $salesPoint->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['fromUser', 'warehouse', 'items.product', 'approvedBy']);
        return view('sales-point.orders.show', compact('order', 'salesPoint'));
    }

    public function approveOrder(Request $request, Order $order, OrderService $orderService)
    {
        $user = auth()->user();
        $salesPoint = $user->managedSalesPoints()->first();

        if (!$salesPoint || $order->sales_point_id !== $salesPoint->id) {
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
        $salesPoint = $user->managedSalesPoints()->first();

        if (!$salesPoint || $order->sales_point_id !== $salesPoint->id) {
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

    // Sales Management
    public function sales()
    {
        $user = auth()->user();
        $salesPoint = $user->managedSalesPoints()->first();

        if (!$salesPoint) {
            return redirect()->route('home')->with('error', 'You are not assigned to any sales point.');
        }

        $inventory = $salesPoint->inventory()
            ->with('product')
            ->where('quantity', '>', 0)
            ->get();

        return view('sales-point.sales.create', compact('salesPoint', 'inventory'));
    }

    public function recordSale(Request $request, SalesService $salesService)
    {
        $user = auth()->user();
        $salesPoint = $user->managedSalesPoints()->first();

        if (!$salesPoint) {
            return redirect()->route('home')->with('error', 'You are not assigned to any sales point.');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $product = Product::findOrFail($request->product_id);

        try {
            $transaction = $salesService->recordSale(
                $salesPoint,
                $product,
                $request->quantity,
                $user
            );

            return redirect()->back()->with('success', 'Sale recorded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error recording sale: ' . $e->getMessage());
        }
    }

    public function dailySales()
    {
        $user = auth()->user();
        $salesPoint = $user->managedSalesPoints()->first();

        if (!$salesPoint) {
            return redirect()->route('home')->with('error', 'You are not assigned to any sales point.');
        }

        $date = request('date', now()->toDateString());
        $dailySale = DailySale::where([
            'sales_point_id' => $salesPoint->id,
            'sale_date' => $date,
        ])->with(['transactions.product'])->first();

        return view('sales-point.sales.daily', compact('salesPoint', 'dailySale', 'date'));
    }

    public function salesReport(Request $request, SalesService $salesService)
    {
        $user = auth()->user();
        $salesPoint = $user->managedSalesPoints()->first();

        if (!$salesPoint) {
            return redirect()->route('home')->with('error', 'You are not assigned to any sales point.');
        }

        $startDate = Carbon::parse($request->get('start_date', now()->startOfMonth()));
        $endDate = Carbon::parse($request->get('end_date', now()->endOfMonth()));

        $report = $salesService->getSalesReport($salesPoint, $startDate, $endDate);

        return view('sales-point.sales.report', compact('salesPoint', 'report', 'startDate', 'endDate'));
    }

    // Return products to warehouse
    public function createReturn()
    {
        $user = auth()->user();
        $salesPoint = $user->managedSalesPoints()->first();

        if (!$salesPoint) {
            return redirect()->route('home')->with('error', 'You are not assigned to any sales point.');
        }

        $inventory = $salesPoint->inventory()
            ->with('product')
            ->where('quantity', '>', 0)
            ->get();

        return view('sales-point.returns.create', compact('salesPoint', 'inventory'));
    }

    public function storeReturn(Request $request, OrderService $orderService)
    {
        $user = auth()->user();
        $salesPoint = $user->managedSalesPoints()->first();

        if (!$salesPoint) {
            return redirect()->route('home')->with('error', 'You are not assigned to any sales point.');
        }

        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        // Verify inventory availability
        foreach ($request->products as $productData) {
            $inventory = $salesPoint->inventory()
                ->where('product_id', $productData['product_id'])
                ->first();
            
            if (!$inventory || $inventory->quantity < $productData['quantity']) {
                $product = Product::find($productData['product_id']);
                return redirect()->back()
                    ->with('error', "Insufficient inventory for product: {$product->name}");
            }
        }

        try {
            $order = $orderService->createReturnOrder(
                $user,
                $salesPoint,
                $salesPoint->warehouse,
                $request->products,
                $request->notes
            );

            return redirect()->route('sales-point.orders.show', $order)
                ->with('success', 'Return order created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating return: ' . $e->getMessage());
        }
    }
}