<?php

use Livewire\Livewire;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Website\FaqController;
use App\Http\Controllers\Website\PaymentGetway;
use App\Http\Controllers\Website\CartController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\PageController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Website\BrandController;
use App\Http\Controllers\Website\AboutUsController;
use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Website\ProductController;
use App\Http\Controllers\Website\ProfileController;
use App\Http\Controllers\Website\CategoryController;
use App\Http\Controllers\Website\CheckoutController;
use App\Http\Controllers\Website\WishlistController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Website\DynamicPageController;
use App\Http\Controllers\Website\PaymentGetwayController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'as' => 'website.',
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {
        ################################## Auth ####################################
        Route::controller(RegisterController::class)->group(function () {
            Route::get('register',  'showRegistrationForm')->name('register.get');
            Route::post('register', 'register')->name('register.post');
        });
        Route::controller(LoginController::class)->group(function () {
            Route::get('login',  'showLoginForm')->name('login.get');
            Route::post('login', 'login')->name('login.post');
            Route::post('logout', 'logout')->name('logout.post');
        });


        Route::get('/home',        [HomeController::class, 'index'])->name('home');
        Route::get('page/{slug}',  [DynamicPageController::class, 'showDynamicPage'])->name('dynamic.page');
        Route::get('faqs',         [FaqController::class, 'showFaqPage'])->name('faqs.index');

        ################################# Brands Routes ######################################
        Route::prefix('brands')->name('brands.')->controller(BrandController::class)->group(function () {
            Route::get('/',                  'getBrands')->name('index');
            Route::get('/{slug}/products',   'getProductsByBrand')->name('products');
        });
        ################################# Categories Routes ######################################
        Route::prefix('categories')->name('categories.')->controller(CategoryController::class)->group(function () {
            Route::get('/',   'getCategories')->name('index');
            Route::get('/{slug}/products', 'getProductsByCategory')->name('products');
        });

        ################################# Products Routes ######################################
        Route::prefix('products')->name('products.')->controller(ProductController::class)->group(function () {
            Route::get('/{type}',                  'getProductsByType')->name('by.type');
            Route::get('/show/{slug}',             'showProductPage')->name('show');
            Route::get('/{slug}/related-products', 'relatedProducts')->name('related');
        });


        Route::get('shop',           [HomeController::class, 'showShopPage'])->name('shop');


        ################################## Profile ####################################
        Route::group(['middleware' => 'auth:web'], function () {

            Route::controller(ProfileController::class)->group(function () {
                Route::get('user-profile',  'showProfile')->name('profile');
            });
            ################################ Wishlist Routes ##########################
            Route::get('wishlist',  WishlistController::class)->name('wishlist');

            ################################ Cart Routes ##############################
            Route::get('cart', [CartController::class, 'showCartPage'])->name('cart');

            ################################ Payment Routes ##############################
            Route::get('checkout',              [CheckoutController::class, 'showCheckoutPage'])->name('checkout.get');
            Route::post('checkout',             [CheckoutController::class, 'checkout'])->name('checkout.post');

            ################################ contacts Routes ##############################
            Route::get('contacts', [ContactController::class, 'index'])->name('contacts.get');
        });

        // refactor mcammara conflict with livewier
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/livewire/update', $handle);
        });
    }

);
Route::get('checkout/callback',    [CheckoutController::class, 'callback']);
Route::get('checkout/error',       [CheckoutController::class, 'error']);


Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');









// endpoint : https://apitest.myfatoorah.com/v2/SendPayment
// method   : POST
// headers  : Authorization =>Bearer rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL

Route::get('test', function () {
    $paypalBaseUrl = env('PAYPAL_MODE') === 'sandbox'
        ? 'https://api-m.sandbox.paypal.com'
        : 'https://api-m.paypal.com';

    // 1. Get OAuth 2.0 token
    $authResponse = Http::asForm()->withBasicAuth(
        env('PAYPAL_CLIENT_ID'),
        env('PAYPAL_SECRET')
    )->post("$paypalBaseUrl/v1/oauth2/token", [
        'grant_type' => 'client_credentials',
    ]);

    $accessToken = $authResponse['access_token'];

    // cretet order
    $response = Http::withToken($accessToken)
        ->post("$paypalBaseUrl/v2/checkout/orders", [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => '10.00'
                ]
            ]],
            'application_context' => [
                'return_url' => route('paypal.callback'),
                'cancel_url' => route('paypal.cancel'),
            ]
        ]);

    $order = $response->json();
    $approvalUrl = collect($order['links'])->firstWhere('rel', 'approve')['href'];

    return redirect($approvalUrl);
});

Route::get('paypal/callback', function(){
   $token = request('token');

    $paypalBaseUrl = env('PAYPAL_MODE') === 'sandbox'
        ? 'https://api-m.sandbox.paypal.com'
        : 'https://api-m.paypal.com';

    $authResponse = Http::asForm()->withBasicAuth(
        env('PAYPAL_CLIENT_ID'),
        env('PAYPAL_SECRET')
    )->post("$paypalBaseUrl/v1/oauth2/token", [
        'grant_type' => 'client_credentials',
    ]);

    $accessToken = $authResponse['access_token'];

    // Capture payment
    $captureResponse = Http::withToken($accessToken)
        ->post("$paypalBaseUrl/v2/checkout/orders/$token/capture");

    return $captureResponse->json();
})->name('paypal.callback');
Route::get('paypal/cancel', function(){})->name('paypal.cancel',);
