<?php
// historico.php - Página de histórico de transações

require_once 'config.php';
require_once 'funcoes.php';

// Verificar se o usuário está autenticado
verificarAutenticacao();

// Calcular saldo e totalizações
$saldo = calcularSaldo();
$totalReceitas = 0;
$totalDespesas = 0;

if (isset($_SESSION['transacoes']) && is_array($_SESSION['transacoes'])) {
    foreach ($_SESSION['transacoes'] as $transacao) {
        if ($transacao['tipo'] === 'receita') {
            $totalReceitas += $transacao['valor'];
        } else {
            $totalDespesas += $transacao['valor'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico - Gestor Financeiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background-color: rgba(0, 0, 0, 0.7) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: #fff !important;
        }
        
        .nav-link {
            color: #fff !important;
            margin-left: 15px;
            transition: color 0.3s;
        }
        
        .nav-link:hover {
            color: #ffc107 !important;
        }
        
        .container-main {
            margin-top: 40px;
            margin-bottom: 40px;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            background-color: rgba(255, 255, 255, 0.95);
            margin-bottom: 30px;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            border: none;
            padding: 20px;
        }
        
        .card-header h5 {
            margin: 0;
            font-weight: bold;
        }
        
        .card-body {
            padding: 25px;
        }
        
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
        }
        
        .table {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
        }
        
        .table thead {
            background-color: #f8f9fa;
            border-bottom: 2px solid #667eea;
        }
        
        .table th {
            color: #333;
            font-weight: 600;
            padding: 15px;
        }
        
        .table td {
            padding: 12px 15px;
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .badge-receita {
            background-color: #27ae60;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            font-weight: 600;
        }
        
        .badge-despesa {
            background-color: #e74c3c;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            font-weight: 600;
        }
        
        .receita {
            color: #27ae60;
            font-weight: 600;
        }
        
        .despesa {
            color: #e74c3c;
            font-weight: 600;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        
        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">💰 Gestor Financeiro</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="historico.php">Histórico</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container container-main">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <!-- Cabeçalho -->
                <div class="card">
                    <div class="card-header">
                        <h5>📋 Histórico de Transações</h5>
                    </div>
                    <div class="card-body">
                        <!-- Estatísticas -->
                        <div class="stats-row">
                            <div class="stat-card">
                                <div class="stat-label">Saldo Total</div>
                                <div class="stat-value"><?php echo formatarMoeda($saldo); ?></div>
                            </div>
                            <div class="stat-card" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
                                <div class="stat-label">Total de Receitas</div>
                                <div class="stat-value"><?php echo formatarMoeda($totalReceitas); ?></div>
                            </div>
                            <div class="stat-card" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                                <div class="stat-label">Total de Despesas</div>
                                <div class="stat-value"><?php echo formatarMoeda($totalDespesas); ?></div>
                            </div>
                        </div>
                        
                        <!-- Tabela de Transações -->
                        <?php if (isset($_SESSION['transacoes']) && count($_SESSION['transacoes']) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Data/Hora</th>
                                            <th>Nome</th>
                                            <th>Tipo</th>
                                            <th>Valor</th>
                                            <th>Impacto no Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $saldoAcumulado = 0;
                                        foreach ($_SESSION['transacoes'] as $transacao):
                                            if ($transacao['tipo'] === 'receita') {
                                                $saldoAcumulado += $transacao['valor'];
                                                $impacto = '+' . formatarMoeda($transacao['valor']);
                                                $classe = 'receita';
                                            } else {
                                                $saldoAcumulado -= $transacao['valor'];
                                                $impacto = '-' . formatarMoeda($transacao['valor']);
                                                $classe = 'despesa';
                                            }
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($transacao['data']); ?></td>
                                            <td><?php echo htmlspecialchars($transacao['nome']); ?></td>
                                            <td>
                                                <?php if ($transacao['tipo'] === 'receita'): ?>
                                                    <span class="badge-receita">Receita</span>
                                                <?php else: ?>
                                                    <span class="badge-despesa">Despesa</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo formatarMoeda($transacao['valor']); ?></td>
                                            <td class="<?php echo $classe; ?>"><?php echo $impacto; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-state-icon">📭</div>
                                <p>Nenhuma transação registrada ainda.</p>
                                <p>Acesse o <a href="index.php" class="btn btn-primary btn-sm">Dashboard</a> para adicionar transações.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Botão Voltar -->
                <div class="text-center">
                    <a href="index.php" class="btn btn-primary">← Voltar ao Dashboard</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
