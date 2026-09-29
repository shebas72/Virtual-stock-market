<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Transaction;
use App\Models\Portfolio;
use App\Models\PortfolioHolding;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display transaction history.
     */
    public function index(Request $request)
    {
        $query = Transaction::with('stock')->where('user_id', Auth::id());

        // Filters
        if ($request->has('stock') && $request->stock) {
            $query->where('stock_id', $request->stock);
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

        $transactions = $query->latest()->paginate(20);

        // Get stocks for filter dropdown
        $stocks = Stock::active()
            ->orderBy('symbol')
            ->get()
            ->pluck('name', 'symbol');

        return view('transactions.index', compact('transactions', 'stocks'));
    }

    /**
     * Show the form to create a new transaction.
     */
    public function create(Stock $stock)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $portfolio = $user->portfolio;
        
        if (!$portfolio) {
            return redirect()->route('dashboard')
                ->with('error', 'No portfolio found. Please contact support.');
        }

        // Calculate maximum buy quantity
        $maxBuyQuantity = floor($portfolio->cash_balance / $stock->current_price);

        // Get current holding for this stock
        $holding = PortfolioHolding::where('portfolio_id', $portfolio->id)
            ->where('stock_id', $stock->id)
            ->first();

        $maxSellQuantity = $holding ? $holding->quantity : 0;

        return view('transactions.create', compact(
            'stock', 
            'portfolio', 
            'maxBuyQuantity', 
            'maxSellQuantity'
        ));
    }

    /**
     * Store a newly created transaction.
     */
    public function store(Request $request, Stock $stock)
    {
        // Validate the request
        $validated = $request->validate([
            'type' => 'required|in:buy,sell',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $portfolio = $user->portfolio;

        if (!$portfolio) {
            return back()->withErrors(['error' => 'No portfolio found.']);
        }

        $quantity = $validated['quantity'];
        $pricePerShare = $stock->current_price;
        $totalAmount = $quantity * $pricePerShare;

        try {
            DB::beginTransaction();

            if ($validated['type'] === 'buy') {
                // Check if user has enough cash
                if ($portfolio->cash_balance < $totalAmount) {
                    DB::rollBack();
                    return back()->withErrors(['quantity' => 'Insufficient cash balance.']);
                }

                // Deduct cash
                $portfolio->cash_balance -= $totalAmount;

                // Update or create holding
                $holding = PortfolioHolding::where('portfolio_id', $portfolio->id)
                    ->where('stock_id', $stock->id)
                    ->first();

                if ($holding) {
                    // Update existing holding
                    $totalShares = $holding->quantity + $quantity;
                    $totalCost = ($holding->average_cost * $holding->quantity) + $totalAmount;
                    $holding->average_cost = $totalCost / $totalShares;
                    $holding->quantity = $totalShares;
                    $holding->save();
                } else {
                    // Create new holding
                    PortfolioHolding::create([
                        'portfolio_id' => $portfolio->id,
                        'stock_id' => $stock->id,
                        'quantity' => $quantity,
                        'average_cost' => $pricePerShare,
                    ]);
                }

            } else {
                // SELL
                // Check if user has enough shares
                $holding = PortfolioHolding::where('portfolio_id', $portfolio->id)
                    ->where('stock_id', $stock->id)
                    ->first();

                if (!$holding || $holding->quantity < $quantity) {
                    DB::rollBack();
                    return back()->withErrors(['quantity' => 'Insufficient shares to sell.']);
                }

                // Add cash
                $portfolio->cash_balance += $totalAmount;

                // Update holding
                $holding->quantity -= $quantity;
                
                if ($holding->quantity == 0) {
                    $holding->delete();
                } else {
                    $holding->save();
                }
            }

            // Update portfolio total value
            $portfolio->total_value = $portfolio->cash_balance + PortfolioHolding::where('portfolio_id', $portfolio->id)
                ->join('stocks', 'portfolio_holdings.stock_id', '=', 'stocks.id')
                ->sum(DB::raw('portfolio_holdings.quantity * stocks.current_price'));

            // Calculate returns
            $portfolio->portfolio_return = $portfolio->total_value - $portfolio->initial_balance;
            $portfolio->portfolio_return_percent = ($portfolio->portfolio_return / $portfolio->initial_balance) * 100;

            // Increment trade counter
            $portfolio->total_trades += 1;
            $portfolio->save();

            // Create transaction record
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'stock_id' => $stock->id,
                'type' => $validated['type'],
                'quantity' => $quantity,
                'price_per_share' => $pricePerShare,
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'] ?? null,
                'status' => 'completed',
            ]);

            DB::commit();

            return redirect()->route('portfolio.index')
                ->with('success', ucfirst($validated['type']) . ' order executed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Transaction failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        // Ensure user can only view their own transactions
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->load('stock');

        return view('transactions.show', compact('transaction'));
    }
}