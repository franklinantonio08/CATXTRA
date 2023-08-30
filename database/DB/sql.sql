drop table DataTrack.dbo.registroExpediente
drop table DataTrack.dbo.actividad
drop table DataTrack.dbo.producto
drop table DataTrack.dbo.modelo
drop table DataTrack.dbo.marca
drop table DataTrack.dbo.agenteEconomico
drop table DataTrack.dbo.estatusExpediente
drop table DataTrack.dbo.solucionExpediente
drop table DataTrack.dbo.tipoLey
drop table DataTrack.dbo.departamento
drop table DataTrack.dbo.tipoLey
drop table DataTrack.dbo.permisoUsuario
drop table DataTrack.dbo.codigoPermiso



CREATE TABLE DataTrack.dbo.codigoPermiso (
    id int PRIMARY KEY,
    description varchar(255),
    codigo varchar(255),
    orden int,
    estatus varchar(10) CHECK (Estatus IN ('activo', 'inactivo')),
    created_at datetime2,
    updated_at datetime2
);

CREATE TABLE DataTrack.dbo.permisoUsuario (
    id int PRIMARY KEY,
    codigo varchar(255),
    valor bit,
    usuarioid bigint REFERENCES dbo.users(id), -- Relación con la tabla users
    codigopermisoid int REFERENCES dbo.codigoPermiso(id), -- Relación con segunda_tabla
    created_at datetime2,
    updated_at datetime2
);

CREATE TABLE DataTrack.dbo.departamento (
    id int PRIMARY KEY,
    description varchar(255),
    codigo varchar(255),
    estatus varchar(10) CHECK (Estatus IN ('Activo', 'Inactivo')),
    usuarioid bigint REFERENCES dbo.users(id), -- Relación con la tabla users
    created_at datetime2,
    updated_at datetime2
);


CREATE TABLE DataTrack.dbo.tipoLey (
    id int PRIMARY KEY,
    description varchar(255),
    codigo varchar(255),
    estatus varchar(10) CHECK (Estatus IN ('Activo', 'Inactivo')),
    usuarioid bigint REFERENCES dbo.users(id), -- Relación con la tabla users
    created_at datetime2,
    updated_at datetime2
);


CREATE TABLE DataTrack.dbo.solucionExpediente (
    id int PRIMARY KEY,
    description varchar(255),
    codigo varchar(255),
    estatus varchar(10) CHECK (Estatus IN ('Activo', 'Inactivo')),
    usuarioid bigint REFERENCES dbo.users(id), -- Relación con la tabla users
    created_at datetime2,
    updated_at datetime2
);

CREATE TABLE DataTrack.dbo.estatusExpediente (
    id int PRIMARY KEY,
    description varchar(255),
    codigo varchar(255),
    estatus varchar(10) CHECK (Estatus IN ('Activo', 'Inactivo')),
    usuarioid bigint REFERENCES dbo.users(id), -- Relación con la tabla users
    created_at datetime2,
    updated_at datetime2
);

CREATE TABLE DataTrack.dbo.agenteEconomico (
    id int PRIMARY KEY,
    description varchar(255),
    codigo varchar(255),
    estatus varchar(10) CHECK (Estatus IN ('Activo', 'Inactivo')),
    usuarioid bigint REFERENCES dbo.users(id), -- Relación con la tabla users
    created_at datetime2,
    updated_at datetime2
);

CREATE TABLE DataTrack.dbo.marca (
    id int PRIMARY KEY,
    description varchar(255),
    codigo varchar(255),
    estatus varchar(10) CHECK (Estatus IN ('Activo', 'Inactivo')),
    usuarioid bigint REFERENCES dbo.users(id), -- Relación con la tabla users
    created_at datetime2,
    updated_at datetime2
);

CREATE TABLE DataTrack.dbo.modelo (
    id int PRIMARY KEY,
    description varchar(255),
    codigo varchar(255),
    estatus varchar(10) CHECK (Estatus IN ('Activo', 'Inactivo')),
    usuarioid bigint REFERENCES dbo.users(id), -- Relación con la tabla users
    marcaId int REFERENCES dbo.marca(id), -- Relación con segunda_tabla
    created_at datetime2,
    updated_at datetime2
);

CREATE TABLE DataTrack.dbo.producto (
    id int PRIMARY KEY,
    description varchar(255),
    codigo varchar(255),
    estatus varchar(10) CHECK (Estatus IN ('Activo', 'Inactivo')),
    usuarioid bigint REFERENCES dbo.users(id), -- Relación con la tabla users
    created_at datetime2,
    updated_at datetime2
);


CREATE TABLE DataTrack.dbo.actividad (
    id int PRIMARY KEY,
    description varchar(255),
    codigo varchar(255),
    estatus varchar(10) CHECK (Estatus IN ('Activo', 'Inactivo')),
    usuarioid bigint REFERENCES dbo.users(id), -- Relación con la tabla users
    created_at datetime2,
    updated_at datetime2
);


CREATE TABLE DataTrack.dbo.registroExpediente (
id int PRIMARY KEY,
description varchar(255),
codigo varchar(255),
estatus varchar(10) CHECK (Estatus IN ('Activo', 'Inactivo')),
usuarioid bigint REFERENCES dbo.users(id), -- Relación con la tabla users
numeroExpediente varchar(255),
departamentoId int REFERENCES dbo.departamento(id),
solucionId int REFERENCES dbo.solucionExpediente(id),
estatusId int REFERENCES dbo.estatusExpediente(id),
marcaId int REFERENCES dbo.marca(id),
modeloId int REFERENCES dbo.modelo(id),
productoId int REFERENCES dbo.producto(id),
actividadId int REFERENCES dbo.actividad(id),
tipoLeyId int REFERENCES dbo.tipoLey(id),
agenteEconomicoId int REFERENCES dbo.agenteEconomico(id),
monto decimal(18, 4),
created_at datetime2,
updated_at datetime2
);