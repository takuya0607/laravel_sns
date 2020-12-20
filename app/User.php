<?php

namespace App;

use App\Mail\BareMail;
use App\Notifications\PasswordResetNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token, new BareMail()));
    }

    public function articles(): HasMany
    {
        return $this->hasMany('App\Article');
    }

    public function followers(): BelongsToMany
    {
      // タグやlikeの時は、中間テーブルのカラム名がリレーション元/先のテーブル名の単数形_idだった。
      // フォローでは、カラム名が上記の規則に反するので、第３,4引数に指定してあげる必要がある。
        return $this->belongsToMany('App\User', 'follows', 'followee_id', 'follower_id')->withTimestamps();
    }

    public function followings(): BelongsToMany
    {
        return $this->belongsToMany('App\User', 'follows', 'follower_id', 'followee_id')->withTimestamps();
    }

    // ユーザーモデルと記事モデルの関係は多対多
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany('App\Article', 'likes')->withTimestamps();
    }

    // ユーザーをフォローしているかの判断
    // 引数$userの型が、Userモデルである事を第一引数で宣言。
    // ?を付ける事で、nullであることも許容される
    public function isFollowedBy(?User $user): bool
    {
        return $user
        // boolをキャストで指定する事で、trueかfalseを返すようにする
            ? (bool)$this->followers->where('id', $user->id)->count()
            : false;
    }

    public function getCountFollowersAttribute(): int
    {
      // $this->followersにより、このユーザーモデルのフォロワー(のユーザーモデル)が、コレクション(配列を拡張したもの)で返ります。
      // これによりユーザーがフォローされている数を算出できる
        return $this->followers->count();
    }

    public function getCountFollowingsAttribute(): int
    {
      // ユーザーモデルが現在フォロー中のユーザー数が算出できる
        return $this->followings->count();
    }
}
