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

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

// opcional: delete progress rows related
$delProg = $conn->prepare("DELETE FROM aulas_progresso WHERE aula_id = ?");
$delProg->bind_param("i", $id);
$delProg->execute();

// delete aula
$del = $conn->prepare("DELETE FROM aulas WHERE id = ?");
$del->bind_param("i", $id);
$ok = $del->execute();

if ($ok) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao excluir aula']);
}

echo json_encode(["status" => "ok"]);
exit;
