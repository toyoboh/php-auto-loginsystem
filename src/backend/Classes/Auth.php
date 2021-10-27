<?php
namespace SToyokura\Classes;

use SToyokura\Classes\UsePdo;

class Auth
{

    /**
     * 認証メソッド
     * @param string $user_info ユーザIDもしくはメールアドレス
     * @param string $password  パスワード
     * @return array $login_result 認証の結果
     */
    public function login($user_info, $password) {

        //ユーザ情報取得のクエリ文作成
        $sql = "SELECT user_id, user_name, password FROM t_users WHERE user_id = :user_id OR mail_address = :mail_address;";
        //SQLインジェクション対策で使用する配列（bindValueで使用する）
        $pdo_item_arr = [
            "user_id" => $user_info,
            "mail_address" => $user_info
        ];

        //認証処理
        $use_pdo = new UsePdo($sql, $pdo_item_arr);
        $count = $use_pdo->stmtRowCount();
        $user  = $use_pdo->stmtFetch();
    
        //認証結果を返す配列を設定
        if($count == 1 && password_verify($password, $user["password"])) {
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

        return $login_result;
    }

    public function logout() {

    }
}
