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

Auth::routes();
Route::get('/', 'ArticleController@index')->name('articles.index');
// except = 指定のアクションを覗く事ができる
// 今回articleのindexが重複するため、'/'を優先した
// middlewareはクライアントからのリクエストに対して、コントローラーで処理する前に処理を行う作業。
// 'auth'をつける事で、ユーザーがログイン済みかをチェックする。
Route::resource('/articles', 'ArticleController')->except(['index'])->middleware('auth');
