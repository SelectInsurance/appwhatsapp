CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ConteoChatAsignados`(IN usuario VARCHAR(50))
BEGIN
SELECT count(idAgentes) FROM dialogs INNER JOIN Agentes ON Agentes.id = dialogs.idAgentes WHERE usuario = usuario;
END