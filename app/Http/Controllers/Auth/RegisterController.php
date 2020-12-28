<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

use App\Services\CheckExtensionServices;

use App\Services\FileUploadServices;
use Intervention\Image\Facades\Image;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'alpha_num', 'min:3', 'max:16', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'img_name' => ['file', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2000'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // もし'img_name'が空でなければ
        if (!empty($data['img_name'])) {
        //引数 $data から'img_name'を取得(アップロードするファイル情報)
        // $imageFileという変数に、$data配列の'img_name'を代入する
        $imageFile = $data['img_name'];

        $list = FileUploadServices::fileUpload($imageFile);

        // list関数を使い、3つの変数に分割
        list($extension, $fileNameToStore, $fileData) = $list;

        //拡張子ごとに base64エンコード実施
        $data_url = CheckExtensionServices::checkExtension($fileData, $extension);

        //画像アップロード(Imageクラス makeメソッドを使用)
        $image = Image::make($data_url);

        //画像を横400px, 縦400pxにリサイズし保存
        $image->resize(400,400)->save(storage_path() . '/app/public/images/' . $fileNameToStore );

        //createメソッドでユーザー情報を作成
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'img_name' => $fileNameToStore,
        ]);
        }else{
        // 'img_nameが空だったら'
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
          }
    }

    public function showProviderUserRegistrationForm(Request $request, string $provider)
    {
        $token = $request->token;

        // userFromTokenメソッドはGoogleから発行済みのトークンを使って、GoogleのAPIに再度ユーザー情報の問い合わせを行う
        // その問い合わせにより取得したユーザー情報は、いったん変数$providerUserに代入
        $providerUser = Socialite::driver($provider)->userFromToken($token);

        return view('auth.social_register', [
            // プロバイダー名'google'
            'provider' => $provider,
            // Googleから取得したメールアドレス
            'email' => $providerUser->getEmail(),
            // Googleが発行したトークン
            'token' => $token,
        ]);
    }

    public function registerProviderUser(Request $request, string $provider)
    {
        $request->validate([
            'name' => ['required', 'string', 'alpha_num', 'min:3', 'max:16', 'unique:users'],
            'token' => ['required', 'string'],
        ]);

        // Googleから発行済みのトークンの値を取得
        $token = $request->token;

        // \Socialite\Two\Userクラスのインスタンスを取得
        $providerUser = Socialite::driver($provider)->userFromToken($token);

        // ユーザーモデルのcreateメソッドを使って、ユーザーモデルのインスタンスを作成
        $user = User::create([
            'name' => $request->name,
            // emailは、トークンを使ってGoogleのAPIから取得したユーザー情報のメールアドレス
            'email' => $providerUser->getEmail(),
            'password' => null,
        ]);

        $this->guard()->login($user, true);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
