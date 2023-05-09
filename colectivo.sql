create database Colectivo;
use Colectivo;
create table usuarios(
idUsuario int (10)auto_increment primary key,
nombreUsuario varchar(20),
correoUsuario varchar(64) UNIQUE,
contrasenaUsuario varchar(100)
);
create table rango_de_frecuencias(
idRango int(3) primary key,
rango_de_frecuencias double,
idUsuario int(10),
foreign key (idUsuario) references usuarios(idUsuario)
);
create table logs_auditoria(
idLogs_auditoria int(3) primary key,
fechaLogs_auditoria date,
horaLogs_auditoria time,
accionLogs_auditoria varchar(64),
descripcionLogs_auditoria varchar(64),
idUsuario int(10),
foreign key (idUsuario) references usuarios(idUsuario)
);
create table calculos(
idCalculos int(3) auto_increment primary key,
idUsuario int(10),
foreign key (idUsuario) references usuarios(idUsuario),
fechaCalculos date,
horaCalculos time,
entradaCalculos float(24),
tipoCalculo varchar(64),
resultadoCalculos double
);
create table registro(
idRegistro int(10) auto_increment  primary key,
ingresoRegistro time,
salidaRegistro time,
idUsuario int(10),
foreign key (idUsuario) references usuarios(idUsuario)
);
/*Creacion de la cuenta administradora*/
insert into usuarios(nombreUsuario,correoUsuario,contrasenaUsuario) values("webMaster","webMaster@admin.s5","$2y$10$AhiJqY49sJLEythPbXkf5epjFHr3miLs3KWMGKOJWupxjOWsbSqaq");
/*Ver todos los usuarios*/
select *from usuarios;
/*Borrar datos de usuarios*/
delete from usuarios where idUsuario=3;
drop database Colectivo;