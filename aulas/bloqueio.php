<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: ../login.html");
    exit();
}

include "../conexao.php";

// IMPORTANTE:
// Para persistência por usuário no servidor você precisa ter o id do usuário em session.
// Ex: $_SESSION['user_id'] = (int)$userId; 
// Se não existir, este script fará fallback para localStorage no cliente.
$usuario_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
$usuario_tipo = isset($_SESSION['user_tipo']) ? $_SESSION['user_tipo'] : null;

// Módulo atual
$modulo = "bloqueio";

// Busca aulas existentes
$stmt = $conn->prepare("SELECT * FROM aulas WHERE modulo = ? ORDER BY numero_aula ASC");
$stmt->bind_param("s", $modulo);
$stmt->execute();
$result = $stmt->get_result();

// Se não houver aulas, cria 4 aulas padrão (corrigido para evitar bind de variáveis indefinidas)
if ($result->num_rows == 0) {
    $aulasPadrao = [
        ['numero_aula' => 1, 'nome_aula' => 'Aula 1', 'titulo' => '', 'link_video' => '', 'professor_nome' => 'Não definido', 'professor_email' => 'email@email.com', 'professor_telefone' => 'Não definido']
    ];

    $ins = $conn->prepare("INSERT INTO aulas (modulo, numero_aula, nome_aula, titulo, link_video, professor_nome, professor_email, professor_telefone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($aulasPadrao as $aula) {
        $ins->bind_param("sissssss",
            $modulo,
            $aula['numero_aula'],
            $aula['nome_aula'],
            $aula['titulo'],
            $aula['link_video'],
            $aula['professor_nome'],
            $aula['professor_email'],
            $aula['professor_telefone']
        );
        $ins->execute();
    }

    // Recarrega as aulas após inserir
    $stmt = $conn->prepare("SELECT * FROM aulas WHERE modulo = ? ORDER BY numero_aula ASC");
    $stmt->bind_param("s", $modulo);
    $stmt->execute();
    $result = $stmt->get_result();
}

$aulas = $result->fetch_all(MYSQLI_ASSOC);

// Carregar progresso do usuário (se houver usuario_id)
$aulasProgresso = [];
if ($usuario_id !== null && count($aulas) > 0) {
    // buscar progressos para as aulas listadas
    $ids = array_column($aulas, 'id');
    // preparar statement com IN (...) dinamicamente
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids) + 1); // usuario_id + ids...
    $sql = "SELECT aula_id, visto FROM aulas_progresso WHERE usuario_id = ? AND aula_id IN ($placeholders)";
    $stmt2 = $conn->prepare($sql);
    // bind params dinamicamente
    $bindParams = [];
    $bindParams[] = $usuario_id;
    foreach ($ids as $v) $bindParams[] = $v;
    // call_user_func_array requires references
    $refs = [];
    $refs[] = & $types;
    foreach ($bindParams as $k => $v) {
        $refs[] = & $bindParams[$k];
    }
    // bind_param via call_user_func_array
    call_user_func_array([$stmt2, 'bind_param'], $refs);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    while ($row = $res2->fetch_assoc()) {
        $aulasProgresso[$row['aula_id']] = (int)$row['visto'];
    }
}

// Pega a primeira aula como inicial
$aulaInicial = $aulas[0] ?? null;
$canEdit = ($usuario_tipo == '2') ? true : false; // boolean flag para o front-end

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Aulas</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="aulas.css">
</head>
<body>

<?php include "topo.php"; ?>

<main class="aulas-container">
    <!-- MENU LATERAL -->
    <aside class="menu-lateral">
        <h2><?php echo ucfirst($modulo); ?></h2>
        <?php foreach ($aulas as $i => $aula): 
            $visto = isset($aulasProgresso[$aula['id']]) ? 1 : 0;
        ?>
            <button class="aula <?php echo $i === 0 ? 'ativa' : ''; ?>"
                    data-id="<?php echo $aula['id']; ?>"
                    data-titulo="<?php echo htmlspecialchars($aula['titulo']); ?>"
                    data-video="<?php echo htmlspecialchars($aula['link_video']); ?>"
                    data-professor_telefone="<?php echo htmlspecialchars($aula['professor_telefone']); ?>"
                    data-professor_nome="<?php echo htmlspecialchars($aula['professor_nome']); ?>"
                    data-professor_email="<?php echo htmlspecialchars($aula['professor_email']); ?>"
                    data-visto="<?php echo $visto; ?>">
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

        <?php if ($canEdit && $aulaInicial): ?>
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
                <small class="infoprof" id="professorNome"><strong>Professor: </strong><?php echo htmlspecialchars($aulaInicial['professor_nome'] ?? 'Não definido'); ?>    </small><br>
                <small class="info" id="professorEmail"><strong>Email: </strong><?php echo htmlspecialchars($aulaInicial['professor_email'] ?? 'Não definido'); ?></small><br>
                <small class="info" id="professorTelefone"><strong>Telefone: </strong><?php echo htmlspecialchars($aulaInicial['professor_telefone'] ?? 'Não definido'); ?></small>
            </div>
        </div>

        <!-- Controles de adicionar/excluir apenas para editores (inseridos via JS) -->
        <div id="editorControls" style="margin-top:12px;"></div>
    </section>

</main>

<script>
    window.PHP_CONFIG = {
        useServer: <?php echo ($usuario_id !== null) ? 'true' : 'false'; ?>,
        canEdit: <?php echo $canEdit ? 'true' : 'false'; ?>,
        modulo: "<?php echo $modulo; ?>"
    };
</script>

<script src="script.js"></script>


</body>
</html>
