CREATE DATABASE CCVDB

CREATE TABLE Usuario(
	ID_Usuario	INT,
	Nombre		VARCHAR(30),
	Correo		VARCHAR(30),
	Contrase�a	VARCHAR(30),
	PRIMARY KEY(ID_Usuario),
	UNIQUE(Correo),
	UNIQUE(Nombre))

CREATE TABLE Tipo_Evento(
	ID_Tipo			INT,
	Nombre_Evento	VARCHAR(30),
	Frecuencia		CHAR(1) CHECK (Frecuencia IN('U','D','S','M','A')),
	ID_Usuario		INT NOT NULL,
	PRIMARY KEY(ID_Tipo),
	FOREIGN KEY(ID_Usuario) REFERENCES Usuario(ID_Usuario))

CREATE TABLE Evento(
	ID_Evento		INT,
	Titulo			VARCHAR(20),
	Fecha			DATE,
	Hora			TIME,
	Descripcion		VARCHAR(100),
	ID_Usuario		INT NOT NULL,
	ID_Tipo			INT NOT NULL,
	PRIMARY KEY(ID_Evento),
	FOREIGN KEY(ID_Usuario) REFERENCES Usuario(ID_Usuario),
	FOREIGN KEY(ID_Tipo) REFERENCES Tipo_Evento(ID_Tipo))

CREATE TABLE Contacto(
	ID_Contacto		INT,
	Nombre			VARCHAR(30),
	Direccion		VARCHAR(40),
	Telefono		CHAR(8),
	Correo			VARCHAR(30),
	Fecha_Na		DATE,
	ID_Usuario		INT,
	ID_Evento		INT,
	PRIMARY KEY(ID_Contacto),
	FOREIGN KEY(ID_Usuario) REFERENCES Usuario(ID_Usuario),
	FOREIGN KEY(ID_Evento) REFERENCES Evento(ID_Evento))