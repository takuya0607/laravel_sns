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
// except = 指定のアクションを除く事ができる
// 今回articleのindexが重複するため、'/'を優先した
// middlewareはクライアントからのリクエストに対して、コントローラーで処理する前に処理を行う作業。
// 'auth'をつける事で、ユーザーがログイン済みかをチェックする。
Route::resource('/articles', 'ArticleController')->except(['index','show'])->middleware('auth');
// onlyを付ける事で、そのアクションのみを指定できる
Route::resource('/articles', 'ArticleController')->only(['show']);

// prefixメソッドは、引数として渡した文字列をURIの先頭に付ける
// groupメソッドを使用する事で、prefixとnameの内容を適用させる事ができる
// これでURLがarticles/{article}/like、名前がarticles.likeへと変更され簡潔なコードへ
Route::prefix('articles')->name('articles.')->group(function () {
    Route::put('/{article}/like', 'ArticleController@like')->name('like')->middleware('auth');
    Route::delete('/{article}/like', 'ArticleController@unlike')->name('unlike')->middleware('auth');
});