// ----------- PEGAR CONFIG DO PHP -----------
const { useServer, canEdit, modulo } = window.PHP_CONFIG;

// ----------- VARIÁVEIS INICIAIS -----------
const aulas = document.querySelectorAll('.aula');
const titulo = document.getElementById('tituloAula');
const video = document.getElementById('videoFrame');
const checkVisto = document.getElementById('checkVisto');
const btnEditar = document.getElementById('btnEditar');
const professorNome = document.getElementById('professorNome');
const professorEmail = document.getElementById('professorEmail');
const professorTelefone = document.getElementById('professorTelefone');
const editorControls = document.getElementById('editorControls');

let progresso = {};


// ----------- INICIALIZA STATUS DO DOM -----------
document.querySelectorAll('.aula').forEach(a => {
    const id = a.dataset.id;
    progresso[id] = a.dataset.visto === '1';
});

// Fallback localStorage se servidor indisponível
if (!useServer) {
    const local = JSON.parse(localStorage.getItem('aulasVistas') || '{}');
    progresso = Object.assign({}, progresso, local);
}


// ----------- ATUALIZA A INTERFACE -----------
function atualizarStatus() {
    document.querySelectorAll('.aula').forEach(aula => {
        const id = aula.dataset.id;
        const visto = !!progresso[id];

        aula.dataset.visto = visto ? '1' : '0';
        aula.querySelector('.status').classList.toggle('visto', visto);
    });

    const ativa = document.querySelector('.aula.ativa');
    checkVisto.checked = ativa ? !!progresso[ativa.dataset.id] : false;
}


// ----------- SALVA LOCAL -----------
function saveLocal() {
    localStorage.setItem('aulasVistas', JSON.stringify(progresso));
}


// ----------- REQUISIÇÃO AO SERVIDOR -----------
async function toggleVistoServer(aulaId, setTo = null) {
    try {
        const body = new FormData();
        body.append('aula_id', aulaId);
        body.append('modulo', modulo);

        if (setTo !== null) body.append('set', setTo ? '1' : '0');

        const res = await fetch('toggle_visto.php', { method: 'POST', body });
        const j = await res.json();

        if (j.success) {
            progresso[aulaId] = !!j.visto;
            return true;
        }
        return false;

    } catch (err) {
        window.location.reload();
        return false;
    }
}


// ----------- MARCAR/DESMARCAR VISTO ----------- 
async function marcarVisto(aulaId, setTo = null) {

    if (useServer) {
        const ok = await toggleVistoServer(aulaId, setTo);
        if (!ok) {
            window.location.reload();
            return;
        }
    } else {
        if (setTo === null) progresso[aulaId] = !progresso[aulaId];
        else progresso[aulaId] = setTo;
        saveLocal();
    }

    atualizarStatus();
}


// ----------- CLICAR EM UMA AULA -----------
document.querySelectorAll('.aula').forEach(aula => {

    aula.addEventListener('click', e => {
        if (e.target.classList.contains('status')) return;

        document.querySelectorAll('.aula').forEach(a => a.classList.remove('ativa'));
        aula.classList.add('ativa');

        titulo.textContent = aula.dataset.titulo;
        video.src = aula.dataset.video;

        professorNome.innerHTML = `<small class="infoprof"><strong>Professor: </strong>${aula.dataset.professor_nome || 'Não definido'}</small>`;
        professorEmail.innerHTML = `<small class="info"><strong>Email: </strong>${aula.dataset.professor_email || 'Não definido'}</small>`;
        professorTelefone.innerHTML = `<small class="info"><strong>Telefone: </strong>${aula.dataset.professor_telefone || 'Não definido'}</small>`;

        if (btnEditar) {
            btnEditar.href = 'editar_aula.php?id=' + aula.dataset.id +
                '&voltar=' + encodeURIComponent(window.location.pathname);
        }

        atualizarStatus();
    });
});


// ----------- CLICAR NO STATUS (BOTÃO DE VISTO) -----------
document.querySelectorAll('.aula .status').forEach(status => {
    status.addEventListener('click', async e => {
        e.stopPropagation();
        const aula = status.closest('.aula');
        await marcarVisto(aula.dataset.id);
    });
});


// ----------- CHECKBOX LATERAL -----------
checkVisto.addEventListener('change', async () => {
    const ativa = document.querySelector('.aula.ativa');
    if (!ativa) return;

    await marcarVisto(ativa.dataset.id, checkVisto.checked);
});


// ----------- INICIALIZA INTERFACE -----------
atualizarStatus();


// ----------- ADICIONAR / REMOVER AULA (MODO EDITOR) -----------
if (canEdit) {
    const addBtn = document.createElement('button');
    addBtn.textContent = '➕ Adicionar aula';
    addBtn.className = 'btn-adicionar-aula';
    addBtn.style.marginRight = '8px';

    const delBtn = document.createElement('button');
    delBtn.textContent = '❌ Excluir aula atual';
    delBtn.className = 'btn-excluir-aula';

    editorControls.appendChild(addBtn);
    editorControls.appendChild(delBtn);

    // ADICIONAR AULA
    addBtn.addEventListener('click', async () => {
        const nome = prompt('Nome da aula:', 'Nova Aula');
        if (nome === null) return;

        const body = new FormData();
        body.append('modulo', modulo);
        body.append('nome_aula', nome);
        body.append('titulo', '');
        body.append('link_video', '');

        try {
            const res = await fetch('adicionar_aula.php', { method: 'POST', body });
            const j = await res.json();

            if (j.success) {
                alert("Aula adicionada com sucesso!");
                location.reload();
            } else {
                alert("Erro ao adicionar aula.");
            }

        } catch (err) {
            console.error(err);
            location.reload();
        }
    });

    // EXCLUIR AULA
    delBtn.addEventListener('click', async () => {
        const ativa = document.querySelector('.aula.ativa');
        if (!ativa) return alert("Selecione uma aula.");

        if (!confirm("Tem certeza que deseja excluir?")) return;

        const id = ativa.dataset.id;
        const body = new FormData();
        body.append('id', id);

        try {
            const res = await fetch('excluir_aula.php', { method: 'POST', body });
            const j = await res.json();

            if (j.success) {
                alert("Aula excluída!");
                location.reload();
            } else {
                alert("Erro ao excluir aula.");
            }

        } catch (err) {
            console.error(err);
            location.reload();
        }
    });
}
