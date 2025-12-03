<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depoimentos</title>
    <link rel="icon" href="./imagens/logospike.png" type="image/x-icon">

    <style>
body {
  background-color: #ffc446;
  font-family: Arial, sans-serif;
  margin: 0;
}

/* ========================= */
/* HEADER (mesmo do style2) */
/* ========================= */
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
  margin-bottom: -10px;
  border-radius: 10%;
  transform: scale(1.4);
  transform-origin: center;
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

.perfil a:hover {
  color: red;
}

.perfil .foto {
  width: 38px;
  height: 38px;
  border-radius: 50%;
}

/* ========================= */
/* FORMULÁRIO DE DEPOIMENTO */
/* ========================= */
.form-depoimento {
  background: linear-gradient(#ff6a00, #ff8000);
  padding: 20px;
  border-radius: 20px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.15);
  max-width: 600px;
  width: 100%;
  margin: 30px auto;
}

.form-depoimento h2 {
  text-align: center;
  margin-bottom: 15px;
  color: #fff;
}

/* Label */
.form-depoimento label {
  font-weight: 600;
  color: #fff;
  margin-top: 12px;
  display: block;
}

/* Textarea */
.form-depoimento textarea {
  width: 100%;
  max-width: 580px;
  height: 120px;
  border-radius: 12px;
  border: 1px solid #400000;
  padding: 10px;
  font-size: 14px;
  font-family: 'Montserrat', sans-serif;
  resize: none;
  outline: none;
}

/* Estrelas */
.stars {
  display: flex;
  flex-direction: row-reverse;
  gap: 5px;
  margin: 10px 0 15px;
}

.stars input {
  display: none;
}

.stars label.star {
  font-size: 32px;
  cursor: pointer;
  color: #fff;
  transition: 0.2s;
}

/* Hover */
.stars label.star:hover,
.stars label.star:hover ~ .star {
  color: #ffbb00;
}

/* Selecionado */
.stars input:checked ~ label.star {
  color: #ffbb00;
}

/* Botão */
.btn-comentar {
  background: #400000;
  color: white;
  border-radius: 10px;
  width: 160px;
  height: 35px;
  font-weight: bold;
  cursor: pointer;
  border: none;
  display: block;
  margin: 10px auto 0;
}

.btn-comentar:hover {
  background: #610707;
  transition: 0.5s;
}
    </style>
</head>

<body>

<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: login.html");
    exit();
}

include "conexao.php";

$nome = $_SESSION['user_name'];

// Verifica se já existe um depoimento desse usuário
$sqlCheck = "SELECT id FROM depoimentos WHERE nome = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("s", $nome);
$stmtCheck->execute();
$stmtCheck->store_result();

if ($stmtCheck->num_rows > 0) {
    echo "<script>alert('Você já enviou um depoimento anteriormente!'); window.location.href='index.php'</script>";
    
    exit;
}
?>

<!-- HEADER -->
<header class="topo">
    <a href="index.php">
        <img src="./imagens/logospike.png" class="logo" alt="Logo">
    </a>

    <div onclick="window.location.href='./editar_conta.php'" class="perfil">
        <img src="./imagens/images.png" class="foto" alt="Foto do usuário">
        <div>
            
                Olá, <?php echo ($_SESSION['user_tipo'] == '1' ? "Aluno " : "Professor ") . ucwords($_SESSION['user_name']); ?>!
            <br>
            <small><?php echo $_SESSION['user_email']; ?></small><br>
            <small><a href="logout.php">Sair</a></small>
        </div>
    </div>
</header>

<!-- FORM -->
<div class="form-depoimento">
  <h2>Deixe seu Depoimento</h2>

  <form action="salvar_depoimento.php" method="POST">

    <label for="depoimento">Seu depoimento (máximo 150 caracteres):</label>
    <textarea id="depoimento" name="depoimento" required maxlength="150"></textarea>

    <label class="label-estrelas">Sua avaliação:</label>
    <div class="stars">
      <input type="radio" id="estrela5" name="estrelas" value="5">
      <label for="estrela5" class="star">★</label>

      <input type="radio" id="estrela4" name="estrelas" value="4">
      <label for="estrela4" class="star">★</label>

      <input type="radio" id="estrela3" name="estrelas" value="3">
      <label for="estrela3" class="star">★</label>

      <input type="radio" id="estrela2" name="estrelas" value="2">
      <label for="estrela2" class="star">★</label>

      <input type="radio" id="estrela1" name="estrelas" value="1">
      <label for="estrela1" class="star">★</label>
    </div>

    <button class="btn-comentar" type="submit">Enviar Depoimento</button>
  </form>
</div>

</body>
</html>
