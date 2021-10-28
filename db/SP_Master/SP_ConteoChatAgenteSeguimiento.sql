use whatsapp;
DROP PROCEDURE IF EXISTS SP_ConteoChatAgenteSeguimiento;

DELIMITER $$

CREATE PROCEDURE SP_ConteoChatAgenteSeguimiento(IN v_user VARCHAR(255))
    BEGIN

        DECLARE v_messageNumber VARCHAR(255);
        DECLARE v_sender VARCHAR(255);
        DECLARE v_idAgente VARCHAR(255);



        SET v_idAgente = (SELECT id FROM agentes WHERE user = v_user);
        SET v_messageNumber = (SELECT messageNumber FROM messages ORDER BY messageNumber DESC LIMIT 1);
        SET v_sender = (SELECT sender FROM messages WHERE messageNumber = v_messageNumber AND sender = v_user LIMIT 1);


        IF v_sender IS NULL THEN

            UPDATE dialogs SET seguimiento = FALSE WHERE idAgentes = v_idAgente;

            ELSE IF v_sender = v_user THEN

                UPDATE dialogs SET seguimiento = TRUE WHERE idAgentes = v_idAgente;
            END IF;
        END IF;

    END $$