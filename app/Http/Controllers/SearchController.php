<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Article;

class SearchController extends Controller
{
    //
    public function search()
    {
        $user = auth()->user();
        // 'search'はinputタグのname属性
        $search_text = $_GET['search'];
        $searched_users = User::where('name', 'LIKE', '%' . $search_text . '%')->get();
        $searched_articles = Article::where('body', 'LIKE', '%' . $search_text . '%')
        ->orwhere('title', 'LIKE', '%' . $search_text . '%')->get();

        return view('search.index', compact(
          'searched_users',
          'searched_articles',
          'user'
        ));
    }
}
