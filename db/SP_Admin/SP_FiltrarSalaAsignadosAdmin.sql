use Whatsapp;
DROP PROCEDURE IF EXISTS SP_FiltrarSalaAsignadosAdmin;

DELIMITER $$
CREATE PROCEDURE SP_FiltrarSalaAsignadosAdmin(IN v_dato VARCHAR(255), IN v_usuario VARCHAR(255))
BEGIN

DECLARE v_id INT;
SET v_id = (SELECT id FROM Agentes WHERE usuario = v_usuario);


    SELECT * FROM dialogs WHERE idAgentes = v_id AND (id LIKE CONCAT(v_dato, '%')  OR name LIKE CONCAT(v_dato, '%'));
END $$