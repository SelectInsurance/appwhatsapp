DROP PROCEDURE IF EXISTS SP_MostrarMensajesChat;

delimiter //
CREATE PROCEDURE SP_MostrarMensajesChat(in v_id TEXT)
BEGIN

DECLARE v_mensajes TEXT;
SET v_mensajes = (SELECT * FROM messages WHERE id = v_id);
SELECT v_mensajes;

END //
delimiter ;