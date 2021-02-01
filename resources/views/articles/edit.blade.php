@extends('app')

@section('title', '記事更新')

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
              <!-- 'article' => $articleと記載する事で、$articleのIDが入力される -->
              <form method="POST" action="{{ route('articles.update', ['article' => $article]) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <!-- methodでPATCHを入力する事で、以下の内容で送られる -->
                <!-- input type="hidden" name="_method" value="PATCH" -->
                @include('articles.form')
                <button type="submit" class="btn aqua-gradient btn-block">更新する</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
