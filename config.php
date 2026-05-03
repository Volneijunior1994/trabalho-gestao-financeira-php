<?php


session_start();


define('USUARIO_VALIDO', 'admin');
define('SENHA_VALIDA', password_hash('123456', PASSWORD_DEFAULT));


if (!isset($_SESSION['transacoes'])) {
    $_SESSION['transacoes'] = array();
}


if (!isset($_SESSION['saldo'])) {
    $_SESSION['saldo'] = 0;
}
?>
