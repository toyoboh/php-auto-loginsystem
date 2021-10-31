<?php

namespace SToyokura\Classes;

class Token
{
    /**
     * トークン生成
     * @param int $token_length 生成したいトークンの文字数 受け取った値に*2をした文字列になる
     * @return string 作成された文字列（トークン）
     */
    public function getToken($token_length = 16) {
        $bytes = openssl_random_pseudo_bytes($token_length);
        return bin2hex($bytes);
    }
}
