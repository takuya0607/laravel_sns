<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'title' => 'required|max:50',
          'body' => 'required|max:500',
          'tags' => 'json|regex:/^(?!.*\s).+$/u|regex:/^(?!.*\/).*$/u',
        ];
    }

    public function attributes()
    {
        return [
          'title' => 'タイトル',
          'body' => '本文',
          'tags' => 'タグ',
        ];
    }

    // フォームリクエストのバリデーションが成功した後に自動的に呼ばれるメソッド
    public function passedValidation()
    {
      // JSON形式の文字列であるタグ情報をPHPのjson_decode関数を使って連想配列に変換
      // collect関数を使ってコレクションに変換
      $this->tags = collect(json_decode($this->tags))
        // sliceメソッドを使うと、コレクションの要素が、第一引数に指定したインデックスから第二引数に指定した数だけになる
        // ここでは最初の5個だけが残る
          ->slice(0, 5)
          // mapメソッドは、コレクションの各要素に対して順に処理を行い、新しいコレクション($requestTag)を作成する
          ->map(function ($requestTag) {
              return $requestTag->text;
          });
    }
}
