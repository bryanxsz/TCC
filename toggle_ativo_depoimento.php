<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit("Acesso negado");
}

include 'conexao.php';

$id = intval($_GET['id']);

$sql = "UPDATE depoimentos 
        SET ativo = IF(ativo = 1, 0, 1) 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}
