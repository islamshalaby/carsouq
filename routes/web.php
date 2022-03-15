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

Route::get('/setlocale/{locale}',function($lang){
    Session::put('locale',$lang);
    return redirect()->back();
});

// Dashboard Routes
Route::group(['middleware'=>'language','prefix' => "admin-panel",'namespace' => "Admin"] , function($router){

    Route::get('' ,'HomeController@show');
    Route::get('login' ,  [ 'as' => 'adminlogin', 'uses' => 'AdminController@getlogin']);
    Route::post('login' , 'AdminController@postlogin');
    Route::get('logout' , 'AdminController@logout');
    Route::get('profile' , 'AdminController@profile');
    Route::post('profile' , 'AdminController@updateprofile');
    Route::get('databasebackup' , 'AdminController@backup');

    // Users routes for dashboard

    Route::group([ 'prefix' => 'users',] , function($router){
        Route::get('add' , 'UserController@AddGet');
        Route::post('add' , 'UserController@AddPost');
        Route::get('show' , 'UserController@show');
        Route::get('edit/{id}' , 'UserController@edit');
        Route::post('edit/{id}' , 'UserController@EditPost');
        Route::get('details/{id}' , 'UserController@details')->name("users.details");
        Route::post('/send_balance' , 'UserController@send_balance')->name("users.send_balance");
        Route::post('/send_group_balance' , 'UserController@send_group_balance')->name("users_group.send_group_balance");
        Route::post('send_notifications/{id}' , 'UserController@SendNotifications');
        Route::post('add_credit/{id}' , 'UserController@addcredit');
        Route::get('block/{id}' , 'UserController@block');
        Route::get('active/{id}' , 'UserController@active');
        Route::get('products/{user}' , 'UserController@get_user_products')->name('user.products');
        Route::get('ended_products/{user}' , 'UserController@get_user_ended_products')->name('user.ended_products');
     });

    // admins routes for dashboard
    Route::group(['prefix' => "managers",], function($router){
        Route::get('add' , 'ManagerController@AddGet');
        Route::post('add' , 'ManagerController@AddPost');
        Route::get('show' , 'ManagerController@show');
        Route::get('edit/{id}' , 'ManagerController@edit');
        Route::post('edit/{id}' , 'ManagerController@EditPost');
        Route::get('delete/{id}' , 'ManagerController@delete');
    });

    // App Pages For Dashboard
    Route::group(['prefix' => 'app_pages' ] , function($router){
        Route::get('aboutapp' , 'AppPagesController@GetAboutApp');
        Route::post('aboutapp' , 'AppPagesController@PostAboutApp');
        Route::get('termsandconditions' , 'AppPagesController@GetTermsAndConditions');
        Route::post('termsandconditions' , 'AppPagesController@PostTermsAndConditions')->name('save.terms');
    });

    // Setting Route
    Route::get('settings' , 'SettingController@GetSetting');
    Route::post('settings' , 'SettingController@PostSetting')->name('save.settings');

    // Rates
    Route::get('rates' , 'RateController@Getrates');
    Route::get('rates/active/{id}' , 'RateController@activeRate');

    // meta tags Route
    Route::get('meta_tags' , 'MetaTagController@getMetaTags');
    Route::post('meta_tags' , 'MetaTagController@postMetaTags');

    // Ads Route
    Route::group(["prefix" => "ads"],function($router){
        Route::get('add' , 'Ads\AdController@AddGet');
        Route::post('add' , 'Ads\AdController@AddPost');
        Route::get('show' , 'Ads\AdController@show')->name('ads.index');
        Route::get('edit/{id}' , 'Ads\AdController@EditGet');
        Route::post('edit/{id}' , 'Ads\AdController@EditPost');
        Route::get('details/{id}' , 'Ads\AdController@details');
        Route::get('delete/{id}' , 'Ads\AdController@delete');
        Route::get('fetchproducts/{userId}' , 'Ads\AdController@fetch_products')->name("products.fetch");
    });
    Route::resource('payments' , 'PaymentsController');
    Route::post('/payments/fetch_by_package' , 'PaymentsController@fetch_by_package')->name('payment.fetch_by_package');

    Route::resource('balance_packages' , 'BalanceBackagesController');
    Route::post('/balance_p/update/{id}' , 'BalanceBackagesController@update')->name('balance_p.update');
    Route::get('/balance_packages/delete/{id}' , 'BalanceBackagesController@destroy')->name('balance_packages.delete');

    //generating categories in create ad manual ..
    //categories
    Route::get('/get_sub_cat/{id}' , 'ProductController@get_sub_cat')->name('product.get_sub_cat');
    Route::get('/get_sub_two_cat/{id}' , 'ProductController@get_sub_two_cat')->name('product.get_sub_two_cat');
    Route::get('/get_sub_three_cat/{id}' , 'ProductController@get_sub_three_cat')->name('product.get_sub_three_cat');
    Route::get('/get_sub_four_cat/{id}' , 'ProductController@get_sub_four_cat')->name('product.get_sub_four_cat');
    Route::get('/get_sub_five_cat/{id}' , 'ProductController@get_sub_five_cat')->name('product.get_sub_five_cat');
    //options
    Route::get('/get_brands/{id}' , 'ProductController@get_brands')->name('product.get_brands');
    Route::get('/get_brand_types/{id}' , 'ProductController@get_brand_types')->name('product.get_brand_types');
    Route::get('/get_model_year/{id}' , 'ProductController@get_model_year')->name('product.get_model_year');
    Route::get('/get_counter/{id}' , 'ProductController@get_counter')->name('product.get_counter');
    //plan package
    Route::get('/get_plan/{id}' , 'ProductController@get_plan')->name('product.get_plan');

    Route::resource('main_ads' , 'Ads\MainAdsController');
    Route::get('main_ads/delete/{id}' , 'Ads\MainAdsController@destroy')->name('main_ads.delete');



    // Categories Route
    Route::group(["prefix" => "categories","namespace" => "categories"], function($router){
         Route::get('add' , 'CategoryController@AddGet');
         Route::post('add' , 'CategoryController@AddPost');
         Route::get('show' , 'CategoryController@show');
         Route::post('change/is_show', 'CategoryController@change_is_show')->name('category.change_is_show');
         Route::post('sub_cat/is_show', 'SubCategoryController@change_is_show')->name('sub_cat.change_is_show');
         Route::post('sub_two_cat/is_show', 'SubTwoCategoryController@change_is_show')->name('sub_two_cat.change_is_show');
         Route::post('sub_three_cat/is_show', 'SubThreeCategoryController@change_is_show')->name('sub_three_cat.change_is_show');
         Route::post('sub_four_cat/is_show', 'SubFourCategoryController@change_is_show')->name('sub_four_cat.change_is_show');
         Route::post('sub_five_cat/is_show', 'SubFiveCategoryController@change_is_show')->name('sub_five_cat.change_is_show');
         Route::get('edit/{id}' , 'CategoryController@EditGet');
         Route::post('edit/{id}' , 'CategoryController@EditPost');
         Route::get('delete/{id}' , 'CategoryController@delete');
         Route::get('products/{category}' , 'CategoryController@category_products')->name('category.products');
    });
    Route::post('categories/sort' , 'categories\CategoryController@sort')->name('category.sort');

    Route::resource('cat_options' , 'categories\CategoryOptionsController');
    Route::get('cat_options/deleted/{id}' , 'categories\CategoryOptionsController@destroy')->name('cat_options.deleted');
    Route::resource('options_values' , 'categories\OptionsValuesController');
    Route::get('options_values/create_new/{option_id}' , 'categories\OptionsValuesController@create')->name('options_values.create_new');
    Route::get('options_values/deleted/{id}' , 'categories\OptionsValuesController@destroy')->name('options_values.deleted');
    Route::post('options_values/update_new/{id}' , 'categories\OptionsValuesController@update')->name('options_values.update.new');
    // Sub Categories Route
    Route::group(["prefix" => "categories","namespace" => "categories"], function($router){
        Route::resource('sub_cat', 'SubCategoryController');
        Route::get('sub_cat/create/{id}' , 'SubCategoryController@create')->name("sub_cat.create.new");
        Route::post('sub_cat/update/{id}' , 'SubCategoryController@update')->name("sub_cat.update.new");
        Route::get('sub_cat/delete/{id}' , 'SubCategoryController@destroy')->name("sub_cat.delete");
    });
    Route::post('sub_cat/sort' , 'categories\SubCategoryController@sort')->name('sub_cat.sort');

    // Sub two Categories Route
    Route::group(["prefix" => "categories","namespace" => "categories"], function($router){
        Route::resource('sub_two_cat', 'SubTwoCategoryController');
        Route::get('sub_two_cat/create/{id}' , 'SubTwoCategoryController@create')->name("sub_two_cat.create.new");
        Route::post('sub_two_cat/update/{id}' , 'SubTwoCategoryController@update')->name("sub_two_cat.update.new");
        Route::get('sub_two_cat/delete/{id}' , 'SubTwoCategoryController@destroy')->name("sub_two_cat.delete");
    });
    Route::post('sub_two_cat/sort' , 'categories\SubTwoCategoryController@sort')->name('sub_two_cat.sort');

    // Sub three Categories Route
    Route::group(["prefix" => "categories","namespace" => "categories"], function($router){
        Route::resource('sub_three_cat', 'SubThreeCategoryController');
        Route::get('sub_three_cat/create/{id}' , 'SubThreeCategoryController@create')->name("sub_three_cat.create.new");
        Route::post('sub_three_cat/update/{id}' , 'SubThreeCategoryController@update')->name("sub_three_cat.update.new");
        Route::get('sub_three_cat/delete/{id}' , 'SubThreeCategoryController@destroy')->name("sub_three_cat.delete");
    });
    Route::post('sub_three_cat/sort' , 'categories\SubThreeCategoryController@sort')->name('sub_three_cat.sort');

    // Sub four Categories Route
    Route::group(["prefix" => "categories","namespace" => "categories"], function($router){
        Route::resource('sub_four_cat', 'SubFourCategoryController');
        Route::get('sub_four_cat/create/{id}' , 'SubFourCategoryController@create')->name("sub_four_cat.create.new");
        Route::post('sub_four_cat/update/{id}' , 'SubFourCategoryController@update')->name("sub_four_cat.update.new");
        Route::get('sub_four_cat/delete/{id}' , 'SubFourCategoryController@destroy')->name("sub_four_cat.delete");
    });
    Route::post('sub_four_cat/sort' , 'categories\SubFourCategoryController@sort')->name('sub_four_cat.sort');
    // Sub five Categories Route
    Route::group(["prefix" => "categories","namespace" => "categories"], function($router){
        Route::resource('sub_five_cat', 'SubFiveCategoryController');
        Route::get('sub_five_cat/create/{id}' , 'SubFiveCategoryController@create')->name("sub_five_cat.create.new");
        Route::post('sub_five_cat/update/{id}' , 'SubFiveCategoryController@update')->name("sub_five_cat.update.new");
        Route::get('sub_five_cat/delete/{id}' , 'SubFiveCategoryController@destroy')->name("sub_five_cat.delete");
    });
    Route::post('sub_five_cat/sort' , 'categories\SubFiveCategoryController@sort')->name('sub_five_cat.sort');


    // Contact Us Messages Route
    Route::group(["prefix" => "contact_us"] , function($router){
        Route::get('' , 'ContactUsController@show');
        Route::get('details/{id}' , 'ContactUsController@details');
        Route::get('delete/{id}' , 'ContactUsController@delete');
    });

    // Notifications Route
    Route::group(["prefix" => "notifications"], function($router){
        Route::get('show' , 'NotificationController@show');
        Route::get('details/{id}' , 'NotificationController@details');
        Route::get('delete/{id}' , 'NotificationController@delete');
        Route::get('send' , 'NotificationController@getsend');
        Route::post('send' , 'NotificationController@send');
        Route::get('resend/{id}' , 'NotificationController@resend');
    });

    // Products Routes
    Route::group(["prefix" => "products"], function($router){
        Route::get('show', 'ProductController@show')->name("products.index");
        Route::get('add' , 'ProductController@AddGet');
        Route::post('add' , 'ProductController@AddPost')->name("products.store");
        Route::get('edit/{id}' , 'ProductController@edit')->name("products.edit");
        Route::post('edit/{id}' , 'ProductController@EditPost')->name("products.update");
        Route::get('delete/productimage/{id}' , 'ProductController@delete_product_image')->name("productImage.delete");
        Route::get('details/{product_id}' , 'ProductController@details')->name("products.details");
        Route::get('delete/{product}' , 'ProductController@delete')->name("delete.product");
    });

    // Plans Routes
    Route::resource('plans', 'PlanController');
    Route::get('show_div/{type}', 'PlanController@show_div');

    //Elmndobeen routes
    Route::resource('mndob', 'MndobController');
    Route::post('mndob/update/{id}', 'MndobController@update')->name('mndob.update.new');
    Route::get('mndob/delete/{id}' , 'MndobController@destroy')->name("mndob.delete");

    Route::resource('categories_ads', 'Ads\categories_ads\CategoriesAdsController');
    Route::group(["prefix" => "categories_ads","namespace" => "Ads\categories_ads"], function($router){
        Route::get('/delete/{id}', 'CategoriesAdsController@destroy')->name('categories_ads.delete');
        Route::get('/create_new/{id}', 'CategoriesAdsController@create')->name('categories_ads.create_new');
        Route::get('/create_all/new', 'CategoriesAdsController@create_all')->name('categories_ads.create_all');
        Route::post('/store_all_categories', 'CategoriesAdsController@store_all_categories')->name('categories_ads.store_all_categories');
    });

    Route::group(["prefix" => "sub_categories_ads","namespace" => "Ads\categories_ads"], function($router){
        Route::get('/index/{id}', 'SubCategoriesAdsController@index')->name('sub_categories_ads.index');
        Route::post('/store', 'SubCategoriesAdsController@store')->name('sub_categories_ads.store');
        Route::get('/{id}', 'SubCategoriesAdsController@show')->name('sub_categories_ads.show');
        Route::get('/create_new/{id}', 'SubCategoriesAdsController@create')->name('sub_categories_ads.create_new');
        Route::get('/create_all/new/{id}', 'SubCategoriesAdsController@create_all')->name('sub_categories_ads.create_all');
        Route::post('/store_all_categories/{id}', 'SubCategoriesAdsController@store_all_categories')->name('sub_categories_ads.store_all_categories');
    });

    Route::group(["prefix" => "sub_two_cat_ads","namespace" => "Ads\categories_ads"], function($router){
        Route::get('/index/{id}', 'SubTwoCategoriesAdsController@index')->name('sub_two_cat_ads.index');
        Route::get('/show/{id}', 'SubTwoCategoriesAdsController@show')->name('sub_two_cat_ads.show');
        Route::get('/create/{id}', 'SubTwoCategoriesAdsController@create')->name('sub_two_cat_ads.create');
        Route::post('/store', 'SubTwoCategoriesAdsController@store')->name('sub_two_cat_ads.store');
        Route::get('/create_all/new/{id}', 'SubTwoCategoriesAdsController@create_all')->name('sub_two_cat_ads.create_all');
        Route::post('/store_all_categories/{id}', 'SubTwoCategoriesAdsController@store_all_categories')->name('sub_two_cat_ads.store_all_categories');
    });

    Route::group(["prefix" => "sub_three_cat_ads","namespace" => "Ads\categories_ads"], function($router){
        Route::get('/index/{id}', 'SubThreeCategoriesAdsController@index')->name('sub_three_cat_ads.index');
        Route::get('/show/{id}', 'SubThreeCategoriesAdsController@show')->name('sub_three_cat_ads.show');
        Route::get('/create/{id}', 'SubThreeCategoriesAdsController@create')->name('sub_three_cat_ads.create');
        Route::post('/store', 'SubThreeCategoriesAdsController@store')->name('sub_three_cat_ads.store');
        Route::get('/create_all/new/{id}', 'SubThreeCategoriesAdsController@create_all')->name('sub_three_cat_ads.create_all');
        Route::post('/store_all_categories/{id}', 'SubThreeCategoriesAdsController@store_all_categories')->name('sub_three_cat_ads.store_all_categories');
    });

    Route::group(["prefix" => "sub_four_cat_ads","namespace" => "Ads\categories_ads"], function($router){
        Route::get('/index/{id}', 'SubFourCategoriesAdsController@index')->name('sub_four_cat_ads.index');
        Route::get('/show/{id}', 'SubFourCategoriesAdsController@show')->name('sub_four_cat_ads.show');
        Route::get('/create/{id}', 'SubFourCategoriesAdsController@create')->name('sub_four_cat_ads.create');
        Route::post('/store', 'SubFourCategoriesAdsController@store')->name('sub_four_cat_ads.store');
        Route::get('/create_all/new/{id}', 'SubFourCategoriesAdsController@create_all')->name('sub_four_cat_ads.create_all');
        Route::post('/store_all_categories/{id}', 'SubFourCategoriesAdsController@store_all_categories')->name('sub_four_cat_ads.store_all_categories');
    });
    Route::group(["prefix" => "sub_five_cat_ads","namespace" => "Ads\categories_ads"], function($router){
        Route::get('/index/{id}', 'SubFiveCategoriesAdsController@index')->name('sub_five_cat_ads.index');
        Route::get('/show/{id}', 'SubFiveCategoriesAdsController@show')->name('sub_five_cat_ads.show');
        Route::get('/create/{id}', 'SubFiveCategoriesAdsController@create')->name('sub_five_cat_ads.create');
        Route::post('/store', 'SubFiveCategoriesAdsController@store')->name('sub_five_cat_ads.store');
        Route::get('/create_all/new/{id}', 'SubFiveCategoriesAdsController@create_all')->name('sub_five_cat_ads.create_all');
        Route::post('/store_all_categories/{id}', 'SubFiveCategoriesAdsController@store_all_categories')->name('sub_five_cat_ads.store_all_categories');
    });

    Route::group(["prefix" => "plans"], function($router){
        Route::post('update/{id}', 'PlanController@update')->name('plans.update.new');
        Route::get('delete/{id}' , 'PlanController@destroy')->name("delete.plan");
        Route::post('showed', 'PlanController@update_plan_status')->name('plans.showed');

        Route::get('details/{plan_id}' , 'PlanController@plans_details')->name("plans.details");
        Route::post('details/showed', 'PlanController@update_status')->name('plans.details.showed');
        Route::get('create_details/{plan_id}' , 'PlanController@create_details')->name("plans.details.create");
        Route::post('create_details' , 'PlanController@store_details')->name("plans.details.store");
        Route::get('details/edit/{detail_id}' , 'PlanController@edit_details')->name("plans.details.edit");
        Route::get('details/update/{detail_id}' , 'PlanController@update_details')->name("plans.details.update");
        Route::get('details/delete/{detail_id}' , 'PlanController@delete_details')->name("plans.details.delete");
    });

    Route::resource('brands', 'BrandController');
    Route::get('brands/update/{id}' , 'BrandController@update')->name("brands.update.new");
    Route::get('brands/delete/{id}' , 'BrandController@destroy')->name("delete.brands");

    Route::resource('brand_types', 'BrandTypesController');
    Route::get('brand_types/create/{id}' , 'BrandTypesController@create')->name("brand_types.create.new");
    Route::get('brand_types/update/{id}' , 'BrandTypesController@update')->name("brand_types.update.new");
    Route::get('brand_types/delete/{id}' , 'BrandTypesController@destroy')->name("brand_types.delete");

    Route::resource('models', 'BrandTypeModelsController');
    Route::get('models/create/{id}' , 'BrandTypeModelsController@create')->name("models.create.new");
    Route::get('model/update/{id}' , 'BrandTypeModelsController@update')->name("model.update.new");
    Route::get('models/delete/{id}' , 'BrandTypeModelsController@destroy')->name("models.delete");

});




// Web View Routes
Route::group([
    'prefix' => "webview"
] , function($router){
    Route::get('aboutapp/{lang}' , 'WebViewController@getabout');
    Route::get('termsandconditions/{lang}' , 'WebViewController@gettermsandconditions' );
});


Route::get('/' , function() {
    return view('index');
});