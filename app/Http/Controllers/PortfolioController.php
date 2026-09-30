<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioHolding;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    /**
     * Display the user's portfolio.
     */
    public function index()
    {
        $user = Auth::user();
        $portfolio = $user->getOrCreatePortfolio();

        // Update all holdings current values
        foreach ($portfolio->holdings as $holding) {
            $holding->updateCurrentValue();
        }

        // Refresh portfolio metrics
        $portfolio->updateMetrics();

        // Get portfolio summary
        $totalValue = $portfolio->calculateTotalValue();
        $totalReturn = $portfolio->portfolio_return;
        $totalReturnPercent = $portfolio->portfolio_return_percent;

        // Get holdings with stock details
        $holdings = $portfolio->holdings()
            ->with('stock')
            ->where('quantity', '>', 0)
            ->get();

        // Get sector allocation
        $sectorAllocation = $portfolio->getSectorAllocation();

        // Get top and worst performers
        $topPerformers = $portfolio->getTopPerformers(3);
        $worstPerformers = $portfolio->getWorstPerformers(3);

        return view('portfolio.index', compact(
            'portfolio',
            'totalValue',
            'totalReturn',
            'totalReturnPercent',
            'holdings',
            'sectorAllocation',
            'topPerformers',
            'worstPerformers'
        ));
    }

    /**
     * Display detailed view of a specific holding.
     */
    public function holding(PortfolioHolding $holding)
    {
        $portfolio = Auth::user()->portfolio;

        if ($holding->portfolio_id !== $portfolio->id) {
            abort(403);
        }

        $holding->load('stock');
        $holding->updateCurrentValue();

        // Get transactions for this stock
        $transactions = $holding->stock->transactions()
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('portfolio.holding', compact('holding', 'transactions'));
    }

    /**
     * Get portfolio performance data for charts.
     */
    public function performance()
    {
        $portfolio = Auth::user()->portfolio;

        // Get portfolio value history (you'd need to track this over time)
        // For now, return current metrics
        return response()->json([
            'total_value' => $portfolio->total_value,
            'cash_balance' => $portfolio->cash_balance,
            'initial_balance' => $portfolio->initial_balance,
            'return' => $portfolio->portfolio_return,
            'return_percent' => $portfolio->portfolio_return_percent,
            'total_trades' => $portfolio->total_trades,
        ]);
    }

    /**
     * Get portfolio allocation data.
     */
    public function allocation()
    {
        $portfolio = Auth::user()->portfolio;

        $allocation = [
            'sectors' => $portfolio->getSectorAllocation(),
            'cash_percent' => $portfolio->cash_balance / $portfolio->calculateTotalValue() * 100,
            'stocks_percent' => 100 - ($portfolio->cash_balance / $portfolio->calculateTotalValue() * 100),
        ];

        return response()->json($allocation);
    }
}
