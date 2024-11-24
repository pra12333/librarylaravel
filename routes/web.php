<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\User\BookController;
use App\Http\Controllers\PublicBookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ReservedBookController;
use App\Http\Middleware\TestRoleMiddleware;
use App\Models\ReservedBook;
use Carbon\Carbon;
use App\Models\RecentActivity;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\BotManController;

// Public Routes
Route::get('/', [PublicBookController::class, 'index'])->name('Public.bookborrow');
Route::get('/search-books', [PublicBookController::class, 'searchBooks'])->name('Public.searchBooks');

// Auth Routes



Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verify-email');
Route::post('/set-password', [AuthController::class, 'setPassword'])->name('set-password.post');

// Password reset routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');



// User Routes (For authenticated users)
Route::prefix('user')->middleware('auth')->group(function () {
    Route::get('/account', [UserController::class, 'showAccount'])->name('User.account');
    Route::get('/search', [UserController::class, 'showSearch'])->name('User.search');
    Route::get('/myborrow', [UserController::class, 'showmyBorrowedBooks'])->name('User.myborrow');
    Route::get('/borrow', [BookController::class, 'showborrowpage2'])->name('User.borrow');
    Route::get('/myreservedbooks', [BookController::class, 'showMyReservedBooks'])->name('User.myreservedbooks');
    Route::post('/searchbooks-action', [BookController::class, 'searchBooks'])->name('user.searchbooks');
    Route::post('/return-book/{id}', [BookController::class, 'returnBook'])->name('User.returnBook');
    Route::get('/myborrow', [BookController::class, 'showmyBookBorrow'])->name('User.myborrow');
    // Route::get('/booklist', [BookController::class, 'showBookList'])->name('User.booklist');
    Route::get('/search-books', [BookController::class, 'searchmyBooks'])->name('searchBooks');
});

// Book actions
Route::middleware('auth')->group(function () {
    Route::get('/user/bookborrow/{id}', [BookController::class, 'showBorrowPage'])->name('User.bookborrow'); //Route to show the borrow confirmation page
    Route::post('/user/bookborrow/confirm-borrow/{id}', [BookController::class, 'confirmBorrow'])->name('User.confirmBorrow'); //Route to handle the confirm borrow action
    Route::post('/user/bookborrow/confirm-reserve/{id}', [BookController::class, 'confirmReserve'])->name('User.confirmReserve');
    Route::post('/borrowing/{id}', [BookController::class, 'nowborrowing'])->name('User.confirmBorrownow');
});

// Admin Management Routes (Only accessible to superadmins)
Route::middleware(['auth',\App\Http\Middleware\RoleMiddleware::class .':superadmin'])->group(function () {
    Route::get('/admin/register', [AuthController::class, 'showRegister'])->name('admin.register');
    Route::post('/admin/register', [AuthController::class, 'register'])->name('admin.register.submit');
    
    // Route::get('/admin-user', [AdminController::class, 'showAdminManagement'])->name('Admin.adminmanagement');
    // Route::get('/admin-user/{id}/edit', [AdminController::class, 'editAdmin'])->name('Admin.admin.edit');
    // Route::post('/admin-user/{id}/update', [AdminController::class, 'updateAdmin'])->name('Admin.admin.update');
    // Route::delete('/admin-user/{id}/delete', [AdminController::class, 'destroyAdmin'])->name('Admin.admin.delete');
});




// unified homepage route for all users

Route::get('/user/homepage', [HomeController::class, 'showHomePage'])->name('User.homepage')->middleware('auth');



// not auth route and this is accessble by anyone 
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// for account
// Route::post('/logout',[AuthController::class,'logout'])->name('logout.post');


// Example route using TestRoleMiddleware
Route::get('/test-role', function () {
    return "Middleware test route";
})->middleware(['auth', \App\Http\Middleware\TestRoleMiddleware::class . ':admin,superadmin']);

// Admin Routes with TestRoleMiddleware
Route::middleware(['auth', \App\Http\Middleware\TestRoleMiddleware::class . ':admin,superadmin'])->group(function () {
    Route::get('/admin/usermanagementadmin', [AdminUserController::class, 'showUserManagement'])->name('Admin.usermanagementadmin');

    
   
   
   
    

Route::post('/admin/userregister', [AdminUserController::class, 'storeUser'])
    ->middleware(['auth', 'role:admin,superadmin'])
    ->name('Admin.userregister.post');


    // Add other admin routes here
    Route::get('/admin/booklistadmin', [AdminBookController::class, 'showBookList'])->name('Admin.booklistadmin');
    Route::get('/admin/bookregisters', [AdminBookController::class, 'showBookRegister'])->name('Admin.bookregister');
    Route::post('/admin/bookregister', [AdminBookController::class, 'store'])->name('Admin.bookregister.post');
    Route::get('/admin/bookupdate/{id}', [AdminBookController::class, 'showBookUpdate'])->name('Admin.bookupdate');
    Route::put('/admin/bookupdate/{id}', [AdminBookController::class, 'update'])->name('Admin.bookupdate.post');
    Route::delete('/admin/bookdelete/{id}', [AdminBookController::class, 'destroy'])->name('Admin.bookdelete');
    Route::get('/admin/booksearch', [AdminBookController::class, 'search'])->name('Admin.booksearch');
    Route::get('/admin/user/{id}/edit', [AdminUserController::class, 'edit'])->name('Admin.user.edit');
    Route::put('/admin/user/{id}/update', [AdminUserController::class, 'update'])->name('Admin.user.update');
    // Route::get('/admin/userregister',[AdminUserController::class,'showUserRegister'])->name('Admin.userregister');
    Route::delete('/admin/user/{id}/delete', [AdminUserController::class, 'destroy'])->name('Admin.user.delete');
    Route::post('/admin/userregister', [AdminUserController::class, 'storeUser']) ->name('Admin.userregister.post');
});

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':admin,superadmin'])->group(function () {
    
    Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'showDashboard'])->name('Admin.dashboard');
});

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':superadmin'])->group(function () {
    Route::get('/admin/userregister', [\App\Http\Controllers\Admin\AdminUserController::class, 'showUserRegister'])->name('Admin.userregister');
});

Route::middleware(['auth',\App\Http\Middleware\RoleMiddleware::class . ':superadmin'])->group(function () {
    Route::get('/superadmin-panel', [AdminController::class, 'showSuperAdmins'])->name('admin.superadmin.panel');
});


Route::middleware(['auth',\App\Http\Middleware\RoleMiddleware::class . ':admin,superadmin,user'])->group(function () {
    Route::get('/user-homepage/search-books',[BookController::class,'searchBooksForHomepage'])->name('User.searchBooksForHomepage');
}
);

Route::middleware(['auth',\App\Http\Middleware\RoleMiddleware::class.':admin,superadmin,user'])->group(function () {
    Route::get('/user-homepage/categories', [BookController::class, 'getCategories'])->name('User.getCategories');
    Route::get('/user-homepage/books-by-category/{genre}', [BookController::class, 'getBooksByCategory'])->name('User.getBooksByCategory');
});


Route::middleware(['auth',\App\Http\Middleware\RoleMiddleware::class.':admin,superadmin,user'])->group(function () {
   Route::post('/reserved-books/{reservedBookId}/pickup',[ReservedBookController::class,'markAsPickedUp'])->name('reserve.markAsPickedUp');
}
);

Route::middleware(['auth',\App\Http\Middleware\RoleMiddleware::class. ':admin,superadmin,user'])->group(function (){
Route::post('/reserve-book/{bookId}', [ReservedBookController::class, 'reserveBook'])->name('reserve.book');
});







Route::get('/test-recent-activity', function () {
    $recentActivity = RecentActivity::create([
        'description' => 'Test activity via route',
        'expires_at' => Carbon::now()->addHours(48),
    ]);

    // \Log::info('Recent Activity Created:', ['activity' => $recentActivity]);

    return 'Recent activity logged';
});

// logout route for top page 

Route::post('/top-page-layout',[AuthController::class,'topPageLogout'])->name('top.logout');

Route::post('/confirm-reservation/{id}',[BookController::class,'confirmReservation'])->name('User.confirmReservation');
Route::get('/cancel-reservation/{id}',[BookController::class,'cancelReservation'])->name('User.cancelReservation');

Route::get('/user/pickup/{id}',[BookController::class,'pickupbook'])->name('User.pickup');

// Route::post('/user/pickup-book/{id}',[BookController::class,'pick-up'])->name('User.pick-up');

Route::post('/user/confirm-borrow/{id}', [BookController::class, 'confirmBorrow'])->name('User.confirmBorrow');

Route::get('/test',function () {
    return view ('test');
});

Route::get('/test1',function () {
    return view ('test1');
});

Route::post('/request-book/{id}',[BookController::class,'requestBook'])->name('User.requestBook');

// Route::put('/approve-request/approve/{id}', [BookController::class, 'approveRequest'])->name('admin.approveRequest');
// Route::put('/reject-request/reject/{id}', [AdminBookController::class, 'rejectRequest'])->name('admin.rejectRequest');


// Route::get('/admin/book-requests', [AdminBookController::class, 'showBookRequests'])->name('admin.bookRequests');

// Route::get('/admin/book-request/{id}', [AdminBookController::class, 'showBookRequestDetails'])->name('admin.bookRequestDetails');

Route::post('/user/update-profile-picture',[UserController::class,'updateProfilePicture'])->name('user.updateProfilePicture');

// route for removing profile picture

Route::get('/user/remove-profilepicture',[UserController::class,'removeProfilePicture'])->name('user.removeProfilePicture');

// update the profile route
Route::post('/user/update-profile',[UserController::class,'updateProfile'])->name('user.updateProfile');

// update password route

Route::post('/user/update-password',[UserController::class,'updatePassword'])->name('user.updatePassword');

Route::get('/admin/genres/create',[GenreController::class,'create'])->name('genres.create');
Route::post('/admin/genres/store',[GenreController::class,'store'])->name('genres.store');

Route::get('/notifications/mark-as-read/{id}', function($id) {
    // \Log::info('Marking notification as read', ['notification_id' => $id, 'user_id' => auth()->user()->id]);

    // Fetch unread notification by ID
    $notification = auth()->user()->unreadNotifications()->find($id);

    // Check if the notification exists and is unread
    if ($notification) {
        $notification->markAsRead();
        // \Log::info('Notification marked as read', ['notification_id' => $id]);

        return redirect()->back()->with('status', 'Notification marked as read!');
    }

    // \Log::error('Notification not found or already read', ['notification_id' => $id]);
    return redirect()->back()->with('error', 'Notification not found or already read.');
})->name('notifications.markAsRead');

Route::get('/bookdetails/{id}',[BookController::class,'showBookDetails'])->name('User.bookdetails');



Route::middleware(['auth',\App\Http\Middleware\RoleMiddleware::class. ':admin,superadmin'])->group(function (){
    Route::post('/request-borrow/{id}',[BookController::class,'requestBorrow'])->name('User.requestBorrow');
    Route::get('/admin/requests',[AdminBookController::class,'viewRequests'])->name('admin.requests');
    Route::post('/admin/requests/approve/{id}',[AdminBookController::class,'approveRequest'])->name('admin.approveRequest');
    Route::post('admin/requests/reject/{id}',[AdminBookController::class,'rejectRequest'])->name('admin.rejectRequest');
    });


    Route::middleware(['auth',\App\Http\Middleware\RoleMiddleware::class. ':admin,superadmin,user'])->group(function (){
        Route::get('/books/{id}',[BookController::class,'showBook'])->name('books.show');
    });

   Route::get('/books/borrow-by-qr/{id}',[BookController::class,'borrowBookByQr'])->name('books.borrowByQr');

   Route::get('/books/{id}',[BookController::class,'showBook'])->name('books.show');

 // cart management routes




 Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':admin,superadmin,user'])->group(function () {

    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('user.addToCart');
    Route::get('/cart', [CartController::class, 'viewCart'])->name('user.viewCart');
    Route::post('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('user.removeFromCart');
    Route::post('/cart/borrow', [CartController::class, 'borrowCart'])->name('user.borrowCart');
    Route::post('/cart/update/{cartId}', [CartController::class, 'updateCart'])->name('user.updateCart');
    

});

Route::get('/cart/count',[CartController::class,'getCartCount']);




Route::post('/wishlist/add/{book}', [WishlistController::class, 'add'])->name('wishlist.add');
Route::get('/wishlist',[WishlistController::class,'index'])->name('wishlist.index')->middleware('auth');
Route::delete('/wishlist/remove/{id}',[WishlistController::class,'remove'])->name('wishlist.remove')->middleware('auth');

Route::match(['get','post'], '/botman',[BotManController::class,'handle']);

// Route::match(['get', 'post'], '/botman', function() {
//     return response()->json(['message' => 'Route is working']);
// });
