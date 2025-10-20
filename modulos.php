<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Módulos - Voleibol</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./css/modulos.css">
</head>
<body>
  <?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: login.html");
    
    exit();
}

?>

 <header class="topo">
    <a href="./modulos.php">
        <img src="./imagens/logospike.png" class="logo" alt="Logo">
    </a>
    <h1>Módulos</h1>
    <div style="cursor: pointer" onclick="window.location.href='./editar_conta.php'" class="perfil">
        <img src="./imagens/images.png" class="foto" alt="Foto do usuário">
        <div>
            <strong>
                Olá, <?php echo ($_SESSION['user_tipo'] == '1' ? "Aluno " : "Professor ") . ucwords(htmlspecialchars($_SESSION['user_name'])); ?>!
            </strong><br>
            <small><?php echo htmlspecialchars($_SESSION['user_email']); ?></small><br>
            <small><a href="logout.php">Sair</a></small>
        </div>
    </div>
</header>


  <main class="conteudo">
    <!-- FUNDAMENTOS -->
    <section class="fundamentos">
      <h2>Fundamentos</h2>
      <div class="grid">
        <div class="card">
          <img src="./imagens/manchete.png" class="thumb" alt="Manchete">
          <div class="info">
            <h3>Manchete</h3>
            <div class="barra"><div style="width:60%"></div></div>
            <a href="./aulas/recepcao.php" class="btn">Acessar</a>
          </div>
        </div>

        <div class="card">
          <img src="./imagens/toque.png" class="thumb" alt="Toque">
          <div class="info">
            <h3>Toque</h3>
            <div class="barra"><div style="width:45%"></div></div>
            <a href="./aulas/toque.php" class="btn">Acessar</a>
          </div>
        </div>

        <div class="card">
          <img src="./imagens/saque.png" class="thumb" alt="Saque">
          <div class="info">
            <h3>Saque</h3>
            <div class="barra"><div style="width:75%"></div></div>
            <a href="./aulas/saque.php" class="btn">Acessar</a>
          </div>
        </div>

        <div class="card">
          <img src="./imagens/ataque.png" class="thumb" alt="Ataque">
          <div class="info">
            <h3>Ataque</h3>
            <div class="barra"><div style="width:30%"></div></div>
            <a href="./aulas/ataque.php" class="btn">Acessar</a>
          </div>
        </div>

        <div class="card">
          <img src="./imagens/bloqueio.png"  class="thumb" alt="Bloqueio">
          <div class="info">
            <h3>Bloqueio</h3>
            <div class="barra"><div style="width:50%"></div></div>
            <a href="./aulas/bloqueio.php" class="btn">Acessar</a>
          </div>
        </div>

    </section>

    <!-- POSIÇÕES -->
    <section class="posicoes">
      <h2>Posições</h2>
      <div class="grid">
        <div class="card">
          <img src="levantador.png" class="thumb" alt="Levantador">
          <div class="info">
            <h3>Levantador</h3>
            <div class="barra"><div style="width:80%"></div></div>
            <a href="./aulas/levantador.php" class="btn">Acessar</a>
          </div>
        </div>

        <div class="card">
          <img src="oposto.png" class="thumb" alt="Oposto">
          <div class="info">
            <h3>Oposto</h3>
            <div class="barra"><div style="width:55%"></div></div>
            <a href="./aulas/oposto.php" class="btn">Acessar</a>
          </div>
        </div>

        <div class="card">
          <img src="ponteiro.png" class="thumb" alt="Ponteiro">
          <div class="info">
            <h3>Ponteiro</h3>
            <div class="barra"><div style="width:65%"></div></div>
            <a href="./aulas/ponteiro.php" class="btn">Acessar</a>
          </div>
        </div>

        <div class="card">
          <img src="central.png" class="thumb" alt="Central">
          <div class="info">
            <h3>Central</h3>
            <div class="barra"><div style="width:45%"></div></div>
            <a href="./aulas/central.php" class="btn">Acessar</a>
          </div>
        </div>

        <div class="card">
          <img src="libero.png" class="thumb" alt="Líbero">
          <div class="info">
            <h3>Líbero</h3>
            <div class="barra"><div style="width:70%"></div></div>
            <a href="./aulas/libero.php" class="btn">Acessar</a>
          </div>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
