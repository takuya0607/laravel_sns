<?php

namespace App\Http\Controllers;


use App\Tag;

use Illuminate\Http\Request;

class TagController extends Controller
{

  // この$nameには、ルーティングに定義したURL/tags/{name}の{name}の部分に入った文字列が渡ってくる
    public function show(string $name)
    {
      // tagテーブルのnameカラムはユニークで設定されているので、重複はしない
      // firstメソッドを使用して、最初のタグモデルを1件取得している
        $tag = Tag::where('name', $name)->first();

        return view('tags.show', ['tag' => $tag]);
    }

}
