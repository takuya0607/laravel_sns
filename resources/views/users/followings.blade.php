@extends('app')

@section('title', $user->name . 'のフォロー中')

@section('content')
  @include('nav')
  <div class="container">
    @include('users.user')
    <!-- そのどちらのタブも選択されていない状態で表示を行うために、falseを記述 -->
    @include('users.tabs', ['hasArticles' => false, 'hasLikes' => false])
    <!-- ユーザーがフォローしているユーザーを、$personに渡して、person.bladeで表示させる -->
    @foreach($followings as $person)
      @include('users.person')
    @endforeach
  </div>
  <div class="my-4 d-flex justify-content-center"></div>
@endsection