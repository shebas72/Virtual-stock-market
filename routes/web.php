<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\AdminTenantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\SubscriptionPaymentController;
use App\Http\Controllers\TenantSubscriptionController;
use App\Http\Controllers\AdminSubscriptionPlanController;
use App\Http\Controllers\TenantInvitationController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])
    ->name('landing');

Route::get('/invitations/{token}', [TenantInvitationController::class, 'show'])->name('invitations.accept.show');
Route::post('/invitations/{token}', [TenantInvitationController::class, 'accept'])->name('invitations.accept');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'tenant.active'])
    ->name('dashboard');

// Leaderboard
Route::get('/leaderboard', [DashboardController::class, 'leaderboard'])
    ->middleware(['auth', 'verified', 'tenant.active'])
    ->name('leaderboard');

// Market Overview
Route::get('/market', [DashboardController::class, 'market'])
    ->middleware(['auth', 'verified', 'tenant.active'])
    ->name('market');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/subscription', [TenantSubscriptionController::class, 'show'])->name('subscription.show');
    Route::post('/subscription/{subscriptionPlan}', [TenantSubscriptionController::class, 'changePlan'])
        ->middleware('tenant.owner')
        ->name('subscription.change');

    // Online subscription payments (Stripe / PayPal)
    Route::middleware('tenant.owner')->group(function () {
        Route::post('/billing/checkout', [SubscriptionPaymentController::class, 'store'])->name('subscription.payment.store');
        Route::get('/billing/{payment}', [SubscriptionPaymentController::class, 'show'])->name('subscription.payment.show');
        Route::post('/billing/{payment}/complete', [SubscriptionPaymentController::class, 'complete'])->name('subscription.payment.complete');
        Route::post('/billing/{payment}/cancel', [SubscriptionPaymentController::class, 'cancel'])->name('subscription.payment.cancel');
        Route::get('/billing/{payment}/callback', [SubscriptionPaymentController::class, 'callback'])->name('subscription.payment.callback');
    });
});

// Stocks
Route::middleware(['auth', 'verified', 'tenant.active'])->group(function () {
    Route::get('/team', [TeamController::class, 'index'])->middleware('tenant.owner')->name('team.index');
    Route::post('/team/invitations', [TeamController::class, 'invite'])->middleware('tenant.owner')->name('team.invitations.store');
    Route::post('/team/members', [TeamController::class, 'createMember'])->middleware('tenant.owner')->name('team.members.store');
    Route::patch('/team/members/{user}/status', [TeamController::class, 'toggleMemberStatus'])->middleware('tenant.owner')->name('team.members.status');
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('/stocks/search', [StockController::class, 'search'])->name('stocks.search');
    Route::get('/stocks/{stock}', [StockController::class, 'show'])->name('stocks.show');
    Route::get('/stocks/{stock}/price', [StockController::class, 'price'])->name('stocks.price');
    Route::get('/stocks/{stock}/historical', [StockController::class, 'historical'])->name('stocks.historical');
});

// Portfolio
Route::middleware(['auth', 'verified', 'tenant.active'])->group(function () {
    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
    Route::get('/portfolio/holdings/{holding}', [PortfolioController::class, 'holding'])->name('portfolio.holding');
    Route::get('/portfolio/performance', [PortfolioController::class, 'performance'])->name('portfolio.performance');
    Route::get('/portfolio/allocation', [PortfolioController::class, 'allocation'])->name('portfolio.allocation');
});

// Transactions
Route::middleware(['auth', 'verified', 'tenant.active'])->group(function () {
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

    // Tenant Management
    Route::get('/tenants', [AdminTenantController::class, 'index'])->name('tenants.index');
    Route::get('/tenants/{tenant}/edit', [AdminTenantController::class, 'edit'])->name('tenants.edit');
    Route::put('/tenants/{tenant}', [AdminTenantController::class, 'update'])->name('tenants.update');
    Route::patch('/tenants/{tenant}/status', [AdminTenantController::class, 'toggleStatus'])->name('tenants.status');
    Route::delete('/tenants/{tenant}', [AdminTenantController::class, 'destroy'])->name('tenants.destroy');

    // Subscription Plans
    Route::get('/subscription-plans', [AdminSubscriptionPlanController::class, 'index'])->name('subscription-plans.index');
    Route::post('/subscription-plans', [AdminSubscriptionPlanController::class, 'store'])->name('subscription-plans.store');
    Route::put('/subscription-plans/trial', [AdminSubscriptionPlanController::class, 'updateTrial'])->name('subscription-plans.trial');
    Route::put('/subscription-plans/payments', [AdminSubscriptionPlanController::class, 'updatePayments'])->name('subscription-plans.payments');
    Route::put('/subscription-plans/{subscriptionPlan}', [AdminSubscriptionPlanController::class, 'update'])->name('subscription-plans.update');
    Route::delete('/subscription-plans/{subscriptionPlan}', [AdminSubscriptionPlanController::class, 'destroy'])->name('subscription-plans.destroy');

    // Subscription Payments
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');

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
