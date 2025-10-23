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

        <?php if ($_SESSION['user_tipo'] == '2' && $aulaInicial): ?>
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
    </section>