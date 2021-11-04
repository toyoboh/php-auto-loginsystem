<?php
namespace SToyokura\Classes;

use SToyokura\Classes\UsePdo;
use SToyokura\Classes\User;
use SToyokura\Classes\Cookie;
use SToyokura\Classes\Token;
use SToyokura\Classes\Session;

class Authentication
{

    /**
     * PCに登録されているcookie_tokenを用いたログイン
     * @param void
     * @return boolean 認証結果
     */
    public function cookieTokenLogin() {

        $old_cookie_token = $_COOKIE["cookie_token"];

        $obj_cookie = new Cookie();
        $obj_datetime = new \DateTime("-14 days");
        $expiration_datetime = $obj_datetime->format("Y-m-d H:i:s");
    
        list($is_expiration, $cookie_token_record_db) = $obj_cookie->checkExpirationDate($old_cookie_token, $expiration_datetime);
    
        if(!$is_expiration) {
            $obj_cookie->delete("cookie_token");
            $obj_cookie->deleteForDb($cookie_token_record_db["id"]);
            return false;
        } else {
            $new_cookie_token = Token::getToken();
            
            $obj_cookie->registerForDb($cookie_token_record_db["user_id"], $new_cookie_token);
            $obj_cookie->register("cookie_token", $token, time()+60*60*24*14);
            $obj_cookie->deleteForDb($cookie_token_record_db["id"]);
    
            $obj_session = new Session();
            $obj_session->regenerate();
            $obj_session->set("user_id", $cookie_token_record_db["user_id"]);

            return true;
        }
    }

    /**
     * 認証メソッド
     * @param string  $user_info ユーザIDもしくはメールアドレス
     * @param string  $plaintext_password  パスワード
     * @param boolean $is_auto 自動ログインするか true:する, false:しない
     * @return boolean 認証結果
     */
    public function login($user_info, $plaintext_password, $is_auto) {

        list($user_count, $user_db_data) = $this->selectUserDbData($user_info);

        $obj_session = new Session();

        if($user_count != 1) {
            $obj_session->set("message", "ユーザ情報がありませんでした");
            return false;
        }
        
        if(!password_verify($plaintext_password, $user_db_data["password"])) {
            $obj_session->set("message", "ユーザID・メールアドレスまたはパスワードが一致しません");
            return false;
        }

        $obj_session->regenerate();
        $obj_session->set("user_id", $user_db_data["user_id"]);

        if(!$is_auto) {
            return true;
        }

        $new_cookie_token = Token::getToken();
        $obj_cookie = new Cookie();
        $obj_cookie->registerForDb($user_db_data["user_id"], $new_cookie_token);
        $obj_cookie->register("cookie_token", $new_cookie_token, time()+60*60*24*14);
        return true;
    }

    /**
     * 
     */
    public function selectUserDbData($user_info) {
        //ユーザ情報の取得
        $user_select_sql = "SELECT user_id, password FROM t_users WHERE user_id = :user_id OR mail_address = :mail_address;";
        $user_select_arr = [
            "user_id" => $user_info,
            "mail_address" => $user_info
        ];
        $obj_use_pdo = new UsePdo($user_select_sql, $user_select_arr);
        $user_count = $obj_use_pdo->stmtRowCount();
        $user_db_data = $obj_use_pdo->stmtFetch();

        return array($user_count, $user_db_data);
    }

}
