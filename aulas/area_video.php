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
                <strong id="professorNome"><?php echo ucwords(htmlspecialchars($aulaInicial['professor_nome'] ?? 'Professor não definido')); ?>    </strong><br>
                <small id="professorEmail"><strong>Email: </strong><?php echo htmlspecialchars($aulaInicial['professor_email'] ?? ''); ?></small><br>
                <small id="professorTelefone"><strong>Telefone: </strong>
                    
                <?php
                if(isset($aula['professor_telefone']) ) {
                    echo 'Não definido'; 
                }else {
                echo $aula['professor_telefone']; 
                }
                ?>
                
            </small>
            </div>
        </div>
    </section>