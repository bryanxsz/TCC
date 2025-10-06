<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Aulas - Voleibol</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="recepcao.css">
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
  <a href="../modulos.php">
  <img src="../imagens/logospike.png" class="logo" alt="Logo">
</a>
  <h1>Aulas</h1>
  <div class="perfil">
    <img src="../imagens/images.png" class="foto" alt="Foto do usuário">
    <div>
      <strong>
        Olá, 
        <?php 
          if ($_SESSION['user_tipo'] == '1') {
              echo "Aluno ";
          } else {
              echo "Professor ";
          }
          echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); 
        ?>!
      </strong><br>
      <small>
        <?php 
          echo isset($_SESSION['user_email']) 
               ? htmlspecialchars($_SESSION['user_email'], ENT_QUOTES, 'UTF-8') 
               : "Email não disponível"; 
        ?>
      </small>
    </div>
  </div>
</header>

<main class="aulas-container">
  <!-- MENU LATERAL -->
  <aside class="menu-lateral">
    <div class="unidade">
      <h2>Recepção</h2>
      <button class="aula ativa" data-video="https://www.youtube.com/embed/dQw4w9WgXcQ" data-titulo="Aula 1: Introdução à Recepção">
        Aula 1
        <span class="status" title="Clique para marcar como visto"></span>
      </button>
      <button class="aula" data-video="https://www.youtube.com/embed/tgbNymZ7vqY" data-titulo="Aula 2: Posição e Movimentação">
        Aula 2
        <span class="status" title="Clique para marcar como visto"></span>
      </button>
      <button class="aula" data-video="https://www.youtube.com/embed/3fumBcKC6RE" data-titulo="Aula 3: Prática Avançada">
        Aula 3
        <span class="status" title="Clique para marcar como visto"></span>
      </button>
    </div>
  </aside>

  <!-- ÁREA DO VÍDEO -->
  <section class="video-area">
    <h2 id="tituloAula">Aula 1: Introdução à Recepção</h2>
    <div class="video-box">
      <iframe id="videoFrame"
        src="https://www.youtube.com/embed/dQw4w9WgXcQ"
        title="Vídeo da Aula"
        frameborder="0"
        allowfullscreen>
      </iframe>
    </div>

    <label class="checkbox-visto">
      <input type="checkbox" id="checkVisto">
      <span class="custom-check"></span>
      Marcar como visto
    </label>
    

    <button class="btn-comentar">Comentar</button>
  </section>
</main>

<script>
  const aulas = document.querySelectorAll('.aula');
  const titulo = document.getElementById('tituloAula');
  const video = document.getElementById('videoFrame');
  const checkVisto = document.getElementById('checkVisto');

  // Progresso local
  const progresso = JSON.parse(localStorage.getItem('aulasVistas')) || {};

  // Atualiza visual
  function atualizarStatus() {
    aulas.forEach(aula => {
      const status = aula.querySelector('.status');
      const id = aula.dataset.titulo;
      if (progresso[id]) {
        status.classList.add('visto');
      } else {
        status.classList.remove('visto');
      }
    });

    const ativa = document.querySelector('.aula.ativa');
    if (ativa) {
      const idAtiva = ativa.dataset.titulo;
      checkVisto.checked = !!progresso[idAtiva];
    }
  }

  // Mudar aula
  aulas.forEach(aula => {
    aula.addEventListener('click', e => {
      if (e.target.classList.contains('status')) return; // se clicou na bolinha, ignora

      aulas.forEach(a => a.classList.remove('ativa'));
      aula.classList.add('ativa');

      titulo.textContent = aula.dataset.titulo;
      video.src = aula.dataset.video;
      atualizarStatus();
    });

    // Clicar na bolinha
    const status = aula.querySelector('.status');
    status.addEventListener('click', e => {
      e.stopPropagation();
      const id = aula.dataset.titulo;
      progresso[id] = !progresso[id];
      localStorage.setItem('aulasVistas', JSON.stringify(progresso));
      atualizarStatus();
    });
  });

  // Checkbox sincronizado
  checkVisto.addEventListener('change', () => {
    const ativa = document.querySelector('.aula.ativa');
    if (!ativa) return;
    const id = ativa.dataset.titulo;
    progresso[id] = checkVisto.checked;
    localStorage.setItem('aulasVistas', JSON.stringify(progresso));
    atualizarStatus();
  });

  atualizarStatus();
</script>

</body>
</html>
