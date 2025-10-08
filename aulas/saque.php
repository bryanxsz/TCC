<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: ../login.html");
    exit();
}

include "../conexao.php";

// Módulo atual
$modulo = "saque";

// Busca aulas
$sql = "SELECT * FROM aulas WHERE modulo='$modulo' ORDER BY numero_aula ASC";
$result = $conn->query($sql);

// Se não houver aulas, cria 3 aulas vazias
if ($result->num_rows == 0) {
    for ($i = 1; $i <= 3; $i++) {
        $conn->query("INSERT INTO aulas (modulo, numero_aula, nome_aula, titulo, link_video) VALUES ('$modulo', $i, 'Aula $i', '', '')");
    }
    $result = $conn->query($sql);
}

$aulas = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Aulas - <?php echo ucfirst($modulo); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="aulas.css">
</head>
<body>
<header class="topo">
    <a href="../modulos.php">
        <img src="../imagens/logospike.png" class="logo" alt="Logo">
    </a>
    <h1>Aulas</h1>
    <div class="perfil">
        <img src="../imagens/images.png" class="foto" alt="Foto do usuário">
        <div>
            <strong>
                Olá, <?php echo ($_SESSION['user_tipo'] == '1' ? "Aluno, " : "Professor, ") . htmlspecialchars($_SESSION['user_name']); ?>!
            </strong><br>
            <small><?php echo htmlspecialchars($_SESSION['user_email']); ?></small>
            </small>
            <br>
      <small><a href="logout.php">Sair</a></small>
        </div>
    </div>
</header>

<main class="aulas-container">
    <!-- MENU LATERAL -->
    <aside class="menu-lateral">
        <div class="unidade">
            <h2><?php echo ucfirst($modulo); ?></h2>
            <?php foreach ($aulas as $i => $aula): ?>
                <button class="aula <?php echo $i == 0 ? 'ativa' : ''; ?>" 
                        data-video="<?php echo htmlspecialchars($aula['link_video']); ?>" 
                        data-titulo="<?php echo htmlspecialchars($aula['titulo']); ?>" 
                        data-id="<?php echo $aula['id']; ?>">
                    <?php echo htmlspecialchars($aula['nome_aula']); ?>
                    <span class="status" title="Clique para marcar como visto"></span>
                </button>
            <?php endforeach; ?>
        </div>
    </aside>

    <!-- ÁREA DO VÍDEO -->
    <section class="video-area">
        <h2 id="tituloAula"><?php echo htmlspecialchars($aulas[0]['titulo']); ?></h2>
        <div class="video-box">
            <iframe id="videoFrame" src="<?php echo htmlspecialchars($aulas[0]['link_video']); ?>" 
                    title="Vídeo da Aula" frameborder="0" allowfullscreen></iframe>
        </div>

        <label class="checkbox-visto">
            <input type="checkbox" id="checkVisto">
            <span class="custom-check"></span> Marcar como visto
        </label>

        <?php if ($_SESSION['user_tipo'] == '2'): // Professor ?>
            <a class="btn-editar" id="btnEditar" href="editar_aula.php?id=<?php echo $aulas[0]['id']; ?>">Editar aula atual</a>
        <?php endif; ?>
    </section>
</main>

<script>
const aulas = document.querySelectorAll('.aula');
const titulo = document.getElementById('tituloAula');
const video = document.getElementById('videoFrame');
const checkVisto = document.getElementById('checkVisto');
const btnEditar = document.getElementById('btnEditar');

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
        if (e.target.classList.contains('status')) return;
        aulas.forEach(a => a.classList.remove('ativa'));
        aula.classList.add('ativa');
        titulo.textContent = aula.dataset.titulo;
        video.src = aula.dataset.video;

        if (btnEditar) {
            btnEditar.href = 'editar_aula.php?id=' + aula.dataset.id;
        }
        atualizarStatus();
    });

    // Clicar na bolinha de status
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
