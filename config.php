<?php
// config.php - Configuração de sessão e inicialização do sistema

session_start();

// Definir credenciais fixas (usuário: admin, senha: 123456)
define('USUARIO_VALIDO', 'admin');
define('SENHA_VALIDA', password_hash('123456', PASSWORD_DEFAULT));

// Inicializar array de transações na sessão se não existir
if (!isset($_SESSION['transacoes'])) {
    $_SESSION['transacoes'] = array();
}

// Inicializar saldo na sessão se não existir
if (!isset($_SESSION['saldo'])) {
    $_SESSION['saldo'] = 0;
}
?>
