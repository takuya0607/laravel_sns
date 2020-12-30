<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Intervention\Image\Facades\Image;
use App\Services\CheckExtensionServices; //追加
use App\Services\FileUploadServices; //追加

use App\Http\Requests\ProfileRequest;





class UserController extends Controller
{
    //
    public function show(string $name)
    {
      // 変数$userに、$nameと一致するnameを代入する
      // ユーザーテーブルのnameはユニークなので、必ず1件になる
        $user = User::where('name', $name)->first()
        // loadメソッドでは、このように.区切りを使って、リレーション先の、さらにリレーション先をEagerロードできる
        // 投稿者のユーザー、投稿にいいねしたユーザー、投稿につけられたタグ
        ->load(['articles.user', 'articles.likes', 'articles.tags']);

        // ユーザーの投稿した記事モデルをコレクションで取得
        // sortByDescメソッドを使って投稿日(created_at)の降順にソートした上で、変数$articlesに代入
        // articlesはUser.phpで記述しているメソッドを表す
        $articles = $user->articles->sortByDesc('created_at');
        return view('users.show', [
            'user' => $user,
            'articles' => $articles,
        ]);
    }

    public function edit(string $name)
    {
      $user = User::where('name', $name)->first();

      return view('users.edit', [
        'user' => $user,
      ]);
    }

    public function update(ProfileRequest $request, string $name)
    {
        // findOrFail = userテーブル内に、指定のidがあれば
        $user = User::where('name', $name)->first();

        // if emptyで$request->imageが空であるか確認する。
        // これで画像を差し替えていない場合、元の画像がそのまま選択される
        if (empty($request->img_name) == false) {
          if ($request ->file('img_name')->isValid([])) {
          $user->img_name = $request->img_name->getClientOriginalName();
          $fileName = $request->file('img_name')->getClientOriginalName();
          $imagePath = $request->img_name->storeAs('public/images/', $fileName);
          }
        }
        $user->name = $request->name;
        $user->save();

        return redirect()->route('articles.index');
    }
    public function likes(string $name)
    {
        // 変数$userに、$nameと一致するnameを代入する
        // ユーザーテーブルのnameはユニークなので、必ず1件になる
        $user = User::where('name', $name)->first()
        // 投稿したユーザー、投稿にいいねしたユーザー、投稿につけられたタグ
        ->load(['likes.user', 'likes.likes', 'likes.tags']);

        // ユーザーがいいねした記事モデルをコレクションで取得
        // likesはUser.phpで記述しているメソッドを表す
        $articles = $user->likes->sortByDesc('created_at');

        return view('users.likes', [
            'user' => $user,
            'articles' => $articles,
        ]);
    }

    public function followings(string $name)
    {
        // 変数$userに、$nameと一致するnameを代入する
        // ユーザーテーブルのnameはユニークなので、必ず1件になる
        $user = User::where('name', $name)->first()
        ->load('followings.followers');

        // ユーザーがフォローしたユーザーモデルをコレクションで取得
        // ユーザーモデルの中から、フォローしているユーザーモデルを取得するイメージ
        // followingsはUser.phpで記述しているメソッドを表す
        $followings = $user->followings->sortByDesc('created_at');

        return view('users.followings', [
            'user' => $user,
            'followings' => $followings,
        ]);
    }

    public function followers(string $name)
    {
        $user = User::where('name', $name)->first()
        ->load('followers.followers');

        $followers = $user->followers->sortByDesc('created_at');

        return view('users.followers', [
            'user' => $user,
            'followers' => $followers,
        ]);
    }

    // $nameはURLの{name}部分
    public function follow(Request $request, string $name)
    {
        // $user変数に、ユーザーモデルのnameカラムで一致する$nameを代入する
        // 'name'はユニークなので、必ず1件になる
        $user = User::where('name', $name)->first();

        // $user->idでユーザーのidを指定し、リクエストされてきたidと一致しているか確認する
        // これでフォローされる側とする側のidが一致しなければ以降の処理を実行する
        if ($user->id === $request->user()->id)
        {
          // abort関数は、第一引数にステータスコードを渡す。
          // ステータスコード404は、ユーザーからのリクエストが誤っている場合などに使われるエラー
          // 第二引数にはクライアントにレスポンスするテキストを渡すことが可能
            return abort('404', 'Cannot follow yourself.');
        }

        // 必ず削除(detach)してから新規登録(attach)する。
        // 1人のユーザーがあるユーザーを複数回重ねてフォローできないようにするための考慮
        $request->user()->followings()->detach($user);
        $request->user()->followings()->attach($user);

        // どのユーザーへのフォローが成功したかがわかるよう、ユーザーの名前を返している
        return ['name' => $name];
    }

    // followとほとんど同じ記述内容
    public function unfollow(Request $request, string $name)
    {
        $user = User::where('name', $name)->first();

        if ($user->id === $request->user()->id)
        {
            return abort('404', 'Cannot follow yourself.');
        }

        $request->user()->followings()->detach($user);

        return ['name' => $name];
    }
}
