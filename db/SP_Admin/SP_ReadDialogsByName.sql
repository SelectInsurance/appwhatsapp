use Whatsapp;
DROP PROCEDURE IF EXISTS SP_ReadDialogsByName;

DELIMITER $$
CREATE PROCEDURE SP_ReadDialogsByName(IN v_dato VARCHAR(255), IN v_user VARCHAR(255))
BEGIN
DECLARE v_id INT;
SET v_id = (SELECT id FROM Agentes WHERE usuario = v_user);

SELECT * FROM dialogs WHERE idAgentes = v_id AND (name LIKE CONCAT(v_dato,'%'));

END $$