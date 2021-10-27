<?php
namespace SToyokura\Classes;

class User
{
    public static $user_id = "";
    public static $user_name = "";
    public static $auth      = false;

    /**
     * ユーザ情報を設定するメソッド
     * @param array $user Auth::login時に返される連想配列
     * @return void
     */
    public static function setUser($user) {
        self::$user_id = $user["user_id"];
        self::$user_name = $user["user_name"];
        self::$auth = $user["auth"];
    }

    

}
