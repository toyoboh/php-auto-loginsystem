<?php
namespace SToyokura\Classes;

class User
{
    private $user_id = "";
    private $user_name = "";
    private $auth      = false;

    /**
     * メンバ変数設定
     * @param array $user_arr Auth::login時に返される連想配列
     * @return void
     */
    public function __construct($user_arr) {
        $this->user_id   = $user_arr["user_id"];
        $this->user_name = $user_arr["user_name"];
        $this->auth      = $user_arr["auth"];
    }

    /**
     * ユーザがログイン成功しているか返すメソッド
     * @param void
     * @return bool 認証成功しているか
     */
    public function isLogind() {
        return $this->auth;
    }

}
