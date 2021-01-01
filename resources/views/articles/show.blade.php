@extends('app')

@section('title', '記事詳細')

@section('content')
  @include('nav')
  @include('error_card_list')
  <div class="container">
    @include('articles.card')
  </div>
  <br>
    @include('comments.comment')
@endsection
