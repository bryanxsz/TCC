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

CREATE TABLE pagamento (
    id_pagamento INT,
    id_usuario INT,
    dataP DATE,
    horaP TIME,
    linkP VARCHAR(500),
    confirmadaP BOOLEAN,
    PRIMARY KEY (id_pagamento, id_usuario),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE agendamento (
    id_agendamento INT PRIMARY KEY AUTO_INCREMENT,
    dataR DATE,
    horaR TIME,
    linkR VARCHAR(255),
    confirmadaR BOOLEAN
);

CREATE TABLE reuniao (
    id_usuario INT,
    id_agendamento INT,
    PRIMARY KEY (id_usuario, id_agendamento),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_agendamento) REFERENCES agendamento(id_agendamento)
);

CREATE TABLE modulo (
    id_modulo INT PRIMARY KEY,
    nome VARCHAR(100)
);

CREATE TABLE aula (
    id_aula INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    titulo VARCHAR(100),
    descricao VARCHAR(500),
    avaliacao DECIMAL(3,2),
    video VARCHAR(500),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE comentario (
    id_comentario INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    id_aula INT,
    conteudo TEXT,
    dataC DATE,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_aula) REFERENCES aula(id_aula)
);

CREATE TABLE perfil (
    id_perfil INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    id_aula INT,
    id_comentario INT,
    comentarios TEXT,
    aulasSalvas TEXT,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_aula) REFERENCES aula(id_aula),
    FOREIGN KEY (id_comentario) REFERENCES comentario(id_comentario)
);