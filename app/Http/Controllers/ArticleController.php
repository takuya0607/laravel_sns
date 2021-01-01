<?php

namespace App\Http\Controllers;

use App\Article;
use App\Tag;
use App\Comment;
// Requestの使用宣言
use App\Http\Requests\ArticleRequest;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct()
    {
      // authorizeResourceメソッドを使用すると,ポリシーとアクションメソッドの関係性が成り立つ
      // 第一引数にモデルのクラス名、第二引数にモデルのIDがセットされるルーティングのパラメーター名を指定
        $this->authorizeResource(Article::class, 'article');
    }

    // 記事一覧画面の表示
    public function index(Article $article, Comment $comment)
    {
      // loadメソッドに引数としてリレーション名を渡すと、リレーション先のテーブルからもデータを取得する
      $articles = Article::all()->sortByDesc('created_at')->load(['user', 'likes', 'tags']);

      // 第二引数の'articles'は任意での自作キー
      // キーに対してのvalueを$articlesで指定している
      // これによりbladeで'articles'が使用できる。
      return view('articles.index', [
        'articles' => $articles,
        ]);
    }

    // 記事投稿作成画面の表示
    public function create()
    {
        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return view('articles.create', [
            'allTagNames' => $allTagNames,
        ]);
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

        // タグに関する記述
        // eachで要素を一つずつ取得している
        // クロージャーの第一引数にはコレクションの値が、第二引数にはコレクションのキーが入る
        // ここでは値を任意で$tagNameとし、キーは不要なので省略している
        // use ($article)とあるのは、クロージャの中の処理で変数$articleを使うため
        // 通常クロージャの中では、クロージャの外側で定義されている変数を通常使用できないため
        $request->tags->each(function ($tagName) use ($article) {
        // firstOrCreateメソッドは、引数として渡した「カラム名と値のペア」を持つレコードがテーブルに存在するかどうかを探す
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            // 記事とタグの紐付け
            $article->tags()->attach($tag);
        });
        return redirect()->route('articles.index');
    }

    public function show(Article $article, Comment $comment)
    {
        $user = auth()->user();
        $article = $article->getArticle($article->id);
        $comments = $comment->getComments($article->id);
        return view('articles.show', [
          'user'     => $user,
          'article' => $article,
          'comments' => $comments
          ]);
    }

    public function edit(Article $article)
    {
        $tagNames = $article->tags->map(function ($tag) {
            return ['text' => $tag->name];
        });

        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });
      // storeアクションで使用した$articleを、viewで'article'として使用可能にする
        return view('articles.edit', [
            'article' => $article,
            'tagNames' => $tagNames,
            'allTagNames' => $allTagNames,
        ]);
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all())->save();

        // detachメソッドを引数無しで使うと、そのリレーションを紐付ける中間テーブルのレコードが全削除される
        // 一旦全削除する事で、複数のタグから一つのみ削除して更新する場合にも対応できる
        $article->tags()->detach();
        $request->tags->each(function ($tagName) use ($article) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $article->tags()->attach($tag);
        });

        return redirect()->route('articles.index');
    }


    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index');
    }



    // いいねに関する記述
    public function like(Request $request, Article $article)
    {
      // この記事モデルと、リクエストを送信したユーザーの両者を紐づけるlikesテーブルのレコードが新規登録される
      // detachは削除を表すメソッドで、二重のいいねを防止するために記述している
      // 一旦削除をする事で、過去にいいねをしていても削除→いいねとなるので重複を避けられる
        $article->likes()->detach($request->user()->id);
        $article->likes()->attach($request->user()->id);

        return [
          // どの記事へのいいね、あるいはいいね解除が成功したかがわかるよう、記事モデルのidをレスポンスするようにしている
            'id' => $article->id,
          // likesテーブル更新後の、その記事のいいね数もレスポンスしている
            'countLikes' => $article->count_likes,
        ];
    }

    // いいねの解除に関する記述
    public function unlike(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }
}
