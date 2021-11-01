use Whatsapp;
DROP PROCEDURE IF EXISTS SP_FiltrarSalaAbiertosAdmin;

DELIMITER $$
CREATE PROCEDURE SP_FiltrarSalaAbiertosAdmin(IN v_datos VARCHAR(255), IN v_usuario VARCHAR(255))
BEGIN 

DECLARE v_id INT;
SET v_id = (SELECT id FROM Agentes WHERE usuario = v_usuario);

    SELECT * FROM dialogs WHERE abierto = 1 AND (idAgentes = v_id OR Asignador = v_id) AND (name LIKE concat(v_datos, '%')  OR id LIKE concat(v_datos, '%'));
END $$