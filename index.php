<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Projeto Spike</title>
  <link rel="stylesheet" href="./css/style-index.css">
  <link rel="icon" href="./imagens/logospike.png" type="image/x-icon">
  <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
</head>

<body>

  <header>

    <nav>
  <div class="nav-logo">
    <img src="./imagens/logospike.png" class="logo" alt="Logo do Projeto Spike" />
  </div>

  <ul class="nav-menu">
    <li><a href="#quemsomos">Quem Somos</a></li>
    <li><a href="#nossosatletas">Nossos Atletas</a></li>
    <li><a href="#nossostreinadores">Nossos Treinadores</a></li>
    <li><a href="#depoimentos">Depoimentos</a></li>
  </ul>

  <div class="nav-cta">
    <a href="login.html" class="btn-comecar">Login</a>
  </div>
</nav>

  </header>

  <main>

 <section class="quemsomos-section" id="quemsomos">
  <div class="carousel-container">
    
    <div class="carousel-slide active">
      <img src="./imagens/equipe-1.png" alt="Time reunido">
      <div class="caption">
        <h3>Como Tudo Começou</h3>
        <p>O Projeto Spike nasceu da paixão de quatro estudantes pelo voleibol e pelo ensino. Inspirados pelos desafios de acesso à educação esportiva de qualidade, decidimos transformar nosso TCC em algo maior.</p>
      </div>
    </div>

    <div class="carousel-slide">
      <img src="./imagens/equipe-2.jpg" alt="Treinamento de salto">
      <div class="caption">
        <h3>O Problema que Enxergamos</h3>
        <p>Percebemos que muitos jovens atletas não têm acesso a treinadores, conteúdos técnicos ou estrutura adequada. Isso limita seu desenvolvimento, principalmente fora dos grandes centros.</p>
      </div>
    </div>

    <div class="carousel-slide">
      <img src="./imagens/equipe-3.jpg" alt="Universitário jogando vôlei">
      <div class="caption">
        <h3>Construindo Soluções</h3>
        <p>Desenvolvemos uma plataforma online com vídeos, textos e feedbacks personalizados. Um espaço para qualquer pessoa evoluir no voleibol, independentemente da sua condição ou localização.</p>
      </div>
    </div>

    <div class="carousel-slide">
      <img src="./imagens/equipe-4.jpg" alt="Palestra">
      <div class="caption">
        <h3>Nosso Propósito</h3>
        <p>Queremos democratizar o ensino do voleibol. Através da tecnologia, levamos orientação técnica acessível a todos e ajudamos a formar atletas mais preparados, confiantes e apaixonados pelo esporte.</p>
      </div>
    </div>

    <button class="carousel-btn prev">&#10094;</button>
    <button class="carousel-btn next">&#10095;</button>
  </div>
</section>



<section class="atletas-section" id="nossosatletas">
  <h2>NOSSOS ATLETAS</h2>
  <div class="atletas-grid">
    <div class="atleta-card">
      <img src="./imagens/jogador-bryan.jpg" alt="Bryan">
      <div class="atleta-info">
        <strong>Bryan</strong>
        <p>Oposto</p>
      </div>
    </div>

    <div class="atleta-card">
      <img src="./imagens/jogador-brissi.jpg" alt="Honorato">
      <div class="atleta-info">
        <strong>Brissi</strong>
        <p>Central</p>
      </div>
    </div>

    <div class="atleta-card">
      <img src="./imagens/jogador-boca.jpg" alt="Cachopa">
      <div class="atleta-info">
        <strong>Boca</strong>
        <p>Libero</p>
      </div>
    </div>

    <div class="atleta-card">
      <img src="./imagens/jogador-laluce.jpg" alt="Lukas Bergmann">
      <div class="atleta-info">
        <strong>Laluce</strong>
        <p>Levantador</p>
      </div>
    </div>
  </div>
</section>

<section id="nossostreinadores" class="nossostreinadores">
    <h2 style=" font-size: 32px; margin-bottom: 40px; font-weight: bold; text-align: center;">NOSSOS TREINADORES</h2>
  <div class="treinador-conteudo">
    <div class="treinador-texto">
      <h3>PROFESSOR</h3>
      <h2>ANDERSON</h2>
      <div class="linha"></div>

      <p>Graduado em Educação Física pela<br>Universidade FEEVALE</p>

      <p>Técnico de voleibol chancelado Nível 2 pela CBV<br>
      <em>(Confederação Brasileira de Vôlei)</em></p>

      <p>Técnico da Sociedade Ginástica Novo Hamburgo<br>
      <em>(Clube tricampeão da superliga)</em></p>

      <p>Especialista em salto vertical e treinamento<br>desportivo</p>

      <p>Treinador de voleibol há mais de 10 anos</p>
    </div>

    <div class="treinador-imagem">
      <img src="./imagens/treinador-anderson.jpg" alt="Professor Cardoso em ação">
    </div>
  </div>
</section>

<section class="depo" id="depoimentos">
  <h2 style="color: white; font-size: 32px; margin-bottom: 40px; font-weight: bold; text-align: center;">
    DEPOIMENTOS (<a href="depoimentos.php">deixe o seu aqui!</a>)
  </h2>


  <div class="carrossel">

    <div class="depoimento" id="depoArea">
      <div class="estrelas" id="estrelas">★★★★★</div>

      <p class="texto" id="texto"> <!-- texto do depoimento vem aqui -->
         teste
      </p>

      <div class="autor">
        <img id="foto" src="imagens/images.png" alt="Avatar" />
        <div class="info">
          <strong id="nome">teste</strong>
          <span id="cargo">teste</span>
        </div>
      </div>

      <div class="base-roxa"></div>
    </div>

  </div>

  <div class="botoes-carrossel">
    <button onclick="mudarDepoimento(-1)" class="botao">
      <i class="fas fa-arrow-left"></i>
    </button>
    <button onclick="mudarDepoimento(1)" class="botao">
      <i class="fas fa-arrow-right"></i>
    </button>
  </div>
</section>


<?php

include 'conexao.php';

$id = 1; 

$sql = "SELECT valor FROM sistema_de_pagamento WHERE id_sistema = $id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $valor = $row['valor'];
} else {
    echo "Nenhum resultado encontrado.";
}

mysqli_close($conn);
?>

<section id="comecar">
  <div class="oferta-box">
    <div class="logo-jump">
      <img src="./imagens/logospike.png" alt="Logo Jump" />
    </div>
    
      <p class="subtitulo">PROMOÇÃO!</p>
      <p class="preco-avista"><strong>R$<?= $valor ?></strong> à vista</p>
      <p class="acesso">Acesso vitalício</p>
     
    </div>

    <div class="botao-comecar">
      <a href="./formulario/cadastrar.html" class="btn-vaga">ADQUIRIR</a>
    </div>
  </section>

  </main>

  <footer>
    <p>&copy; 2025 Projeto Spike. Todos os direitos reservados.</p>
  </footer>

<script>
/*
  Script melhorado para navegar pelos depoimentos ativos.
  Coloque este script no final do documento (antes de </body>).
*/

let depoimentoAtual = 1; // id inicial
const MAX_TENTATIVAS = 10; // proteção contra loop infinito

document.addEventListener("DOMContentLoaded", () => {
  // carrega inicial
  carregarDepoimento(depoimentoAtual);

  // exemplo: ligando botões sem usar onclick inline (caso tenha botões com esses IDs)
  const btnPrev = document.getElementById('btn-prev');
  const btnNext = document.getElementById('btn-next');
  if (btnPrev) btnPrev.addEventListener('click', () => mudarDepoimento(-1));
  if (btnNext) btnNext.addEventListener('click', () => mudarDepoimento(1));

  // tornar a função acessível globalmente caso ainda use onclick inline
  window.mudarDepoimento = mudarDepoimento;
});

async function fetchJson(url) {
  try {
    const resp = await fetch(url, { cache: "no-store" });
    const text = await resp.text();

    // detectar HTML acidental (página de erro ou redirect)
    if (text.trim().startsWith('<')) {
      console.error("Resposta não é JSON (HTML recebido):", text.slice(0,200));
      return null;
    }

    return JSON.parse(text);
  } catch (err) {
    console.error("Erro ao buscar/parsear JSON:", err);
    return null;
  }
}

/**
 * Tenta carregar o depoimento apontado por `id`.
 * Se o depo estiver inativo ou não existir, tenta avançar na mesma direção até achar ativo
 * ou até atingir MAX_TENTATIVAS.
 *
 * @param {number} id - id inicial a tentar
 * @param {number} direcao - 1 para frente, -1 para trás
 * @param {number} tentativas - contador recursivo
 */
async function carregarDepoimento(id, direcao = 1, tentativas = 0) {
  console.log("Tentativa carregar ID:", id, "direcao:", direcao, "tentativas:", tentativas);

  if (tentativas >= MAX_TENTATIVAS) {
    console.warn("Máximo de tentativas atingido. Nenhum depoimento ativo encontrado nesse sentido.");
    return;
  }

  const data = await fetchJson(`get_depoimento.php?id=${encodeURIComponent(id)}`);

  if (!data) {
    // não veio JSON válido — tentar próximo (avançar ou retroceder)
    console.warn("Nenhum JSON válido para ID", id, " tentando próximo.");
    depoimentoAtual = id + direcao;
    // evita IDs < 1
    if (depoimentoAtual < 1) depoimentoAtual = 1;
    await carregarDepoimento(depoimentoAtual, direcao, tentativas + 1);
    return;
  }

  // se o registro não existe (fetch retorna null/array vazio) verifique se há id no objeto
  if (!data.id) {
    console.warn("Nenhum depoimento com ID", id, " tentando próximo.");
    depoimentoAtual = id + direcao;
    if (depoimentoAtual < 1) depoimentoAtual = 1;
    await carregarDepoimento(depoimentoAtual, direcao, tentativas + 1);
    return;
  }

  // se campo ativo existe e não é 1, pula
  const ativo = Number(data.ativo);
  if (ativo !== 1) {
    console.log(`Depoimento ${id} não ativo (ativo=${data.ativo}). Pulando...`);
    depoimentoAtual = id + direcao;
    if (depoimentoAtual < 1) {
      depoimentoAtual = 1;
      console.warn("Chegou ao início da lista, não há depoimentos ativos anteriores.");
      return;
    }
    await carregarDepoimento(depoimentoAtual, direcao, tentativas + 1);
    return;
  }

  // Se chegou aqui, temos um depoimento ativo — atualiza o HTML
  depoimentoAtual = Number(data.id); // garante que o ponteiro aponte pro id real
  console.log("Mostrando depoimento ativo ID:", depoimentoAtual);

  // Atualize os elementos do DOM conforme sua estrutura:
  const elNome = document.getElementById("nome");
  const elCargo = document.getElementById("cargo");
  const elTexto = document.getElementById("texto");
  const elFoto = document.getElementById("foto");
  const elEstrelas = document.getElementById("estrelas");

  if (elNome) elNome.textContent = data.nome ?? "Anônimo";
  if (elCargo) elCargo.textContent = data.cargo ?? "";
  if (elTexto) elTexto.textContent = data.texto ?? "";
  if (elFoto) elFoto.src = data.foto ?? "imagens/images.png";
  if (elEstrelas) {
    const n = Math.max(0, Math.min(5, Number(data.estrelas) || 0));
    elEstrelas.textContent = "⭐".repeat(n);
  }
}

function mudarDepoimento(direcao) {
  // garante que seja número inteiro
  direcao = Number(direcao) >= 0 ? 1 : -1;
  depoimentoAtual += direcao;
  if (depoimentoAtual < 1) depoimentoAtual = 1;
  carregarDepoimento(depoimentoAtual, direcao);
}
</script>






  <script>
  const slides = document.querySelectorAll('.carousel-slide');
  const nextBtn = document.querySelector('.carousel-btn.next');
  const prevBtn = document.querySelector('.carousel-btn.prev');
  let current = 0;

  function showSlide(index) {
    slides.forEach(slide => slide.classList.remove('active'));
    slides[index].classList.add('active');
  }

  nextBtn.addEventListener('click', () => {
    current = (current + 1) % slides.length;
    showSlide(current);
  });

  prevBtn.addEventListener('click', () => {
    current = (current - 1 + slides.length) % slides.length;
    showSlide(current);
  });

  // auto slide every 6s
  setInterval(() => {
    current = (current + 1) % slides.length;
    showSlide(current);
  }, 20000);
</script>

</body>
</html>
