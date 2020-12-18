@csrf
<div class="md-form">
  <label>タイトル</label>
  <!-- null合体演算子は、式1 ?? 式2で表記をする -->
  <!-- 式1がnullでない場合は、式1が結果となり、式1がnullである場合は、式2が結果となる -->
  <!-- ArticleControllerで、createアクションの記述に変数$articleを渡していないため、ここでは式2が適用される -->
  <input type="text" name="title" class="form-control" required value="{{ $article->title ?? old('title') }}">
</div>

<div class="form-group">
  <article-tags-input
    :initial-tags='@json($tagNames ?? [])'
    :autocomplete-items='@json($allTagNames ?? [])'
  >
  </article-tags-input>
</div>

<div class="form-group">
  <label></label>
  <textarea name="body" required class="form-control" rows="16" placeholder="本文">{{ $article->body ?? old('body') }}</textarea>
</div>
