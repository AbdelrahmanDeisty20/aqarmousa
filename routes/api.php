<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\GovernorateController;
use App\Http\Controllers\Api\CompoundController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\Maintenance\MaintenanceController;
use App\Http\Controllers\Api\BannerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- Public Routes ---

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [App\Http\Controllers\Api\Auth\ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::get('/password/verify/{token}', [App\Http\Controllers\Api\Auth\PasswordLinkConfirmationController::class, 'verify'])->name('api.password.verify');
Route::post('/reset-password', [App\Http\Controllers\Api\Auth\ResetPasswordController::class, 'reset']);

// Email Verification
Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Api\Auth\VerificationController::class, 'verify'])
    ->name('api.verification.verify');
Route::post('/email/resend', [App\Http\Controllers\Api\Auth\VerificationController::class, 'resend'])
    ->name('api.verification.send');

// Informational & Content
Route::get('/unit-types', [App\Http\Controllers\Api\UnitTypeController::class, 'index']);
Route::get('/amenities', [App\Http\Controllers\Api\AmenityController::class, 'index']);
Route::get('/faqs', [App\Http\Controllers\Api\FaqController::class, 'index']);
Route::get('/pages', [App\Http\Controllers\Api\PageController::class, 'index']);
Route::get('/pages/{slug}', [App\Http\Controllers\Api\PageController::class, 'show']);
Route::get('/services', [App\Http\Controllers\Api\ServiceController::class, 'index']);
Route::get('/services/{id}', [App\Http\Controllers\Api\ServiceController::class, 'show']);

Route::get('/units/{id}/related', [App\Http\Controllers\Api\UnitController::class, 'related']);
Route::get('/testimonials', [App\Http\Controllers\Api\TestimonialController::class, 'index']);
Route::post('/contact', [App\Http\Controllers\Api\ContactController::class, 'store']);
Route::get('/settings', [App\Http\Controllers\Api\SettingController::class, 'index']);
Route::get('/banners', [BannerController::class, 'index']);
Route::get('/maintenance-services', [MaintenanceController::class, 'index']);
Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'maintenance'], function () {
    Route::get('my-bookings', [MaintenanceController::class, 'myBookings']);
    Route::post('bookings', [MaintenanceController::class, 'store']);
    Route::put('bookings/{booking}', [MaintenanceController::class, 'update']);
    Route::delete('bookings/{booking}', [MaintenanceController::class, 'destroy']);
});
Route::get('/settings/{key}', [App\Http\Controllers\Api\SettingController::class, 'show']);
Route::get('/stats', [App\Http\Controllers\Api\StatsController::class, 'index']);

Route::get('/search', [SearchController::class, 'globalSearch']);

// Listings (Units, Compounds, Governorates)
Route::get('/cities', [App\Http\Controllers\Api\GovernorateController::class, 'index']);
Route::get('/units', [UnitController::class, 'index']);
Route::get('/units/latest', [UnitController::class, 'latest']);
Route::get('/units/nearby', [UnitController::class, 'nearby'])->middleware('auth:sanctum');
Route::get('/units/{id}', [UnitController::class, 'show']);
Route::get('/units/{id}/reviews', [UnitController::class, 'reviews']);

Route::get('/compounds', [CompoundController::class, 'index']);
Route::get('/compounds/{id}', [CompoundController::class, 'show']);

Route::get('/governorates', [GovernorateController::class, 'index']);

// Developers
Route::get('/developers', [App\Http\Controllers\Api\DeveloperController::class, 'index']);
Route::get('/developers/{id}', [App\Http\Controllers\Api\DeveloperController::class, 'show']);

// Sellers
Route::get('/sellers', [App\Http\Controllers\Api\SellerController::class, 'index']);
Route::get('/sellers/{id}', [App\Http\Controllers\Api\SellerController::class, 'show']);

// Payment Callbacks
Route::get('/payment/callback', [App\Http\Controllers\Api\PaymentController::class, 'callback'])->name('api.payment.callback');


// --- Protected Routes ---

Route::middleware('auth:sanctum')->group(function () {

    // User Profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'update']);
    Route::delete('/profile', [AuthController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh-token', [AuthController::class, 'refresh']);

    // Seller Unit Management
    Route::middleware(['approved_seller', 'verified'])->group(function () {
        Route::get('/seller/units', [App\Http\Controllers\Api\SellerUnitController::class, 'index']);
        Route::post('/seller/units', [App\Http\Controllers\Api\SellerUnitController::class, 'store']);
        Route::put('/seller/units/{id}', [App\Http\Controllers\Api\SellerUnitController::class, 'update']);
        Route::delete('/seller/units/{id}', [App\Http\Controllers\Api\SellerUnitController::class, 'destroy']);
        Route::post('/seller/units/{id}/media', [App\Http\Controllers\Api\SellerUnitController::class, 'uploadMedia']);
        Route::post('/seller/units/{id}/toggle-visibility', [App\Http\Controllers\Api\SellerUnitController::class, 'toggleVisibility']);
        Route::get('/seller/stats', [App\Http\Controllers\Api\SellerUnitController::class, 'stats']);
        Route::get('/seller/messages', [App\Http\Controllers\Api\ContactController::class, 'index']);
    });

    // Transactions & Payments (Restricted to verified users)
    Route::middleware('verified')->group(function () {
        Route::get('/transactions', [App\Http\Controllers\Api\TransactionController::class, 'index']);
        Route::post('/payment/initiate', [App\Http\Controllers\Api\PaymentController::class, 'initiate'])->name('api.payment.initiate');
    });
    // Callback moved to public routes


    // Social & Interaction (Restricted to verified users)
    Route::middleware('verified')->group(function () {
        Route::get('/favorites', [App\Http\Controllers\Api\FavoriteController::class, 'index']);
        Route::post('/favorites/toggle', [App\Http\Controllers\Api\FavoriteController::class, 'toggle']);

        // Reviews
        Route::get('/reviews', [App\Http\Controllers\Api\ReviewController::class, 'index']);
        Route::post('/reviews', [App\Http\Controllers\Api\ReviewController::class, 'store']);
        Route::put('/reviews/{id}', [App\Http\Controllers\Api\ReviewController::class, 'update']);
        Route::delete('/reviews/{id}', [App\Http\Controllers\Api\ReviewController::class, 'destroy']);

        Route::get('/my-testimonials', [App\Http\Controllers\Api\TestimonialController::class, 'myTestimonials']);
        Route::post('/testimonials', [App\Http\Controllers\Api\TestimonialController::class, 'store']);
        Route::put('/testimonials/{id}', [App\Http\Controllers\Api\TestimonialController::class, 'update']);
        Route::delete('/testimonials/{id}', [App\Http\Controllers\Api\TestimonialController::class, 'destroy']);

        // Bookings
        Route::get('/bookings', [App\Http\Controllers\Api\ViewingController::class, 'index']);
        Route::post('/bookings', [App\Http\Controllers\Api\ViewingController::class, 'store']);
        Route::put('/bookings/{id}', [App\Http\Controllers\Api\ViewingController::class, 'update']);
        Route::delete('/bookings/{id}', [App\Http\Controllers\Api\ViewingController::class, 'destroy']);
    });
});
