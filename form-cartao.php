<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Demo Cartão</title>
<link rel="icon" href="./imagens/logospike.png" type="image/x-icon">
<style>
  :root{
    --card-w:320px;
    --card-h:190px;
    --radius:12px;
    --accent: linear-gradient(135deg,#3b82f6,#06b6d4);
    --muted:#6b7280;
    font-family: Arial, Helvetica, sans-serif;
  }

  body{
    margin:0;
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    background-color: #ffc446;  
    padding:30px;
  }

  .wrap{
    display:flex;
    gap:28px;
    align-items:flex-start;
    max-width:900px;
    width:100%;
  }

  /* cartão */
  .card{
    width:var(--card-w);
    height:var(--card-h);
    border-radius:var(--radius);
    background: #400000;;
    color:#fff;
    padding:18px;
    box-shadow:0 12px 30px rgba(2,6,23,.12);
    display:flex;
    flex-direction:column;
    justify-content:space-between;
  }
  .bank{font-weight:700}
  .card-number{font-family:monospace;font-size:18px;letter-spacing:3px}
  .card-row{display:flex;justify-content:space-between;align-items:center}
  .small{font-size:12px;opacity:.9}

  /* formulário */
  .form{
    background:#fff;
    padding:16px;
    border-radius:10px;
    box-shadow:0 8px 20px rgba(2,6,23,.06);
    width:400px;
  }
  .form h2{margin:0 0 10px 0;font-size:18px}
  label{display:block;font-size:13px;color:#374151;margin-top:8px}
  input[type=text]{
    width:90%;
    padding:9px 10px;
    margin-top:6px;
    border-radius:8px;
    border:1px solid #e6e9ef;
    font-size:14px;
  }
  .row{display:flex;gap:10px}
  .small-input{width:110px}
  .muted{font-size:12px;color:var(--muted);margin-top:8px}

  /* botão */
  button{
    margin-top:14px;
    width:100%;
    padding:10px;
    border:none;
    border-radius:8px;
    background:linear-gradient(#ffcc33, #ff6600);
    color:#fff;
    font-size:15px;
    cursor:pointer;
  }
  button:hover{
    background:linear-gradient(#ffa500, #e65c00);
  }

  @media(max-width:780px){
    .wrap{flex-direction:column;align-items:center}
  }
</style>
</head>
<body>

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
if (!$row || $row['ativo'] == 1 || $row['tipo'] == 2) {
    header('Location: modulos.php');
    exit;
}elseif ($row['tipo'] == 3) {
  header('Location: painel.php');
  exit;
}

?>



  <div class="wrap">
    <!-- Cartão -->
    <div class="card" aria-hidden="true">
      <div class="card-row">
        <div class="bank">Meu Banco</div>
        <div class="small">VISA</div>
      </div>

      <div class="card-number">#### #### #### ####</div>

      <div class="card-row">
        <div>
          <div class="small">Titular</div>
          <div><strong>SEU NOME AQUI</strong></div>
        </div>
        <div>
          <div class="small">Validade</div>
          <div><strong>MM/AA</strong></div>
        </div>
      </div>
    </div>

    <!-- Formulário -->
    <form class="form" method="POST" action="processa-pagamento.php">
  <h2>Dados do cartão (demo)</h2>

  <label for="number">Número do cartão</label>
  <input id="number" name="number" type="text" placeholder="1234 5678 9012 3456" maxlength="16">

  <label for="name">Nome do titular</label>
  <input id="name" name="name" type="text" placeholder="FULANO DA SILVA" 
         style="text-transform: uppercase;" pattern="[A-Za-zÀ-ÿ\s]+" maxlength="40">

  <div class="row">
    <div style="flex:1">
      <label for="expiry">Validade (MM/AA)</label>
      <input id="expiry" name="expiry" type="text" placeholder="MM/AA" maxlength="5">
    </div>
    <div class="small-input">
      <label for="cvv">CVV</label>
      <input id="cvv" name="cvv" type="text" placeholder="123" maxlength="4">
    </div>
  </div>

  <button type="submit">Enviar</button>
</form>

    </form>
  </div>

</body>
</html>
