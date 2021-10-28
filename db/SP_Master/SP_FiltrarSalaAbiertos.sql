use whatsapp;
DROP PROCEDURE IF EXISTS SP_FiltrarSalaAbiertos;

DELIMITER $$
CREATE PROCEDURE SP_FiltrarSalaAbiertos(IN v_datos VARCHAR(255))
BEGIN 

    SELECT * FROM dialogs WHERE abierto = 1 AND (name LIKE concat(v_datos, '%')  || id LIKE concat(v_datos, '%'));
END $$