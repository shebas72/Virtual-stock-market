# Virtual Stock Market (Laravel/PHP)

This workspace is ready for a Laravel app, but Composer in this environment is blocked by an SSL certificate issue. Once Composer can access packages, use the steps below to build a simple practice app with user login and admin login.

## 1. Create the Laravel project

```bash
cd "d:\job\guardian\2026\virtual stock market"
composer create-project laravel/laravel . "9.*" --prefer-dist
```

If you still have SSL issues, fix your Composer environment or use a trusted network.

## 2. Set up authentication

Install Laravel Breeze for simple auth scaffolding:

```bash
composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
php artisan migrate
```

Alternatively, you can use `laravel/ui` and Bootstrap.

## 3. Add admin support

Update `users` table migration to add a role field:

```php
$table->string('role')->default('user');
```

Then run:

```bash
php artisan migrate
```

Create an admin user manually or via seeder:

```php
User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
    'role' => 'admin',
]);
```

## 4. Create the stock market core models

Suggested models:

- `Stock` — ticker, name, price
- `Holding` — user_id, stock_id, quantity, average_price
- `Trade` — user_id, stock_id, quantity, price, type (`buy`/`sell`)

### Example migrations

`stocks` table:

```php
Schema::create('stocks', function (Blueprint $table) {
    $table->id();
    $table->string('ticker')->unique();
    $table->string('name');
    $table->decimal('price', 10, 2);
    $table->timestamps();
});
```

`holdings` table:

```php
Schema::create('holdings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('stock_id')->constrained()->cascadeOnDelete();
    $table->integer('quantity');
    $table->decimal('average_price', 10, 2);
    $table->timestamps();
});
```

`trades` table:

```php
Schema::create('trades', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('stock_id')->constrained()->cascadeOnDelete();
    $table->string('type');
    $table->integer('quantity');
    $table->decimal('price', 10, 2);
    $table->timestamps();
});
```

## 5. Admin routes and middleware

Add middleware to protect admin pages. In `app/Http/Middleware`, create `EnsureAdmin.php`:

```php
public function handle(Request $request, Closure $next)
{
    if (auth()->check() && auth()->user()->role === 'admin') {
        return $next($request);
    }

    abort(403);
}
```

Register it in `app/Http/Kernel.php` and use it for admin routes:

```php
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::resource('stocks', StockController::class);
});
```

## 6. User features

Build these pages:

- User dashboard: portfolio holdings, current value, cash balance
- Stock list: show available stocks and current prices
- Trade page: buy or sell shares
- Trade history: list of executed trades

## 7. Simple stock logic

For practice, use fixed stock prices in the database and update them manually from admin.

Example buy flow:

1. Check user has enough cash balance
2. Create a `Trade` record
3. Increase or create `Holding`
4. Deduct cash balance

Example sell flow:

1. Check user owns enough quantity
2. Create a `Trade` record
3. Decrease `Holding`
4. Add cash balance

## 8. Recommended next steps

1. Fix Composer SSL or use a working network
2. Scaffold the Laravel app
3. Install Breeze or auth UI
4. Create the admin middleware and stock market migrations
5. Build the dashboard, stock list, and trade forms

## 9. Useful commands

```bash
php artisan migrate
php artisan make:model Stock -m
php artisan make:model Holding -m
php artisan make:model Trade -m
php artisan make:controller Admin/StockController --resource
php artisan make:middleware EnsureAdmin
```

---

If you want, I can also help by generating the exact Laravel migration, model, controller, and route code once the project is scaffolded successfully.