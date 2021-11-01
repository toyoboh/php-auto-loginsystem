<?php

namespace SToyokura\Classes;

use SToyokura\Classes\UsePdo;

class Cookie
{
    /**
     * cookieの登録（更新）※４つ目以降の引数を調整することが多くなってきたら廃止するかも
     * @param string $key cookieのキー
     * @param mixed $value cookieの値
     * @param int $time 保存期間
     */
    public function set($key, $value, $time) {
        setcookie($key, $value, $time, "/", null, false, false);
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
        $sql = "DELETE FROM t_auto_login WHERE id = :id";
        $delete_item_arr = ["id" => $id];
        $obj_use_pdo = new UsePdo($sql, $delete_item_arr);
    }
}
