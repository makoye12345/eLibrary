<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
//use App\Http\Controllers\BookController as MainBookController;
use App\Http\Controllers\ProfileController;
//use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BorrowingController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AccessLogController;
//use App\Http\Controllers\User\MainAccessLogController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\BorrowingHistoryController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ReturnBookController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\AccessLog;
use App\Http\Controllers\User\PasswordController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Auth\ForgotPasswordController;
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    // routes zingine za admin
});


Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/change-password', [App\Http\Controllers\Admin\ChangePasswordController::class, 'showChangePasswordForm'])->name('admin.change-password');
    Route::post('/admin/change-password', [App\Http\Controllers\Admin\ChangePasswordController::class, 'changePassword'])->name('admin.change-password.update');
});

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/access logs', [AccessLogController::class, 'index'])->name('access_logs.index');
   Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
     Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
     Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
     Route::get('/messages/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');
});

 // admin view transaction
Route::prefix('admin')->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('admin.transactions.index');

    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])
        ->name('admin.transactions.show');

    Route::patch('/transactions/{transaction}/return', [TransactionController::class, 'markAsReturned'])
        ->name('admin.transactions.return');
});

 
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});


 ///user in admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function() {
    Route::resource('users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy'
    ]);
});
Route::prefix('admin')->group(function () {
    Route::resource('notifications', NotificationController::class);
    Route::get('/admin/notifications/create', [NotificationController::class, 'create'])->name('admin.notifications.create');
    Route::post('/admin/notifications', [NotificationController::class, 'store'])->name('admin.notifications.store');
});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // other admin routes here...
    });
});

Route::get('/admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile.index');
Route::get('/admin/profile/edit', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
Route::get('/admin/profile/image', [AdminProfileController::class, 'uploadImage'])->name('admin.profile.upload');
Route::put('/admin/profile/update', [AdminProfileController::class, 'updateProfile'])->name('admin.profile.update');


Route::get('/admin/statistics', [StatisticsController::class, 'index'])->name('admin.statistics');
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index'); // View all users
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create'); // Show create form
    Route::post('/users', [UserController::class, 'store'])->name('users.store'); // Store new user
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit'); // Show edit form
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update'); // Update user
    
    // Add the destroy route
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); // Delete user
});


Route::get('/admin/users', [App\Http\Controllers\Admin\UserController::class, 'index']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/user/borrowed-books', [UserController::class, 'borrowedBooks'])->name('user.borrowed-books');
    Route::get('/user/returned-books', [UserController::class, 'returnedBooks'])->name('user.returned-books');
    Route::get('/user/transactions', [UserController::class, 'transactionsPage'])->name('user.transactions');
    Route::get('/user/metrics', [UserController::class, 'metrics'])->name('user.metrics');
    Route::get('/user/transactions-data', [UserController::class, 'transactions'])->name('user.transactions-data');
    Route::get('/user/access-logs', [UserController::class, 'accessLogs'])->name('user.access.logs');
    Route::get('/transaction/{id}', [UserController::class, 'transactionDetails'])->name('transaction.details');
    Route::get('/books/search', [BookController::class, 'search'])->name('books.search');
    Route::get('/user/books', [BookController::class, 'index'])->name('user.books.index');
    Route::get('/book/{slug}', [BookController::class, 'details'])->name('book.details');
    Route::post('/books/borrow', [BookController::class, 'borrow'])->name('books.borrow');
    Route::post('/books/renew', [BookController::class, 'renew'])->name('books.renew');
    Route::post('admin/logout', [UserController::class, 'logout'])->name('admin.logout');
    Route::get('/books/{book}/view', [BookController::class, 'view'])->name('admin.books.view');
});

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);


Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserLoginController::class, 'login']);
//user access logs
Route::get('/user/access-logs', function () {
    $logs = AccessLog::where('user_id', Auth::id())->latest()->paginate(10);
    return view('user.access_logs', compact('logs'));
})->name('user.access.logs')->middleware('auth');
 // manage book user route
Route::get('/books', [BookController::class, 'userBooksIndex'])->name('user.books.index');
Route::get('/books/search', [BookController::class, 'search'])->name('user.books_search.index');
Route::post('/books/{id}/borrow', [BookController::class, 'borrow'])->name('books.borrow');
Route::post('/books/{id}/cart', [BookController::class, 'addToCart'])->name('books.cart.add');
Route::get('/books/{book}', fn($book) => view('books.show', ['book' => $book]))->name('book.details');
Route::middleware(['auth'])->group(function () {
Route::post('/user/books/store', [BookController::class, 'store'])->name('user.books.store');

});

Route::middleware(['auth'])->post('/user/password/update', [PasswordController::class, 'update'])->name('user.password.update');

// Profile routes
Route::middleware(['auth'])->get('/user/profile', [UserProfileController::class, 'index'])->name('user.profile');
Route::middleware(['auth'])->get('/user/profile/edit', [UserProfileController::class, 'edit'])->name('user.edit');
Route::middleware(['auth'])->patch('/user/profile/update', [UserProfileController::class, 'update'])->name('user.update');
Route::middleware(['auth'])->post('/user/profile/update', [UserProfileController::class, 'update'])->name('user.profile.update');

  //user book route
  Route::middleware('auth')->get('/user/borrowed-books', [BorrowController::class, 'myBorrowedBooks'])->name('user.borrowed.books');
Route::get('/books/search', [BookController::class, 'index'])->name('books.search');
// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Dashboard route
Route::middleware(['auth', 'is_user'])->get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);


Route::middleware(['auth', 'is_user'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});

Route::post('/logout', function () {
    Auth::guard('web')->logout();  // For default user guard
    return redirect('/login');     // Redirect after logout
})->name('logout');

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/books/data', [BookController::class, 'getBooksData'])->name('books.data');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard/data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/return-books', [ReturnBookController::class, 'index'])->name('return-books.index');
});


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function() {
    // ... routes zako zingine
    
    Route::resource('notifications', \App\Http\Controllers\Admin\NotificationController::class);
    
    // AU kama unataka kufafanua mtu kwa mtu
    Route::get('notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/create', [\App\Http\Controllers\Admin\NotificationController::class, 'create'])->name('notifications.create');
    Route::post('notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('notifications.store');
    // ... na kadhalika kwa methods zingine
});

// Home Page
Route::get('/', function () {
    return view('welcome');
})->name('home');



// Public Dashboard (accessible without login)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Public Book Browsing Routes
Route::get('/books', [MainBookController::class, 'index'])->name('books.index');
Route::get('/books/{id}', [MainBookController::class, 'show'])->name('books.show');
Route::get('/user/books', [BookController::class, 'userBooksIndex'])->name('user.books.index');
Route::get('/user/books/search', [BookController::class, 'search'])->name('user.books_search.index');
Route::get('/user/books/{id}/borrow', [BookController::class, 'borrow'])->name('user.books.borrow');
Route::get('/user/books/{id}/add-to-cart', [BookController::class, 'addToCart'])->name('user.books.add_to_cart');
Route::get('/books/{id}', [BookController::class, 'show'])->name('book.details');
// Public Borrow Routes
Route::get('/borrow', [BorrowController::class, 'showBorrowForm'])->name('borrow.form');
Route::post('/borrow', [BorrowController::class, 'borrowBook'])->name('borrow.book');
Route::post('/borrow-book', [BorrowController::class, 'borrowBook']);
Route::post('/books/{id}/cart', [BookController::class, 'addToCart'])->name('books.cart'); // Adjusted to MainBookController if needed
Route::get('/borrowings/{id}', [BorrowingController::class, 'show'])->name('borrowings.show');

// Public Return Book Route
Route::get('/return-book', [ReturnBookController::class, 'index'])->name('return-books.index');

// Public Static Views
Route::get('/borrowed-books', function () {
    return view('user.borrowed-books');
})->name('borrowed-books');

Route::get('/manage-books', function () {
    return view('user.manage-books');
})->name('manage-books');

Route::get('/reports', function () {
    return view('user.reports');
})->name('reports');

Route::get('/notifications', function () {
    return view('user.notifications');
})->name('notifications');

// Public Profile View (no auth required)
Route::get('/profile', function () {
    return view('user.profile');
})->name('profile');

Route::get('/profile/edit', function () {
    return view('profile.edit');
})->name('profile.edit');

// Search Books
Route::get('/search-books', [DashboardController::class, 'search'])->name('search-books');

// Note: These could be restricted later if admin functionality is needed
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Book Management (public for browsing/editing)
    Route::get('/books', [AdminBookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [AdminBookController::class, 'create'])->name('books.create');
    Route::post('/books', [AdminBookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [AdminBookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [AdminBookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [AdminBookController::class, 'destroy'])->name('books.destroy');

    // Categories
    Route::resource('categories', CategoryController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    // Borrowing Management
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::get('/borrowings/{id}', [BorrowingController::class, 'show'])->name('borrowings.show');
    Route::get('/borrowings/{id}/edit', [BorrowingController::class, 'edit'])->name('borrowings.edit');
    Route::put('/borrowings/{id}', [BorrowingController::class, 'update'])->name('borrowings.update');
    Route::delete('/borrowings/{id}', [BorrowingController::class, 'destroy'])->name('borrowings.destroy');

    // Borrow Requests and History
    Route::get('/borrow-requests', [BorrowRequestController::class, 'index'])->name('borrow-requests.index');
    Route::get('/borrowing-history', [BorrowingHistoryController::class, 'index'])->name('borrowing-history.index');

    // Reports and Settings
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Statistics
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/statistics/{id}', [StatisticsController::class, 'show'])->name('statistics.show');

    // Access Logs
    Route::get('/access-logs', [AccessLogController::class, 'index'])->name('logs.index');
});

Route::get('/profile', function () {
    // Return your profile view
    return view('admin.profile');
})->name('profile');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});

