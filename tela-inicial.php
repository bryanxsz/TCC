<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    // Se tentar acessar sem logar, volta pro login
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Tela Inicial</title>
</head>
<body>
    <h1>Bem-vindo, <?php if ($_SESSION['user_tipo'] == '1') {
        echo "Aluno ";
    } else  {
        echo "Professor ";
    }  echo $_SESSION['user_name']; ?>!</h1>
    

    <a href="logout.php">Sair</a>
</body>
</html>
