<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Leaderboard
Route::get('/leaderboard', [DashboardController::class, 'leaderboard'])
    ->middleware(['auth', 'verified'])
    ->name('leaderboard');

// Market Overview
Route::get('/market', [DashboardController::class, 'market'])
    ->middleware(['auth', 'verified'])
    ->name('market');

// Stocks
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('/stocks/search', [StockController::class, 'search'])->name('stocks.search');
    Route::get('/stocks/{stock}', [StockController::class, 'show'])->name('stocks.show');
    Route::get('/stocks/{stock}/price', [StockController::class, 'price'])->name('stocks.price');
    Route::get('/stocks/{stock}/historical', [StockController::class, 'historical'])->name('stocks.historical');
});

// Portfolio
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
    Route::get('/portfolio/holdings/{holding}', [PortfolioController::class, 'holding'])->name('portfolio.holding');
    Route::get('/portfolio/performance', [PortfolioController::class, 'performance'])->name('portfolio.performance');
    Route::get('/portfolio/allocation', [PortfolioController::class, 'allocation'])->name('portfolio.allocation');
});

// Transactions
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create/{stock}', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions/{stock}', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/recent', [TransactionController::class, 'recent'])->name('transactions.recent');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Stocks Management
    Route::get('/stocks', [AdminController::class, 'stocks'])->name('stocks');
    Route::get('/stocks/create', [AdminController::class, 'createStock'])->name('stocks.create');
    Route::post('/stocks', [AdminController::class, 'storeStock'])->name('stocks.store');
    Route::get('/stocks/{stock}/edit', [AdminController::class, 'editStock'])->name('stocks.edit');
    Route::put('/stocks/{stock}', [AdminController::class, 'updateStock'])->name('stocks.update');
    Route::delete('/stocks/{stock}', [AdminController::class, 'deleteStock'])->name('stocks.delete');
    Route::post('/stocks/{stock}/toggle', [AdminController::class, 'toggleStock'])->name('stocks.toggle');
    
    // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
    Route::post('/users/{user}/reset-portfolio', [AdminController::class, 'resetPortfolio'])->name('users.reset-portfolio');
    
    // Market Controls
    Route::get('/market', [AdminController::class, 'market'])->name('market');
    Route::post('/market/refresh', [AdminController::class, 'refreshPrices'])->name('market.refresh');
    Route::post('/market/reset', [AdminController::class, 'resetMarket'])->name('market.reset');
    
    // Transactions Overview
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
