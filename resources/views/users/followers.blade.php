@extends('app')

@section('title', $user->name . 'のフォロワー')

@section('content')
  @include('nav')
  <div class="container">
    @include('users.user')
    @include('users.tabs', ['hasArticles' => false, 'hasLikes' => false])
    <!-- ユーザーをフォローしているユーザーを、$personに渡して、person.bladeで表示させる -->
    @foreach($followers as $person)
      @include('users.person')
    @endforeach
  </div>
  <div class="my-4 d-flex justify-content-center"></div>
@endsection