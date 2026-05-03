<?php
// login.php - Página de autenticação do usuário

require_once 'config.php';
require_once 'funcoes.php';

$erro = '';
$sucesso = '';

// Se já está autenticado, redireciona para o dashboard
if (estaAutenticado()) {
    header('Location: index.php');
    exit();
}

// Processar formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';
    
    // Validar credenciais
    if ($usuario === USUARIO_VALIDO && password_verify($senha, SENHA_VALIDA)) {
        $_SESSION['usuario_logado'] = true;
        $_SESSION['usuario'] = $usuario;
        header('Location: index.php');
        exit();
    } else {
        $erro = 'Usuário ou senha inválidos!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gestor Financeiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
        }
        
        .login-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            background-color: rgba(255, 255, 255, 0.95);
            border: none;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            border: none;
            padding: 30px 20px;
            text-align: center;
        }
        
        .card-header h3 {
            margin: 0;
            font-weight: bold;
            font-size: 1.8rem;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .form-control {
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            padding: 12px;
            transition: border-color 0.3s;
            margin-bottom: 15px;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
            color: white;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
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
        
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .info-box strong {
            display: block;
            margin-bottom: 5px;
            color: #0c5460;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card login-card">
            <div class="card-header">
                <h3>💰 Gestor Financeiro</h3>
            </div>
            <div class="card-body">
                <?php if ($erro): ?>
                    <div class="alert alert-danger" role="alert">
                        <strong>Erro!</strong> <?php echo htmlspecialchars($erro); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuário</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" required autofocus>
                    </div>
                    
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    
                    <button type="submit" class="btn btn-login">Entrar</button>
                </form>
                
                <div class="info-box">
                    <strong>Credenciais de Teste:</strong>
                    Usuário: <code>admin</code><br>
                    Senha: <code>123456</code>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
