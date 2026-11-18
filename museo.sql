-- ============================================
--  base de datos: museo
-- ============================================

create database `museo` default character set utf8mb4 collate utf8mb4_general_ci;

use `museo`;

set names utf8mb4;


-- roles
create table `roles` (
  `id` int(10) unsigned not null auto_increment,
  `denominacion` varchar(50) not null,
  primary key (`id`),
  unique key `uq_roles_denominacion` (`denominacion`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_unicode_ci;


-- usuarios
create table `usuarios` (
  `id` int(11) not null auto_increment,
  `denominacion` varchar(100) not null,
  `email` varchar(100) default null,
  `password` varchar(255) not null,
  `telefono` varchar(20) default null,
  `fecha_alta` datetime default current_timestamp(),
  `fecha_baja` datetime default null,
  `fecha_edicion` datetime default null,
  `id_rol` int(10) unsigned not null default 3,
  primary key (`id`),
  key `idx_usuarios_id_rol` (`id_rol`),
  constraint `fk_usuarios_rol` foreign key (`id_rol`) references `roles` (`id`) on update cascade
) engine=innodb default charset=utf8mb4 collate=utf8mb4_general_ci;


-- rol_permiso (a = alta, b = baja, m = modificación, l = listar)
create table `rol_permiso` (
  `id` int(10) unsigned not null auto_increment,
  `id_rol` int(10) unsigned not null,
  `objeto` set('a','b','m','l') not null,
  `etiqueta` set('a','b','m','l') not null,
  `atributo` set('a','b','m','l') not null,
  `usuario` set('a','b','m','l') not null,
  `sala` set('a','b','m','l') not null,
  primary key (`id`),
  unique key `id_rol` (`id_rol`),
  constraint `rol_permiso_ibfk_1` foreign key (`id_rol`) references `roles` (`id`) on delete cascade on update cascade
) engine=innodb default charset=utf8mb4 collate=utf8mb4_general_ci;


-- salas (id_pertenece = sala padre)
create table `salas` (
  `id` int(10) unsigned not null auto_increment,
  `denominacion` varchar(100) not null,
  `descripcion` varchar(255) default null,
  `id_pertenece` int(10) unsigned default null,
  `fecha_alta` datetime not null default current_timestamp(),
  `fecha_baja` datetime default null,
  `fecha_edicion` datetime default null,
  primary key (`id`),
  key `fk_salas_pertenece` (`id_pertenece`),
  constraint `fk_salas_pertenece` foreign key (`id_pertenece`) references `salas` (`id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_general_ci;


-- atributos
create table `atributos` (
  `id` int(11) not null auto_increment,
  `denominacion` varchar(100) not null,
  `tipo_dato` varchar(20) not null,
  `unidad` varchar(20) default null,
  `fecha_alta` datetime default current_timestamp(),
  `fecha_baja` datetime default null,
  `fecha_edicion` datetime default null,
  primary key (`id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_general_ci;


-- etiquetas
create table `etiquetas` (
  `id` int(11) not null auto_increment,
  `denominacion` varchar(100) not null,
  `fecha_alta` datetime default current_timestamp(),
  `fecha_baja` datetime default null,
  `fecha_edicion` datetime default null,
  primary key (`id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_general_ci;


-- museos
create table `museos` (
  `id` int(11) not null auto_increment,
  `denominacion` varchar(100) not null,
  `direccion` varchar(150) default null,
  `telefono` varchar(20) default null,
  `email` varchar(100) default null,
  `whatsapp` varchar(20) default null,
  `facebook` varchar(150) default null,
  `instagram` varchar(150) default null,
  `fecha_alta` datetime default current_timestamp(),
  `fecha_baja` datetime default null,
  `fecha_edicion` datetime default null,
  primary key (`id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_general_ci;


-- objetos
create table `objetos` (
  `id` int(11) not null auto_increment,
  `codigo` varchar(50) default null,
  `denominacion` varchar(100) not null,
  `descripcion` varchar(255) default null,
  `id_usuario` int(11) default null,
  `fecha_alta` datetime default current_timestamp(),
  `fecha_baja` datetime default null,
  `fecha_edicion` datetime default null,
  `id_ubicacion` int(10) unsigned default null,
  primary key (`id`),
  key `id_usuario` (`id_usuario`),
  key `fk_objetos_ubicacion` (`id_ubicacion`),
  constraint `fk_objetos_ubicacion` foreign key (`id_ubicacion`) references `salas` (`id`),
  constraint `fk_objetos_usuarios` foreign key (`id_usuario`) references `usuarios` (`id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_general_ci;


-- objeto_atributos
create table `objeto_atributos` (
  `id` int(11) not null auto_increment,
  `objeto_id` int(11) default null,
  `atributo_id` int(11) default null,
  `valor` varchar(255) default null,
  `fecha_alta` datetime default null,
  `fecha_baja` datetime default null,
  `fecha_edicion` datetime default null,
  primary key (`id`),
  key `fk_objatr_objeto` (`objeto_id`),
  key `fk_objatr_atributo` (`atributo_id`),
  constraint `fk_objatr_atributo` foreign key (`atributo_id`) references `atributos` (`id`),
  constraint `fk_objatr_objeto` foreign key (`objeto_id`) references `objetos` (`id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_general_ci;


-- objeto_etiquetas
create table `objeto_etiquetas` (
  `id` int(11) not null auto_increment,
  `objeto_id` int(11) default null,
  `etiqueta_id` int(11) default null,
  `fecha_alta` datetime default null,
  `fecha_baja` datetime default null,
  `fecha_edicion` datetime default null,
  primary key (`id`),
  key `fk_objetq_objeto` (`objeto_id`),
  key `fk_objetq_etiqueta` (`etiqueta_id`),
  constraint `fk_objetq_etiqueta` foreign key (`etiqueta_id`) references `etiquetas` (`id`),
  constraint `fk_objetq_objeto` foreign key (`objeto_id`) references `objetos` (`id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_general_ci;


-- piezas_archivos
create table `piezas_archivos` (
  `id` int(11) not null auto_increment,
  `objeto_id` int(11) not null,
  `nombrearchivo` varchar(255) not null,
  `nombreoriginal` varchar(255) not null,
  `fecha_alta` datetime default current_timestamp(),
  `fecha_baja` datetime default null,
  `fecha_edicion` datetime default null,
  primary key (`id`),
  key `fk_parch_objeto` (`objeto_id`),
  constraint `fk_parch_objeto` foreign key (`objeto_id`) references `objetos` (`id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_general_ci;


-- piezas_imagenes (portada: 1 = imagen de portada, 0 = imagen común)
create table `piezas_imagenes` (
  `id` int(11) not null auto_increment,
  `objeto_id` int(11) not null,
  `nombrearchivo` varchar(255) not null,
  `portada` tinyint(1) not null default 0,
  `fecha_alta` datetime default current_timestamp(),
  `fecha_baja` datetime default null,
  `fecha_edicion` datetime default null,
  primary key (`id`),
  key `fk_pimg_objeto` (`objeto_id`),
  constraint `fk_pimg_objeto` foreign key (`objeto_id`) references `objetos` (`id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_general_ci;


-- auditoria (accion: a = alta, b = baja, m = modificación, r = recuperación)
create table `auditoria` (
  `id` int(11) not null auto_increment,
  `tabla` enum('objetos','objeto_etiquetas','objeto_atributos','etiquetas','atributos','piezas_imagenes') not null,
  `accion` enum('a','b','m','r') not null,
  `id_dato` int(11) not null,
  `id_usuario` int(11) not null,
  `fecha` datetime not null default current_timestamp(),
  primary key (`id`),
  key `tabla` (`tabla`,`id_dato`),
  key `id_usuario` (`id_usuario`),
  constraint `auditoria_ibfk_1` foreign key (`id_usuario`) references `usuarios` (`id`)
) engine=innodb default charset=utf8mb4 collate=utf8mb4_general_ci;