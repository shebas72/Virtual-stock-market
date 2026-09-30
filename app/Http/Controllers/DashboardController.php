<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $portfolio = $user->getOrCreatePortfolio();

        // Update portfolio metrics
        foreach ($portfolio->holdings as $holding) {
            $holding->updateCurrentValue();
        }
        $portfolio->updateMetrics();

        // Portfolio summary
        $totalValue = $portfolio->calculateTotalValue();
        $cashBalance = $portfolio->cash_balance;
        $totalReturn = $portfolio->portfolio_return;
        $totalReturnPercent = $portfolio->portfolio_return_percent;
        $totalTrades = $portfolio->total_trades;

        // Recent transactions
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with('stock')
            ->latest()
            ->limit(5)
            ->get();

        // Top holdings
        $topHoldings = $portfolio->holdings()
            ->with('stock')
            ->where('quantity', '>', 0)
            ->orderBy('current_value', 'desc')
            ->limit(5)
            ->get();

        // Watchlist stocks performance
        $watchlistStocks = $user->watchedStocks()
            ->active()
            ->limit(5)
            ->get();

        // Market movers (gainers and losers)
        $topGainers = Stock::active()
            ->orderByRaw('(current_price - previous_close) / previous_close DESC')
            ->limit(5)
            ->get();

        $topLosers = Stock::active()
            ->orderByRaw('(current_price - previous_close) / previous_close ASC')
            ->limit(5)
            ->get();

        // Sector performance
        $sectorPerformance = Stock::active()
            ->selectRaw('sector, 
                        AVG((current_price - previous_close) / NULLIF(previous_close, 0) * 100) as avg_change_percent,
                        COUNT(*) as stock_count')
            ->groupBy('sector')
            ->orderBy('avg_change_percent', 'desc')
            ->get();

        return view('dashboard', compact(
            'portfolio',
            'totalValue',
            'cashBalance',
            'totalReturn',
            'totalReturnPercent',
            'totalTrades',
            'recentTransactions',
            'topHoldings',
            'watchlistStocks',
            'topGainers',
            'topLosers',
            'sectorPerformance'
        ));
    }

    /**
     * Display the leaderboard.
     */
    public function leaderboard()
    {
        $leaderboard = User::where('tenant_id', Auth::user()->tenant_id)
            ->whereHas('portfolio')
            ->with('portfolio')
            ->get()
            ->map(function ($user) {
                $portfolio = $user->portfolio;

                return [
                    'user' => $user,
                    'total_value' => $portfolio->total_value,
                    'return_percent' => $portfolio->portfolio_return_percent,
                    'total_trades' => $portfolio->total_trades,
                ];
            })
            ->sortByDesc('return_percent')
            ->values();

        return view('leaderboard', compact('leaderboard'));
    }

    /**
     * Display market overview.
     */
    public function market()
    {
        // Market statistics
        $totalStocks = Stock::active()->count();
        $advancing = Stock::active()->whereColumn('current_price', '>', 'previous_close')->count();
        $declining = Stock::active()->whereColumn('current_price', '<', 'previous_close')->count();
        $unchanged = $totalStocks - $advancing - $declining;

        // Sector performance
        $sectorPerformance = Stock::active()
            ->selectRaw('sector, 
                        AVG((current_price - previous_close) / NULLIF(previous_close, 0) * 100) as avg_change_percent,
                        COUNT(*) as stock_count,
                        SUM(volume) as total_volume')
            ->groupBy('sector')
            ->orderBy('avg_change_percent', 'desc')
            ->get();

        // Most active stocks
        $mostActive = Stock::active()
            ->orderBy('volume', 'desc')
            ->limit(10)
            ->get();

        // Top gainers
        $topGainers = Stock::active()
            ->orderByRaw('(current_price - previous_close) / NULLIF(previous_close, 0) DESC')
            ->limit(10)
            ->get();

        // Top losers
        $topLosers = Stock::active()
            ->orderByRaw('(current_price - previous_close) / NULLIF(previous_close, 0) ASC')
            ->limit(10)
            ->get();

        return view('market', compact(
            'totalStocks',
            'advancing',
            'declining',
            'unchanged',
            'sectorPerformance',
            'mostActive',
            'topGainers',
            'topLosers'
        ));
    }
}
