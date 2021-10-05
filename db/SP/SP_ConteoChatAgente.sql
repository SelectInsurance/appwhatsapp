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