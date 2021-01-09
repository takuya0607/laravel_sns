<div class="card mt-3">
  <div class="card-body">
    <div class="d-flex justify-content-between">
      <div>
        <a href="{{ route('users.show', ['name' => $user->name]) }}" class="text-dark">
          @isset($user->img_name)
            <img src="/storage/images/{{$user->img_name}}" class="rounded-circle userProfileImgIcon">
          @else
            <i class="fas fa-user-circle fa-3x"></i>
          @endisset
        </a>
      </div>

      @if( Auth::id() == $user->id )
      <!-- dropdown -->
      <div class="dropdown">
        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-ellipsis-v"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a class="dropdown-item" href="{{ route('users.edit', ['name' => $user->name]) }}">
            <i class="fas fa-pen mr-1"></i>プロフィールを編集する
          </a>
          <!-- destroyの記述 -->
          <div class="dropdown-divider"></div>
          <a class="dropdown-item text-danger" data-toggle="modal" data-target="#modal-delete-{{ $user->id }}">
            <i class="fas fa-trash-alt mr-1"></i>プロフィールを削除する
          </a>
          <!-- destroyの記述 -->
        </div>
      </div>
      <!-- dropdown -->
      @endif

      <!-- modal -->
      <div id="modal-delete-{{ $user->id }}" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('users.destroy', ['name' => $user->name]) }}">
              @csrf
              @method('DELETE')
              <div class="modal-body">
                アカウントを削除します。本当によろしいですか？
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

      @if( Auth::id() !== $user->id )
        <follow-button
          class="ml-auto"
          :initial-is-followed-by='@json($user->isFollowedBy(Auth::user()))'
          :authorized='@json(Auth::check())'
          endpoint="{{ route('users.follow', ['name' => $user->name]) }}"
        >
        </follow-button>
      @endif
    </div>
    <h2 class="h5 card-title m-0">
      <a href="{{ route('users.show', ['name' => $user->name]) }}" class="text-dark">
        {{ $user->name }}
      </a>
    </h2>
  </div>
  <div class="card-body">
    <div class="card-text">
      <a href="{{ route('users.followings', ['name' => $user->name]) }}" class="text-muted">
        {{ $user->count_followings }} フォロー
      </a>
      <a href="{{ route('users.followers', ['name' => $user->name]) }}" class="text-muted">
        {{ $user->count_followers }} フォロワー
      </a>
    </div>
  </div>
</div>


