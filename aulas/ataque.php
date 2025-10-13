<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: ../login.html");
    exit();
}

include "../conexao.php";

// Módulo atual
$modulo = "ataque";

// Busca aulas
$sql = "SELECT * FROM aulas WHERE modulo='$modulo' ORDER BY numero_aula ASC";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    $aulas = []; // Nenhuma aula cadastrada
} else {
    $aulas = $result->fetch_all(MYSQLI_ASSOC);
}

// Pega a primeira aula como padrão
$aulaInicial = $aulas[0] ?? null;
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
            <small><?php echo htmlspecialchars($_SESSION['user_email']); ?></small><br>
            <small><a href="logout.php">Sair</a></small>
        </div>
    </div>
</header>

<main class="aulas-container">
    <!-- MENU LATERAL -->
    <aside class="menu-lateral">
        <h2><?php echo ucfirst($modulo); ?></h2>
        <?php foreach ($aulas as $i => $aula): ?>
            <button class="aula <?php echo $i === 0 ? 'ativa' : ''; ?>"
                    data-id="<?php echo $aula['id']; ?>"
                    data-titulo="<?php echo htmlspecialchars($aula['titulo']); ?>"
                    data-video="<?php echo htmlspecialchars($aula['link_video']); ?>"
                    data-professor_nome="<?php echo htmlspecialchars($aula['professor_nome']); ?>"
                    data-professor_email="<?php echo htmlspecialchars($aula['professor_email']); ?>">
                <?php echo htmlspecialchars($aula['nome_aula']); ?>
                <span class="status" title="Clique para marcar como visto"></span>
            </button>
        <?php endforeach; ?>
    </aside>

    <!-- ÁREA DO VÍDEO -->
    <section class="video-area">
        <h2 id="tituloAula"><?php echo htmlspecialchars($aulaInicial['titulo'] ?? ''); ?></h2>

        <div class="video-box">
            <iframe id="videoFrame"
                    src="<?php echo htmlspecialchars($aulaInicial['link_video'] ?? ''); ?>"
                    title="Vídeo da Aula"
                    frameborder="0"
                    allowfullscreen>
            </iframe>
        </div>

        <label class="checkbox-visto">
            <input type="checkbox" id="checkVisto">
            <span class="custom-check"></span> Marcar como visto
        </label>

        <?php if ($_SESSION['user_tipo'] == '2' && $aulaInicial): ?>
            <a class="btn-editar"
               id="btnEditar"
               href="editar_aula.php?id=<?php echo $aulaInicial['id']; ?>&voltar=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">
                Editar aula atual
            </a>
        <?php endif; ?>

        <!-- PERFIL DO PROFESSOR -->
        <div class="professor-box" id="professorBox">
            <img class="professor-foto" src="../imagens/images.png" alt="Foto do Professor">
            <div>
                <strong id="professorNome"><?php echo htmlspecialchars($aulaInicial['professor_nome'] ?? 'Professor não definido'); ?></strong><br>
                <small id="professorEmail"><?php echo htmlspecialchars($aulaInicial['professor_email'] ?? ''); ?></small>
            </div>
        </div>
    </section>
</main>

<script>
// Seletores
const aulas = document.querySelectorAll('.aula');
const titulo = document.getElementById('tituloAula');
const video = document.getElementById('videoFrame');
const checkVisto = document.getElementById('checkVisto');
const btnEditar = document.getElementById('btnEditar');
const professorNome = document.getElementById('professorNome');
const professorEmail = document.getElementById('professorEmail');

// Progresso local
const progresso = JSON.parse(localStorage.getItem('aulasVistas')) || {};

// Função para atualizar status visual
function atualizarStatus() {
    aulas.forEach(aula => {
        const status = aula.querySelector('.status');
        const id = aula.dataset.id;
        status.classList.toggle('visto', !!progresso[id]);
    });

    const ativa = document.querySelector('.aula.ativa');
    if (ativa) {
        checkVisto.checked = !!progresso[ativa.dataset.id];
    }
}

// Seleção de aula
aulas.forEach(aula => {
    aula.addEventListener('click', e => {
        if (e.target.classList.contains('status')) return;

        aulas.forEach(a => a.classList.remove('ativa'));
        aula.classList.add('ativa');

        titulo.textContent = aula.dataset.titulo;
        video.src = aula.dataset.video;

        professorNome.textContent = aula.dataset.professor_nome || 'Professor não definido';
        professorEmail.textContent = aula.dataset.professor_email || '';

        if (btnEditar) {
            btnEditar.href = 'editar_aula.php?id=' + aula.dataset.id + '&voltar=' + encodeURIComponent(window.location.pathname);
        }

        atualizarStatus();
    });

    // Clique na bolinha de status
    aula.querySelector('.status').addEventListener('click', e => {
        e.stopPropagation();
        const id = aula.dataset.id;
        progresso[id] = !progresso[id];
        localStorage.setItem('aulasVistas', JSON.stringify(progresso));
        atualizarStatus();
    });
});

// Checkbox sincronizado
checkVisto.addEventListener('change', () => {
    const ativa = document.querySelector('.aula.ativa');
    if (!ativa) return;
    const id = ativa.dataset.id;
    progresso[id] = checkVisto.checked;
    localStorage.setItem('aulasVistas', JSON.stringify(progresso));
    atualizarStatus();
});

// Inicializa status
atualizarStatus();
</script>
</body>
</html>
