@extends('app')

@section('title', '記事一覧')

@section('content')
  @include('nav')
  <div class="container">
    @include('search_form')
    @foreach($articles as $article)
      @include('articles.card')
    @endforeach
  </div>
  @include('footer')
@endsection