@extends('app')

@section('title', '検索一覧')

@section('content')
  @include('nav')
  <div class="container">
    @include('search_form')
    @if(count($searched_users) >= 1)
      <div style="width:40%; height:40px;">
      <p style="line-height:40px; font-weight:bold; font-size:22px">ユーザー 一覧</p>
      </div>
    @endif
    @foreach ($searched_users as $searched_user)
    <div class="card mt-3">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div>
            <a href="{{ route('users.show', ['name' => $searched_user->name]) }}" class="text-dark">
              @isset($searched_user->img_name)
                <img src="/storage/images/{{$searched_user->img_name}}" class="rounded-circle userProfileImgIcon">
              @else
                <i class="fas fa-user-circle fa-3x"></i>
              @endisset
            </a>
          </div>
          @if( Auth::id() == $searched_user->id )
          <div class="dropdown">
            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="{{ route('users.edit', ['name' => $searched_user->name]) }}">
                <i class="fas fa-pen mr-1"></i>プロフィールを編集する
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item text-danger" data-toggle="modal" data-target="/">
                <i class="fas fa-trash-alt mr-1"></i>プロフィールを削除する
              </a>
            </div>
          </div>
          @endif
          @if( Auth::id() !== $searched_user->id )
            <follow-button
              class="ml-auto"
              :initial-is-followed-by='@json($user->isFollowedBy(Auth::user()))'
              :authorized='@json(Auth::check())'
              endpoint="{{ route('users.follow', ['name' => $searched_user->name]) }}"
            >
            </follow-button>
          @endif
        </div>
        <h2 class="h5 card-title m-0">
          <a href="{{ route('users.show', ['name' => $searched_user->name]) }}" class="text-dark">
            {{ $searched_user->name }}
          </a>
        </h2>
      </div>
      <div class="card-body">
        <div class="card-text">
          <a href="{{ route('users.followings', ['name' => $searched_user->name]) }}" class="text-muted">
            {{ $searched_user->count_followings }} フォロー
          </a>
          <a href="{{ route('users.followers', ['name' => $searched_user->name]) }}" class="text-muted">
            {{ $searched_user->count_followers }} フォロワー
          </a>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <br>
  <div class="container">
    @if(count($searched_articles) >= 1)
      <div style="width:30%; height:40px;">
        <p style="line-height:40px; font-weight:bold; font-size:22px">投稿一覧</p>
      </div>
    @endif
    @foreach ($searched_articles as $searched_article)
    <div class="card mt-3">
      <div class="card-body d-flex flex-row">
        <a href="{{ route('users.show', ['name' => $searched_article->user->name]) }}" class="text-dark">
          @isset($searched_article->user->img_name)
            <img src="/storage/images/{{$searched_article->user->img_name}}" class="rounded-circle userProfileImgIcon">
          @else
            <i class="fas fa-user-circle fa-3x mr-2"></i>
          @endisset
        </a>
        <div>
          <a href="{{ route('users.show', ['name' => $searched_article->user->name]) }}" class="text-dark">
            <div class="font-weight-bold">{{ $searched_article->user->name }}</div>
          </a>
          <div class="font-weight-lighter">{{ $searched_article->created_at->format('Y/m/d H:i') }}</div>
        </div>
      @if( Auth::id() === $searched_article->user_id )
        <!-- dropdown -->
          <div class="ml-auto card-text">
            <div class="dropdown">
              <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('articles.edit', ['article' => $searched_article]) }}">
                  <i class="fas fa-pen mr-1"></i>記事を更新する
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" data-toggle="modal" data-target="#modal-delete-{{ $searched_article->id }}">
                  <i class="fas fa-trash-alt mr-1"></i>記事を削除する
                </a>
              </div>
            </div>
          </div>
          <!-- dropdown -->

          <!-- modal -->
          <div id="modal-delete-{{ $searched_article->id }}" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form method="POST" action="{{ route('articles.destroy', ['article' => $searched_article]) }}">
                  @csrf
                  @method('DELETE')
                  <div class="modal-body">
                    {{ $searched_article->title }}を削除します。よろしいですか？
                  </div>
                  <div class="modal-footer justify-content-between">
                    <a class="btn btn-outline-grey" data-dismiss="modal">キャンセル</a>
                    <button type="submit" class="btn btn-danger">削除する</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- modal -->
        @endif

      </div>
      <div class="card-body pt-0">
        <h3 class="h4 card-title">
          <a class="text-dark" href="{{ route('articles.show', ['article' => $searched_article]) }}">
            {{ $searched_article->title }}
          </a>
        </h3>
        <div class="card-text">
          {{ $searched_article->body }}
        </div>
      </div>
      <div class="card-body pt-0 pb-2 pl-3 ">
        <div class="card-text d-flex justify-content-start">
          <!-- initial-is-liked-byはv-bind:initial-is-liked-byの省略形 -->
          <!-- jsonを使用する事で、結果を値ではなく文字列でVueに渡している -->
          <!-- authorized=承認する ユーザーがログインしている状態のみを許容 -->
          <!-- endpointを新たに定義し、route関数で取得したURLを渡している -->
          <article-like
            :initial-is-liked-by='@json($searched_article->isLikedBy(Auth::user()))'
            :initial-count-likes='@json($searched_article->count_likes)'
            :authorized='@json(Auth::check())'
            endpoint="{{ route('articles.like', ['article' => $searched_article]) }}"
          >
          </article-like>
          <a href="{{ route('articles.show', ['article' => $searched_article]) }}">
            <i class="fas fa-comment ml-3" style="margin-top:6px;"></i>
          </a>
          <div class="card-text ml-2" style="margin-top:2px;">
            {{ count($searched_article->comments) }}
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>

@endsection