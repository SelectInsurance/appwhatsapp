USE whatsapp;
DROP PROCEDURE IF EXISTS SP_FiltrarSalaAdmin;

DELIMITER //
CREATE PROCEDURE SP_FiltrarSalaAdmin(IN v_datos VARCHAR(255), IN v_user VARCHAR(255))
BEGIN
DECLARE v_id INT;
SET v_id = (SELECT id FROM Agentes WHERE usuario = v_user);

SELECT * FROM dialogs WHERE (id LIKE concat(v_datos,'%') OR name LIKE concat(v_datos,'%')) AND idAgentes = v_id;

END //