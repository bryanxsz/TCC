<?php
session_start();
include 'conexao.php';

// Garante que o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Erro: usuário não logado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = strtoupper(trim($_POST['name'] ?? ''));

    if ($nome === "APRO") {
        // Atualiza o usuário logado
        $id = $_SESSION['user_id'];
        $sql = "UPDATE usuario SET ativo = 1 WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        echo "<script>
                alert('Compra realizada com sucesso!');
                window.location.href = 'modulos.php';
              </script>";
        exit;
    } elseif ($nome === "RECU") {
        echo "<script>
                alert('Erro na compra! Tente novamente.');
                window.history.back();
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Nome inválido para teste (use APRO ou RECU).');
                window.history.back();
              </script>";
        exit;
    }
}
?>
