<?php

function calcularSaldo() {
    $saldo = 0;
    
    if (isset($_SESSION['transacoes']) && is_array($_SESSION['transacoes'])) {
        foreach ($_SESSION['transacoes'] as $transacao) {
            if ($transacao['tipo'] === 'receita') {
                $saldo += $transacao['valor'];
            } else {
                $saldo -= $transacao['valor'];
            }
        }
    }
    
    return $saldo;
}


function formatarMoeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}


function calcularPercentualDespesas() {
    $totalDespesas = 0;
    $despesas = array();
    
    if (isset($_SESSION['transacoes']) && is_array($_SESSION['transacoes'])) {
        foreach ($_SESSION['transacoes'] as $transacao) {
            if ($transacao['tipo'] === 'despesa') {
                $totalDespesas += $transacao['valor'];
                $despesas[] = $transacao;
            }
        }
    }
    
    $resultado = array();
    
    if ($totalDespesas > 0) {
        foreach ($despesas as $despesa) {
            $percentual = ($despesa['valor'] / $totalDespesas) * 100;
            $resultado[] = array(
                'nome' => $despesa['nome'],
                'valor' => $despesa['valor'],
                'percentual' => round($percentual, 2)
            );
        }
    }
    
    return $resultado;
}


function adicionarTransacao($nome, $valor, $tipo) {
    if (!isset($_SESSION['transacoes'])) {
        $_SESSION['transacoes'] = array();
    }
    
    $transacao = array(
        'nome' => htmlspecialchars($nome),
        'valor' => floatval($valor),
        'tipo' => htmlspecialchars($tipo),
        'data' => date('d/m/Y H:i:s')
    );
    
    $_SESSION['transacoes'][] = $transacao;
}


function limparHistorico() {
    $_SESSION['transacoes'] = array();
    $_SESSION['saldo'] = 0;
}


function estaAutenticado() {
    return isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'] === true;
}


function verificarAutenticacao() {
    if (!estaAutenticado()) {
        header('Location: login.php');
        exit();
    }
}
?>
