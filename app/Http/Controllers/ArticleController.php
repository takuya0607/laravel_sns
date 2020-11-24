<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    //

    public function index()
    {
      $articles = Article::all()->sortByDesc('created_at');

      // 第二引数の'articles'は任意での自作キー
      // キーに対してのvalueを$articlesで指定している
      // これによりbladeで'articles'が使用できる。
      return view('articles.index', ['articles' => $articles]);
    }
}
