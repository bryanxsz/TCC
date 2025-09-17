<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php 

$nome = "localhost";
$user = "root";
$senha = "";
$db = "pj_spike";
$porta = 3307;

$conn = new mysqli($nome, $user, $senha, $db, $porta);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

?>
    
</body>
</html>

