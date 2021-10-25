USE whatsapp;
DROP PROCEDURE IF EXISTS SP_FiltrarSala;

DELIMITER //
CREATE PROCEDURE SP_FiltrarSala(IN v_datos VARCHAR(255), IN v_user VARCHAR(255))
BEGIN

DECLARE id INT;
SET id = (SELECT id FROM Agentes WHERE usuario  = v_user);


SELECT * FROM dialogs WHERE idAgentes = id AND id LIKE concat(v_datos,'%') OR name LIKE concat(v_datos,'%');
END //