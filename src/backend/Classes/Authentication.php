<?php
namespace SToyokura\Classes;

use SToyokura\Classes\UsePdo;
use SToyokura\Classes\User;

class Authentication
{

    /**
     * 認証メソッド
     * @param string  $user_info ユーザIDもしくはメールアドレス
     * @param string  $password  パスワード
     * @return object $user      Userインスタンス
     */
    public function login($user_info, $password) {

        //ユーザ情報取得のクエリ文作成
        $sql = "SELECT user_id, user_name, password FROM t_users WHERE user_id = :user_id OR mail_address = :mail_address;";
        //SQLインジェクション対策で使用する配列（bindValueで使用する）
        $pdo_item_arr = [
            "user_id" => $user_info,
            "mail_address" => $user_info
        ];

        //DB情報取得
        $obj_use_pdo = new UsePdo($sql, $pdo_item_arr);
        $count = $obj_use_pdo->stmtRowCount();
        $user  = $obj_use_pdo->stmtFetch();
    
        //Userクラスに渡す配列を定義
        if($count != 0 && password_verify($password, $user["password"])) {
            $login_result = [
                "auth" => true,
                "user_id" => $user["user_id"],
                "user_name" => $user["user_name"]
            ];
        } else {
            $login_result = [
                "auth" => false
            ];
        }
        
        //Userインスタンス生成
        $user = new User($login_result);

        //ログイン結果としてUserインスタンスを返す
        return $user;
    }

    public function logout() {

    }
}
