<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::resource('/brand','BrandController')->middleware('brand');

Route::resource('/category','CategoryController')->middleware('category');

Route::resource('/color','ColorController')->middleware('color');

Route::resource('/size','SizeController')->middleware('size');

Route::resource('/website','WebsiteController')->middleware('website');

Route::resource('/ads','AdsController')->middleware('ads');

Route::resource('/ads_type','AdsTypeController')->middleware('ads_type');

Route::resource('/announcement','AnnouncementController')->middleware('announcement');

Route::resource('/income','IncomeController')->middleware('income');

Route::resource('/order','OrderController')->middleware('order');

Route::resource('/outcome','OutcomeController')->middleware('outcome');

Route::resource('/product','ProductController')->middleware('product');
