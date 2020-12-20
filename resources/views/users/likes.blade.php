@extends('app')

@section('title', $user->name . 'のいいねした記事')

@section('content')
  @include('nav')
  <div class="container">
    <!-- likeした記事の一覧を表示するviewを読み込み -->
    @include('users.user')
    <!-- tabs.blade.phpでは、変数$hasArticlesがfalse、変数$hasLikesがtrueとなる -->
    @include('users.tabs', ['hasArticles' => false, 'hasLikes' => true])
    @foreach($articles as $article)
      @include('articles.card')
    @endforeach
  </div>
@endsection
