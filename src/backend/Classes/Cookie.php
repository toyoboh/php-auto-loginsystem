<?php

namespace SToyokura\Classes;

use SToyokura\Classes\UsePdo;

class Cookie
{
    /**
     * @param string $old_cookie_token 現時点で登録されているcookie_token
     * @param string $expiration_datetime cookie_tokenの有効日時
     */
    public function checkExpirationDate($old_cookie_token, $expiration_datetime) {
        $use_pdo_arr = [
            "cookie_token"    => $cookie_token,
            "expiration_datetime" => $expiration_datetime
        ];
        $sql = "SELECT * FROM t_auto_login WHERE cookie_token = :cookie_token AND created_at >= :expiration_datetime;";
        $obj_use_pdo = new UsePdo($sql, $use_pdo_arr);
        $row_count = $obj_use_pdo->stmtRowCount();
        $row       = $obj_use_pdo->stmtFetch();
        return array($row_count == 1, $row);
    }

    

    /**
     * cookieの登録（更新）※４つ目以降の引数を調整することが多くなってきたら廃止するかも
     * @param string $key cookieのキー
     * @param mixed $value cookieの値
     * @param int $time 保存期間
     */
    public function register($key, $value, $time) {
        setcookie($key, $value, $time, "/", null, false, false);
    }

    /**
     * 
     */
    public function registerForDb($user_id, $cookie_token) {
        $insert_item_arr = [
            "user_id" => $user_id,
            "cookie_token" => $cookie_token
        ];
        $insert_sql = "INSERT INTO t_auto_login(user_id, cookie_token) VALUES(:user_id, :cookie_token);";
        new UsePdo($insert_sql, $insert_arr);
    }
     
    /**
     * 指定されたcookie情報の削除
     * @param string $key cookieのキー
     */
    public function delete($key) {
        setcookie($key, "", time()-60, "/", null, false, false);
    }

    /**
     * 指定されてcookieトークンの削除（DB用）
     * @param string $id t_auto_loginテーブルの主キー
     */
    public function deleteForDb($id) {
        $delete_sql = "DELETE FROM t_auto_login WHERE id = :id";
        $delete_item_arr = ["id" => $id];
        $obj_use_pdo = new UsePdo($sql, $delete_item_arr);
    }
}
