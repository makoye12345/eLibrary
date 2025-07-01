<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BorrowingController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AccessLogController;
//use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\BorrowingHistoryController;
use App\Http\Controllers\ReturnBookController;
use App\Http\Controllers\User\PasswordController;
use App\Http\Controllers\User\UserBookController;
use App\Http\Controllers\Admin\CategoryBookController;
use App\Http\Controllers\User\BorrowedBooksController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\AdminAuthController;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\BookSearchController;
use App\Http\Controllers\User\ReservedBooksController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\BookReservationController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
//use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\UserMessageController;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Admin\Borrow;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\FinesController;
//use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\ContactController;








/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/
// routes/web.php


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('admin.messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('admin.messages.store');
    Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('admin.messages.destroy');
});

Route::prefix('user')->middleware(['auth'])->group(function () {
    Route::get('/messages', [UserMessageController::class, 'index'])->name('user.messages.index');
    Route::post('/messages', [UserMessageController::class, 'store'])->name('user.messages.store');
    Route::get('/messages/{id}/read', [UserMessageController::class, 'read'])->name('user.messages.read');
});


Route::get('/pages/contact', [ContactController::class, 'submit'])->name('pages.contact');

Route::get('/', function () {
   return view('welcome');
})->name('welcome');
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');
Route::view('/about', 'pages.about');
Route::view('/contact', 'pages.contact');
Route::view('/blog', 'pages.blog');
Route::view('/privacy', 'pages.privacy');
Route::view('/term', 'pages.term');
Route::view('/welcome', 'pages.welcome');


Route::middleware('auth')->group(function () {
    Route::get('/user/help', function () {
        return view('user.help');
    })->name('user.help');
});

Route::post('/logout', function () {
    if (Auth::check()) {
        Auth::logout();
        return redirect('/login')->with('success', 'Logged out successfully.');
    }
    return redirect('/login')->with('info', 'You are already logged out.');
})->name('logout');


// Admin payment routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/payments', [PaymentsController::class, 'index'])->name('admin.payments.index');
    Route::get('/payments/{id}', [PaymentsController::class, 'show'])->name('admin.payments.show');
    Route::patch('/payments/{id}/mark-as-paid', [PaymentsController::class, 'markAsPaid'])->name('admin.payments.mark-as-paid');
});
// User payment routes, protected by auth middleware
Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/payments', [PaymentsController::class, 'index'])->name('user.payments.index');
    Route::get('/payments/create-invoice', [PaymentsController::class, 'createInvoiceForm'])->name('user.payments.create-invoice');
    Route::post('/payments/create-invoice', [PaymentsController::class, 'createInvoice'])->name('user.payments.store-invoice');
    Route::get('/payments/statement/{id}', [PaymentsController::class, 'showStatement'])->name('user.payments.statement');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'store']);
});
Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

 //Route::get('/import-users', [UserController::class, 'showImportForm'])->name('admin.users.import.form');
   // Route::post('/import-users', [UserController::class, 'import'])->name('admin.users.import');
   Route::prefix('admin')->group(function () {
    Route::get('users/import', [ControllerA::class, 'import'])->name('admin.users.import');
});
Route::prefix('admin')->group(function () {
    Route::post('users/import', [ControllerB::class, 'process'])->name('admin.users.import');
});
    

    Route::get('/admin/users/template', function () {
    return response()->download(public_path('templates/users_template.xlsx'));
})->name('admin.users.template');


  //user  messages route 
  Route::prefix('user')->middleware(['auth'])->name('user.')->group(function () {
    
    Route::get('/messages', [UserMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [UserMessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [UserMessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{id}/mark-as-read', [UserMessageController::class, 'markAsRead'])->name('messages.markAsRead');
      Route::get('/{message}', [UserMessageController::class, 'show'])->name('user.messages.show');
});


Route::post('admin/users/import', [App\Http\Controllers\Admin\UserController::class, 'import'])->name('admin.users.import');
Route::post('/admin/books/import', [\App\Http\Controllers\Admin\BookController::class,'import'])->name('admin.books.import');

Route::middleware('auth:web')->group(function () {
   Route::get('/fines', [BookController::class, 'fines'])->name('user.fines.index');
    Route::get('/user/fines', [BookController::class, 'fines']);
   Route::get('/fines', [BookController::class, 'fines'])->name('user.fines');
    Route::get('/pay/mpesa/{id}', [BookController::class, 'payMpesa'])->name('pay.mpesa');
    Route::get('/pay/halopesa/{id}', [BookController::class, 'payHalopesa'])->name('pay.halopesa');
    Route::get('/pay/crdb/{id}', [BookController::class, 'payCrdb'])->name('pay.crdb');
    Route::get('/pay/nmb/{id}', [BookController::class, 'payNmb'])->name('pay.nmb');
    Route::get('/pay/control/{id}', [BookController::class, 'generateControlNumber'])->name('pay.control');
});

// Admin routes
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/books/borrowed', [BookController::class, 'borrowed'])->name('admin.books.borrowed');

// User routes
Route::post('/books/{borrow}/return', [BooksController::class, 'return'])->name('books.return');
Route::get('/user/books/returned', [UserBookController::class, 'returnedBooks'])->name('user.books.returned');
Route::prefix('user/messages')->name('user.messages.')->group(function () {
    Route::get('/', [UserMessageController::class, 'index'])->name('index');
    Route::post('/', [UserMessageController::class, 'store'])->name('store');
    Route::delete('/{id}', [UserMessageController::class, 'destroy'])->name('destroy');
});

// Add other routes as needed...

Route::get('/books/today-borrow-count', [BookController::class, 'getTodayBorrowCount'])->name('books.today-borrow-count')->middleware('auth');
Route::post('/books/{book}/borrow', [BookController::class, 'store'])->name('user.books.borrow.store')->middleware('auth');
Route::get('/admin/books/borrowed', [\App\Http\Controllers\Admin\BookController::class, 'borrowed'])->name('admin.books.borrowed');
Route::get('/books/issued', [\App\Http\Controllers\Admin\BookController::class, 'issued'])->name('admin.books.issued');
Route::get('/books/overdue', [\App\Http\Controllers\Admin\BookController::class, 'overdue'])->name('admin.books.overdue');
// routes/web.php
Route::put('/user/profile/update', [UserProfileController::class, 'update'])->name('user.profile.update');
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function() {
    // Other admin routes...
    
    Route::get('/statistics', [\App\Http\Controllers\Admin\StatisticsController::class, 'index'])
        ->name('admin.statistics');
});


// Admin-only routes (e.g., adding a new book)
Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::post('/notifications/send', [NotificationController::class, 'send'])->name('notifications.send');

});
Route::middleware('auth')->group(function () {
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
});

Route::post('/access-logs/clear', [UserController::class, 'clearAll'])->name('access-logs.clear');

Route::middleware(['auth'])->group(function () {
    Route::get('/my-access-logs', [UserController::class, 'accessLogs'])->name('user.access.logs');
});

// Password Update Route
Route::get('/loans/pay/{id}', [App\Http\Controllers\LoanController::class, 'payFine'])->name('loans.pay')->middleware('auth');
Route::get('/dashboard', [App\Http\Controllers\user\UserController::class, 'dashboard'])->name('dashboard');


Route::get('/reports', [ReportsController::class, 'index'])->name('reports')->middleware(['auth', 'fetch_notifications', 'log_access']);
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [UserController::class, 'update'])->name('profile.update');
    Route::get('/reports', [UserController::class, 'reports'])->name('reports');
});

// Routes for both web and admin users
Route::middleware(['auth:web,admin'])->group(function () {
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::delete('{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::post('/reservations/cancel/{id}', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::post('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');


});


Route::middleware(['auth'])->group(function () {
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
      
    });
});

// Public profile route
Route::get('/profile/{username}', [DashboardController::class, 'showProfile'])->name('profile.public');

// Public profile route
Route::get('/profile/{username}', [DashboardController::class, 'showProfile'])->name('profile.public');


Route::prefix('admin')->name('admin.')->group(function () {
    // Authentication Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });

    // Protected Admin Routes
    Route::middleware('auth:admin')->group(function () {
       // Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // Add other admin routes here
    });
});

Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/books/reserved', [ReservedBooksController::class, 'index'])->name('user.books.reserved');
});


 //user borrowed books
Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/books/borrowed', [BorrowedBooksController::class, 'index'])->name('user.books.borrowed');
});
   //user search books
Route::middleware(['auth'])->group(function () {
    Route::get('/books/search', [BookSearchController::class, 'search'])->name('books.search');
});


Route::get('/admin/books/returned', [BookController::class, 'adminReturnedBooks'])->name('admin.books.returned');
// Public Routes
//Route::get('/', fn () => view('welcome'))->name('home');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
//Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Public Book Routes
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/search', [BookController::class, 'search'])->name('books.search');
Route::get('/categories/{id}/books', [CategoryBookController::class, 'index'])->name('categories.books');

// Public Borrow and Return Routes
Route::get('/borrow', [BorrowController::class, 'showBorrowForm'])->name('borrow.form');
Route::post('/borrow', [BorrowController::class, 'borrowBook'])->name('borrow.book');
Route::get('/return-book', [ReturnBookController::class, 'index'])->name('return-books.index');
Route::get('/returned', [App\Http\Controllers\BookController::class, 'returnedBooks'])->name('user.books.returned');

// Public Static Views
Route::get('/borrowed-books', fn () => view('user.borrowed-books'))->name('borrowed-books');
Route::get('/manage-books', fn () => view('user.manage-books'))->name('manage-books');
Route::get('/reports', fn () => view('user.reports'))->name('reports');
Route::get('/profile', fn () => view('user.profile'))->name('profile');
//Route::get('/profile/edit', fn () => view('profile.edit'))->name('profile.edit');
Route::get('/search-books', [UserDashboardController::class, 'search'])->name('search-books');



// User Routes (authenticated with web guard)
Route::prefix('user')->middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    
    // Book Routes
    Route::get('/books', [BookController::class, 'index'])->name('user.books.index');
    Route::get('/books/search', [BookController::class, 'search'])->name('user.books.search');
    Route::get('/books/{id}', [BookController::class, 'show'])->name('user.books.show');
    Route::get('/books/{id}/borrow', [BookController::class, 'showBorrowForm'])->name('user.books.borrow');
    Route::post('/books/{id}/borrow', [BookController::class, 'borrowStore'])->name('user.books.borrow.store');
    Route::post('/books/return/{borrow}', [BookController::class, 'returnBook'])->name('user.books.return');
    Route::get('/books/borrowed', [BookController::class, 'borrowedBooks'])->name('user.books.borrowed');
    Route::get('/books/{id}/read', [BookController::class, 'read'])->name('user.books.read');
    Route::post('/books/borrow/submit', [BookController::class, 'submitBorrowRequest'])->name('books.borrow.submit');
    Route::get('/books/{id}/read', [BookController::class, 'read'])->name('user.books.read');
    Route::post('/books/{id}/return', [BookController::class, 'returnBook'])->name('books.return');
   
    // Notification Routes
    Route::get('/notifications', [UserDashboardController::class, 'notifications'])->name('user.notifications');
    Route::get('/notifications/clear', [UserDashboardController::class, 'clearNotifications'])->name('user.notifications.clear');
    Route::post('/notifications/{notification}/read', [UserDashboardController::class, 'markNotificationAsRead'])->name('notifications.read');
    
    // Profile and Other Routes
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::get('/borrowings', [BorrowedBooksController::class, 'index'])->name('borrowings');
    Route::post('/borrowings/extension', [BorrowedBooksController::class, 'requestExtension'])->name('borrowings.extension');
    Route::get('/reservations', [UserDashboardController::class, 'reservations'])->name('reservations');
    Route::get('/contact-librarian', [UserDashboardController::class, 'contactLibrarian'])->name('contact.librarian');
    Route::post('/books/buy', [UserDashboardController::class, 'buyBook'])->name('books.buy');
});

// User Borrowed Books Resource Routes
Route::prefix('user')->middleware(['auth:web'])->group(function () {
    Route::resource('borrowed-books', BorrowedBooksController::class, [
        'names' => [
            'index' => 'user.borrowed-books.index',
            'create' => 'user.borrowed-books.create',
            'store' => 'user.borrowed-books.store',
            'show' => 'user.borrowed-books.show',
            'edit' => 'user.borrowed-books.edit',
            'update' => 'user.borrowed-books.update',
            'destroy' => 'user.borrowed-books.destroy',
        ]
    ]);
});

// Admin Routes (authenticated with admin guard and is_admin middleware)
Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'is_admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [AdminDashboardController::class, 'getDashboardData'])->name('dashboard.data');

    // Admin Profile Routes
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/image', [AdminProfileController::class, 'uploadImage'])->name('profile.upload');
    Route::put('/profile/update', [AdminProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('change-password.update');

    // User Management
    Route::resource('users', UserController::class)->names([
        'index' => 'users.index',
        'create' => 'users.create',
        'store' => 'users.store',
        'edit' => 'users.edit',
        'update' => 'users.update',
        'destroy' => 'users.destroy',
    ]);

    // Book Management
    Route::resource('books', AdminBookController::class)->names([
        'index' => 'books.index',
        'create' => 'books.create',
        'store' => 'books.store',
        'edit' => 'books.edit',
        'update' => 'books.update',
        'destroy' => 'books.destroy',
    ]);
    Route::get('/books/data', [AdminBookController::class, 'getBooksData'])->name('books.data');
    Route::get('/books/{book}/view', [AdminBookController::class, 'view'])->name('books.view');
    Route::get('/books/{book}/content', [BookController::class, 'showContent'])->name('books.show');
    Route::get('/books/pdf', [BookController::class, 'downloadPdf'])->name('books.pdf');
    Route::get('/books/returned', [BookController::class, 'adminReturnedBooks'])->name('books.returned');

    // Category Management
    Route::resource('categories', CategoryController::class)->names([
        'index' => 'categories.index',
        'create' => 'categories.create',
        'store' => 'categories.store',
        'edit' => 'categories.edit',
        'update' => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);
    Route::get('/categories/{category}/books', [CategoryController::class, 'showBooks'])->name('categories.books');

    // Category-Book Management
    Route::resource('category-books', CategoryBookController::class);

    // Borrowing Management
    Route::resource('borrowings', BorrowingController::class)->names([
        'index' => 'borrowings.index',
        'create' => 'borrowings.create',
        'store' => 'borrowings.store',
        'show' => 'borrowings.show',
        'edit' => 'borrowings.edit',
        'update' => 'borrowings.update',
        'destroy' => 'borrowings.destroy',
    ]);

    // Transaction Management
  //  Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
  //  Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
   // Route::patch('/transactions/{transaction}/return', [TransactionController::class, 'markAsReturned'])->name('transactions.return');
    
    // Notification Management
    Route::resource('notifications', NotificationController::class)->names([
        'index' => 'notifications.index',
        'create' => 'notifications.create',
        'store' => 'notifications.store',
        'edit' => 'notifications.edit',
        'update' => 'notifications.update',
        'destroy' => 'notifications.destroy',
    ]);

    // Message Management
   
    // Access Logs
    Route::get('/access-logs', [AccessLogController::class, 'index'])->name('access-logs.index');

    // Reports and Statistics
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/statistics/{id}', [StatisticsController::class, 'show'])->name('statistics.show');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Borrow Requests and History
    Route::get('/borrow-requests', [BorrowRequestController::class, 'index'])->name('borrow-requests.index');
    Route::get('/borrowing-history', [BorrowingHistoryController::class, 'index'])->name('borrowing-history.index');

    // Return Books
    Route::get('/return-books', [ReturnBookController::class, 'index'])->name('return-books.index');
});



Route::prefix('admin')->group(function () {
   Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

