<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: login.html");
    exit();
}

include "conexao.php";
$userId = $_SESSION['user_id'];

$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $telefone = $_POST['telefone'];

    if(!empty($senha)){
        $sql = "UPDATE usuario SET nome=?, email=?, senha=?, telefone=? WHERE id_usuario=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nome, $email, $senha, $telefone, $userId);
    } else {
        $sql = "UPDATE usuario SET nome=?, email=?, telefone=? WHERE id_usuario=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nome, $email, $telefone, $userId);
    }
    $stmt->execute();

    // 🔹 Atualiza a sessão com os novos valores
    $_SESSION['user_name'] = $nome;
    $_SESSION['user_email'] = $email;

    // 🔹 Redireciona para a mesma página
    header("Location: modulos.php");
    exit();
}

// Buscar dados atuais para preencher o formulário
$sql = "SELECT nome, email, telefone FROM usuario WHERE id_usuario=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>


<!DOCTYPE html>

<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Conta</title>
    <link rel="icon" href="./imagens/logospike.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">

<style>
/* Adaptação do CSS fornecido */
body {
    font-family: Arial, sans-serif;
    background-color: #ffc446;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.container {
    background-color: #ff6a00;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 1);
    width: 350px;
    padding-right: 50px;
}
h1 {
    text-align: center;
    color: white;
}
label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
    color: white;
}
input, select {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
}
button {
    margin: 20px auto;   
    display: block;      
    width: 300px;  
    padding: 10px 30px;  
    background-color: #400000;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin-bottom: 20px;
}
button:hover {
    background-color: rgba(255, 0, 0, 0.3);
    transition: 0.5s;
}
.mensagem {
    text-align: center;
    font-weight: bold;
    margin-bottom: 10px;
    color: #00ff00;
}
.topo {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background-color: #ffc446;
    display: flex;
    align-items: center;
    border-bottom: 1px solid black;
    z-index: 10;
    padding: 20px;
    padding-bottom: 10px;
    height: 80px;
}

.logo { 
  height: 60px;
  width: 60px;
  background-color: black; 
  padding: 1px; 
  margin: 0px;
  border-radius: 10%;
  transform: scale(1.4); /* aumenta sem mexer no nav */
  transform-origin: center; /* garante que cresça pro centro */
  margin-left: 20px;

}

</style>
</head>
<body>
    
    <header class="topo">
        <a href="javascript:history.back()">
            <img src="./imagens/logospike.png" class="logo" alt="Logo">
        </a>
        
    </header>

<div class="container">
    <h1>Editar Conta</h1>

    <form method="POST" action="">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label>Senha (deixe em branco para não alterar):</label>
        <input type="password" name="senha">

        <label>Telefone (opcional):</label>
        <input type="tel" name="telefone" placeholder="(00) 00000-0000" value="<?php echo htmlspecialchars($user['telefone']); ?>">

        <button type="submit">Salvar Alterações</button>
    </form>
</div>

</body>
</html>
