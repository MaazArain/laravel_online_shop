<?php

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\BrandsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductSubCategoryController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TempImageController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




// Frontend work Here
Route::controller(HomeController::class)->group(function(){
    Route::get('/' , 'home')->name('frontend.home');
});



Route::controller(ShopController::class)->group(function(){
    Route::get('/shop/{categorySlug?}/{subCategorySlug?}' , 'index')->name('frontend.shop');
    Route::get('/product/{slug}' , 'product')->name('frontend.product');
});


Route::controller(CartController::class)->group(function(){
    Route::get('/cart' , 'cart')->name('frontend.cart');
    Route::post('/add-to-cart' , 'add_to_cart')->name('frontend.add_to_cart');
    Route::post('/update-cart', 'updateCart')->name('frontend.updateCart');
    Route::post('/delete-item', 'deleteItem')->name('frontend.deleteItem.cart');
    Route::get('/checkout' , 'checkout')->name('frontend.checkout');
    Route::post('/process-checkout' , 'processCheckout')->name('frontend.processCheckout');
    Route::get('/thanks/{order_id}' , 'thankyou')->name('frontend.thankyou');
    Route::post('/get-order-summary' ,   'getOrderBySummary')->name('frontend.getOrderBySummary');
});





Route::group(['prefix' => 'account'], function () {
    // Routes for Guests (Not Logged In)
    Route::middleware('guest')->controller(AuthController::class)->group(function () {
        Route::get('/register', 'register')->name('frontend.account.register');
        Route::post('/register-post', 'processRegister')->name('frontend.account.register_post');

        Route::get('/login', 'login')->name('frontend.account.login');
        Route::post('/login-post', 'processLogin')->name('frontend.account.login_post');
    });
    // Routes for Logged-In Users
    Route::middleware('auth')->controller(AuthController::class)->group(function () {
        Route::get('/profile', 'profile')->name('frontend.account.profile');
        Route::post('/logout', 'logout')->name('frontend.account.logout');
    });
});
// Here it is Starting of Backend....
Route::prefix('admin')->group(function(){
    Route::group(['middleware' => 'admin.guest'] , function(){
        Route::controller(AdminLoginController::class)->group(function () {
            Route::get('/login' ,  'admin_login')->name('admin.login');
            Route::post('/authenticate' , 'admin_authenticate')->name('admin.authenticate');
        });
    });

    Route::group(['middleware' => 'admin.auth'] , function(){
        Route::controller(DashboardController::class)->group(function(){
            Route::get('/dashboard' , 'admin_index')->name('admin.dashboard');
            Route::get('/logout' , 'logout')->name('admin.logout');
        });


        Route::controller(CategoryController::class)->group(function(){
            Route::get('/categories' , 'admin_index')->name('admin.categories');
            Route::get('/categories/create' , 'admin_categories')->name('admin.add.categories');
            Route::post('/categories/store' , 'store')->name('admin.categories.store');
            Route::get('/categories/{category}/edit' , 'edit')->name('admin.categories.edit');
            Route::put('/categories/{category}', 'update')->name('admin.categories.update');
            Route::delete('/categories/{category}', 'destroy')->name('admin.categories.delete');
            Route::get('/getSlug' , 'getSlug')->name('admin.getSlug');
        });


        Route::controller(SubCategoryController::class)->group(function(){
            Route::get('/sub-categories' , 'index')->name('admin.sub_categories');
            Route::get('/sub-categories/create' , 'create')->name('admin.add.sub_categories');
            Route::post('/sub-categories/store' , 'store')->name('admin.store.sub_categories');
            Route::get('/sub-categories/{subCategory}/edit' , 'edit')->name('admin.edit.sub_categories');
            Route::put('/sub-categories/{subCategory}' , 'update')->name('admin.update.sub_categories');
            Route::delete('/sub-categories/{subCategory}/delete' , 'destroy')->name('admin.delete.sub_categories');
        });

        Route::controller(BrandsController::class)->group(function(){
            Route::get('/brands' , 'index')->name('admin.brands');
            Route::get('/brands/create' , 'create')->name('admin.add.brands');
            Route::post('/brands/store' , 'store')->name('admin.store.brands');
            Route::get('/brands/{brand}/edit' , 'edit')->name('admin.edit.brands');
            Route::put('/brands/{brand}' , 'update')->name('admin.update.brands');
            Route::delete('/brands/{brand}/delete' , 'destroy')->name('admin.delete.brands');
        });

        Route::controller(ProductController::class)->group(function(){
            Route::get('/products' , 'index')->name('admin.products');
            Route::get('/products/create' , 'create')->name('admin.add.products');
            Route::post('/products/store' , 'store')->name('admin.store.products');
            Route::get('/products/{product}/edit' , 'edit')->name('admin.edit.products');
            Route::put('/products/{product}' , 'update')->name('admin.update.products');
            Route::delete('/products/{product}/delete' , 'destroy')->name('admin.delete.products');
            Route::get('/products/get-products' , 'getProducts')->name('admin.getProducts');
        });

        Route::controller(ShippingController::class)->group(function(){
            Route::get('/shipping' , 'index')->name('admin.shipping');
            Route::get('/shipping/create' , 'create')->name('admin.add.shipping');
            Route::post('/shipping/store' , 'store')->name('admin.store.shipping');
            Route::get('/shipping/edit/{id}' , 'editShipping')->name('admin.edit.shipping');
            Route::put('/shipping/update/{id}' , 'updateShipping')->name('admin.update.shipping');
            Route::delete('/shipping/delete/{id}' , 'destoryShipping')->name('admin.delete.shipping');
        });


        Route::controller(DiscountCodeController::class)->group(function(){
            Route::get('/discount-code' , 'index')->name('admin.discount_code');
            Route::get('/discount-code/create' , 'create')->name('admin.add.discount_code');
            Route::post('/discount-code/post' , 'post')->name('admin.store.discount_code');
            Route::get('/discount-code/edit/{id}' , 'edit')->name('admin.edit.discount_code');
            Route::put('/discount-code/update/{id}' , 'update')->name('admin.update.discount_code');
            Route::delete('/discount-code/delete/{id}' , 'destroy')->name('admin.delete.discount_code');
        });

        Route::get('/product-subCategories' , [ProductSubCategoryController::class , 'index'])->name('admin.product_subCategories.index');
        Route::post('/product-images/update' , [ProductImageController::class  , 'update'])->name('product-images.update');
        Route::delete('/product-images' , [ProductImageController::class  , 'destroy'])->name('product-images.destroy');
        Route::post('/upload-temp-image' , [TempImageController::class  , 'create'])->name('temp-images.create');
    });
});
