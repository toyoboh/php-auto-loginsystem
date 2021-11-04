<?php

namespace SToyokura\Classes;

class Session
{
    /**
     * session設定
     * @param void
     * @return void
     */
    public function __construct() {
        //sessionとcookieに関するiniファイルの情報を更新 ※開発中に適宜追加していく

        //セッションが開始されていなければ開始
        if(!isset($_SESSION)) session_start();
    }

    /**
     * セッションIDの更新
     * @param void
     * @return void
     */
    public function regenerate() {
        session_regenerate_id(true);
    }

    /**
     * セッション変数に追加
     * @param string $key $_SESSIONのキー
     * @param mixed  $value $_SESSIONの値
     * @return void
     */
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * 特定のセッションを削除
     * @param string $key $_SESSIONのキー
     * @return void
     */
    public function delete($key) {
        unset($_SESSION[$key]);
    }

    /**
     * すべてのセッション情報削除
     * @param void
     * @return void
     */
    public function allDelete() {
        $_SESSION = array();
        session_destroy();
    }

    /**
     * 特定のセッションの値を取得
     * @param string $key $_SESSIONのキー
     * @return mixed $_SESSIONの登録値
     */
    public function get($key) {
        return $_SESSION[$key];
    }

    /**
     * 特定のセッションの値が存在するか（登録されているか）
     * @param string $key $_SESSIONのキー
     * @return boolean 存在していたらtrue
     */
    public function isThere($key) {
        return isset($_SESSION[$key]);
    }

    /**
     * 全てのセッション情報を取得
     */
    public function getAll() {
        return $_SESSION;
    }

}
