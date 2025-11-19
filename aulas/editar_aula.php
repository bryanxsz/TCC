<?php
session_start();
include "../conexao.php";

// Verifica se o usuário está logado
if (!isset($_SESSION['user_name'])) {
  header("Location: ../login.html");
  exit();
}

// Pega o ID da aula
$id = $_GET['id'] ?? 0;
$voltar = $_GET['voltar'] ?? '../modulos.php';

// Busca a aula no banco (agora inclui o campo modulo)
$aula = $conn->query("SELECT id, nome_aula, titulo, link_video, modulo FROM aulas WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo = $_POST['titulo'];
  $nome_aula = $_POST['nome_aula'];
  $link_video = $_POST['link_video'];
  $professor_nome = $_SESSION['user_name'];
  $professor_email = $_SESSION['user_email'];
  $professor_telefone = $_SESSION['user_telefone'];

  $sql = "UPDATE aulas 
          SET titulo='$titulo', 
              nome_aula='$nome_aula', 
              link_video='$link_video',
              professor_nome='$professor_nome', 
              professor_email='$professor_email',
              professor_telefone='$professor_telefone'
          WHERE id=$id";
  $conn->query($sql);

  header("Location: $voltar");
  exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Editar Aula</title>
<style>
body {
  font-family: 'Montserrat', sans-serif;
  margin: 0;
  background-color: #ffc446;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  min-height: 100vh;
  padding-top: 110px; /* espaço para o topo fixo */
}

/* HEADER FIXO */
/* HEADER FIXO */
.topo {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: #ffc446;
  border-bottom: 1px solid black;
  padding: 15px 20px;
  height: 110px;
  box-sizing: border-box;
}

/* Garantir que os itens não se mexam */
.topo > div,
.logo,
.perfil {
  flex-shrink: 0;
}

/* Logo */
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

/* Título central fixo */
.topo h1 {
  flex-grow: 1;
  text-align: center;
  font-size: 22px;
  color: black;
  margin: 0;
  pointer-events: none; /* Evita clique alterar alinhamento */
}

/* Perfil */
.perfil {
  display: flex;
  align-items: center;
  gap: 10px;

  border: 1px solid black;
  padding: 5px 10px;
  border-radius: 8px;
  background: white;
  cursor: pointer;

  white-space: nowrap; /* impede quebra */
}

.perfil .foto {
  width: 38px;
  height: 38px;
  border-radius: 50%;
}

/* FORM */
.form-container {
  background: white;
  margin-top: 200px;
  padding: 30px 50px;
  border-radius: 20px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  width: 400px;
}

.form-container h1 {
  text-align: center;
  margin-bottom: 25px;
  color: #222;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: 600;
  color: #222;
}

input[type="text"] {
  width: 100%;
  padding: 10px 15px;
  margin-bottom: 20px;
  border-radius: 12px;
  border: 1px solid #ccc;
  font-size: 15px;
  transition: 0.3s;
}

input[type="text"]:focus {
  border-color: #400000;
  outline: none;
}

button {
  width: 100%;
  background: black;
  color: white;
  border: none;
  padding: 12px;
  border-radius: 25px;
  font-weight: 600;
  font-size: 16px;
  cursor: pointer;
  transition: 0.3s;
}

button:hover {
  background: #400000;
}
</style>
</head>

<body>

<header class="topo">
    <a href="javascript:history.back()"

href="../aulas/<?php echo $moduloSemAcento; ?>.php">

        <img src="../imagens/logospike.png" class="logo" alt="Logo">
    </a>
    <h1>Módulo: <?php echo ucwords(htmlspecialchars($aula['modulo'])); ?> </h1>
    <div style="cursor: pointer" onclick="window.location.href='../editar_conta.php'" class="perfil">
        <img src="../imagens/images.png" class="foto" alt="Foto do usuário">
        <div>
            <strong>
                Olá, <?php echo ($_SESSION['user_tipo'] == '1' ? "Aluno " : "Professor ") . ucwords(htmlspecialchars($_SESSION['user_name'])); ?>!
            </strong><br>
            <small><?php echo htmlspecialchars($_SESSION['user_email']); ?></small><br>
            <small><a href="../logout.php">Sair</a></small>
        </div>
    </div>
</header>


<div class="form-container">

  <!-- Agora o H1 mostra o nome do módulo -->
  <h1>Editar <?php echo htmlspecialchars($aula['nome_aula']); ?> </h1>

  <form method="POST">
    <label>Nome da Aula:</label>
    <input type="text" name="nome_aula" value="<?php echo htmlspecialchars($aula['nome_aula']); ?>">

    <label>Título:</label>
    <input type="text" name="titulo" value="<?php echo htmlspecialchars($aula['titulo']); ?>">

    <label>Link do Vídeo:</label>
    <input type="text" name="link_video" value="<?php echo htmlspecialchars($aula['link_video']); ?>">

    <button type="submit">Salvar</button>
  </form>
</div>

</body>
</html>
