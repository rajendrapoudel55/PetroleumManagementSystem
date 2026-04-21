<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockVoucherController;
use App\Http\Controllers\NozzleEntryController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TaxInvoiceController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\KhaltiPaymentController;
use App\Http\Controllers\LeadRequestController;

/*
|--------------------------------------------------------------------------
| AUTH / LANDING
|--------------------------------------------------------------------------
*/

// ✅ REQUIRED by Laravel
Route::get('/login', function () {
    return view('home'); // landing page
})->name('login');

// Landing page (same view)
Route::get('/', function () {
    return view('home');
});

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('resetpassword.post');

Route::post('/lead-requests', [LeadRequestController::class, 'store'])
    ->name('lead-requests.store');


Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/'); // landing page (home.blade)
})->name('logout');


/*
|--------------------------------------------------------------------------
| DASHBOARD (PROTECTED)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::middleware('role:employee')->group(function () {
        Route::get('/employee', [DashboardController::class, 'employeePortal'])
            ->name('employee.index');

        Route::put('/employee/profile/update', [DashboardController::class, 'updateEmployeeProfile'])
            ->name('employee.update.profile');
    });

    Route::middleware('role:admin,operator')->group(function () {
        Route::get('/admin', function () {
            return view('admin');
        })->name('admin.index');

        Route::put('/admin/profile/update', [DashboardController::class, 'updateProfile'])
            ->name('admin.update.profile');

        Route::get('/api/admin/users', [AuthController::class, 'listUsers']);
        Route::post('/api/admin/users', [AuthController::class, 'createUser']);
        Route::put('/api/admin/users/{id}', [AuthController::class, 'updateUser']);
        Route::delete('/api/admin/users/{id}', [AuthController::class, 'deleteUser']);

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard.index');

        Route::get('/inventory', [DashboardController::class, 'inventory'])
            ->name('inventory.index');

        // Products routes (Inventory)
        Route::post('/api/products', [ProductController::class, 'store'])
            ->name('products.store');

        Route::put('/api/products/{id}', [ProductController::class, 'update'])
            ->name('products.update');

        Route::delete('/api/products/{id}', [ProductController::class, 'destroy'])
            ->name('products.destroy');
    });

    Route::get('/api/products', [ProductController::class, 'index'])
        ->name('products.index');

    Route::get('/stock', [StockController::class, 'index'])
        ->name('stock.index');

    Route::get('/api/stock/reduction/{fuelCode}', [StockController::class, 'reductionStats'])
        ->name('stock.reduction');

    Route::post('/stock/store', [StockController::class, 'store'])
        ->name('stock.store');

    Route::post('/stock/voucher/save', [StockVoucherController::class, 'store'])
        ->name('stock.voucher.save');

    Route::get('/nozzle', [NozzleEntryController::class, 'index'])
        ->name('nozzle.index');

    Route::post('/nozzle', [NozzleEntryController::class, 'store'])
        ->name('nozzle.store');

    Route::put('/nozzle/{id}', [NozzleEntryController::class, 'update'])->name('nozzle.update');
    Route::delete('/nozzle/{id}', [NozzleEntryController::class, 'destroy'])
        ->name('nozzle.destroy');

    Route::get('/taxinvoice', [TaxInvoiceController::class, 'index'])->name('taxinvoice.index');
    Route::get('/api/taxinvoice', [TaxInvoiceController::class, 'getAll']);
    Route::post('/api/taxinvoice', [TaxInvoiceController::class, 'store']);
    Route::put('/api/taxinvoice/{id}', [TaxInvoiceController::class, 'update']);
    Route::delete('/api/taxinvoice/{id}', [TaxInvoiceController::class, 'destroy']);
    Route::post('/api/payments/khalti/initiate', [KhaltiPaymentController::class, 'initiate'])
        ->name('payments.khalti.initiate');
    Route::post('/api/payments/khalti/lookup', [KhaltiPaymentController::class, 'lookup'])
        ->name('payments.khalti.lookup');

    // Expenses routes
    Route::get('/expenses', [ExpensesController::class, 'index'])
        ->name('expenses.index');
    
    Route::get('/api/expenses', [ExpensesController::class, 'getAll'])
        ->name('expenses.getAll');
    
    Route::post('/api/expenses', [ExpensesController::class, 'store'])
        ->name('expenses.store');
    
    Route::put('/api/expenses/{expense}', [ExpensesController::class, 'update'])
        ->name('expenses.update');
    
    Route::delete('/api/expenses/{expense}', [ExpensesController::class, 'destroy'])
        ->name('expenses.destroy');

    // Reports routes
    Route::get('/reports', [ReportsController::class, 'index'])
        ->name('reports.index');
    
    Route::post('/api/reports/generate', [ReportsController::class, 'generate'])
        ->name('reports.generate');
    
    Route::post('/api/reports/pdf', [ReportsController::class, 'generatePDF'])
        ->name('reports.pdf');

    Route::get('/cash', [CashController::class, 'index'])->name('cash.index');
    Route::get('/api/cash', [CashController::class, 'getAll']);
    Route::post('/api/cash', [CashController::class, 'store']);
    Route::put('/api/cash/{id}', [CashController::class, 'update']);
    Route::delete('/api/cash/{id}', [CashController::class, 'destroy']);
});




