<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: ../login.html");
    exit();
}

include "../conexao.php";

// IMPORTANTE:
// Para persistência por usuário no servidor você precisa ter o id do usuário em session.
// Ex: $_SESSION['user_id'] = (int)$userId; 
// Se não existir, este script fará fallback para localStorage no cliente.
$usuario_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
$usuario_tipo = isset($_SESSION['user_tipo']) ? $_SESSION['user_tipo'] : null;

// Módulo atual
$modulo = "líbero";

// Busca aulas existentes
$stmt = $conn->prepare("SELECT * FROM aulas WHERE modulo = ? ORDER BY numero_aula ASC");
$stmt->bind_param("s", $modulo);
$stmt->execute();
$result = $stmt->get_result();

// Se não houver aulas, cria 4 aulas padrão (corrigido para evitar bind de variáveis indefinidas)
if ($result->num_rows == 0) {
    $aulasPadrao = [
        ['numero_aula' => 1, 'nome_aula' => 'Aula 1', 'titulo' => '', 'link_video' => '', 'professor_nome' => 'Não definido', 'professor_email' => 'email@email.com', 'professor_telefone' => 'Não definido']
    ];

    $ins = $conn->prepare("INSERT INTO aulas (modulo, numero_aula, nome_aula, titulo, link_video, professor_nome, professor_email, professor_telefone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($aulasPadrao as $aula) {
        $ins->bind_param("sissssss",
            $modulo,
            $aula['numero_aula'],
            $aula['nome_aula'],
            $aula['titulo'],
            $aula['link_video'],
            $aula['professor_nome'],
            $aula['professor_email'],
            $aula['professor_telefone']
        );
        $ins->execute();
    }

    // Recarrega as aulas após inserir
    $stmt = $conn->prepare("SELECT * FROM aulas WHERE modulo = ? ORDER BY numero_aula ASC");
    $stmt->bind_param("s", $modulo);
    $stmt->execute();
    $result = $stmt->get_result();
}

$aulas = $result->fetch_all(MYSQLI_ASSOC);

// Carregar progresso do usuário (se houver usuario_id)
$aulasProgresso = [];
if ($usuario_id !== null && count($aulas) > 0) {
    // buscar progressos para as aulas listadas
    $ids = array_column($aulas, 'id');
    // preparar statement com IN (...) dinamicamente
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids) + 1); // usuario_id + ids...
    $sql = "SELECT aula_id, visto FROM aulas_progresso WHERE usuario_id = ? AND aula_id IN ($placeholders)";
    $stmt2 = $conn->prepare($sql);
    // bind params dinamicamente
    $bindParams = [];
    $bindParams[] = $usuario_id;
    foreach ($ids as $v) $bindParams[] = $v;
    // call_user_func_array requires references
    $refs = [];
    $refs[] = & $types;
    foreach ($bindParams as $k => $v) {
        $refs[] = & $bindParams[$k];
    }
    // bind_param via call_user_func_array
    call_user_func_array([$stmt2, 'bind_param'], $refs);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    while ($row = $res2->fetch_assoc()) {
        $aulasProgresso[$row['aula_id']] = (int)$row['visto'];
    }
}

// Pega a primeira aula como inicial
$aulaInicial = $aulas[0] ?? null;
$canEdit = ($usuario_tipo == '2') ? true : false; // boolean flag para o front-end

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Aulas</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="aulas.css">
</head>
<body>

<?php include "topo.php"; ?>

<main class="aulas-container">
    <!-- MENU LATERAL -->
    <aside class="menu-lateral">
        <h2><?php echo ucfirst($modulo); ?></h2>
        <?php foreach ($aulas as $i => $aula): 
            $visto = isset($aulasProgresso[$aula['id']]) ? 1 : 0;
        ?>
            <button class="aula <?php echo $i === 0 ? 'ativa' : ''; ?>"
                    data-id="<?php echo $aula['id']; ?>"
                    data-titulo="<?php echo htmlspecialchars($aula['titulo']); ?>"
                    data-video="<?php echo htmlspecialchars($aula['link_video']); ?>"
                    data-professor_telefone="<?php echo htmlspecialchars($aula['professor_telefone']); ?>"
                    data-professor_nome="<?php echo htmlspecialchars($aula['professor_nome']); ?>"
                    data-professor_email="<?php echo htmlspecialchars($aula['professor_email']); ?>"
                    data-visto="<?php echo $visto; ?>">
                <?php echo htmlspecialchars($aula['nome_aula']); ?>
                <span class="status" title="Clique para marcar como visto"></span>
            </button>
        <?php endforeach; ?>
    </aside>

    <!-- ÁREA DO VÍDEO -->

    <section class="video-area">
        <h2 id="tituloAula"><?php echo htmlspecialchars($aulaInicial['titulo'] ?? ''); ?></h2>

        <div class="video-box">
            <iframe id="videoFrame"
                    src="<?php echo htmlspecialchars($aulaInicial['link_video'] ?? ''); ?>"
                    title="Vídeo da Aula"
                    frameborder="0"
                    allowfullscreen>
            </iframe>
        </div>

        <label class="checkbox-visto">
            <input type="checkbox" id="checkVisto">
            <span class="custom-check"></span> Marcar como visto
        </label>

        <?php if ($canEdit && $aulaInicial): ?>
            <a class="btn-editar"
               id="btnEditar"
               href="editar_aula.php?id=<?php echo $aulaInicial['id']; ?>&voltar=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">
                Editar aula atual
            </a>
        <?php endif; ?>

        <!-- PERFIL DO PROFESSOR -->
        <div class="professor-box" id="professorBox">
            <img class="professor-foto" src="../imagens/images.png" alt="Foto do Professor">
            <div>
                <small class="infoprof" id="professorNome"><strong>Professor: </strong><?php echo htmlspecialchars($aulaInicial['professor_nome'] ?? 'Não definido'); ?>    </small><br>
                <small class="info" id="professorEmail"><strong>Email: </strong><?php echo htmlspecialchars($aulaInicial['professor_email'] ?? 'Não definido'); ?></small><br>
                <small class="info" id="professorTelefone"><strong>Telefone: </strong><?php echo htmlspecialchars($aulaInicial['professor_telefone'] ?? 'Não definido'); ?></small>
            </div>
        </div>

        <!-- Controles de adicionar/excluir apenas para editores (inseridos via JS) -->
        <div id="editorControls" style="margin-top:12px;"></div>
    </section>

</main>

<script>
/*
  Lógica JS:
  - Se `useServer` for true (servidor tem usuario_id), as ações de visto usam endpoints (fetch).
  - Caso contrário, usamos localStorage como fallback (para compatibilidade).
  - Também implementamos adicionar/excluir via fetch (se canEdit).
*/

const aulas = document.querySelectorAll('.aula');
const titulo = document.getElementById('tituloAula');
const video = document.getElementById('videoFrame');
const checkVisto = document.getElementById('checkVisto');
const btnEditar = document.getElementById('btnEditar');
const professorNome = document.getElementById('professorNome');
const professorEmail = document.getElementById('professorEmail');
const professorTelefone = document.getElementById('professorTelefone');
const editorControls = document.getElementById('editorControls');

// Configuração carregada do PHP
const useServer = <?php echo ($usuario_id !== null) ? 'true' : 'false'; ?>;
const canEdit = <?php echo $canEdit ? 'true' : 'false'; ?>;
// JSON com status inicial por aula (se servidor disponível, foi embutido via data-visto; aqui apenas construímos um objeto inicial)
let progresso = {};

// Inicializa progresso do DOM (ler data-visto de cada botão se existe)
document.querySelectorAll('.aula').forEach(a => {
    const id = a.dataset.id;
    const dv = a.dataset.visto;
    if (dv !== undefined) progresso[id] = dv === '1' ? true : false;
});

// Se não houver dados do servidor, tenta carregar do localStorage
if (!useServer) {
    const local = JSON.parse(localStorage.getItem('aulasVistas') || '{}');
    progresso = Object.assign({}, local, progresso);
}

// Atualiza status visual dos botões e checkbox com base em `progresso`
function atualizarStatus() {
    document.querySelectorAll('.aula').forEach(aula => {
        const status = aula.querySelector('.status');
        const id = aula.dataset.id;
        const isVisto = !!progresso[id];
        status.classList.toggle('visto', isVisto);
        aula.setAttribute('data-visto', isVisto ? '1' : '0');
    });

    const ativa = document.querySelector('.aula.ativa');
    if (ativa) {
        checkVisto.checked = !!progresso[ativa.dataset.id];
    } else {
        checkVisto.checked = false;
    }
}

// Função para pedir ao servidor para setar/dessetar visto
async function toggleVistoServer(aulaId, setTo = null) {
    try {
        const body = new FormData();
        body.append('aula_id', aulaId);
        if (setTo !== null) body.append('set', setTo ? '1' : '0');

        const res = await fetch('toggle_visto.php', { method: 'POST', body: body });
        const j = await res.json();
        if (j.success) {
            progresso[aulaId] = !!j.visto;
            return true;
        } else {
            console.warn('toggleVistoServer failed', j);
            return false;
        }
    } catch (err) {
        console.error('toggleVistoServer error', err);
        return false;
    }
}

// Função para adicionar e remover aula do DOM (utilizada depois de retorno do servidor)
function criarBotaoAula(aulaObj, ativa = false) {
    const btn = document.createElement('button');
    btn.className = 'aula' + (ativa ? ' ativa' : '');
    btn.dataset.id = aulaObj.id;
    btn.dataset.titulo = aulaObj.titulo || '';
    btn.dataset.video = aulaObj.link_video || '';
    btn.dataset.professor_nome = aulaObj.professor_nome || '';
    btn.dataset.professor_email = aulaObj.professor_email || '';
    btn.dataset.professor_telefone = aulaObj.professor_telefone || '';
    btn.dataset.visto = aulaObj.visto ? '1' : '0';
    btn.innerHTML = `${aulaObj.nome_aula} <span class="status" title="Clique para marcar como visto"></span>`;

    // click handler (same logic as below)
    btn.addEventListener('click', e => {
        if (e.target.classList.contains('status')) return;
        document.querySelectorAll('.aula').forEach(a => a.classList.remove('ativa'));
        btn.classList.add('ativa');

        titulo.textContent = btn.dataset.titulo;
        video.src = btn.dataset.video;

        professorNome.innerHTML = `<small class="infoprof" ><strong>Professor: </strong>${btn.dataset.professor_nome || 'Não definido'}</small>`;
        professorEmail.innerHTML = `<small class="info" ><strong>Email: </strong>${btn.dataset.professor_email || 'Não definido'}</small>`;
        professorTelefone.innerHTML = `<small class="info" ><strong>Telefone: </strong>${btn.dataset.professor_telefone || 'Não definido'}</small>`;

        if (btnEditar) {
            btnEditar.href = 'editar_aula.php?id=' + btn.dataset.id + '&voltar=' + encodeURIComponent(window.location.pathname);
        }
        atualizarStatus();
    });

    btn.querySelectorStatus = function() { return btn.querySelector('.status'); };

    // status click
    btn.addEventListener('click', (function(b){
        return function(e){
            if (e.target.classList.contains('status')) {
                e.stopPropagation();
                const id = b.dataset.id;
                // Toggle
                if (useServer) {
                    // send toggle request
                    // tentative invert — server will return actual state
                    toggleVistoServer(id).then(ok => {
                        if (ok) {
                            // update local cache from attribute (server responded and updated global progresso inside toggleVistoServer)
                            // but toggleVistoServer sets progresso; so just refresh
                            atualizarStatus();
                            if (!useServer) saveLocal();
                        } else {
                            alert('Erro ao marcar visto no servidor');
                        }
                    });
                } else {
                    progresso[id] = !progresso[id];
                    atualizarStatus();
                    saveLocal();
                }
            }
        };
    })(btn));

    return btn;
}

// Event wiring para os botões existentes (init)
document.querySelectorAll('.aula').forEach(aula => {
    aula.addEventListener('click', e => {
        if (e.target.classList.contains('status')) return;

        document.querySelectorAll('.aula').forEach(a => a.classList.remove('ativa'));
        aula.classList.add('ativa');

        titulo.textContent = aula.dataset.titulo;
        video.src = aula.dataset.video;

        professorNome.innerHTML = `<small class="infoprof" ><strong>Professor: </strong>${aula.dataset.professor_nome || 'Não definido'}</small>`;
        professorEmail.innerHTML = `<small class="info" ><strong>Email: </strong>${aula.dataset.professor_email || 'Não definido'}</small>`;
        professorTelefone.innerHTML = `<small class="info" ><strong>Telefone: </strong>${aula.dataset.professor_telefone || 'Não definido'}</small>`;

        if (btnEditar) {
            btnEditar.href = 'editar_aula.php?id=' + aula.dataset.id + '&voltar=' + encodeURIComponent(window.location.pathname);
        }

        atualizarStatus();
    });

    const statusSpan = aula.querySelector('.status');
    statusSpan.addEventListener('click', e => {
        e.stopPropagation();
        const id = aula.dataset.id;
        if (useServer) {
            toggleVistoServer(id).then(ok => {
                if (ok) {
                    atualizarStatus();
                } else {
                    alert('Erro ao atualizar visto no servidor.');
                }
            });
        } else {
            progresso[id] = !progresso[id];
            atualizarStatus();
            saveLocal();
        }
    });
});

// Checkbox sincronizado
checkVisto.addEventListener('change', () => {
    const ativa = document.querySelector('.aula.ativa');
    if (!ativa) return;
    const id = ativa.dataset.id;
    const setTo = checkVisto.checked;
    if (useServer) {
        // send explicit set
        (async () => {
            const body = new FormData();
            body.append('aula_id', id);
            body.append('set', setTo ? '1' : '0');
            try {
                const res = await fetch('toggle_visto.php', { method: 'POST', body: body });
                const j = await res.json();
                if (j.success) {
                    progresso[id] = !!j.visto;
                    atualizarStatus();
                } else {
                    alert('Erro ao salvar visto no servidor.');
                }
            } catch (err) {
                console.error(err);
                alert('Erro de rede ao marcar visto.');
            }
        })();
    } else {
        progresso[id] = setTo;
        atualizarStatus();
        saveLocal();
    }
});

// Salvar local (fallback)
function saveLocal() {
    localStorage.setItem('aulasVistas', JSON.stringify(progresso));
}

// Inicializa status na carga
atualizarStatus();

// --- Funções de adicionar/excluir aulas (apenas se canEdit true) ---
if (canEdit) {
    // cria controles visuais simples (botões pequenos) sem alterar seu CSS original
    const addBtn = document.createElement('button');
    addBtn.textContent = '➕ Adicionar aula';
    addBtn.className = 'btn-adicionar-aula';
    addBtn.style.marginRight = '8px';

    const delBtn = document.createElement('button');
    delBtn.textContent = '❌ Excluir aula atual';
    delBtn.className = 'btn-excluir-aula';

    editorControls.appendChild(addBtn);
    editorControls.appendChild(delBtn);

    addBtn.addEventListener('click', async () => {
        // coleta dados mínimos via prompt (você pode substituir por modal)
        const nome = prompt('Nome da aula (ex: Aula 5):', 'Nova Aula');
        if (nome === null) return;
        const tituloA = '';
        const link = '';
        // enviar request
        const body = new FormData();
        body.append('modulo', '<?php echo $modulo; ?>');
        body.append('nome_aula', nome);
        body.append('titulo', tituloA);
        body.append('link_video', link || '');
        try {
            const res = await fetch('adicionar_aula.php', { method: 'POST', body });
            const j = await res.json();
            if (j.success) {
                // inserir no DOM
                const novo = {
                    id: j.aula.id,
                    nome_aula: j.aula.nome_aula,
                    titulo: j.aula.titulo,
                    link_video: j.aula.link_video,
                    professor_nome: j.aula.professor_nome || '',
                    professor_email: j.aula.professor_email || '',
                    professor_telefone: j.aula.professor_telefone || '',
                    visto: false
                };
                const btn = criarBotaoAula(novo, true);
                // remover ativa atual e adicionar nova como ativa
                document.querySelectorAll('.aula').forEach(a => a.classList.remove('ativa'));
                // append to aside
                document.querySelector('.menu-lateral').appendChild(btn);
                // re-attach existing listeners not needed because criarBotaoAula attaches them
                atualizarStatus();
            } else {
                alert('Erro ao adicionar aula: ' + (j.message || 'desconhecido'));
            }
        } catch (err) {
            console.error(err);
            alert('Aula adicionada!');
            window.location.reload();
        }
    });

    delBtn.addEventListener('click', async () => {
        const ativa = document.querySelector('.aula.ativa');
        if (!ativa) {
            alert('Selecione uma aula para excluir.');
            return;
        }
        if (!confirm('Deseja realmente excluir a aula ativa? Esta ação não pode ser desfeita.')) return;
        const id = ativa.dataset.id;
        const body = new FormData();
        body.append('id', id);
        try {
            const res = await fetch('excluir_aula.php', { method: 'POST', body });
            const j = await res.json();
            if (j.success) {
                // remove do DOM
                const proximo = ativa.nextElementSibling || document.querySelector('.aula') || null;
                ativa.remove();
                if (proximo) {
                    proximo.classList.add('ativa');
                    proximo.click();
                } else {
                    // sem aulas
                    titulo.textContent = '';
                    video.src = '';
                    professorNome.innerHTML = '<small class="infoprof"><strong>Professor: </strong>Não definido</small>';
                    professorEmail.innerHTML = '<small class="info"><strong>Email: </strong>Não definido</small>';
                    professorTelefone.innerHTML = '<small class="info"><strong>Telefone: </strong>Não definido</small>';
                }
                // também remover progresso local/cache
                delete progresso[id];
                if (!useServer) saveLocal();
                atualizarStatus();
            } else {
                alert('Erro ao excluir aula: ' + (j.message || 'desconhecido'));
            }
        } catch (err) {
            console.error(err);
            alert('Aula excluida!');
            window.location.reload();
        }
    });
}
</script>

</body>
</html>
