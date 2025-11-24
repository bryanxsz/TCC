<?php
session_start();
include "conexao.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Se existir usuário logado, usa os dados reais
    $nome = $_SESSION['user_name'] ?? "Anônimo";
    $cargo = ($_SESSION['user_tipo'] == 1) ? "Aluno" : "Professor";

    $texto = trim($_POST['depoimento'] ?? '');
    $estrelas = intval($_POST['estrelas'] ?? 5);

    if (empty($texto)) {
        echo "Por favor, escreva seu depoimento.";
        exit;
    }

    $sql = "INSERT INTO depoimentos (nome, cargo, texto, estrelas)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nome, $cargo, $texto, $estrelas);

    if ($stmt->execute()) {
        echo "<script>alert('Depoimento enviado com sucesso!'); window.history.back();</script>";
        exit;
    } else {
        echo "Erro ao enviar depoimento.";
    }

    $stmt->close();
    $conn->close();
}

?>
