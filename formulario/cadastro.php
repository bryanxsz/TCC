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


$nome=$_POST['nome'] ?? '';
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
        echo "<h1>Novo registro criado com sucesso</h1>";

        if ($nome && $email && $senha && $tipo)
        {
            $linha="Nome: $nome | Email: $email | Senha: $senha | Tipo: $tipo\n";
            file_put_contents("cadastro.txt",$linha,FILE_APPEND);
        
            echo "Nome: $nome <br>";
            echo "Email: $email <br>";
            echo "Senha: $senha <br>";
            echo "Tipo: $tipo <br>";
        }else

        {
            echo "Todos os Campos São Obrigatórios";
        }

    } else {
        echo "Erro ao cadastrar: " . mysqli_error($conn);
    }
}



?>    
</body>
</html>
