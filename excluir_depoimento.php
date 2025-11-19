<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit("Acesso negado");
}

include 'conexao.php';

$id = intval($_GET['id']);

$sql = "DELETE FROM depoimentos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>
            alert('Depoimento excluído com sucesso!');
            window.location.href='painel_depoimentos.php';
          </script>";
} else {
    echo "<script>
            alert('Erro ao excluir depoimento.');
            window.location.href='painel_depoimentos.php';
          </script>";
}
