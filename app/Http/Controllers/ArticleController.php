<?php

namespace App\Http\Controllers;

use App\Article;
// Requestの使用宣言
use App\Http\Requests\ArticleRequest;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    //

    // 記事一覧画面の表示
    public function index()
    {
      $articles = Article::all()->sortByDesc('created_at');

      // 第二引数の'articles'は任意での自作キー
      // キーに対してのvalueを$articlesで指定している
      // これによりbladeで'articles'が使用できる。
      return view('articles.index', ['articles' => $articles]);
    }

    // 記事投稿作成画面の表示
    public function create()
    {
        return view('articles.create');
    }

    // 記事の保存に関する処理
    // 引数にクラスと変数を記述する事で、インスタンスが自動生成される
    // $article = new Article(); と同じ意味に
    public function store(ArticleRequest $request, Article $article)
    {
        // $article->~はDBへの記述で、$request->~は送られてきたデータ
        // $article->title = $request->title;
        // $article->body = $request->body;

        // allメソッドを使用する事で全件データを取得する
        // fillメソッドに上記の配列を渡すと、指定しておいたプロパティのみが代入される
        // これでクライアントからの不正リクエストをブロックできる点と、冗長なコードを回避する
        $article->fill($request->all());
        // ログイン済みのユーザーが送信したリクエストであれば、userメソッドを使うことでUserクラスのインスタンスにアクセスできる
        $article->user_id = $request->user()->id;
        // 最後にモデルのsaveメソッドを使用し、articleテーブルにデータを保存する
        $article->save();
        // 投稿保存後、redirectでarticle.indexへ遷移させる
        return redirect()->route('articles.index');
    }
}
