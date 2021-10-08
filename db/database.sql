  DROP DATABASE IF EXISTS Whatsapp;

  CREATE DATABASE IF NOT EXISTS Whatsapp;

  USE Whatsapp;

  CREATE TABLE Usuarios(
    usuario VARCHAR(50) NOT NULL PRIMARY KEY,
    password VARCHAR(50) NOT NULL,
    admin BOOLEAN NOT NULL,
    maestro BOOLEAN NOT NULL
  );

  CREATE TABLE Agentes(
    id INT(50) PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    documento VARCHAR(50) NOT NULL,
    telefono VARCHAR(50) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    creador VARCHAR(50) NOT NULL,
    usuario VARCHAR(50) NOT NULL,
    INDEX(usuario),
    FOREIGN KEY Agentes(usuario) REFERENCES Usuarios(usuario)
  );

  CREATE TABLE TokenChatApi(
    idToken INT PRIMARY KEY AUTO_INCREMENT,
    Instance VARCHAR(100) NOT NULL,
    Token VARCHAR(100) NOT NULL
  );

  CREATE TABLE dialogs(
    id VARCHAR(25) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    image TEXT NOT NULL,
    last_time VARCHAR(25) NOT NULL,
    abierto BOOLEAN DEFAULT FALSE,
    idAgentes INT(50) NULL,
    INDEX(idAgentes),
    FOREIGN KEY dialogs(idAgentes) REFERENCES Agentes(id)
  );

  CREATE TABLE messages(
    id VARCHAR(255) PRIMARY KEY,
    body TEXT NOT NULL,
    fromMe BOOLEAN NOT NULL,
    self INT NOT NULL,
    isForwarded INT NOT NULL,
    author VARCHAR(100) NOT NULL,
    time INT NOT NULL,
    chatId VARCHAR(100) NOT NULL,
    messageNumber INT NOT NULL,
    type VARCHAR(55) NOT NULL,
    senderName VARCHAR(255),
    quotedMsgBody TEXT NOT NULL,
    quotedMsgId TEXT NOT NULL,
    quotedMsgType TEXT NOT NULL,
    metadata TEXT NOT NULL,
    ack TEXT NOT NULL,
    chatName VARCHAR(255) NOT NULL,
    FechaHora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    sender VARCHAR(255) NOT NULL
  );





DROP PROCEDURE IF EXISTS SP_AlmacenarMensajes;

delimiter // 
CREATE PROCEDURE SP_AlmacenarMensajes (in v_id VARCHAR(255), in v_body TEXT, in v_fromMe BOOLEAN, in v_self INT, in v_isForwarded INT, in v_author VARCHAR(100), in v_time INT, in v_chatId VARCHAR(100), in v_messageNumber INT, in v_type VARCHAR(55), in v_senderName VARCHAR(255), in v_quotedMsgBody TEXT, in v_quotedMsgId TEXT, in v_quotedMsgType TEXT, in v_metadata TEXT, in v_ack TEXT, in v_chatName VARCHAR(255), in v_sender VARCHAR(255)) 
BEGIN

INSERT INTO messages(id, body, fromMe, self, isForwarded, author, time, chatId, messageNumber, type, senderName, quotedMsgBody, quotedMsgId, quotedMsgType, metadata, ack, chatName, sender) VALUES( v_id, v_body, v_fromMe, v_self, v_isForwarded, v_author, v_time, v_chatId, v_messageNumber, v_type, v_senderName, v_quotedMsgBody, v_quotedMsgId, v_quotedMsgType, v_metadata, v_ack, v_chatName, v_sender);
END // 





DROP PROCEDURE IF EXISTS SP_ConteoChatAgente;
delimiter $$
CREATE PROCEDURE SP_ConteoChatAgente(in v_usuario VARCHAR(255))
BEGIN

DECLARE v_id INT;
DECLARE v_cantidad INT;

SET v_id = (SELECT id FROM Agentes WHERE usuario = v_usuario);
SET v_cantidad = (SELECT count(abierto) FROM dialogs WHERE idAgentes = v_id);

SELECT v_cantidad;

END$$
delimiter ;





DROP PROCEDURE IF EXISTS SP_ConteoChatAsignados;

delimiter $$
CREATE PROCEDURE SP_ConteoChatAsignados()
	BEGIN
		DECLARE v_conteo INT;
		SET v_conteo = (SELECT COUNT(idAgentes) FROM dialogs);
		SELECT v_conteo;
	END $$
delimiter ;







DROP PROCEDURE IF EXISTS SP_MostrarMensajesChat;

delimiter //
CREATE PROCEDURE SP_MostrarMensajesChat(in v_id TEXT)
BEGIN
SELECT * FROM messages WHERE id like concat('%',v_id,'%') ORDER BY messageNumber DESC;
END //
delimiter ;



  INSERT INTO
    Usuarios(usuario, password, admin, maestro)
  VALUES
    (
      'master',
      '202cb962ac59075b964b07152d234b70',
      TRUE,
      TRUE
    );

  INSERT INTO
    Agentes(
      nombre,
      apellido,
      documento,
      telefono,
      direccion,
      correo,
      creador,
      usuario
    )
  VALUES
    (
      'Cristian',
      'Aguirre Cataño',
      '1035391050',
      '3166857000',
      'carrera 33 47 35',
      'dextter1913@gmail.com',
      'master',
      'master'
    );

  INSERT INTO
    Usuarios(usuario, password, admin, maestro)
  VALUES
    (
      'admin',
      '202cb962ac59075b964b07152d234b70',
      TRUE,
      FALSE
    );

  INSERT INTO
    Agentes(
      nombre,
      apellido,
      documento,
      telefono,
      direccion,
      correo,
      creador,
      usuario
    )
  VALUES
    (
      'Carlos',
      'Monsalve Builes',
      '105874596',
      '3134558574',
      'carrera 22 52 35',
      'carlithos634@gmail.com',
      'master',
      'admin'
    );

  INSERT INTO
    Usuarios(usuario, password, admin, maestro)
  VALUES
    (
      'regular',
      '202cb962ac59075b964b07152d234b70',
      FALSE,
      FALSE
    );

  INSERT INTO
    Agentes(
      nombre,
      apellido,
      documento,
      telefono,
      direccion,
      correo,
      creador,
      usuario
    )
  VALUES
    (
      'Maria',
      'Isabel Beltran',
      '105896354',
      '3167459321',
      'Avenida 7 15 30',
      'MariaBe1994@gmail.com',
      'admin',
      'regular'
    );