<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login/{lang}/{v}', [ 'as' => 'login', 'uses' => 'AuthController@login'])->middleware('checkguest');
    Route::post('logout/{lang}/{v}', 'AuthController@logout');
    Route::post('refresh/{lang}/{v}', 'AuthController@refresh');
    Route::post('me/{lang}/{v}', 'AuthController@me');
    Route::post('register/{lang}/{v}' , [ 'as' => 'register', 'uses' => 'AuthController@register'])->middleware('checkguest');
});

Route::get('/invalid/{lang}/{v}', [ 'as' => 'invalid', 'uses' => 'AuthController@invalid']);


// users apis group
Route::group([
    'middleware' => 'api',
    'prefix' => 'user'
], function($router) {
    Route::get('profile/{lang}/{v}' , 'UserController@getprofile');
    Route::put('profile/{lang}/{v}' , 'UserController@updateprofile');
    Route::put('resetpassword/{lang}/{v}' , 'UserController@resetpassword');
    Route::put('resetforgettenpassword/{lang}/{v}' , 'UserController@resetforgettenpassword')->middleware('checkguest');
    Route::post('checkphoneexistance/{lang}/{v}' , 'UserController@checkphoneexistance')->middleware('checkguest');
    Route::post('checkphoneexistanceandroid/{lang}/{v}' , 'UserController@checkphoneexistanceandroid')->middleware('checkguest');
    Route::get('notifications/{lang}/{v}' , 'UserController@notifications');
    Route::get('adscount/{lang}/{v}' , 'UserController@getadscount');
    Route::get('current_ads/{lang}/{v}' , 'UserController@getcurrentads');
    Route::get('expired_ads/{lang}/{v}' , 'UserController@getexpiredads');
    Route::post('renew_ad/{lang}/{v}' , 'UserController@renewad');
    Route::delete('delete_ad/{lang}/{v}' , 'UserController@deletead');
    Route::put('edit_ad/{lang}/{v}' , 'UserController@editad');
    Route::delete('delete_ad_image/{lang}/{v}' , 'UserController@delteadimage');
    Route::get('ad_details/{id}/{lang}/{v}' , 'UserController@getaddetails');
});

Route::get('ad_owner_profile/{id}/{lang}/{v}' , 'UserController@getownerprofile')->middleware('checkguest');

Route::get('products/{lang}/{v}' , 'ProductController@getproducts')->middleware('checkguest');
Route::get('products/search/{lang}/{v}' , 'ProductController@getsearch')->middleware('checkguest');

//  plans apis
Route::group([
    'middleware' => 'api',
    'prefix' => 'plans'
],function($router){
    Route::get('pricing/{lang}/{v}' , 'PlanController@getpricing')->middleware('checkguest');
    Route::post('buy/{lang}/{v}' , 'PlanController@buyplan');
});

Route::get('/excute_pay' , 'PlanController@excute_pay');
Route::get('/pay/success' , 'PlanController@pay_sucess');
Route::get('/pay/error' , 'PlanController@pay_error');


Route::group([
    'middleware' => 'api',
    'prefix' => 'products'
] , function($router){
    Route::post('create/{lang}/{v}' , 'ProductController@create');
    Route::post('uploadimages/{lang}/{v}' , 'ProductController@uploadimages');
    Route::get('details/{id}/{lang}/{v}' , 'ProductController@getdetails')->middleware('checkguest');
});

// offers
Route::get('/offers/{lang}/{v}' , 'ProductController@getoffers')->middleware('checkguest');

// feature offers
Route::get('/feature-offers/{lang}/{v}' , 'ProductController@getFeatureOffers')->middleware('checkguest');

Route::group([
    'middleware' => 'api',
    'prefix' => 'categories'
], function($router){
    Route::get('/{lang}/{v}' , 'CategoryController@getcategories')->middleware('checkguest');
});

// sub category level 1
Route::get('/sub_categories/level1/{category_id}/{lang}/{v}' , 'CategoryController@getAdSubCategories')->middleware('checkguest');
// sub category level 2
Route::get('/sub_categories/level2/{sub_category_id}/{lang}/{v}' , 'CategoryController@get_sub_categories_level2')->middleware('checkguest');
// sub category level 3
Route::get('/sub_categories/level3/{sub_category_id}/{lang}/{v}' , 'CategoryController@get_sub_categories_level3')->middleware('checkguest');
// sub category level 4
Route::get('/sub_categories/level4/{sub_category_id}/{lang}/{v}' , 'CategoryController@get_sub_categories_level4')->middleware('checkguest');
// sub category level 5
Route::get('/sub_categories/level5/{sub_category_id}/{lang}/{v}' , 'CategoryController@get_sub_categories_level5')->middleware('checkguest');
// products last level
Route::get('/products/last-level/{sub_category_id}/{lang}/{v}' , 'CategoryController@getproducts')->middleware('checkguest');

// get home data
Route::get('/home/{lang}/{v}' , 'HomeController@gethome')->middleware('checkguest');

// get home data
Route::get('/home-ads/{lang}/{v}' , 'HomeController@getHomeAds')->middleware('checkguest');

// send contact us message
Route::post('/contactus/{lang}/{v}' , 'ContactUsController@SendMessage')->middleware('checkguest');

// get app number
Route::get('/getappnumber/{lang}/{v}' , 'SettingController@getappnumber')->middleware('checkguest');

// get whats app number
Route::get('/getwhatsappnumber/{lang}/{v}' , 'SettingController@getwhatsapp')->middleware('checkguest');

Route::get('/showbuybutton/{lang}/{v}' , 'SettingController@showbuybutton')->middleware('checkguest');


//nasser code
//for get cat toads/search create ad
Route::get('/ad/sub_categories/level0/{lang}/{v}' , 'CategoryController@show_first_cat');
Route::get('/ad/sub_categories/level1/{cat_id}/{lang}/{v}' , 'CategoryController@show_second_cat');

Route::get('/ad/sub_categories/level2/{sub_category_id}/{lang}/{v}' , 'CategoryController@show_third_cat');

Route::get('/ad/sub_categories/level3/{sub_category_id}/{lang}/{v}' , 'CategoryController@show_four_cat');
Route::get('/ad/sub_categories/level4/{sub_category_id}/{lang}/{v}' , 'CategoryController@show_five_cat');
Route::get('/ad/sub_categories/level5/{sub_category_id}/{lang}/{v}' , 'CategoryController@show_six_cat');

//search ads
//    Route::post('/ads/search/{lang}/{v}' , 'ProductController@getsearch');
Route::post('/ads/range_price_search/{lang}/{v}' , 'ProductController@range_price_search')->middleware('checkguest');

//delete my ad
Route::get('/ad/delete/{id}/{lang}/{v}' , 'ProductController@delete_my_ad');

//to edit ad
Route::get('/ad/select_ad_data/{id}/{lang}/{v}' , 'ProductController@select_ad_data');
// get first step data
Route::get('/ad/first-step-data/{id}/{lang}/{v}' , 'ProductController@getFirstStepData');

Route::get('/ad/remove_main_image/{id}/{lang}/{v}' , 'ProductController@remove_main_image');
Route::get('/ad/remove_product_image/{image_id}/{lang}/{v}' , 'ProductController@remove_product_image');
Route::post('/ad/update/{id}/{lang}/{v}' , 'ProductController@update_ad');
Route::post('/ad/update_image/{lang}/{v}' , 'ProductController@update_image');


//marka select
Route::get('/ad/get_marka/{lang}/{v}' , 'MarkaController@get_marka');
Route::get('/ad/get_marka_types/{marka_id}/{lang}/{v}' , 'MarkaController@get_marka_types');
Route::get('/ad/get_type_model/{marka_type_id}/{lang}/{v}' , 'MarkaController@get_type_model');
Route::get('/ad/category_options/{category}/{lang}/{v}' , 'CategoryController@getCategoryOptions');

//store ad with steps
Route::post('/ad/save_first_step/{lang}/{v}' , 'ProductController@save_first_step');
Route::post('/ad/save_second_step/{lang}/{v}' , 'ProductController@save_second_step');
Route::get('/ad/get-images/{lang}/{v}' , 'ProductController@getAdImages');

Route::get('/ad/select_all_plans/{cat_id}/{lang}/{v}' , 'PlanController@select_all_plans');
Route::get('/ad/save_third_step/{ad_id}/{plan_id}/{lang}/{v}' , 'ProductController@save_third_step');

Route::get('/ad/save_third_step_with_money/{ad_id}/{plan_id}/{lang}/{v}' , 'ProductController@save_third_step_with_money2');
Route::get('/ad/save_third_step/excute_pay' , 'ProductController@third_step_excute_pay');
Route::post('/ad/save_third_step/excute_pay2' , 'ProductController@third_step_excute_pay2');
Route::get('/ad/save_third_step/excute_response' , 'ProductController@execute_response')->name('addAdExecuteResponse');


Route::get('/ad/select_ended_ads/{lang}/{v}' , 'ProductController@select_ended_ads');
Route::get('/ad/select_current_ads/{lang}/{v}' , 'ProductController@select_current_ads');
Route::get('/ad/select_all_ads/{lang}/{v}' , 'ProductController@select_all_ads');
Route::get('/select_all_mndobeen/{lang}/{v}' , 'MndobController@index');

Route::get('/ad/get_special_ads/{lang}/{v}' , 'ProductController@get_special_ads');
//notifications
Route::get('/sellect_notofications/{lang}/{v}' , 'UserController@notifications');

//favorite
Route::get('/favorites/{lang}/{v}' , 'FavoriteController@getfavorites');
Route::post('/favorite/create/{lang}/{v}' , 'FavoriteController@addtofavorites');
Route::post('/favorite/destroy/{lang}/{v}' , 'FavoriteController@removefromfavorites');

//terms and condition
Route::get('/terms/{lang}/{v}' , 'SettingController@terms');
Route::get('/about_app/{lang}/{v}' , 'SettingController@about_app');
Route::get('/app_address/{lang}/{v}' , 'SettingController@app_address');

//auth routes
Route::get('/my_account/{lang}/{v}' , 'UserController@my_account');
Route::get('/my_balance/{lang}/{v}' , 'UserController@my_balance');

Route::group([
    'middleware' => 'api'
],function($router) {
    Route::post('/addbalance/{lang}/{v}', 'UserController@addbalance2');
    Route::post('/addbalance2/{lang}/{v}', 'UserController@addbalance2');
});
Route::get('/wallet/excute_pay' , 'UserController@excute_pay');
Route::post('/package-pay/excute_pay' , 'UserController@excute_pay2');
Route::get('/package-pay/excute_response' , 'UserController@execute_response')->name('executeResponse');
Route::get('/pay/error' , 'UserController@pay_error');
Route::get('/pay/success' , 'UserController@pay_sucess');

Route::get('/check_ad/{lang}/{v}' , 'HomeController@check_ad')->middleware('checkguest');
Route::get('/main_ad/{lang}/{v}' , 'HomeController@main_ad')->middleware('checkguest');

//balance package
Route::get('/balance_packages/{lang}/{v}' , 'HomeController@balance_packages');

//visitor
Route::post('/visitor/create/{lang}/{v}' , 'VisitorController@create')->middleware('checkguest');

//re new my ad   (re publish)
Route::get('ad/republish_ad/{product_id}/{plan_id}/{lang}/{v}' , 'ProductController@republish_ad');



