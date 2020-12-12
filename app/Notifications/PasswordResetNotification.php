<?php

namespace App\Notifications;


use App\Mail\BareMail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;

    public $token;
    public $mail;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $token, BareMail $mail)
    {
        $this->token = $token;
        $this->mail = $mail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return $this->mail
            // 第一引数に送り主のアドレス、第二引数に送り主の名前
            // config関数を使って、config/mail.phpの'address'と'name'の値を取得
            ->from(config('mail.from.address'), config('mail.from.name'))
            // toには送信メールアドレスを渡す
            // $notifiableには、パスワード再設定メール送信先となるUserモデルが代入されている
            ->to($notifiable->email)
            // subjectメソッドには、メールの件名を渡す
            ->subject('[memo]パスワード再設定')
            // textメソッドは、テキスト形式のメールを送る場合に使うメソッド
            // 'emails.password_reset'とすることで、resources/views/emailsディレクトリのpassword_reset.blade.phpがテンプレートとして使用される
            ->text('emails.password_reset')
            // テンプレートとなるBladeに渡す変数を、withメソッドに連想配列形式で渡す
            ->with([
                'url' => route('password.reset', [
                    'token' => $this->token,
                    'email' => $notifiable->email,
                ]),
                'count' => config(
                    'auth.passwords.' .
                    config('auth.defaults.passwords') .
                    '.expire'
                ),
            ]);
    }
}
