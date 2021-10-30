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

        session_start();
        //既にログインしていないか確認
        if(isset($_SESSION["user_id"])) {
            $user_data = $this->logindUserDataSelect($_SESSION["user_id"]);
            return new User($user_data);
        } else {
            //条件に該当するユーザ数、ユーザ情報を取得
            list($user_count, $user_data) = $this->notLogindUserDataSelect($user_info);
            //パスワード確認
            if($user_count != 0 && password_verify($password, $user_data["password"])) {
                $login_try_result = $this->loginSuccessArr($user_data);
            } else {
                $login_try_result = $this->loginFailArr();
            }
            //Userインスタンスを生成し、返す
            return new User($login_try_result);
        }
    }

    /**
     * セッションにあるuser_idを元にユーザの情報を取得する
     * @param string $s_user_id セッションに登録されているuser_id
     * @return array $user_data Userインスタンスに使用するユーザ情報
     */
    public function logindUserDataSelect($s_user_id) {
        $sql = "SELECT user_id, user_name FROM t_users WHERE user_id = :user_id;";
        $pdo_item_arr = [
            "user_id" => $s_user_id
        ];
        $obj_use_pdo = new UsePdo($sql, $pdo_item_arr);
        $user_data = $obj_use_pdo->stmtFetch();
        $user_data["auth"] = true; //認証成功済みとしてauthをセット
        return $user_data;
    }

    /**
     * 条件に該当するユーザの数とユーザ情報の取得
     * @param string  $user_info ユーザIDもしくはメールアドレス
     * @return int    $user_count 条件に該当したユーザの数
     * @return array  $user_data  ユーザの情報
     */
    public function notLogindUserDataSelect($user_info) {
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
     * @return array $user_data
     */
    public function loginSuccessArr($user_data) {
        $user_data["auth"] = true; //auth情報を追加
        return $user_data;
    }

    /**
     * ログイン失敗時に使用するユーザ情報の配列を返すメソッド
     * @param  void
     * @return void
     */
    public function loginFailArr() {
        return [
            "auth" => false
        ];
    }
}
