<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear', function () {
	// Artisan::call('migrate', ["--force"=> true ]);
	Artisan::call('optimize:clear'); // clear everything e.g. cache
	return 'Compiled views cleared! <br> Application cache cleared! <br> Route cache cleared! <br> Configuration cache cleared! <br> Compiled services and packages files removed! <br> Caches cleared successfully!';
});

Route::view('/maintenance', 'errors.maintenance')->name('maintenance');

Auth::routes(["verify" => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth', 'middleware' => 'verified'], function(){

	/* prefix admin routes */
	Route::group(['prefix' => 'admin', 'middleware' => 'role:administrator|admin|vendor'], function(){
		/* action methods */
		Route::view('/', 'admin.index')->name('admin');
		// Route::get('/', 'Admin\AdminController@index')->name('admin');
		Route::get('export-orders', 'Admin\OrdersController@export')->name('export.orders');

		Route::get('product-reviews', 'Admin\AdminController@productReviews')->name('product.reviews');
		Route::get('review-delete/{id}', 'Admin\AdminController@reviewDestroy')->name('review.destroy');
		Route::get('review-edit/{id}', 'Admin\AdminController@editReview')->name('review.edit');

		Route::get('/notifications', 'Admin\AdminController@orderNotifications')->name('order.notifications');

		Route::match(['get', 'post'], '/site-options', 'Admin\AdminController@siteOptions')->name('site.options');
		Route::post('/users/{id}/trashed', 'Admin\UsersController@trashed')->name('users.trashed');
		Route::post('/users/{id}/restore', 'Admin\UsersController@restore')->name('users.restore');
		Route::delete('/users/{id}', 'Admin\UsersController@dropped')->name('users.dropped');
		Route::get('/categories/{id}/sub-categories', 'Admin\CategoriesController@subCategories')->name('categories.sub');
		Route::get('/products/{id}/galleries', 'Admin\ProductsController@productGalleries')->name('products.galleries');
		Route::post('/products/{id}/galleries', 'Admin\ProductsController@productGalleries')->name('products.galleries');
		Route::get('/sliders/{slider}/slides', 'Admin\SlidersController@sliderSlides')->name('sliders.slides');
		Route::get('/products/{id}/stocks', 'Admin\ProductsController@productStocks')->name('products.stocks');
		Route::post('/products/{id}/stocks', 'Admin\ProductsController@productStocks')->name('products.stocks');
		Route::get('/stocks/profit', 'Admin\ProductStocksController@stocksProfit')->name('stocks.profit');
		/* resource methods */
		Route::resource('/users', 'Admin\UsersController');
		Route::resource('/brands', 'Admin\BrandsController');
		Route::resource('/categories', 'Admin\CategoriesController');
		Route::resource('/sub-categories', 'Admin\SubCategoriesController');
		Route::resource('/shipping-costs', 'Admin\ShippingCostsController');
		Route::resource('/products', 'Admin\ProductsController');
		// Route::resource('/product-stocks', 'Admin\ProductStocksController');
		Route::resource('/stocks', 'Admin\ProductStocksController');
		// Route::resource('/coupons', 'Admin\CouponsController');
		Route::resource('/galleries', 'Admin\GalleriesController');
		Route::resource('/orders', 'Admin\OrdersController');
		Route::resource('/videos', 'Admin\VideosController');
		Route::resource('/logos', 'Admin\LogosController');
		Route::resource('/contacts', 'Admin\ContactController');
		Route::resource('/sliders', 'Admin\SlidersController');
		Route::resource('/slides', 'Admin\SlidesController');
		Route::resource('/meta-tags', 'Admin\MetaTagsController', ['names' => 'meta.tags']);
	});
});

// Routes for Public and Admin
Route::post('/avatar/{id}', 'Admin\AdminController@avatarUpdate')->name('avatar.update');

// Public Routes
Route::view('faq', 'faq')->name('faq');
Route::view('discount', 'discount')->name('discount');
Route::view('site-map', 'site-map')->name('site.map');
Route::view('shipping', 'shipping')->name('shipping');
Route::view('contact-us', 'contact-us')->name('contact.us');
Route::view('track-order', 'track-order')->name('track.order');
Route::view('privacy-policy', 'privacy-policy')->name('privacy.policy');
Route::view('terms-conditions', 'terms-conditions')->name('terms.conditions');
Route::view('delivery-information', 'delivery-information')->name('delivery.information');

Route::get('/', 'PublicController@index')->name('front.home');
Route::post('contact-us', 'PublicController@contactUs')->name('contact.us');

Route::match(['get', 'post'], 'search/{anything}', 'PublicController@search')->name('search');
Route::get('products' ,'PublicController@products')->name('products');

// Cart Routes
Route::get('product/{id}', 'ProductController@productDetail')->name('product.detail');
Route::post('add-to-cart', 'CartController@storeCart')->name('add.to.cart');
Route::get('cart-items', 'CartController@index')->name('cart.show');
Route::get('delete/cart-item/{id}','CartController@destroy');
Route::post('update/cart', 'CartController@update')->name('update.cart');
Route::post('apply/coupon', 'CartController@applyCoupon')->name('apply.coupon');
Route::post('set-ship-cost','CartController@setShipCost')->name('set.ship.cost');
Route::post('add-ship-cost', 'PublicController@addShipCost')->name('add.ship.cost');
Route::get('order-status/{ref?}', 'PublicController@orderStatus')->name('order.status');

Route::group(['middleware' => ['auth','verified']], function(){
	// Profile
	Route::PUT('update-profile/{id}','PublicController@updateProfile')->name('user.update');
	Route::get('profile', 'PublicController@profile')->name('profile');

	// Payment Routes
	Route::get('checkout', 'CartController@checkout')->name('checkout');
	Route::post('pay','PaymentController@payment')->name('payment');
	Route::post('show-pay','PaymentController@showPayTab')->name('show.pay');
	Route::get('get-states/{code}', 'PublicController@getStates')->name('get.states');

	Route::post('self-collect' , 'PublicController@selfCollect')->name('self.collect');
	Route::post('cash-on-delivery','PublicController@cashOnDelivery')->name('cash.on.delivery');
	Route::get('order-completed', 'PublicController@orderCompleted')->name('order.completed');

	// Order Tracking
	Route::get('track-orders','OrderController@index')->name('track.orders');
	Route::get('order/show/{id?}', 'OrderController@show')->name('order.show');

	// Review
	Route::get('order-review/{id?}', 'ReviewController@show')->name('order.review');
	Route::post('save-review', 'ReviewController@save')->name('save.review');

});
