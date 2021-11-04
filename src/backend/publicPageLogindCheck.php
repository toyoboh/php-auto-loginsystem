<?php

use SToyokura\Classes\Redirect;
use SToyokura\Classes\Authentication;
use SToyokura\Classes\Session;

$obj_session = new Session();

if($obj_session->isThere("user_id")) {
    $obj_redirect = new Redirect();
    $obj_redirect->go("home");
}

if(isset($_COOKIE["cookie_token"])) {
    $obj_authentication = new Authentication();
    $isLogin = $obj_authentication->cookieTokenLogin();
    
    if($isLogin) {
        $obj_redirect = new Redirect();
        $obj_redirect->go("home");
    }
}
