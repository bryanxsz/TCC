<?php
// Impede QUALQUER saída antes do JSON
ob_start();

// Força PHP a não mostrar erros na tela (evita HTML no JSON)
error_reporting(E_ALL);
ini_set('display_errors', 0);

header("Content-Type: application/json; charset=UTF-8");

include "conexao.php";

// Garante que conexão não gere output (só pra paranoias)
ob_clean();

// Pegando o ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Prepara
$sql = $conn->prepare("SELECT id, nome, cargo, texto, estrelas, ativo FROM depoimentos WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();
$depo = $result->fetch_assoc();

// Se não encontrou nada → devolve objeto vazio, mas ainda JSON
if (!$depo) {
    echo json_encode([
        "id" => null
    ]);
    exit;
}

// Limpa QUALQUER coisa que tenha vazado até aqui
ob_clean();

// Devolve 100% JSON puro
echo json_encode($depo, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
exit;
?>
