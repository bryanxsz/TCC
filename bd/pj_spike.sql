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


drop table aulas;

INSERT INTO `pj_spike`.`usuario` (`id_usuario`, `nome`, `email`, `senha`, `tipo`) VALUES ('1', 'ADMIN', 'admin@gmail.com', '12', '3');
INSERT INTO `pj_spike`.`usuario` (`id_usuario`, `nome`, `email`, `senha`, `tipo`) VALUES ('2', 'Cledenilson', 'cledenilson@gmail.com', '12', '2');
INSERT INTO `pj_spike`.`usuario` (`id_usuario`, `nome`, `email`, `senha`, `tipo`) VALUES ('3', 'Cardoso', 'cardoso@gmail.com', '12', '2');
INSERT INTO `pj_spike`.`usuario` (`id_usuario`, `nome`, `email`, `senha`, `tipo`) VALUES ('4', 'Paulo Amorim', 'paulo@gmail.com', '12', '2');
INSERT INTO `pj_spike`.`usuario` (`id_usuario`, `nome`, `email`, `senha`, `tipo`) VALUES ('5', 'Satoru', 'satoru@gmail.com', '12', '2');
INSERT INTO `pj_spike`.`usuario` (`id_usuario`, `nome`, `email`, `senha`, `tipo`) VALUES ('6', 'Cristian Freitas', 'cristian@gmail.com', '12', '2');
INSERT INTO `pj_spike`.`usuario` (`id_usuario`, `nome`, `email`, `senha`, `tipo`) VALUES ('7', 'Fabi', 'fabi@gmail.com', '12', '2');
INSERT INTO `pj_spike`.`usuario` (`id_usuario`, `nome`, `email`, `senha`, `tipo`) VALUES ('8', 'Roberto', 'roberto@gmail.com', '12', '2');
INSERT INTO `pj_spike`.`usuario` (`id_usuario`, `nome`, `email`, `senha`, `tipo`) VALUES ('9', 'Sikana', 'sikana@gmail.com', '12', '2');

INSERT INTO `pj_spike`.`sistema_de_pagamento` (`id_sistema`, `valor`) VALUES ('1', '99');
INSERT INTO `pj_spike`.`depoimentos` (`id`, `nome`, `cargo`, `texto`, `estrelas`, `ativo`) VALUES ('1', 'Anderson', 'Professor', 'Adoro trabalhar no Projeto spike!', '5', '1');


INSERT INTO `pj_spike`.`aulas`(`id`, `modulo`, `nome_aula`, `numero_aula`, `titulo`, `link_video`, `professor_nome`, `professor_email`, `professor_telefone`) VALUES (1, 'recepcão', 'Introdução', 1, 'TÉCNICA DA MANCHETE', 'https://www.youtube.com/embed/NHtMA_cTXE4?si=w0xE2BZvT-12mgCN', 'Cledenilson', 'cledenilson@gmail.com', 'Não definido');
INSERT INTO `pj_spike`.`aulas`(`id`, `modulo`, `nome_aula`, `numero_aula`, `titulo`, `link_video`, `professor_nome`, `professor_email`, `professor_telefone`) VALUES (2, 'recepcão', 'Aula I', 2, 'TUDO SOBRE RECEPÇÃO', 'https://www.youtube.com/embed/lDlDJzMSIYs?si=IYrfZ54TNwRf1MdI', 'Cardoso', 'cardoso@gmail.com', 'Não definido');
INSERT INTO `pj_spike`.`aulas`(`id`, `modulo`, `nome_aula`, `numero_aula`, `titulo`, `link_video`, `professor_nome`, `professor_email`, `professor_telefone`) VALUES (3, 'recepcão', 'Aula II', 3, 'ERROS COMUNS', 'https://www.youtube.com/embed/grijG6ZCLxk?si=3aijc5r6nL_VSWoj', 'Cardoso', 'cardoso@gmail.com', 'Não definido');
INSERT INTO `pj_spike`.`aulas`(`id`, `modulo`, `nome_aula`, `numero_aula`, `titulo`, `link_video`, `professor_nome`, `professor_email`, `professor_telefone`) VALUES (4, 'recepcão', 'Aula III', 4, 'POSICIONAMENTO DEFENSIVO', 'https://www.youtube.com/embed/IxeIHgMuhkg?si=DvoLlgc93k6UHfbH', 'Cardoso', 'cardoso@gmail.com', 'Não definido');

INSERT INTO `pj_spike`.`aulas`(`id`, `modulo`, `nome_aula`, `numero_aula`, `titulo`, `link_video`, `professor_nome`, `professor_email`, `professor_telefone`) VALUES (5, 'toque', 'Introdução', 1, 'TÉCNICA DO TOQUE', 'https://www.youtube.com/embed/njvxg_MFBtA?si=KBqvOZFYckf2MS7e', 'Paulo Amorim', 'paulo@gmail.com', 'Não definido');
INSERT INTO `pj_spike`.`aulas`(`id`, `modulo`, `nome_aula`, `numero_aula`, `titulo`, `link_video`, `professor_nome`, `professor_email`, `professor_telefone`) VALUES (6, 'toque', 'Como praticar I', 2, 'EXERCÍCIOS 1', 'https://www.youtube.com/embed/c5tPv-LAQ3A?si=2X0ENDJBg6D1ELaq', 'Satoru', 'satoru@gmail.com', 'Não definido');
INSERT INTO `pj_spike`.`aulas`(`id`, `modulo`, `nome_aula`, `numero_aula`, `titulo`, `link_video`, `professor_nome`, `professor_email`, `professor_telefone`) VALUES (7, 'toque', 'Como praticar II', 3, 'EXERCÍCIOS 2', 'https://www.youtube.com/embed/XoyB0uT_qQ4?si=3V017vgwYQjWq8O8', 'Cristian Freitas', 'cristian@gmail.com', 'Não definido');

INSERT INTO `pj_spike`.`aulas`(`id`, `modulo`, `nome_aula`, `numero_aula`, `titulo`, `link_video`, `professor_nome`, `professor_email`, `professor_telefone`) VALUES (8, 'ataque', 'Introdução', 1, 'PASSADA DE ATAQUE', 'https://www.youtube.com/embed/QUCm84Ica2w?si=L1xLopsxUukK0213', 'Fabi', 'fabi@gmail.com', 'Não definido');
INSERT INTO `pj_spike`.`aulas`(`id`, `modulo`, `nome_aula`, `numero_aula`, `titulo`, `link_video`, `professor_nome`, `professor_email`, `professor_telefone`) VALUES (9, 'ataque', 'Aula I', 2, 'ENCAIXE', 'https://www.youtube.com/embed/_pONb6Ien8M?si=EfOEfSx8DwPZSCbS', 'Cardoso', 'cardoso@gmail.com', 'Não definido');
INSERT INTO `pj_spike`.`aulas`(`id`, `modulo`, `nome_aula`, `numero_aula`, `titulo`, `link_video`, `professor_nome`, `professor_email`, `professor_telefone`) VALUES (10, 'ataque', 'Aula II', 3, 'TEMPOS DE ATAQUE', 'https://www.youtube.com/embed/RLXceufYu10?si=94meeSB6LQ0eKFQ2', 'Roberto', 'roberto@gmail.com', 'Não definido');
INSERT INTO `pj_spike`.`aulas`(`id`, `modulo`, `nome_aula`, `numero_aula`, `titulo`, `link_video`, `professor_nome`, `professor_email`, `professor_telefone`) VALUES (11, 'ataque', 'Aula III', 4, 'EXERCÍCIOS E ERROS COMUNS', 'https://www.youtube.com/embed/tI-ZHi9ULw4?si=lqpeRSjlo8kLE1Xv', 'Cardoso', 'cardoso@gmail.com', 'Não definido');

INSERT INTO `pj_spike`.`aulas`(`id`,`modulo`,`nome_aula`,`numero_aula`,`titulo`,`link_video`,`professor_nome`,`professor_email`,`professor_telefone`) VALUES (12,'bloqueio','Introdução',1,'TÉCNICA DO BLOQUEIO','https://www.youtube.com/embed/Ya_fU1H7Dw8?si=JlCxHpIBvLUfPQVR','Cardoso','cardoso@gmail.com','Não definido');
INSERT INTO `pj_spike`.`aulas`(`id`,`modulo`,`nome_aula`,`numero_aula`,`titulo`,`link_video`,`professor_nome`,`professor_email`,`professor_telefone`) VALUES (13,'bloqueio','Dicas e exercícios I',2,'DICAS','https://www.youtube.com/embed/IruKQwCteUk?si=OdKhCVjmo32Iqbbp','Cledenilson','cledenilson@gmail.com','Não definido');
INSERT INTO `pj_spike`.`aulas`(`id`,`modulo`,`nome_aula`,`numero_aula`,`titulo`,`link_video`,`professor_nome`,`professor_email`,`professor_telefone`) VALUES (14,'bloqueio','Dicas e exercícios II',3,'EXERCÍCIOS','https://www.youtube.com/embed/AHW_gTjPtzc?si=nLkWQAB8tKL5jyb4','Sikana','sikana@gmail.com','Não definido');

INSERT INTO `pj_spike`.`aulas`(`id`,`modulo`,`nome_aula`,`numero_aula`,`titulo`,`link_video`,`professor_nome`,`professor_email`,`professor_telefone`) VALUES (15,'saque','Introdução',1,'SAQUE POR BAIXO','https://www.youtube.com/embed/3We1wH0HnJQ?si=5o5Gp2PeTfss2qx5','Satoru','satoru@gmail.com','Não definido');
INSERT INTO `pj_spike`.`aulas`(`id`,`modulo`,`nome_aula`,`numero_aula`,`titulo`,`link_video`,`professor_nome`,`professor_email`,`professor_telefone`) VALUES (16,'saque','Aula I',2,'SAQUE POR CIMA','https://www.youtube.com/embed/pxeFBK7LteA?si=OtqecqbWZD4LeGIR','Cardoso','cardoso@gmail.com','Não definido');
INSERT INTO `pj_spike`.`aulas`(`id`,`modulo`,`nome_aula`,`numero_aula`,`titulo`,`link_video`,`professor_nome`,`professor_email`,`professor_telefone`) VALUES (17,'saque','Aula II',3,'SAQUE FLUTUANTE','https://www.youtube.com/embed/xHXCSYeOQN8?si=RU7a6_Jxm677M3o4','Fabi','fabi@gmail.com','Não definido');
INSERT INTO `pj_spike`.`aulas`(`id`,`modulo`,`nome_aula`,`numero_aula`,`titulo`,`link_video`,`professor_nome`,`professor_email`,`professor_telefone`) VALUES (18,'saque','Aula III',4,'SAQUE VIAGEM','https://www.youtube.com/embed/B_n3ek1WsMs?si=3cZXjYcoUql9LWwj','Cardoso','cardoso@gmail.com','Não definido');

