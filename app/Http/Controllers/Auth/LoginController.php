<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    // Socialiteのdirverメソッドに、外部のサービス名を渡す
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // 下記、ログインまでの流れ
    // 「Googleでログインボタン」を押す
    // localhost/login/googleにアクセスする(GETリクエストする)
    // redirectToProviderアクションメソッドが実行される
    // Googleのアカウント選択画面へリダイレクトされる

    public function handleProviderCallback(Request $request, string $provider)
    {
        // Laravel\Socialite\Two\Userというクラスのインスタンスを取得し、$providerUserに代入
        $providerUser = Socialite::driver($provider)->stateless()->user();

        // Googleから取得したユーザー情報からメールアドレスを取り出し、
        // そのメールアドレスが本教材のWebサービスのusersテーブルに存在するかを確認
        // このメールアドレスをwhereメソッドの第二引数に渡し、条件に一致するユーザーモデルをコレクションとして取得
        // コレクションメソッドのfirstメソッドを使用して、コレクションの最初の1件のユーザーモデルを取得
        // $userには、Googleから取得したメールアドレスと同じメールアドレスを持つユーザーモデルが代入
        $user = User::where('email', $providerUser->getEmail())->first();

        if ($user) {
          // loginメソッドの第二引数をtrueにする。
          // こうすることでログアウト操作をしない限り、ログイン状態が維持される
            $this->guard()->login($user, true);
            return $this->sendLoginResponse($request);
        }

        return redirect()->route('register.{provider}', [
            'provider' => $provider,
            'email' => $providerUser->getEmail(),
            // $providerUser->tokenでは、Googleから発行されたトークンが返る
            'token' => $providerUser->token,
        ]);
    }
}
