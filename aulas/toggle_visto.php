<?php
session_start();
header('Content-Type: application/json');
include "../conexao.php";

if (!isset($_SESSION['user_name'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$usuario_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
$aula_id = isset($_POST['aula_id']) ? intval($_POST['aula_id']) : 0;
$modulo = isset($_POST['modulo']) ? trim($_POST['modulo']) : ""; // 🔥 recebendo nome do módulo
$set = isset($_POST['set']) ? ($_POST['set'] === '1' ? 1 : 0) : null;

if (!$aula_id) {
    echo json_encode(['success' => false, 'message' => 'aula_id inválido']);
    exit;
}

if ($modulo === "") {
    echo json_encode(['success' => false, 'message' => 'módulo não informado']);
    exit;
}

if ($usuario_id === null) {
    echo json_encode([
        'success' => false,
        'message' => 'Sem usuario_id no servidor',
        'server_available' => false
    ]);
    exit;
}

// Verifica se já existe registro
$stmt = $conn->prepare("SELECT id, visto FROM aulas_progresso WHERE usuario_id = ? AND aula_id = ?");
$stmt->bind_param("ii", $usuario_id, $aula_id);
$stmt->execute();
$res = $stmt->get_result();

$now = date('Y-m-d H:i:s');

if ($res->num_rows > 0) {

    $row = $res->fetch_assoc();
    $current = (int)$row['visto'];

    if ($set === null) {
        $novo = $current ? 0 : 1;
    } else {
        $novo = $set ? 1 : 0;
    }

    if ($novo === 1) {
        // MARCOU COMO VISTO → atualizar visto + data + módulo
        $upd = $conn->prepare("
            UPDATE aulas_progresso 
            SET visto = 1, data_visto = ?, modulo = ?
            WHERE id = ?
        ");
        $upd->bind_param("ssi", $now, $modulo, $row['id']);
        $upd->execute();

        echo json_encode(['success' => true, 'visto' => 1]);
        exit;

    } else {
        // DESMARCOU → deletar registro
        $del = $conn->prepare("DELETE FROM aulas_progresso WHERE id = ?");
        $del->bind_param("i", $row['id']);
        $del->execute();

        echo json_encode(['success' => true, 'visto' => 0]);
        exit;
    }

} else {

    // INSERIR NOVO REGISTRO
    $v = ($set === null) ? 1 : ($set ? 1 : 0);
    $data_visto = $v ? $now : null;

    $ins = $conn->prepare("
        INSERT INTO aulas_progresso (usuario_id, aula_id, modulo, visto, data_visto) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $ins->bind_param("iisis", $usuario_id, $aula_id, $modulo, $v, $data_visto);

    $ok = $ins->execute();

    if ($ok) {
        echo json_encode(['success' => true, 'visto' => (int)$v]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao inserir progresso']);
    }

    exit;
}
