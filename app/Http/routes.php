<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

Route::auth();

// 控制台
Route::get('/', 'Admin\HomeController@index');

Route::group(['prefix' => 'admin', 'middleware' => 'auth', 'namespace' => 'Admin'], function () {
    // 公众号管理
    Route::group(['prefix' => 'wechat'], function () {
        Route::resource('info', 'WechatInfoController', ['except' => ['create', 'edit']]);
        Route::resource('menu', 'WechatMenuController');
        Route::post('pushMenu', 'WechatMenuController@pushMenu');
        Route::resource('follow', 'WechatFollowController', ['except' => ['create']]);
        Route::get('refresh', 'WechatFollowController@refresh');
    });
    // 店铺管理
    Route::group(['prefix' => 'shop'], function () {
        Route::resource('config', 'ShopConfigController', ['except' => ['create', 'edit','show','destroy']]);
        Route::resource('banner', 'ShopBannerController');
    });
    // 商品管理
    Route::group(['prefix'=>'product'],function(){
        Route::resource('topic','ProductTopicController');
        Route::resource('plate','ProductPlateController');
        Route::resource('category','ProductCategoryController');
        Route::resource('commodity','ProductCommodityController');
        // Ajax Get Tree & Table Data
        Route::get('getTreeData','ProductCategoryController@treeData');
        Route::get('getTableData','ProductCommodityController@tableData');
        // 富文本编辑器上传图片
        Route::post('editorUpload','ProductCommodityController@editorUpload');
    });
});

// DEBUG
Route::get('/wechat/debug', 'WechatController@debug');

// Wechat http main route
Route::any('/wechat', 'WechatController@serve');

// 微信商城
Route::group(['prefix' => 'mall', 'middleware' => ['web', 'wechat.oauth'], 'namespace' => 'Mall'], function () {
    // Wechat OAuth2.0 (type=snsapi_userinfo)
    Route::get('/user', 'IndexController@oauth');
    // 首页
    Route::get('/', 'IndexController@index');
});

Route::group(['prefix' => 'api', 'middleware' => 'web', 'namespace' => 'Api'], function () {
    Route::get('userinfo', 'UserController@userinfo');
    Route::get('banners', 'ShopController@getBanners');
    Route::get('topics','ShopController@getTopics');
    Route::get('plates','ShopController@getPlates');
    Route::get('categories','ShopController@getCategories');
    Route::post('commodities/topic','ShopController@getCommodityByTopic');
    Route::post('commodities/plate','ShopController@getCommodityByPlate');
    Route::post('commodities/category','ShopController@getCommodityByCategory');
    Route::get('commodity/{commodity}','ShopController@getCommodity');
    // 意见建议
    Route::post('suggestion','UserController@suggestion');
    // 地址管理
    Route::get('address','UserController@indexAddress');
    Route::post('address','UserController@storeAddress');
    Route::get('address/{address}','UserController@showAddress');
    Route::put('address/{address}','UserController@updateAddress');
    Route::delete('address/{address}','UserController@deleteAddress');
});