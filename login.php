<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="./imagens/logospike.png" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #4CAF50;
        }
        a {
            text-decoration: none;
            color: #2196F3;
        }
        a:hover {
            text-decoration: underline;
        }
        div {
            margin-top: 20px;
        }
    </style>
    
</head>
<body>
    
<?php
session_start();
include 'conexao.php';

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Busca o usuário pelo email
$sql = "SELECT * FROM usuario WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && $row['senha'] == $senha) {

    // Salva dados do usuário na sessão
    $_SESSION['user_id'] = $row['id_usuario']; // ID correto
    $_SESSION['user_name'] = $row['nome'];
    $_SESSION['user_tipo'] = $row['tipo'];
    $_SESSION['user_email'] = $row['email'];

    // Verifica o campo 'ativo'
    if (isset($row['ativo'])) {
        if ($row['ativo'] == 1) {
            // Usuário ativo -> tela inicial
            header('Location: modulos.php');
            exit;
        } elseif ($row['ativo'] == 0) {
            // Usuário não ativo -> redireciona para página de pagamento
            header('Location: form-cartao.php');
            exit;
        } else {
            echo "Usuário ou senha inválida!";
        }
    } else {
        echo "Erro: atributo 'ativo' não encontrado.";
    }

} else {
    echo "Usuário ou senha inválida!";
}

$stmt->close();
$conn->close();
?>







</body>
</html>