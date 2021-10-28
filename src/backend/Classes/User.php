<?php
namespace SToyokura\Classes;

class User
{
    private $user_id   = "";
    private $user_name = "";
    private $auth      = false;

    /**
     * メンバ変数設定
     * @param array $user_arr Auth::login時に返される連想配列
     * @return void
     */
    public function __construct($user_arr) {
        //認証成功している時だけ
        if(!$user_arr["auth"]) return;
        $this->user_id   = $user_arr["user_id"];
        $this->user_name = $user_arr["user_name"];
        $this->auth      = $user_arr["auth"];
    }

    /**
     * ユーザがログイン済みか返すメソッド
     * @param void
     * @return bool ログイン済みの時はtrue
     */
    public function isLogind() {
        return $this->auth;
    }

}
