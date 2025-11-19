<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Usuário não logado
    header('Location: login.html');
    exit;
}

include 'conexao.php';

$valor_atual = 0;

$pega = $conn->query("SELECT valor FROM sistema_de_pagamento LIMIT 1");
if ($pega && $pega->num_rows > 0) {
    $valor_atual = $pega->fetch_assoc()['valor'];
}


$id = $_SESSION['user_id'];
$sql = "SELECT ativo, tipo FROM usuario WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Se usuário já é ativo ou é professor (tipo = 2), redireciona
if ($row['tipo'] == 3) {

}else {
    echo "<script>
                alert('Acesso NEGADO!');
                window.history.back();
              </script>";
        exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $novo_valor = floatval($_POST['valor']);
    $conn->query("UPDATE sistema_de_pagamento SET valor = $novo_valor LIMIT 1");
    header("Location: painel.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Alterar Preço</title>
<link rel="icon" href="./imagens/logospike.png" type="image/x-icon">
<style>
 body {
  font-family: 'Montserrat', sans-serif;
  margin: 0;
  background-color: #ffc446;
  text-align: center;
 }

form { 
    background:#fff; 
    padding:20px; 
    border-radius:8px; 
    display:inline-block; 
}

input { 
    padding:8px; 
    font-size:16px; 
    margin:10px; 
}

button { 
    padding:8px 15px; 
    background:green; 
    background: linear-gradient(#ff9900, #ff6600);
    border:none; 
    border-radius:5px; 
    cursor:pointer; 
    font-weight: bold;
    color: #333;
}
button:hover {
  transition: 0.5s;
  background: linear-gradient(#e67e00, #cc5500);
  color: #ffffff;;
}

.topo {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: #ffc446;
  border-bottom: 1px solid black;
  padding: 15px 20px;
  margin-bottom: 50px;
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
        <a href="painel.php">
            <img src="./imagens/logospike.png" class="logo" alt="Logo">
        </a>
        
    </header>

<form method="post">
    <label><strong>Novo valor:</strong></label>
    <input type="number" step="0.01" name="valor" value="<?php echo $valor_atual; ?>" required>
    <button type="submit">Salvar</button>
</form>
</body>
</html>


