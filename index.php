<?php
// index.php - Dashboard principal do gestor financeiro

require_once 'config.php';
require_once 'funcoes.php';

// Verificar se o usuário está autenticado
verificarAutenticacao();

$mensagem = '';
$tipo_mensagem = '';

// Processar formulário de nova transação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    if ($_POST['acao'] === 'adicionar') {
        $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
        $valor = isset($_POST['valor']) ? $_POST['valor'] : '';
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
        
        // Validar dados
        if (empty($nome) || empty($valor) || empty($tipo)) {
            $mensagem = 'Por favor, preencha todos os campos!';
            $tipo_mensagem = 'danger';
        } elseif ($valor <= 0) {
            $mensagem = 'O valor deve ser maior que zero!';
            $tipo_mensagem = 'danger';
        } else {
            adicionarTransacao($nome, $valor, $tipo);
            $mensagem = 'Transação adicionada com sucesso!';
            $tipo_mensagem = 'success';
        }
    } elseif ($_POST['acao'] === 'limpar') {
        limparHistorico();
        $mensagem = 'Histórico limpo com sucesso!';
        $tipo_mensagem = 'success';
    }
}

// Calcular saldo atual
$saldo = calcularSaldo();
$percentuais = calcularPercentualDespesas();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestor Financeiro</title>
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
        
        .saldo-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
            border: none;
        }
        
        .saldo-label {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 10px;
        }
        
        .saldo-valor {
            font-size: 2.5rem;
            font-weight: bold;
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
        
        .btn-danger {
            background-color: #e74c3c;
            border: none;
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(231, 76, 60, 0.4);
        }
        
        .form-control {
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            padding: 12px;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .form-select {
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            padding: 12px;
            transition: border-color 0.3s;
        }
        
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .row-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .row-buttons button {
            flex: 1;
        }
        
        .percentual-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }
        
        .percentual-item {
            margin-bottom: 10px;
            font-size: 0.9rem;
        }
        
        .percentual-item strong {
            display: block;
            margin-bottom: 5px;
        }
        
        .percentual-bar {
            background-color: #e0e0e0;
            border-radius: 5px;
            height: 20px;
            overflow: hidden;
        }
        
        .percentual-fill {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
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
            <div class="col-md-8 offset-md-2">
                <!-- Saldo Total -->
                <div class="card saldo-card">
                    <div class="saldo-label">Saldo Total</div>
                    <div class="saldo-valor"><?php echo formatarMoeda($saldo); ?></div>
                </div>
                
                <!-- Mensagens de Feedback -->
                <?php if ($mensagem): ?>
                    <div class="alert alert-<?php echo $tipo_mensagem; ?>" role="alert">
                        <?php echo htmlspecialchars($mensagem); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Formulário de Nova Transação -->
                <div class="card">
                    <div class="card-header">
                        <h5>➕ Nova Transação</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="nome" class="form-label">Nome da Transação</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Ex: Salário, Aluguel, Compras..." required>
                            </div>
                            
                            <div class="form-group">
                                <label for="valor" class="form-label">Valor (R$)</label>
                                <input type="number" class="form-control" id="valor" name="valor" placeholder="0.00" step="0.01" min="0" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="tipo" class="form-label">Tipo de Transação</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="">-- Selecione --</option>
                                    <option value="receita">Receita</option>
                                    <option value="despesa">Despesa</option>
                                </select>
                            </div>
                            
                            <input type="hidden" name="acao" value="adicionar">
                            <button type="submit" class="btn btn-primary w-100">Adicionar Transação</button>
                        </form>
                    </div>
                </div>
                
                <!-- Análise de Despesas -->
                <?php if (!empty($percentuais)): ?>
                <div class="card">
                    <div class="card-header">
                        <h5>📊 Análise de Despesas</h5>
                    </div>
                    <div class="card-body">
                        <div class="percentual-info">
                            <?php foreach ($percentuais as $item): ?>
                            <div class="percentual-item">
                                <strong><?php echo htmlspecialchars($item['nome']); ?> - <?php echo $item['percentual']; ?>%</strong>
                                <div class="percentual-bar">
                                    <div class="percentual-fill" style="width: <?php echo $item['percentual']; ?>%;">
                                        <?php echo formatarMoeda($item['valor']); ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Botão Limpar Histórico -->
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="" onsubmit="return confirm('Tem certeza que deseja limpar todo o histórico? Esta ação não pode ser desfeita!');">
                            <input type="hidden" name="acao" value="limpar">
                            <button type="submit" class="btn btn-danger w-100">🗑️ Limpar Histórico / Zerar Mês</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
