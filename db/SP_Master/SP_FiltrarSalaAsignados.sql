use whatsapp;
DROP PROCEDURE IF EXISTS SP_FiltrarSalaAsignados;

DELIMITER $$
CREATE PROCEDURE SP_FiltrarSalaAsignados(IN v_dato VARCHAR(255))
BEGIN
    SELECT * FROM dialogs WHERE idAgentes <> '' AND (id LIKE CONCAT(v_dato, '%')  OR name LIKE CONCAT(v_dato, '%'));
END $$