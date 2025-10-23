<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: ../login.html");
    exit();
}

include "../conexao.php";

// Módulo atual
$modulo = "central";

// Busca aulas existentes
$sql = "SELECT * FROM aulas WHERE modulo='$modulo' ORDER BY numero_aula ASC";
$result = $conn->query($sql);

// Se não houver aulas, cria 3 aulas padrão
if ($result->num_rows == 0) {
    $aulasPadrao = [
        ['numero_aula' => 1, 'nome_aula' => 'Aula 1', 'titulo' => '', 'link_video' => ''],
        ['numero_aula' => 2, 'nome_aula' => 'Aula 2', 'titulo' => '', 'link_video' => ''],
        ['numero_aula' => 3, 'nome_aula' => 'Aula 3', 'titulo' => '', 'link_video' => ''],
    ];

    foreach ($aulasPadrao as $aula) {
        $stmt = $conn->prepare("INSERT INTO aulas (modulo, numero_aula, nome_aula, titulo, link_video, professor_nome, professor_email, professor_telefone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissssss",
            $modulo,
            $aula['numero_aula'],
            $aula['nome_aula'],
            $aula['titulo'],
            $aula['link_video'],
            $aula['professor_nome'],
            $aula['professor_email'],
            $aula['professor_telefone']
        );
        $stmt->execute();
    }

    // Recarrega as aulas após inserir
    $result = $conn->query("SELECT * FROM aulas WHERE modulo='$modulo' ORDER BY numero_aula ASC");
}

// Carrega todas as aulas
$aulas = $result->fetch_all(MYSQLI_ASSOC);

// Pega a primeira aula como inicial
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

<?php include "topo.php"; ?>

<main class="aulas-container">
    <!-- MENU LATERAL -->
    <aside class="menu-lateral">
        <h2><?php echo ucfirst($modulo); ?></h2>
        <?php foreach ($aulas as $i => $aula): ?>
            <button class="aula <?php echo $i === 0 ? 'ativa' : ''; ?>"
                    data-id="<?php echo $aula['id']; ?>"
                    data-titulo="<?php echo htmlspecialchars($aula['titulo']); ?>"
                    data-video="<?php echo htmlspecialchars($aula['link_video']); ?>"
                    data-professor_telefone="<?php echo htmlspecialchars($aula['professor_telefone']); ?>"
                    data-professor_nome="<?php echo htmlspecialchars($aula['professor_nome']); ?>"
                    data-professor_email="<?php echo htmlspecialchars($aula['professor_email']); ?>">
                <?php echo htmlspecialchars($aula['nome_aula']); ?>
                <span class="status" title="Clique para marcar como visto"></span>
            </button>
        <?php endforeach; ?>
    </aside>

    <!-- ÁREA DO VÍDEO -->

    <?php include "area_video.php" ?>  

</main>

<script src="script.js"></script>

</body>
</html>
