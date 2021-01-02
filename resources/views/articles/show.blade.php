@extends('app')

@section('title', '記事詳細')

@section('content')
  @include('nav')
  @include('error_card_list')
  <div class="container">
    @include('articles.card')
  </div>
  @include('comments.comment')
@endsection
