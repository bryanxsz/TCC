<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Usuário não logado
    header('Location: login.html');
    exit;
}

include 'conexao.php';
$id = $_SESSION['user_id'];
$sql = "SELECT ativo, tipo FROM usuario WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Se usuário já é ativo ou é professor (tipo = 2), redireciona
if ($row['tipo'] == 3) {

}else {
    echo "<script>
                alert('Acesso NEGADO!');
                window.history.back();
              </script>";
        exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM usuario WHERE id_usuario = $id";
    $conn->query($sql);
}
header("Location: painel.php");
exit();
?>
