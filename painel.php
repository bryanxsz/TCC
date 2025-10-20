<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Usuário não logado
    header('Location: login.html');
    exit;
}

include 'conexao.php';
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

// --- BUSCAR VALOR DO SISTEMA DE PAGAMENTO ---
$sql_valor = "SELECT * FROM sistema_de_pagamento LIMIT 1";
$result_valor = $conn->query($sql_valor);
$valor = ($result_valor->num_rows > 0) ? $result_valor->fetch_assoc()['valor'] : 0.00;

// --- BUSCAR ALUNOS E PROFESSORES ---
$sql_alunos = "SELECT id_usuario, nome FROM usuario WHERE tipo = '1'";
$result_alunos = $conn->query($sql_alunos);

$sql_professores = "SELECT id_usuario, nome FROM usuario WHERE tipo = '2'";
$result_professores = $conn->query($sql_professores);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Painel</title>
    <link rel="icon" href="./imagens/logospike.png" type="image/x-icon">
</head>
<body>
    <style>
 body {
  font-family: 'Montserrat', sans-serif;
  margin: 0;
  background-color: #ffc446;
}

/* HEADER */
.topo {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: #ffc446;
  border-bottom: 1px solid black;
  padding: 15px 20px;
}

.logo {
  height: 50px;
  width: 50px;
  background-color: black; 
  padding: 7px; 
  margin-bottom: -10px; 
  border-radius: 10%
}

.topo h1 {
  text-align: center;
    color: black;
    font-size: 22px;
    margin: 0;
}

.perfil {
  display: flex;
  align-items: center;
  gap: 10px;
  border: 1px solid black;
  padding: 5px 10px;
  border-radius: 8px;
  background: white;
  cursor: pointer;
}
.perfil a:hover{
  color: red;
}
.perfil .foto {
  width: 38px;
  height: 38px;
  border-radius: 50%;
}

 .container {
        width: 90%;
        margin: 30px auto;
        background-color: #fda726;
        padding: 20px;
        border-radius: 10px;
        color: #000;
        border: 1px solid black
    }
    .preco {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #fff;
        padding: 5px;
        border-radius: 4px;
        font-weight: bold;
        width: fit-content;
        margin: 0 auto 20px auto;
        border: 1px solid black
    }
    .preco a {
        margin-left: 10px;
        color: blue;
        font-weight: bold;
        text-decoration: underline;
    }
     .preco a:hover {
        color: red;
    }
   
    .tabelas {
        display: flex;
        justify-content: space-between;
        gap: 20px;
    }
    .tabela {
        background-color: #fff;
        border-radius: 6px;
        flex: 1;
        padding: 10px;
        box-shadow: 0 0 5px rgba(0,0,0,0.2);
        border: 1px solid black
    }
    .tabela h3 {
        text-align: center;
        color: #000;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    th, td {
        padding: 8px;
        border: 1px solid black
    }
    th {
        color: #000;
        border: 1px solid black
    }
    .excluir {
        color: red;
        text-decoration: underline;
        cursor: pointer;
    }
    .excluir:hover {
        color: blue;
    }
    </style>

<header class="topo">
    <a href="">
        <img src="./imagens/logospike.png" class="logo" alt="Logo">
    </a>
    <h1>PAINEL</h1>
    <div style="cursor: pointer" onclick="window.location.href='" class="perfil">
        <img src="./imagens/images.png" class="foto" alt="Foto do usuário">
        <div>
            <strong>
                Olá, <?php echo ucwords(htmlspecialchars($_SESSION['user_name'])); ?>!
            </strong><br>
            <small><?php echo htmlspecialchars($_SESSION['user_email']); ?></small><br>
            <small><a href="./logout.php">Sair</a></small>
        </div>
    </div>
</header>

<div class="container">
    <div class="preco">
        <span>PREÇO: </span>&nbsp;&nbsp; R$<?= number_format($valor, 2, ',', '.') ?>&nbsp;&nbsp;
        <a class="alterar" href="alterar_preco.php">ALTERAR</a>
    </div>

    <div class="tabelas">
        <div class="tabela">
            <h3>ALUNOS</h3>
            <table>
                <tr><th>NOME</th><th>ID</th><th><span style="color:red;">EXCLUIR</span></th></tr>
                <?php while ($row = $result_alunos->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nome']) ?></td>
                        <td><?= $row['id_usuario'] ?></td>
                        <td><a class="excluir" href="excluir_usuario.php?id=<?= $row['id_usuario'] ?>">excluir</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="tabela">
            <h3>PROFESSORES</h3>
            <table>
                <tr><th>NOME</th><th>ID</th><th><span style="color:red;">EXCLUIR</span></th></tr>
                <?php while ($row = $result_professores->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nome']) ?></td>
                        <td><?= $row['id_usuario'] ?></td>
                        <td><a class="excluir" href="excluir_usuario.php?id=<?= $row['id_usuario'] ?>">excluir</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>
