<?php

namespace App\Policies;

use App\Article;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any articles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    // ?を付けると、その引数がnullであることも許容される
    // これにより未ログインユーザーでもアクセスが可能になる
    public function viewAny(?User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */
    
    // ?を付けると、その引数がnullであることも許容される
    // これにより未ログインユーザーでもアクセスが可能になる
    public function view(?User $user, Article $article)
    {
        // return trueとする事で、ログインユーザーでなくても権限を与える
        return true;
    }

    /**
     * Determine whether the user can create articles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // 記事投稿作成画面の段階では、ユーザーidの整合性が取れないのでtrue
        return true;
    }

    /**
     * Determine whether the user can update the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */
    public function update(User $user, Article $article)
    {
        // ログインユーザーと記事投稿者が一致すれば許可する
        return $user->id === $article->user_id;
    }

    /**
     * Determine whether the user can delete the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */
    public function delete(User $user, Article $article)
    {

        // ログインユーザーと記事投稿者が一致すれば許可する
        return $user->id === $article->user_id;
    }
    // 略
}
