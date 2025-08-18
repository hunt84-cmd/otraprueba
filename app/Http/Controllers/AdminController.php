<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Warehouse;
use App\Models\SalesPoint;
use App\Models\Product;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{


    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_warehouses' => Warehouse::count(),
            'total_sales_points' => SalesPoint::count(),
            'total_products' => Product::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // User Management
    public function users()
    {
        $users = User::with('role')->paginate(15);
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function createUser()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'email', 'role_id', 'is_active']);
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    // Warehouse Management
    public function warehouses()
    {
        $warehouses = Warehouse::with('manager')->paginate(15);
        return view('admin.warehouses.index', compact('warehouses'));
    }

    public function createWarehouse()
    {
        $managers = User::whereHas('role', function ($query) {
            $query->where('name', 'almacenero');
        })->get();
        return view('admin.warehouses.create', compact('managers'));
    }

    public function storeWarehouse(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:warehouses',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        Warehouse::create($request->all());

        return redirect()->route('admin.warehouses')->with('success', 'Warehouse created successfully.');
    }

    public function editWarehouse(Warehouse $warehouse)
    {
        $managers = User::whereHas('role', function ($query) {
            $query->where('name', 'almacenero');
        })->get();
        return view('admin.warehouses.edit', compact('warehouse', 'managers'));
    }

    public function updateWarehouse(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('warehouses')->ignore($warehouse->id)],
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'manager_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $warehouse->update($request->all());

        return redirect()->route('admin.warehouses')->with('success', 'Warehouse updated successfully.');
    }

    // Sales Point Management
    public function salesPoints()
    {
        $salesPoints = SalesPoint::with(['warehouse', 'manager'])->paginate(15);
        return view('admin.sales-points.index', compact('salesPoints'));
    }

    public function createSalesPoint()
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        $managers = User::whereHas('role', function ($query) {
            $query->where('name', 'punto');
        })->get();
        return view('admin.sales-points.create', compact('warehouses', 'managers'));
    }

    public function storeSalesPoint(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:sales_points',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'warehouse_id' => 'required|exists:warehouses,id',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        SalesPoint::create($request->all());

        return redirect()->route('admin.sales-points')->with('success', 'Sales point created successfully.');
    }

    public function editSalesPoint(SalesPoint $salesPoint)
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        $managers = User::whereHas('role', function ($query) {
            $query->where('name', 'punto');
        })->get();
        return view('admin.sales-points.edit', compact('salesPoint', 'warehouses', 'managers'));
    }

    public function updateSalesPoint(Request $request, SalesPoint $salesPoint)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('sales_points')->ignore($salesPoint->id)],
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'warehouse_id' => 'required|exists:warehouses,id',
            'manager_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $salesPoint->update($request->all());

        return redirect()->route('admin.sales-points')->with('success', 'Sales point updated successfully.');
    }

    // Product Management
    public function products()
    {
        $products = Product::paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        return view('admin.products.create');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:products',
            'name' => 'required|string|max:255',
            'unit' => 'required|in:kg,lb,unidad',
            'description' => 'nullable|string',
        ]);

        Product::create($request->all());

        return redirect()->route('admin.products')->with('success', 'Product created successfully.');
    }

    public function editProduct(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('products')->ignore($product->id)],
            'name' => 'required|string|max:255',
            'unit' => 'required|in:kg,lb,unidad',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $product->update($request->all());

        return redirect()->route('admin.products')->with('success', 'Product updated successfully.');
    }

    // Order Management
    public function orders()
    {
        $orders = Order::with(['fromUser', 'toUser', 'warehouse', 'salesPoint'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['fromUser', 'toUser', 'warehouse', 'salesPoint', 'items.product', 'approvedBy']);
        return view('admin.orders.show', compact('order'));
    }

    // Create warehouse entry order
    public function createWarehouseEntry()
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        return view('admin.orders.warehouse-entry', compact('warehouses', 'products'));
    }

    public function storeWarehouseEntry(Request $request, OrderService $orderService)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:0.01',
            'products.*.unit_price' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        $warehouse = Warehouse::findOrFail($request->warehouse_id);
        
        $order = $orderService->createWarehouseEntryOrder(
            auth()->user(),
            $warehouse,
            $request->products,
            $request->notes
        );

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Warehouse entry order created successfully.');
    }
}