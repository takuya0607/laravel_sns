@extends('app')

@section('title', 'プロフィール編集')

@section('content')
  @include('nav')
  <div class="container">
    <div class="row">
      <div class="mx-auto col col-12 col-sm-11 col-md-9 col-lg-7 col-xl-6">
        <div class="card mt-3">
          <div class="card-body text-center">
            @include('error_card_list')
            <div class="card-text">
              <form method="POST" action="{{ route('users.update' , ['name' => $user->name]) }}">
                @csrf
                @method('PATCH')
                <label for="file_photo" class="rounded-circle userProfileImg">
                  <div class="userProfileImg_description">画像をアップロード</div>
                  <i class="fas fa-camera fa-3x"></i>
                  <input type="file" id="file_photo" name="img_name">

                </label>
                <div class="userImgPreview" id="userImgPreview">
                  <img id="thumbnail" class="userImgPreview_content" accept="image/*" src="">
                  <p class="userImgPreview_text">画像をアップロード済み</p>
                </div>
                <div class="md-form">
                  <label for="name">ユーザー名</label>
                  <input class="form-control" type="text" id="name" name="name" required value="{{ $user->name }}">
                  <small>英数字3〜16文字</small>
                </div>
                <button class="btn btn-block aqua-gradient mt-2 mb-2" type="submit">更新する</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection




