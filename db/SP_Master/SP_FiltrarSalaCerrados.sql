USE whatsapp;
DROP PROCEDURE IF EXISTS SP_FiltrarSalaCerrados;

DELIMITER $$
CREATE PROCEDURE SP_FiltrarSalaCerrados(IN v_datos VARCHAR(255))
BEGIN 
    SELECT * FROM dialogs WHERE abierto = 0 AND (name LIKE CONCAT(v_datos, '%') OR id LIKE CONCAT(v_datos, '%'));
END$$