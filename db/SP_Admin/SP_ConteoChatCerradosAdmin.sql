USE whatsapp;

DROP PROCEDURE IF EXISTS SP_ConteoChatCerradosAdmin;
DELIMITER //
CREATE procedure SP_ConteoChatCerradosAdmin(in v_usuario VARCHAR(255))
BEGIN

DECLARE v_conteo INT;
DECLARE v_idAgentes INT;
SET v_idAgentes = (SELECT id FROM Agentes WHERE creador = v_usuario);
SET v_conteo = (SELECT COUNT(abierto) FROM  dialogs WHERE idAgentes = v_idAgentes);

SELECT v_conteo;

END //
DELIMITER;

