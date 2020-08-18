<?php

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::post('/register', 'API\AuthController@register');
Route::post('/login', 'API\AuthController@login');

Route::middleware('auth:api')->group(function () {
    // Роуты для Item
    Route::post('/item', 'API\TodoItemController@store')->name('item.store');
    Route::get('/item', 'API\TodoItemController@index')->name('item.index');
    Route::delete('/item/{item}', 'API\TodoItemController@destroy')->name('item.destroy');
    Route::put('/item/{item}', 'API\TodoItemController@update')->name('item.update');
    Route::get('/item/{item}', 'API\TodoItemController@show')->name('item.show');

    // Роуты для List
    Route::get('/list', 'API\TodoListController@index')->name('list.index');
    Route::post('/list', 'API\TodoListController@store')->name('list.store');
    Route::get('/list/{list}', 'API\TodoListController@show')->name('list.show');
    Route::put('/list/{list}', 'API\TodoListController@update')->name('list.update');
    Route::delete('/list/{list}', 'API\TodoListController@destroy')->name('list.destroy');

    // Роуты для List
    Route::get('/{list}', 'API\ListOfListsController@show')->name('lists.show');
    Route::get('/', 'API\ListOfListsController@index')->name('lists.index');
    Route::post('/', 'API\ListOfListsController@store')->name('lists.store');
    Route::put('/{list}', 'API\ListOfListsController@update')->name('lists.update');
    Route::delete('/{list}', 'API\ListOfListsController@destroy')->name('lists.destroy');
});

