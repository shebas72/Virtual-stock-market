<?php

namespace App\Http\Controllers;

use App\Models\MarketData;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of stocks.
     */
    public function index(Request $request)
    {
        $query = Stock::active();

        // Search functionality
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Filter by sector
        if ($request->has('sector') && $request->sector !== '') {
            $query->bySector($request->sector);
        }

        // Sorting
        $sortBy = $request->get('sort', 'symbol');
        $sortOrder = $request->get('order', 'asc');
        $validSorts = ['symbol', 'name', 'current_price', 'price_change_percent', 'volume', 'market_cap'];

        if (in_array($sortBy, $validSorts)) {
            if ($sortBy === 'price_change_percent') {
                $query->orderByRaw('(current_price - previous_close) / previous_close * 100 '.($sortOrder === 'desc' ? 'DESC' : 'ASC'));
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        }

        $stocks = $query->paginate(20)->withQueryString();

        // Get unique sectors for filter
        $sectors = Stock::active()->distinct()->pluck('sector')->sort()->values();

        return view('stocks.index', compact('stocks', 'sectors'));
    }

    /**
     * Display the specified stock.
     */
    public function show(Stock $stock)
    {
        $stock->load(['transactions' => function ($query) {
            $query->latest()->limit(10);
        }]);

        // Get historical data for charts (last 30 days)
        $historicalData = MarketData::forStock($stock->id)
            ->interval('1min')
            ->betweenDates(now()->subDays(30), now())
            ->orderBy('timestamp', 'asc')
            ->get();

        return view('stocks.show', compact('stock', 'historicalData'));
    }

    /**
     * Get stock price for API/JSON responses.
     */
    public function price(Stock $stock)
    {
        return response()->json([
            'symbol' => $stock->symbol,
            'name' => $stock->name,
            'current_price' => $stock->current_price,
            'price_change' => $stock->price_change,
            'price_change_percent' => $stock->price_change_percent,
            'volume' => $stock->volume,
            'market_cap' => $stock->market_cap,
            'last_updated' => $stock->updated_at->toIso8601String(),
        ]);
    }

    /**
     * Search stocks for autocomplete.
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $stocks = Stock::active()
            ->search($query)
            ->limit(10)
            ->get(['id', 'symbol', 'name', 'current_price', 'price_change', 'price_change_percent']);

        return response()->json($stocks);
    }

    /**
     * Get historical price data for charts.
     */
    public function historical(Stock $stock, Request $request)
    {
        $interval = $request->get('interval', '1d');
        $days = $request->get('days', 30);

        $validIntervals = ['1m', '5m', '15m', '30m', '1h', '4h', '1d', '1w', '1m'];
        if (! in_array($interval, $validIntervals)) {
            $interval = '1d';
        }

        $historicalData = MarketData::forStock($stock->id)
            ->interval($interval)
            ->betweenDates(now()->subDays($days), now())
            ->orderBy('timestamp', 'asc')
            ->get();

        return response()->json($historicalData);
    }
}
