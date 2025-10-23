
// Seletores
const aulas = document.querySelectorAll('.aula');
const titulo = document.getElementById('tituloAula');
const video = document.getElementById('videoFrame');
const checkVisto = document.getElementById('checkVisto');
const btnEditar = document.getElementById('btnEditar');
const professorNome = document.getElementById('professorNome');
const professorEmail = document.getElementById('professorEmail');
const professorTelefone = document.getElementById('professorTelefone');

// Progresso local
const progresso = JSON.parse(localStorage.getItem('aulasVistas')) || {};

// Atualiza status visual
function atualizarStatus() {
    aulas.forEach(aula => {
        const status = aula.querySelector('.status');
        const id = aula.dataset.id;
        status.classList.toggle('visto', !!progresso[id]);
    });

    const ativa = document.querySelector('.aula.ativa');
    if (ativa) {
        checkVisto.checked = !!progresso[ativa.dataset.id];
    }
}

// Seleção de aula
aulas.forEach(aula => {
    aula.addEventListener('click', e => {
        if (e.target.classList.contains('status')) return;

        aulas.forEach(a => a.classList.remove('ativa'));
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

    aula.querySelector('.status').addEventListener('click', e => {
        e.stopPropagation();
        const id = aula.dataset.id;
        progresso[id] = !progresso[id];
        localStorage.setItem('aulasVistas', JSON.stringify(progresso));
        atualizarStatus();
    });
});

// Checkbox sincronizado
checkVisto.addEventListener('change', () => {
    const ativa = document.querySelector('.aula.ativa');
    if (!ativa) return;
    const id = ativa.dataset.id;
    progresso[id] = checkVisto.checked;
    localStorage.setItem('aulasVistas', JSON.stringify(progresso));
    atualizarStatus();
});

// Inicializa status
atualizarStatus(); 
