<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Article extends Model
{
    protected $fillable = [
        'title',
        'body',
    ];

    public function user(): BelongsTo
    {
      // $thisはArticleクラスのインスタンス
        return $this->belongsTo('App\User');
    }

    public function likes(): BelongsToMany
    {
        // 記事に対していいねをしているユーザーを取得するメソッド
        // 第二引数で中間テーブルの名前を指定してあげる
        // ->withTimestampsを入力する事で、中間テーブルにも日付が反映される
        return $this->belongsToMany('App\User', 'likes')->withTimestamps();
    }

    // ここでのisLikedByメソッドは、ユーザーモデルを渡すと、そのユーザーがこの記事をいいね済みかどうかを返すメソッド
    // 引数$userの型が、Userモデルである事を第一引数で宣言。
    // ?を付ける事で、nullであることも許容される
    public function isLikedBy(?User $user): bool
    {
        return $user
            // boolをキャストで指定する事で、trueかfalseを返すようにする
            // $this->likesでlikesテーブル経由で紐づくユーザーを検索する
            ? (bool)$this->likes->where('id', $user->id)->count()
            : false;
    }

    public function getCountLikesAttribute(): int
    {
      // $this->likesにより、記事モデルからlikesテーブル経由で紐付いているユーザーモデルがコレクションで返る
        return $this->likes->count();
    }

    public function tags(): BelongsToMany
      {
        // 第二引数に中間テーブル名を記載するが、今回は単数名article_tagなので、省略が可能
          return $this->belongsToMany('App\Tag')->withTimestamps();
      }
}
