<?php

# PHP 7.3.0以降でのみ動作
$cookieParams = session_get_cookie_params();

session_set_cookie_params([
    'lifetime' => $cookieParams['lifetime'],
    'path' => $cookieParams['path'],
    'domain' => $cookieParams['domain'],
    'secure' => $cookieParams['secure'],
    'httponly' => $cookieParams['httponly'],    # クッキー盗難防止
    'samesite' => 'Lax'                         # SameSite属性を追加
]);

session_start();
?>
