<header class="topo">
    <a href="../modulos.php">
        <img src="../imagens/logospike.png" class="logo" alt="Logo">
    </a>
    <h1>Aulas</h1>
    <div style="cursor: pointer" onclick="window.location.href='../editar_conta.php'" class="perfil">
        <img src="../imagens/images.png" class="foto" alt="Foto do usuário">
        <div>
            <strong>
                Olá, <?php echo ($_SESSION['user_tipo'] == '1' ? "Aluno, " : "Professor, ") . htmlspecialchars($_SESSION['user_name']); ?>!
            </strong><br>
            <small><?php echo htmlspecialchars($_SESSION['user_email']); ?></small><br>
            <small><a href="../logout.php">Sair</a></small>
        </div>
    </div>
</header>
