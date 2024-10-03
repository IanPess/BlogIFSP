create database blog;

use blog;

CREATE TABLE usuario (
	id int NOT NULL auto_increment,
    nome varchar(50) NOT NULL,
    email varchar(255) NOT NULL,
    senha varchar(60) NOT NULL,
    data_criacao datetime NOT NULL DEFAULT current_timestamp,
    ativo tinyint NOT NULL DEFAULT '0',
    adm tinyint NOT NULL DEFAULT '0',
    PRIMARY KEY (id)
    );
CREATE TABLE post (
	id int NOT NULL auto_increment,
    titulo varchar(255) NOT NULL,
    texto text NOT NULL,
    usuario_id int NOT NULL,
    data_criacao datetime NOT NULL default current_timestamp,
    data_postagem datetime NOT NULL,
    primary key(id),
    Key fk_post_usuario_idx (usuario_id),
    constraint fk_post_usuario foreign key (usuario_id) references usuario(id)
    );