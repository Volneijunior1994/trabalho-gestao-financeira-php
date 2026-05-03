<?php
// funcoes.php - Funções auxiliares para cálculos e formatação

/**
 * Calcula o saldo total baseado nas transações da sessão
 * @return float Saldo total
 */
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

/**
 * Formata um valor monetário para o padrão brasileiro (R$)
 * @param float $valor Valor a ser formatado
 * @return string Valor formatado
 */
function formatarMoeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

/**
 * Calcula o percentual de uma despesa em relação ao total de despesas
 * @return array Array com nome da transação e seu percentual
 */
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

/**
 * Adiciona uma nova transação à sessão
 * @param string $nome Nome da transação
 * @param float $valor Valor da transação
 * @param string $tipo Tipo: 'receita' ou 'despesa'
 */
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

/**
 * Limpa o histórico de transações
 */
function limparHistorico() {
    $_SESSION['transacoes'] = array();
    $_SESSION['saldo'] = 0;
}

/**
 * Verifica se o usuário está autenticado
 * @return bool True se autenticado, false caso contrário
 */
function estaAutenticado() {
    return isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'] === true;
}

/**
 * Redireciona para a página de login se não estiver autenticado
 */
function verificarAutenticacao() {
    if (!estaAutenticado()) {
        header('Location: login.php');
        exit();
    }
}
?>
