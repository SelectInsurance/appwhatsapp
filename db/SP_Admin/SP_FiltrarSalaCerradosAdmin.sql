


USE Whatsapp;
DROP PROCEDURE IF EXISTS SP_FiltrarSalaCerradosAdmin;

DELIMITER $$
CREATE PROCEDURE SP_FiltrarSalaCerradosAdmin(IN v_datos VARCHAR(255), IN v_usuario VARCHAR(255))
BEGIN 
    DECLARE v_id INT;
    SET v_id = (SELECT id FROM Agentes WHERE usuario = v_usuario);

    SELECT * FROM dialogs WHERE abierto = 0 AND (idAgentes = v_id OR Asignador = v_id) AND (name LIKE CONCAT(v_datos, '%') OR id LIKE CONCAT(v_datos, '%'));
END$$