# Virtual Stock Market Application

A full-featured virtual stock market simulation built with Laravel 13, featuring real-time trading, portfolio management, and market analytics.

## 🚀 Features

### Core Functionality
- **Stock Trading**: Buy and sell stocks with real-time price updates
- **Portfolio Management**: Track holdings, P&L, and asset allocation
- **Market Overview**: View gainers, losers, sector performance
- **Leaderboard**: Compete with other traders
- **Transaction History**: Complete audit trail of all trades

### Technical Features
- **Laravel 13** with latest features
- **Blade Templates** with Tailwind CSS
- **Responsive Design** for all devices
- **Dark Mode** support
- **Real-time Updates** (simulated)

## 📁 Project Structure

```
app/
├── Http/
│   └── Controllers/
│       ├── DashboardController.php    # Main dashboard
│       ├── StockController.php        # Stock listings & details
│       ├── PortfolioController.php    # Portfolio management
│       └── TransactionController.php  # Buy/sell transactions
├── Models/
│   ├── Stock.php                 # Stock entity
│   ├── Portfolio.php             # User portfolio
│   ├── PortfolioHolding.php      # Stock holdings
│   ├── Transaction.php           # Trade records
│   ├── MarketData.php           # Historical prices
│   └── Watchlist.php            # Watched stocks
└── Services/
    └── MarketService.php        # Price simulation

database/
├── migrations/                  # Database schema
└── seeders/
    └── StockSeeder.php          # Sample stocks (12 companies)

resources/views/
├── dashboard.blade.php          # Main dashboard
├── stocks/
│   ├── index.blade.php         # Stock market list
│   └── show.blade.php          # Stock detail page
├── portfolio/
│   └── index.blade.php         # Portfolio overview
├── transactions/
│   ├── create.blade.php        # Trading interface
│   └── index.blade.php         # Transaction history
├── market.blade.php            # Market overview
└── leaderboard.blade.php       # Trader rankings
```

## 🛠️ Installation & Setup

### Prerequisites
- PHP 8.3+
- Composer
- Node.js & npm
- MySQL/PostgreSQL/SQLite

### Installation Steps

1. **Clone and install dependencies**
```bash
composer install
npm install
```

2. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Setup database**
```bash
# Configure your database in .env
DB_CONNECTION=sqlite
# or use MySQL/PostgreSQL

php artisan migrate
php artisan db:seed
```

4. **Build assets**
```bash
npm run build
```

5. **Start development server**
```bash
php artisan serve
# Visit http://localhost:8000
```

### Test Credentials
- **Admin**: admin@example.com / password
- **User**: test@example.com / password

## 📊 Sample Data

The application comes pre-seeded with 12 popular stocks:
- **Technology**: AAPL, MSFT, GOOGL, NVDA
- **Consumer**: AMZN, TSLA, WMT, DIS
- **Financial**: JPM, V
- **Healthcare**: JNJ
- **Communication**: NFLX

## 🎯 Key Features Explained

### 1. Dashboard
- Portfolio summary (total value, cash, returns)
- Recent transactions
- Top holdings with P&L
- Quick actions and market movers

### 2. Stock Market
- Filterable/searchable stock list
- Sort by price, change %, volume, etc.
- Detailed stock pages with:
  - Price charts (Chart.js)
  - Key statistics
  - Recent transactions

### 3. Trading Interface
- Buy/Sell orders
- Real-time cost calculation
- Maximum quantity helpers
- Trade notes

### 4. Portfolio
- Current holdings with unrealized P&L
- Sector allocation visualization
- Top/worst performers
- Performance metrics

### 5. Market Overview
- Market statistics (advancing/declining)
- Sector performance
- Most active stocks
- Top gainers & losers

### 6. Leaderboard
- Rankings by portfolio return
- Medal system (🥇🥈🥉)
- Portfolio values and trade counts

## Live Stock Quotes

Stock prices and daily OHLC values are refreshed from Finnhub. Set `FINNHUB_API_KEY`
in `.env`, then run `php artisan stocks:refresh-quotes` to fetch quotes immediately.
Laravel Scheduler refreshes active stocks every five minutes; run `php artisan schedule:work`
locally or configure the Laravel scheduler on the server. Quote availability and delay
depend on the Finnhub account and exchange permissions.

## 🎨 UI/UX Features

- **Responsive**: Works on desktop, tablet, and mobile
- **Dark Mode**: Full dark theme support
- **Color Coding**: Green for gains, red for losses
- **Real-time Feel**: Dynamic updates and calculations
- **Accessibility**: Semantic HTML and ARIA labels

## 📱 API Endpoints (Bonus)

The application includes JSON endpoints for potential API usage:
- `GET /stocks/{stock}/price` - Current stock price
- `GET /stocks/{stock}/historical` - Historical data
- `GET /portfolio/performance` - Portfolio metrics
- `GET /transactions/recent` - Recent transactions

## 🚧 Future Enhancements

### Phase 2 (Not Implemented)
- [ ] Real-time WebSocket updates
- [ ] Advanced charting (candlestick, technical indicators)
- [ ] Options trading
- [ ] Margin trading
- [ ] Portfolio rebalancing

### Phase 3 (Not Implemented)
- [ ] Achievements/badges system
- [ ] Trading competitions
- [ ] Social features (follow traders)
- [ ] News integration
- [ ] Earnings calendar

### Phase 4 (Not Implemented)
- [ ] Progressive Web App (PWA)
- [ ] Mobile app (React Native)
- [ ] Advanced analytics
- [ ] Risk management tools
- [ ] Tax reporting

## 🐛 Known Issues

1. **Price Simulation**: Currently manual, needs scheduled task
2. **Charts**: Require Chart.js CDN (should be bundled)
3. **Market Hours**: Simplified simulation (not timezone-aware)

## 📝 License

MIT License - Feel free to use for learning or commercial projects.

## 🤝 Contributing

This is a demonstration project. For production use, consider:
- Adding proper authentication (2FA, OAuth)
- Implementing rate limiting
- Adding comprehensive testing
- Using real market data APIs
- Implementing proper queue jobs for price updates

## 📧 Support

For questions or issues, please open an issue on GitHub.

---

**Built with ❤️ using Laravel 13**