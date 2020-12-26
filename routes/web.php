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

// {provider}の部分は、利用する他サービスの名前を入れることを想定している
Route::prefix('login')->name('login.')->group(function () {
    Route::get('/{provider}', 'Auth\LoginController@redirectToProvider')->name('{provider}');
    Route::get('/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('{provider}.callback');
});

Route::prefix('register')->name('register.')->group(function () {
    Route::get('/{provider}', 'Auth\RegisterController@showProviderUserRegistrationForm')->name('{provider}');
    Route::post('/{provider}', 'Auth\RegisterController@registerProviderUser')->name('{provider}');
});

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
// putはデータの更新に使用するメソッド
Route::prefix('articles')->name('articles.')->group(function () {
    Route::put('/{article}/like', 'ArticleController@like')->name('like')->middleware('auth');
    Route::delete('/{article}/like', 'ArticleController@unlike')->name('unlike')->middleware('auth');
});

// タグ別記事一覧画面のURLは/tags/PHPといった形式にするので、getメソッドの第一引数は'/tags/{name}としておく
// タグの一覧画面ではなく、単一の詳細ページなのでshowを選択した
Route::get('/tags/{name}', 'TagController@show')->name('tags.show');

Route::prefix('users')->name('users.')->group(function () {
    Route::get('/{name}', 'UserController@show')->name('show');

    Route::get('/{name}/likes', 'UserController@likes')->name('likes');

    Route::get('/{name}/followings', 'UserController@followings')->name('followings');
    Route::get('/{name}/followers', 'UserController@followers')->name('followers');
    // グループメソッドを使用する事で、middlewareが各ルーティングに適用される
    Route::middleware('auth')->group(function () {
        Route::put('/{name}/follow', 'UserController@follow')->name('follow');
        Route::delete('/{name}/follow', 'UserController@unfollow')->name('unfollow');
    });
});