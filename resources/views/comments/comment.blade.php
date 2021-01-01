<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-8 mb-3">
          <ul class="list-group">
            @forelse ($comments as $comment)
                <li class="list-group-item">
                    <div class="py-3 w-100 d-flex">
                        <img src="{{ asset('storage/images/' .$comment->user->img_name) }}" class="rounded-circle" width="50" height="50">
                        <div class="ml-2 d-flex flex-column">
                            <a href="{{ route('users.show' ,['name' => $comment->user->name]) }}" class="text-dark">{{ $comment->user->name }}</a>
                        </div>
                        <div class="d-flex justify-content-end flex-grow-1">
                            <p class="mb-0 text-dark">{{ $comment->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                    <div class="py-3">
                        {!! nl2br(e($comment->text)) !!}
                    </div>
                </li>
            @empty
              <p class="mb-0 text-dark" style="margin:auto;">コメントはまだありません。</p>
            @endforelse
            <br>
            @if( Auth::id() !== $article->user_id )
              <li class="list-group-item">
                  <div class="py-3">
                      <form method="POST" action="{{ route('comments.store') }}">
                        @csrf
                        <div class="form-group row mb-0">
                            <div class="col-md-12 p-3 w-100 d-flex">
                                <img src="{{ asset('storage/images/' .$user->img_name) }}" class="rounded-circle" width="50" height="50">
                                <div class="ml-2 d-flex flex-column">
                                  <p class="mb-0">{{ $user->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" name="article_id" value="{{ $article->id }}">
                                <textarea class="form-control @error('text') is-invalid @enderror" name="text" required autocomplete="text" rows="4">{{ old('text') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12 text-right">
                                <p class="mb-4 text-danger">140文字以内</p>
                                <button type="submit" class="btn btn-primary">
                                    コメントする
                                </button>
                            </div>
                        </div>
                      </form>
                  </div>
              </li>
            @endif
          </ul>
      </div>
  </div>
</div>
<div class="my-4 d-flex justify-content-center">
  {{ $comments->links() }}
</div>