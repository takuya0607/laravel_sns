<br>
<div class="row">
  <div class="col-sm-10 offset-sm-1 text-center">
    <div class="info-form">
      <form class="form-inline justify-content-center" type="get" action="{{ url('/search') }}">
        {{ csrf_field() }}
        <div class="form-group">
          <input size="40"  class="form-control rounded-pill" type="search" placeholder="検索" placeholder="検索ワードを入力して下さい"
            name="search" required autocomplete="search" pattern=".*\S+.*" title="全角・半角スペースは無効な値です">
        </div>
        <button type="submit" class="btn btn-primary rounded-pill">
          <i class="fa fa-search" aria-hidden="true"></i>
        </button>
      </form>
    </div>
  </div>
</div>
<br>








