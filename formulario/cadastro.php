<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="css/estilo-cadastro.css">
</head>
<body>
    
<?php

include "../conexao.php";


$nome = ucwords(strtolower($_POST['nome'] ?? ''));
$email=$_POST['email'] ?? '';
$senha=$_POST['senha'] ?? '';
$tipo=$_POST['tipo'] ?? '';


$sql_check = "SELECT * FROM usuario WHERE email = '$email'";
$result = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($result) > 0) {
    echo "Este email já está cadastrado!";
} else {
    $sql = "INSERT INTO usuario (nome, email, senha, tipo) VALUES ('$nome', '$email', '$senha', '$tipo')";
    
    if (mysqli_query($conn, $sql)) {

        echo "<script>
            alert('Cadastro realizado com Sucesso! Clique em OK para fazer login.');
            window.location.replace('../login.html');
          </script>";
    exit;

     
}
}


?>    
</body>
</html>
