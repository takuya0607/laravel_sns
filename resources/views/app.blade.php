<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>
    @yield('title')
  </title>
  <!-- Font Awesome -->

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <!-- Bootstrap core CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/css/mdb.min.css" rel="stylesheet">
</head>

<body>

<div id="app">
  @yield('content')
</div>

<script src="{{ mix('js/app.js') }}"></script>
  <!-- JQuery -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/js/mdb.min.js"></script>


<script>
   // changeイベントは、フォーム要素が変更された時に発火するイベント
  $(document).on("change", "#file_photo", function (e) {
    // varで変数readerを定義する
    var reader;
    // ファイルの有無を判定
    if (e.target.files.length) {
    // JavaScriptでファイル操作をしたい時はFileReaderのオブジェクトを作成
    // 4行目で定義したreaderという変数に、FileReaderオブジェクトのインスタンスを代入
    // これでFileReaderに関するオブジェクトが、readerを用いて使用可能に！
    reader = new FileReader;
      // ファイルの読み込みがうまくいけば、reader.onloadイベントが発生
      reader.onload = function (e) {
        // varで変数userThumbnail(サムネイル)を定義する
        var userThumbnail;
        // プレビューを表示するための要素を取得
        userThumbnail = document.getElementById('thumbnail');
        // 取得したファイルを表示させるために、.is - activeクラスを付与
        $("#userImgPreview").addClass("is-active");
        // プレビュー画像を表示するためにimgタグのsrc属性に、e.target.resultで取得したファイル名を設定
        userThumbnail.setAttribute('src', e.target.result);
      };
      return reader.readAsDataURL(e.target.files[0]);
    }
  });
</script>
</body>

</html>