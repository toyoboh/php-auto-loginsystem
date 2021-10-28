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

        //条件に該当するユーザ数、ユーザ情報を取得
        list($user_count, $user_data) = $this->selectUserData($user_info);
    
        //Userクラスに渡す配列を定義
        if($user_count != 0 && password_verify($password, $user_data["password"])) {
            $login_result_arr = $this->loginSuccessArr($user_data);
        } else {
            $login_result_arr = $this->loginFailArr($user_data);
        }
        
        //Userインスタンスを生成し、返す
        return new User($login_result_arr);
    }

    public function logout() {

    }

    /**
     * 条件に該当するユーザの数とユーザ情報の取得
     * @param string  $user_info ユーザIDもしくはメールアドレス
     * @return int    $user_count 条件に該当したユーザの数
     * @return array  $user_data  ユーザの情報
     */
    public function selectUserData($user_info) {
        //ユーザ情報取得のクエリ文作成
        $sql = "SELECT user_id, user_name, password FROM t_users WHERE user_id = :user_id OR mail_address = :mail_address;";
        //SQLインジェクション対策で使用する配列（bindValueで使用する）
        $pdo_item_arr = [
            "user_id" => $user_info,
            "mail_address" => $user_info
        ];

        //DB情報取得
        $obj_use_pdo = new UsePdo($sql, $pdo_item_arr);
        $user_count = $obj_use_pdo->stmtRowCount();
        $user_data  = $obj_use_pdo->stmtFetch();

        return array($user_count, $user_data);
    }

    /**
     * ログイン成功時に使用するユーザ情報の配列を返すメソッド
     * @param array $user_data
     * @return array $login_result_arr
     */
    public function loginSuccessArr($user_data) {
        return [
            "auth" => true,
            "user_id" => $user_data["user_id"],
            "user_name" => $user_data["user_name"]
        ];
    }

    /**
     * ログイン失敗時に使用するユーザ情報の配列を返すメソッド
     * @param array $user_data
     * @return array $login_result_arr
     */
    public function loginFailArr($user_data) {
        return [
            "auth" => false
        ];
    }
}
