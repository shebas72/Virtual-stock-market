<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Portfolio;
use App\Models\MarketData;
use App\Services\FinnhubQuoteService;
use App\Services\MarketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function dashboard()
    {
        // System statistics
        $stats = [
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'total_stocks' => Stock::active()->count(),
            'total_trades' => Transaction::count(),
            'total_portfolio_value' => Portfolio::sum('total_value'),
            'total_cash_in_market' => Portfolio::sum('cash_balance'),
            'total_stocks_value' => Portfolio::sum('total_value') - Portfolio::sum('cash_balance'),
        ];

        // Recent users
        $recentUsers = User::where('role', '!=', 'admin')
            ->with('portfolio')
            ->latest()
            ->limit(5)
            ->get();

        // Recent transactions
        $recentTransactions = Transaction::with(['user', 'stock'])
            ->latest()
            ->limit(10)
            ->get();

        // Top traders
        $topTraders = User::where('role', '!=', 'admin')
            ->has('portfolio')
            ->with('portfolio')
            ->get()
            ->sortByDesc(fn($u) => $u->portfolio->portfolio_return_percent)
            ->take(5)
            ->values();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentTransactions', 'topTraders'));
    }

    /**
     * Display stocks management.
     */
    public function stocks()
    {
        $stocks = Stock::withCount('transactions')
            ->orderBy('symbol')
            ->paginate(20);

        return view('admin.stocks.index', compact('stocks'));
    }

    /**
     * Show form to create new stock.
     */
    public function createStock()
    {
        return view('admin.stocks.create');
    }

    /**
     * Store new stock.
     */
    public function storeStock(Request $request)
    {
        $validated = $request->validate([
            'symbol' => 'required|string|max:10|unique:stocks,symbol',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sector' => 'nullable|string|max:100',
            'industry' => 'nullable|string|max:100',
            'current_price' => 'required|numeric|min:0.01',
            'opening_price' => 'nullable|numeric|min:0',
            'previous_close' => 'nullable|numeric|min:0',
            'day_high' => 'nullable|numeric|min:0',
            'day_low' => 'nullable|numeric|min:0',
            'volume' => 'nullable|integer|min:0',
            'market_cap' => 'nullable|numeric|min:0',
            'pe_ratio' => 'nullable|numeric|min:0',
            'dividend_yield' => 'nullable|numeric|min:0|max:100',
        ]);

        Stock::create($validated);

        return redirect()->route('admin.stocks')
            ->with('success', 'Stock created successfully.');
    }

    /**
     * Show form to edit stock.
     */
    public function editStock(Stock $stock)
    {
        return view('admin.stocks.edit', compact('stock'));
    }

    /**
     * Update stock.
     */
    public function updateStock(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'symbol' => 'required|string|max:10|unique:stocks,symbol,' . $stock->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sector' => 'nullable|string|max:100',
            'industry' => 'nullable|string|max:100',
            'current_price' => 'required|numeric|min:0.01',
            'opening_price' => 'nullable|numeric|min:0',
            'previous_close' => 'nullable|numeric|min:0',
            'day_high' => 'nullable|numeric|min:0',
            'day_low' => 'nullable|numeric|min:0',
            'volume' => 'nullable|integer|min:0',
            'market_cap' => 'nullable|numeric|min:0',
            'pe_ratio' => 'nullable|numeric|min:0',
            'dividend_yield' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $stock->update($validated);

        return redirect()->route('admin.stocks')
            ->with('success', 'Stock updated successfully.');
    }

    /**
     * Delete stock.
     */
    public function deleteStock(Stock $stock)
    {
        // Check if stock has transactions
        if ($stock->transactions()->count() > 0) {
            return back()->with('error', 'Cannot delete stock with existing transactions.');
        }

        $stock->delete();

        return redirect()->route('admin.stocks')
            ->with('success', 'Stock deleted successfully.');
    }

    /**
     * Toggle stock active status.
     */
    public function toggleStock(Stock $stock)
    {
        $stock->update(['is_active' => !$stock->is_active]);

        return back()->with('success', 'Stock status updated.');
    }

    /**
     * Display users management.
     */
    public function users()
    {
        $users = User::where('role', '!=', 'admin')
            ->with('portfolio')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show user details.
     */
    public function showUser(User $user)
    {
        if ($user->role === 'admin') {
            abort(403);
        }

        $user->load(['portfolio.holdings.stock', 'transactions.stock']);

        $portfolio = $user->portfolio;
        $transactions = $user->transactions()->latest()->paginate(20);

        return view('admin.users.show', compact('user', 'portfolio', 'transactions'));
    }

    /**
     * Update user role.
     */
    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:user,admin',
        ]);

        $user->update($validated);

        return back()->with('success', 'User role updated successfully.');
    }

    /**
     * Reset user portfolio.
     */
    public function resetPortfolio(User $user)
    {
        if ($user->role === 'admin') {
            abort(403);
        }

        $portfolio = $user->portfolio;
        
        if ($portfolio) {
            // Delete all holdings
            $portfolio->holdings()->delete();
            
            // Reset portfolio values
            $portfolio->update([
                'cash_balance' => $portfolio->initial_balance,
                'total_value' => $portfolio->initial_balance,
                'portfolio_return' => 0,
                'portfolio_return_percent' => 0,
                'total_trades' => 0,
                'winning_trades' => 0,
                'losing_trades' => 0,
            ]);
        }

        return back()->with('success', 'User portfolio reset successfully.');
    }

    /**
     * Display market controls.
     */
    public function market()
    {
        $marketService = new MarketService();
        
        $marketStatus = [
            'is_open' => $marketService->isMarketOpen(),
            'total_stocks' => Stock::active()->count(),
            'advancing' => Stock::active()->whereColumn('current_price', '>', 'previous_close')->count(),
            'declining' => Stock::active()->whereColumn('current_price', '<', 'previous_close')->count(),
            'unchanged' => Stock::active()->whereColumn('current_price', '=', 'previous_close')->count(),
        ];

        $recentMarketData = MarketData::query()
            ->join('stocks', 'market_data.stock_id', '=', 'stocks.id')
            ->select('market_data.*', 'stocks.symbol', 'stocks.name')
            ->orderBy('market_data.created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.market', compact('marketStatus', 'recentMarketData'));
    }

    /**
     * Refresh prices from Finnhub.
     */
    public function refreshPrices(FinnhubQuoteService $quoteService)
    {
        try {
            $result = $quoteService->refreshActiveStocks();
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', "Refreshed {$result['updated']} live quotes; {$result['failed']} failed.");
    }

    /**
     * Reset daily market data.
     */
    public function resetMarket()
    {
        $marketService = new MarketService();
        $marketService->resetDailyData();

        return back()->with('success', 'Daily market data reset successfully.');
    }

    /**
     * Display all transactions.
     */
    public function transactions(Request $request)
    {
        $query = Transaction::with(['user', 'stock']);

        // Filters
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('stock_id') && $request->stock_id) {
            $query->where('stock_id', $request->stock_id);
        }
        if ($request->has('type') && in_array($request->type, ['buy', 'sell'])) {
            $query->where('type', $request->type);
        }
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transactions = $query->latest()->paginate(50);

        // Filter options
        $users = User::where('role', '!=', 'admin')
            ->orderBy('name')
            ->get(['id', 'name']);
        $stocks = Stock::active()->orderBy('symbol')->get(['id', 'symbol', 'name']);

        return view('admin.transactions', compact('transactions', 'users', 'stocks'));
    }
}