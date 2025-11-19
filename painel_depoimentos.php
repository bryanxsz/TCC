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

// --- BUSCAR DEPOIMENTOS ---
$sql_depo = "SELECT * FROM depoimentos ORDER BY id DESC";
$result_depo = $conn->query($sql_depo);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Painel - Depoimentos</title>
    <link rel="icon" href="./imagens/logospike.png" type="image/x-icon">
</head>
<body>
<style>
<?php /* exatamente o mesmo CSS */ ?>
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
.tabelas {
  display: flex;
  justify-content: center;
}
.tabela {
  background-color: #fff;
  border-radius: 6px;
  width: 100%;
  padding: 10px;
  box-shadow: 0 0 5px rgba(0,0,0,0.2);
  border: 1px solid black;
}
.tabela h3 {
  text-align: center;
  color: #000;
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
    <h1>PAINEL - DEPOIMENTOS</h1>
    <div class="perfil">
        <img src="./imagens/images.png" class="foto" alt="Foto do usuário">
        <div>
            <strong>Olá, <?= ucwords(htmlspecialchars($_SESSION['user_name'])); ?>!</strong><br>
            <small><?= htmlspecialchars($_SESSION['user_email']); ?></small><br>
            <small><a href="./logout.php">Sair</a></small>
        </div>
    </div>
</header>

<div class="container">

    <div class="tabelas">
        <div class="tabela">
            <h3>DEPOIMENTOS</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>NOME</th>
                    <th>CARGO</th>
                    <th>MENSAGEM</th>
                    <th>ESTRELAS</th>
                    <th>ATIVO</th>
                    <th><span style="color:red;">EXCLUIR</span></th>
                </tr>

                <?php while ($row = $result_depo->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nome']) ?></td>
                        <td><?= htmlspecialchars($row['cargo']) ?></td>
                        <td><?= htmlspecialchars($row['texto']) ?></td>
                        <td><?= $row['estrelas'] ?></td>

                        <td>
                            <input type="checkbox"
                                onclick="toggleAtivo(<?= $row['id'] ?>)"
                                <?= $row['ativo'] == 1 ? 'checked' : '' ?>>
                        </td>

                        <td>
                            <a class="excluir" href="excluir_depoimento.php?id=<?= $row['id'] ?>">excluir</a>
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
}
</script>

</body>
</html>
