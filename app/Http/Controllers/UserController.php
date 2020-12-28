<?php

namespace App\Http\Controllers;

use App\User;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Intervention\Image\Facades\Image;
use App\Services\CheckExtensionServices; //追加
use App\Services\FileUploadServices; //追加





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

        // // !is_null = img_nameが空でなければ
        // if(!is_null($request['img_name'])){

        //     // $imageFileにリクエストで送られた'img_name'を代入
        //     $imageFile = $request['img_name'];

        //     // FileUploadServicesクラスのfileUploadメソッドを使用する
        //     // ①まずは拡張子を含んだファイル名を取得
        //     // ②次に拡張子を除いたファイル名を取得
        //     // ③拡張子を取得
        //     // ④ファイル名_時間_拡張子として設定
        //     // ⑤ファイルの存在自体を取得
        //     // ⑥拡張子、ファイル名、ファイル本体の3つの変数を配列として$listに代入
        //     dd($imageFile);
        //     $list = FileUploadServices::fileUpload($imageFile);

        //     // list関数を使い、3つの変数に分割
        //     list($extension, $fileNameToStore, $fileData) = $list;

        //     //拡張子ごとに base64エンコード実施
        //     $data_url = CheckExtensionServices::checkExtension($fileData, $extension);

        //     //画像アップロード(Imageクラス makeメソッドを使用)
        //     $image = Image::make($data_url);

        //     //画像を横400px, 縦400pxにリサイズし保存
        //     $image->resize(400,400)->save(storage_path() . '/app/public/images/' . $fileNameToStore );

        //     // DBの'img_name'に＄fileNameToStore＝ファイル名_時間_拡張子として保存
        //     $user->img_name = $fileNameToStore;
        // }

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
