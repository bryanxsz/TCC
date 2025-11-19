<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0); // grava erro no log sem mostrar na tela

header("Content-Type: application/json; charset=UTF-8");

include "conexao.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 1;

$sql = $conn->prepare("SELECT * FROM depoimentos WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();
$depo = $result->fetch_assoc();

ob_clean(); // limpa QUALQUER HTML que vazou
echo json_encode($depo);


?>