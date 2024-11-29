<?php

use App\Http\Middleware\SetSiteLocation;
use App\Http\Middleware\SetSiteStyles;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// ECS Health Check
Route::get('ecs_ping', function () {
    Log::info('ECS Ping');

    return ['healthy' => true];
});

Route::get('testimage', [App\Http\Controllers\API\Admin\ImageController::class, 'testImageCreation'])->name('testImageCreation');

$domain = parse_url(config('app.url'))['host'];

Route::get('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);

Route::group(['domain' => $domain], function () {
    //customer routing
    Route::get('/welcome', function () {
        return view('applicant.home');
    });

    //customer shop links
    Route::get('m/{code}', [App\Http\Controllers\ShopController::class, 'show'])->name('memorialsHome')->middleware(SetSiteStyles::class, SetSiteLocation::class);
    Route::get('m/{code}/categories', [App\Http\Controllers\ShopController::class, 'show'])->name('memorials')->middleware(SetSiteStyles::class);
    Route::get('m/{code}/productpage/{productid}', [App\Http\Controllers\ShopController::class, 'show'])->name('memorials')->middleware(SetSiteStyles::class);
    Route::get('m/{code}/{categoryid}/productpage/{productid}', [App\Http\Controllers\ShopController::class, 'show'])->name('memorials')->middleware(SetSiteStyles::class);
    Route::get('m/{code}/productlisting/{categoryid}', [App\Http\Controllers\ShopController::class, 'show'])->name('memorials')->middleware(SetSiteStyles::class);
    Route::get('m/paymentConfirmed/{code}/{productid}', [App\Http\Controllers\ShopController::class, 'show'])->name('memorials')->middleware(SetSiteStyles::class);
    Route::get('m/{code}/bereavementsupport', [App\Http\Controllers\ShopController::class, 'show'])->name('memorials')->middleware(SetSiteStyles::class);
    Route::get('m/{code}/faqs', [App\Http\Controllers\ShopController::class, 'show'])->name('memorials')->middleware(SetSiteStyles::class);
    Route::get('m/{code}/viewarticle/{articleid}', [App\Http\Controllers\ShopController::class, 'show'])->name('memorials')->middleware(SetSiteStyles::class);
    Route::get('m/{code}/termsofservice', [App\Http\Controllers\ShopController::class, 'show'])->name('memorials')->middleware(SetSiteStyles::class);

    Route::get('memorialsQr/{code}', [App\Http\Controllers\ShopController::class, 'memorialsQr'])->name('memorialsQr');

    Route::get('{any}', function () {
        return view('applicant.home');
    })->where('any', '.*');

    //remove access to register route
    Route::get('register', function () {
        return view('login');
    });
});

//admin routing
$adminDomain = 'admin.'.$domain;
Route::group(['domain' => $adminDomain], function () {
    Route::impersonate();

    Route::get('/', function () {
        if (Auth::guest()) {
            return redirect('/login');
        }

        return redirect('/index');
    });

    Route::get('password/reset/', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('showLinkRequestForm');
    Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('showResetForm');

    Route::middleware(['auth'])->group(function () {
        Route::get('{any}', function () {
            return view('admin.index');
        })->where('any', '.*');
    });
});
Auth::routes();

Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
