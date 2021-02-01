<ul class="nav nav-tabs nav-justified mt-3">
  <li class="nav-item">
    <!-- 新たな変数$hasArticlesを使って、その値によってclass属性に'active'を付加するかどうかを三項演算子で制御する -->
    <!-- 三項演算子は、式1 ? 式2 : 式3という形式で記述し、trueであれば式2が値、falseであれば式3が値になる -->
    <a class="nav-link text-muted {{ $hasArticles ? 'active' : '' }}"
      href="{{ route('users.show', ['name' => $user->name]) }}">
      記事
    </a>
  </li>
  <li class="nav-item">
    <!-- 新たな変数$hasLikesを使って、その値によってclass属性に'active'を付加するかどうかを三項演算子で制御する -->
    <!-- 三項演算子は、式1 ? 式2 : 式3という形式で記述し、trueであれば式2が値、falseであれば式3が値になる -->
    <a class="nav-link text-muted {{ $hasLikes ? 'active' : '' }}"
      href="{{ route('users.likes', ['name' => $user->name]) }}">
      いいね
    </a>
  </li>
</ul>