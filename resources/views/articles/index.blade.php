@extends('app')

@section('title', '記事一覧')

@section('content')
  @include('nav')
  @auth
  <div class="container">
    @include('search_form')
    @foreach($articles as $article)
      @include('articles.card')
    @endforeach
  </div>
  <div class="my-4 d-flex justify-content-center">
    {{ $articles->links() }}
  </div>
  @endauth
@endsection