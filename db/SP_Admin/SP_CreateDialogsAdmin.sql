USE Whatsapp;
DROP PROCEDURE IF EXISTS SP_CreateDialogsAdmin;
DELIMITER $$
CREATE PROCEDURE SP_CreateDialogsAdmin(IN v_id VARCHAR(255), IN v_name VARCHAR(255), IN v_image VARCHAR(255), IN v_last_time VARCHAR(255), IN v_user VARCHAR(255))
BEGIN
DECLARE v_id INT;

SET v_id = (SELECT id FROM Agentes WHERE usuario = v_user);

INSERT INTO dialogs(id, name, image, last_time, idAgentes) VALUES(v_id, v_name, v_image, v_last_time, v_id);

END $$