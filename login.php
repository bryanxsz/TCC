<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
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
include 'conexao.php';

    $email=$_POST['email'] ?? '';
    $senha=$_POST['senha'] ?? '';


    $sql = "SELECT * FROM usuario WHERE email='$email'";
    $result= mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    print_r ($row);
    if($row ['senha'] == $senha){
        session_start();
        $_SESSION ['user_name'] = $row ['nome'];
        $_SESSION['user_tipo'] = $row['tipo']; 
        header ('Location: apimercadopago.php');
    }else{
        echo"Usuário ou senha inválida!";

    }
    mysqli_close($conn);
?>





</body>
</html>