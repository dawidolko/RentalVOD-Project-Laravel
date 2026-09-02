<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\OpinionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieRecommendationController;
use App\Http\Middleware\CheckNotAdmin;
use App\Http\Middleware\ShareDataToViews;
use App\Http\Middleware\EnsureUserHasLoyaltyPoints;
use App\Http\Middleware\IsAdmin;

Route::middleware([ShareDataToViews::class, EnsureUserHasLoyaltyPoints::class])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/regulamin', [HomeController::class, 'regulamin'])->name('regulamin');
    Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
    Route::get('/search', [MoviesController::class, 'search'])->name('movies.search');
    Route::get('/movies', [MoviesController::class, 'index'])->name('movies.index');
    Route::get('/movie/{id}', [MoviesController::class, 'show'])->name('movies.show');
    Route::post('/movies/{id}/setSuperPromoPrice', [AdminController::class, 'setSuperPromoPrice'])->name('movies.setSuperPromoPrice');

    Route::controller(AuthController::class)->group(function () {
        Route::get('/auth/login', 'login')->name('login');
        Route::post('/auth/login', 'authenticate')->name('login.authenticate');
        Route::get('/auth/logout', 'logout')->name('logout');
        Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/profile', [UsersController::class, 'showProfile'])->name('user.profile');
        Route::get('/settings', [UsersController::class, 'showSettings'])->name('settings');
        Route::put('/user/update', [UsersController::class, 'update'])->name('user.update');
        Route::put('/user/change-password', [UsersController::class, 'changePassword'])->name('user.change_password');
        Route::put('/user/update-avatar', [UsersController::class, 'updateAvatar'])->name('user.update_avatar');
        Route::get('/movies/image/{id}', [MoviesController::class, 'image'])->name('movies.image');
        Route::post('/opinions', [OpinionController::class, 'store'])->name('opinions.store');
        Route::get('/loans/{movie}', [UsersController::class, 'showMovie'])->name('loans.show');
        Route::post('/movies/{movie}/upgrade', [UsersController::class, 'upgradeToPremium'])->name('user.upgradeToPremium');
        Route::get('/loans/premium/{movie_id}', [UsersController::class, 'showPremiumMovie'])->name('loans.premium');

        Route::middleware([CheckNotAdmin::class])->group(function () {
            Route::post('cart/add/{movie_id}', [UsersController::class, 'addToCart'])->name('cart.add');
            Route::post('cart/remove/{movie_id}', [UsersController::class, 'removeFromCart'])->name('cart.remove');
            Route::post('cart/update/{movie_id}', [UsersController::class, 'updateCart'])->name('cart.update');
            Route::post('checkout', [UsersController::class, 'checkout'])->name('checkout');
            Route::get('cart', [UsersController::class, 'showCart'])->name('cart.show');
        });

        Route::post('/send-friend-request', [FriendshipController::class, 'sendRequest'])->name('friends.sendRequest');
        Route::post('/accept-friend-request/{friendshipId}', [FriendshipController::class, 'acceptRequest'])->name('friends.acceptRequest');
        Route::post('/decline-friend-request/{friendshipId}', [FriendshipController::class, 'declineRequest'])->name('friends.declineRequest');
        Route::delete('/remove-friend/{friendId}', [FriendshipController::class, 'removeFriend'])->name('friends.removeFriend');
        Route::get('/search-users', [FriendshipController::class, 'searchUsers']);
        Route::post('/movies/{movie}/recommend', [MovieRecommendationController::class, 'recommendMovie'])->name('movies.recommend');
    });

    Route::middleware(['auth', IsAdmin::class])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/users/edit/{id}', [AdminController::class, 'editUser'])->name('admin.editUser');
        Route::delete('/admin/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
        Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
        Route::get('/admin/users/{id}/orders', [AdminController::class, 'userOrders'])->name('admin.userOrders');
        Route::post('/admin/users/update/{id}', [AdminController::class, 'updateUser'])->name('admin.updateUser');
        Route::get('/admin/movies', [AdminController::class, 'movies'])->name('admin.movies');
        Route::get('/admin/movies/edit/{id}', [AdminController::class, 'editMovie'])->name('admin.editMovie');
        Route::put('/admin/movies/update/{id}', [AdminController::class, 'updateMovie'])->name('admin.updateMovie');
        Route::delete('/admin/movies/delete/{id}', [AdminController::class, 'deleteMovie'])->name('admin.deleteMovie');
        Route::post('/admin/movies/add', [AdminController::class, 'addMovie'])->name('admin.addMovie');
        Route::post('/admin/category/add', [AdminController::class, 'addCategory'])->name('admin.addCategory');
        Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::post('/admin/settings/rule', [AdminController::class, 'updateRule'])->name('admin.updateRule');
        Route::post('/admin/settings/promotions', [AdminController::class, 'togglePromotions'])->name('admin.togglePromotions');
    });
});
