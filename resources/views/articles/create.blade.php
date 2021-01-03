@extends('app')

@section('title', '記事投稿')

@include('nav')

@section('content')
  <br>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card mt-3">
          <div class="card-body pt-0">
            @include('error_card_list')
            <div class="card-text">
              <form method="POST" action="{{ route('articles.store') }}">
                <!-- 記事更新画面と共有するため、別でbladeを用意 -->
                @include('articles.form')
                <button type="submit" class="btn aqua-gradient btn-block">投稿する</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@include('footer')
@endsection
