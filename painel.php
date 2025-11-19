<?php
session_start();

if (!isset($_SESSION['user_id'])) {
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

if ($row['tipo'] != 3) {
    echo "<script>
            alert('Acesso NEGADO!');
            window.history.back();
          </script>";
    exit;
}

// PREÇO
$sql_valor = "SELECT * FROM sistema_de_pagamento LIMIT 1";
$result_valor = $conn->query($sql_valor);
$valor = ($result_valor->num_rows > 0) ? $result_valor->fetch_assoc()['valor'] : 0.00;

// ALUNOS
$sql_alunos = "SELECT id_usuario, nome FROM usuario WHERE tipo = '1'";
$result_alunos = $conn->query($sql_alunos);

// PROFESSORES
$sql_professores = "SELECT id_usuario, nome FROM usuario WHERE tipo = '2'";
$result_professores = $conn->query($sql_professores);

// DEPOIMENTOS
$sql_depo = "SELECT * FROM depoimentos ORDER BY id DESC";
$result_depo = $conn->query($sql_depo);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Painel</title>
    <link rel="icon" href="./imagens/logospike.png" type="image/x-icon">
</head>

<body>

<style>
/* ———————— EXATAMENTE O MESMO CSS ———————— */



body {
  font-family: 'Montserrat', sans-serif;
  margin: 0;
  background-color: #ffc446;
}
.topo {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: #ffc446;
  border-bottom: 1px solid black;
  padding: 15px 20px;
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
.topo h1 {
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
.perfil a:hover { color: red; }
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
  border: 1px solid black;
}
.preco {
  display: flex;
  justify-content: center;
  background-color: #fff;
  padding: 5px;
  border-radius: 4px;
  font-weight: bold;
  width: fit-content;
  margin: 0 auto 20px auto;
  border: 1px solid black;
}
.preco a {
  margin-left: 10px;
  color: blue;
  text-decoration: underline;
}
.preco a:hover { color: red; }

.tabelas {
  display: flex;
  justify-content: space-between;
  gap: 20px;
  flex-wrap: wrap;
}

.tabela {
  background-color: #fff;
  border-radius: 6px;
  flex: 1;
  padding: 10px;
  box-shadow: 0 0 5px rgba(0,0,0,0.2);
  border: 1px solid black;
  min-width: 300px;
}

.tabela h3 {
  text-align: center;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 8px;
  border: 1px solid black;
}

.excluir {
  color: red;
  cursor: pointer;
  text-decoration: underline;
}
.excluir:hover { color: blue; }
</style>

<header class="topo">
    <a href="">
        <img src="./imagens/logospike.png" class="logo" alt="Logo">
    </a>
    <h1>PAINEL</h1>

    <div class="perfil">
        <img src="./imagens/images.png" class="foto">
        <div>
            <strong>Olá, <?= ucwords($_SESSION['user_name']); ?>!</strong><br>
            <small><?= $_SESSION['user_email']; ?></small><br>
            <small><a href="./logout.php">Sair</a></small>
        </div>
    </div>
</header>

<div class="container">

    <!-- PREÇO -->
    <div class="preco">
        <span>PREÇO: </span>&nbsp; R$<?= number_format($valor, 2, ',', '.') ?>&nbsp;
        <a href="alterar_preco.php">ALTERAR</a>
    </div>

    <div class="tabelas">

        <!-- ALUNOS -->
        <div class="tabela">
            <h3>ALUNOS</h3>
            <table>
                <tr><th>NOME</th><th>ID</th><th>EXCLUIR</th></tr>
                <?php while ($a = $result_alunos->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($a['nome']) ?></td>
                        <td><?= $a['id_usuario'] ?></td>
                        <td><a class="excluir" href="excluir_usuario.php?id=<?= $a['id_usuario'] ?>">excluir</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- PROFESSORES -->
        <div class="tabela">
            <h3>PROFESSORES</h3>
            <table>
                <tr><th>NOME</th><th>ID</th><th>EXCLUIR</th></tr>
                <?php while ($p = $result_professores->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nome']) ?></td>
                        <td><?= $p['id_usuario'] ?></td>
                        <td><a class="excluir" href="excluir_usuario.php?id=<?= $p['id_usuario'] ?>">excluir</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- DEPOIMENTOS -->
        <div class="tabela" style="flex-basis: 100%;">
            <h3>DEPOIMENTOS</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>NOME</th>
                    <th>CARGO</th>
                    <th>MENSAGEM</th>
                    <th>ESTRELAS</th>
                    <th>ATIVO</th>
                    <th>EXCLUIR</th>
                </tr>

                <?php while ($d = $result_depo->fetch_assoc()): ?>
                    <tr>
                        <td><?= $d['id'] ?></td>
                        <td><?= htmlspecialchars($d['nome']) ?></td>
                        <td><?= htmlspecialchars($d['cargo']) ?></td>
                        <td><?= htmlspecialchars($d['texto']) ?></td>
                        <td><?= $d['estrelas'] ?></td>

                        <td>
                            <button  onclick="toggleAtivo(<?= $d['id'] ?>)">
                                <?= $d['ativo'] == 1 ? 'Ativo' : 'Inativo' ?>
                            </button>
                        </td>

                        <td>
                            <a class="excluir" href="excluir_depoimento.php?id=<?= $d['id'] ?>">excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>

            </table>
        </div>

    </div>
</div>

<script>
function toggleAtivo(id) {
    fetch("toggle_ativo_depoimento.php?id=" + id)
        .then(r => r.text())
        .then(t => console.log(t))
        .catch(err => console.error(err));
        window.location.reload();
    
}

</script>

</body>
</html>
