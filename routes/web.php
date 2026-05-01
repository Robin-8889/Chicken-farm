<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmRecordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : view('welcome');
})->name('home');

// Locale switcher (en = English, sw = Swahili)
Route::get('/locale/{lang}', function (Request $request, $lang) {
    $allowed = ['en', 'sw'];
    if (!in_array($lang, $allowed, true)) {
        abort(404);
    }

    session(['locale' => $lang]);
    app()->setLocale($lang);

    if (Auth::check()) {
        $user = Auth::user();
        $user->locale = $lang;
        $user->save();
    }

    return redirect()->back();
})->name('locale.switch');

// POST locale switch (preferred: CSRF protected)
Route::post('/locale', function (Request $request) {
    $lang = $request->input('lang');
    $allowed = ['en', 'sw'];
    if (!in_array($lang, $allowed, true)) {
        abort(404);
    }

    session(['locale' => $lang]);
    app()->setLocale($lang);

    if (Auth::check()) {
        $user = Auth::user();
        $user->locale = $lang;
        $user->save();
    }

    return redirect()->back();
})->name('locale.switch.post');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'createLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'createRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/overview', [DashboardController::class, 'overview'])->name('dashboard.overview');
    Route::get('/batch-management', [DashboardController::class, 'batchManagement'])->name('batch-management');
    Route::get('/batches/{batch}', [DashboardController::class, 'showBatch'])->name('batches.show');
    Route::get('/growth-tracking', [DashboardController::class, 'growthTracking'])->name('growth-tracking');
    Route::get('/egg-production', [DashboardController::class, 'eggProduction'])->name('egg-production');
    Route::get('/feed-medicine', [DashboardController::class, 'feedMedicine'])->name('feed-medicine');
    Route::get('/mortality-consumption', [DashboardController::class, 'mortalityConsumption'])->name('mortality-consumption');
    Route::get('/sales', [DashboardController::class, 'salesPage'])->name('sales-page');
    Route::get('/financial-records', [DashboardController::class, 'financialRecords'])->name('financial-records');

    Route::post('/batches', [FarmRecordController::class, 'storeBatch'])->name('batches.store');
    Route::post('/growth', [FarmRecordController::class, 'storeGrowth'])->name('growth.store');
    Route::post('/eggs', [FarmRecordController::class, 'storeEgg'])->name('eggs.store');
    Route::post('/stocks', [FarmRecordController::class, 'storeStock'])->name('stocks.store');
    Route::post('/stocks/{stock}/use', [FarmRecordController::class, 'useStock'])->name('stocks.use');
    Route::post('/mortality', [FarmRecordController::class, 'storeMortality'])->name('mortality.store');
    Route::post('/sales', [FarmRecordController::class, 'storeSale'])->name('sales.store');
    Route::post('/expenses', [FarmRecordController::class, 'storeExpense'])->name('expenses.store');

    Route::middleware('role:admin')->group(function (): void {
        Route::patch('/users/{user}/role', [FarmRecordController::class, 'updateUserRole'])->name('users.role');
    });
});
