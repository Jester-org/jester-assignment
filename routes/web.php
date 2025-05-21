<?php
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpiryDateController;
use App\Http\Controllers\InventoryAdjustmentController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseItemController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleItemController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaxRateController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/debug-roles', function () {
    if (Auth::check()) {
        return response()->json([
            'user' => Auth::user()->name,
            'email' => Auth::user()->email,
            'roles' => Auth::user()->getRoleNames(),
        ]);
    }
    return response()->json(['error' => 'Not authenticated'], 401);
})->name('debug.roles');

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/home', [DashboardController::class, 'index'])->middleware(['auth', 'admin'])->name('dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('tax-rates', TaxRateController::class);
    Route::resource('products', ProductController::class);
    Route::resource('promotions', PromotionController::class);
    Route::resource('inventories', InventoryController::class);
    Route::resource('inventory-adjustments', InventoryAdjustmentController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::resource('purchase-items', PurchaseItemController::class);
    Route::resource('sales', SaleController::class);
    Route::resource('sale-items', SaleItemController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('payment-methods', PaymentMethodController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('reports', ReportController::class);
    Route::resource('audit-logs', AuditLogController::class);
    Route::resource('batches', BatchController::class);
    Route::resource('expiry-dates', ExpiryDateController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('leave-types', LeaveTypeController::class);
    Route::resource('leaves', LeaveController::class);
});

require __DIR__.'/auth.php';

