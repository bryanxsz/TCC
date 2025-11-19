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
$set = isset($_POST['set']) ? ($_POST['set'] === '1' ? 1 : 0) : null;

if (!$aula_id) {
    echo json_encode(['success' => false, 'message' => 'aula_id inválido']);
    exit;
}

// Se não houver usuario_id, retornamos erro para indicar que o cliente deve usar fallback
if ($usuario_id === null) {
    // opcional: podemos retornar success false e uma flag para o cliente
    echo json_encode(['success' => false, 'message' => 'Sem usuario_id no servidor', 'server_available' => false]);
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
        // toggle
        $novo = $current ? 0 : 1;
    } else {
        $novo = $set ? 1 : 0;
    }

    if ($novo === 1) {
        // MARCOU → atualizar
        $upd = $conn->prepare("
            UPDATE aulas_progresso 
            SET visto = 1, data_visto = ? 
            WHERE id = ?
        ");
        $upd->bind_param("si", $now, $row['id']);
        $upd->execute();

        echo json_encode(['success' => true, 'visto' => 1]);
        exit;

    } else {
        // DESMARCOU → deletar
        $del = $conn->prepare("
            DELETE FROM aulas_progresso 
            WHERE id = ?
        ");
        $del->bind_param("i", $row['id']);
        $del->execute();

        echo json_encode(['success' => true, 'visto' => 0]);
        exit;
    }



    
    $upd->execute();
    echo json_encode(['success' => true, 'visto' => $novo]);
    exit;
} else {
    // inserir
    $v = ($set === null) ? 1 : ($set ? 1 : 0);
    $ins = $conn->prepare("INSERT INTO aulas_progresso (usuario_id, aula_id, visto, data_visto) VALUES (?, ?, ?, ?)");
    $data_visto = $v ? $now : null;
    $ins->bind_param("iiis", $usuario_id, $aula_id, $v, $data_visto);
    $ok = $ins->execute();
    if ($ok) {
        echo json_encode(['success' => true, 'visto' => (int)$v]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao inserir progresso']);
    }
    exit;
}
