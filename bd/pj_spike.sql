CREATE DATABASE IF NOT EXISTS pj_spike;
USE pj_spike;

CREATE TABLE sistema_de_pagamento (
    id_sistema INT PRIMARY KEY AUTO_INCREMENT,
    metodo VARCHAR(50),
    valor DECIMAL(10,2)
);

CREATE TABLE usuario (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50),
    email VARCHAR(50),
    senha VARCHAR(50),
    tipo CHAR(1),
    ativo tinyint(4) not null default '0',
    telefone VARCHAR(20) NULL DEFAULT 'Não definido'
);

CREATE TABLE aulas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  modulo VARCHAR(100) NOT NULL,
  numero_aula INT NOT NULL,
  nome_aula VARCHAR(255) DEFAULT '',
  titulo VARCHAR(255) DEFAULT '',
  link_video TEXT DEFAULT '',
  professor_nome VARCHAR(100) DEFAULT '',
  professor_email VARCHAR(100) DEFAULT '',
  professor_telefone VARCHAR(20) DEFAULT ''
);


CREATE TABLE IF NOT EXISTS aulas_progresso (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  aula_id INT NOT NULL,
  modulo VARCHAR(255) NOT NULL,   
  visto TINYINT(1) NOT NULL DEFAULT 0,
  data_visto DATETIME DEFAULT NULL,
  UNIQUE KEY ux_usuario_aula (usuario_id, aula_id),
  INDEX idx_aula_id (aula_id)
);


CREATE TABLE depoimentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cargo VARCHAR(150) NOT NULL,
    texto TEXT NOT NULL,
    estrelas INT DEFAULT 5,
    ativo INT DEFAULT 0
);


select * from depoimentos;
select * from aulas;
select * from usuario;
select * from aulas_progresso;




INSERT INTO `pj_spike`.`usuario` (`id_usuario`, `nome`, `email`, `senha`, `tipo`) VALUES ('1', 'ADMIN', 'admin@gmail.com', '12', '3');
INSERT INTO `pj_spike`.`sistema_de_pagamento` (`id_sistema`, `valor`) VALUES ('1', '99');
INSERT INTO `pj_spike`.`depoimentos` (`id`, `nome`, `cargo`, `texto`, `estrelas`, `ativo`) VALUES ('1', 'Anderson', 'Professor', 'Adoro trabalhar no Projeto spike!', '5', '1');