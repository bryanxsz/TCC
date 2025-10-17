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

// Busca a aula no banco
$aula = $conn->query("SELECT * FROM aulas WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo = $_POST['titulo'];
  $nome_aula = $_POST['nome_aula'];
  $link_video = $_POST['link_video'];
  $professor_nome = $_SESSION['user_name'];
  $professor_email = $_SESSION['user_email'];

  // Atualiza a aula com os dados e o nome/email do professor
  $sql = "UPDATE aulas 
          SET titulo='$titulo', 
              nome_aula='$nome_aula', 
              link_video='$link_video',
              professor_nome='$professor_nome', 
              professor_email='$professor_email'
          WHERE id=$id";
  $conn->query($sql);

  // Redireciona de volta para a página anterior
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
      padding-top: 50px;
    }

    .form-container {
      background: white;
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

  <div class="form-container">
    <h1>Editar <?php echo htmlspecialchars($aula['nome_aula']); ?></h1>

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
