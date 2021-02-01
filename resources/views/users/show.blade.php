@extends('app')

@section('title', $user->name)

@section('content')
  @include('nav')
  <div class="container">
    @include('users.user')
    <!-- includeでは、第二引数に変数名とその値を連想配列形式で渡すことが可能 -->
    <!-- 渡された変数は、includeの第一引数に指定したビューで使用することができる -->
    <!-- tabs.blade.phpでは、変数$hasArticlesがtrue、変数$hasLikesがfalseになる -->
    @include('users.tabs', ['hasArticles' => true, 'hasLikes' => false])
    @foreach($articles as $article)
      @include('articles.card')
    @endforeach
  </div>
  <div class="my-4 d-flex justify-content-center"></div>
@endsection
