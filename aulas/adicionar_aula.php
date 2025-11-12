<?php
session_start();
header('Content-Type: application/json');
include "../conexao.php";

if (!isset($_SESSION['user_name'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$usuario_tipo = isset($_SESSION['user_tipo']) ? $_SESSION['user_tipo'] : null;
if ($usuario_tipo != '2') {
    echo json_encode(['success' => false, 'message' => 'Permissão negada']);
    exit;
}

$modulo = isset($_POST['modulo']) ? $_POST['modulo'] : null;
$nome_aula = isset($_POST['nome_aula']) ? $_POST['nome_aula'] : 'Nova Aula';
$titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
$link_video = isset($_POST['link_video']) ? $_POST['link_video'] : '';
$prof_nome = isset($_POST['professor_nome']) ? $_POST['professor_nome'] : '';
$prof_email = isset($_POST['professor_email']) ? $_POST['professor_email'] : '';
$prof_tel = isset($_POST['professor_telefone']) ? $_POST['professor_telefone'] : '';

if (!$modulo) {
    echo json_encode(['success' => false, 'message' => 'Módulo é obrigatório']);
    exit;
}

// calcula numero_aula = MAX(numero_aula) + 1 dentro do módulo
$stmt = $conn->prepare("SELECT COALESCE(MAX(numero_aula), 0) as maxn FROM aulas WHERE modulo = ?");
$stmt->bind_param("s", $modulo);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$numero = intval($row['maxn']) + 1;

$ins = $conn->prepare("INSERT INTO aulas (modulo, numero_aula, nome_aula, titulo, link_video, professor_nome, professor_email, professor_telefone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$ins->bind_param("sissssss", $modulo, $numero, $nome_aula, $titulo, $link_video, $prof_nome, $prof_email, $prof_tel);
$ok = $ins->execute();

if ($ok) {
    $newId = $conn->insert_id;
    $aula = [
        'id' => $newId,
        'modulo' => $modulo,
        'numero_aula' => $numero,
        'nome_aula' => $nome_aula,
        'titulo' => $titulo,
        'link_video' => $link_video,
        'professor_nome' => $prof_nome,
        'professor_email' => $prof_email,
        'professor_telefone' => $prof_tel
    ];
    echo json_encode(['success' => true, 'aula' => $aula]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao inserir aula']);
}

