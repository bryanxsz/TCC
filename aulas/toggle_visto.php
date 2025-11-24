<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

include "../conexao.php"; // espera $conn (mysqli)

// checagens básicas
if (!isset($_SESSION['user_name'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$usuario_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
$aula_id = isset($_POST['aula_id']) ? intval($_POST['aula_id']) : 0;
$modulo = isset($_POST['modulo']) ? trim($_POST['modulo']) : "";
$set = isset($_POST['set']) ? ($_POST['set'] === '1' ? 1 : 0) : null;

if (!$aula_id) {
    echo json_encode(['success' => false, 'message' => 'aula_id inválido']);
    exit;
}

if ($modulo === "") {
    // Se você prefere permitir módulo vazio, comente este bloco.
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

// time
$now = date('Y-m-d H:i:s');

// Verifica se já existe registro para esse usuario + aula
if (!($stmt = $conn->prepare("SELECT id, visto FROM aulas_progresso WHERE usuario_id = ? AND aula_id = ?"))) {
    echo json_encode(['success' => false, 'message' => 'Erro prepare SELECT', 'error' => $conn->error]);
    exit;
}
$stmt->bind_param("ii", $usuario_id, $aula_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res === false) {
    echo json_encode(['success' => false, 'message' => 'Erro ao executar SELECT', 'error' => $stmt->error]);
    exit;
}

if ($res->num_rows > 0) {
    // já existe → toggle/update ou delete
    $row = $res->fetch_assoc();
    $current = (int)$row['visto'];

    if ($set === null) {
        $novo = $current ? 0 : 1; // toggle
    } else {
        $novo = $set ? 1 : 0; // set explicito
    }

    if ($novo === 1) {
        // MARCOU COMO VISTO → atualizar visto, data_visto e modulo
        if (!($upd = $conn->prepare("UPDATE aulas_progresso SET visto = ?, data_visto = ?, modulo = ? WHERE id = ?"))) {
            echo json_encode(['success' => false, 'message' => 'Erro prepare UPDATE', 'error' => $conn->error]);
            exit;
        }
        $visto_val = 1;
        $data_visto = $now;
        $id_row = (int)$row['id'];
        $upd->bind_param("issi", $visto_val, $data_visto, $modulo, $id_row);
        $ok = $upd->execute();
        if (!$ok) {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar progresso', 'error' => $upd->error]);
        } else {
            echo json_encode(['success' => true, 'visto' => 1]);
        }
        exit;
    } else {
        // DESMARCOU → deletar registro (mantive isso conforme sua lógica original)
        if (!($del = $conn->prepare("DELETE FROM aulas_progresso WHERE id = ?"))) {
            echo json_encode(['success' => false, 'message' => 'Erro prepare DELETE', 'error' => $conn->error]);
            exit;
        }
        $id_row = (int)$row['id'];
        $del->bind_param("i", $id_row);
        $ok = $del->execute();
        if (!$ok) {
            echo json_encode(['success' => false, 'message' => 'Erro ao deletar progresso', 'error' => $del->error]);
        } else {
            echo json_encode(['success' => true, 'visto' => 0]);
        }
        exit;
    }
} else {
    // não existe ainda → inserir novo registro
    $v = ($set === null) ? 1 : ($set ? 1 : 0);
    $data_visto = $v ? $now : null;

    if (!($ins = $conn->prepare("INSERT INTO aulas_progresso (usuario_id, aula_id, modulo, visto, data_visto) VALUES (?, ?, ?, ?, ?)"))) {
        echo json_encode(['success' => false, 'message' => 'Erro prepare INSERT', 'error' => $conn->error]);
        exit;
    }

    // tipos: i (usuario_id), i (aula_id), s (modulo), i (visto), s (data_visto)
    // OBS: $data_visto pode ser NULL — bind_param aceita variável nula.
    $ins->bind_param("iisis", $usuario_id, $aula_id, $modulo, $v, $data_visto);
    $ok = $ins->execute();
    if ($ok) {
        echo json_encode(['success' => true, 'visto' => (int)$v]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao inserir progresso', 'error' => $ins->error]);
    }
    exit;
}
