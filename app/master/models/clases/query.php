<?php

//Aqui van todos los querys de el servicio login
class  query
{
    //Crear usuario
    public static function CreateUsuario(
        $usuario,
        $password,
        $admin,
        $maestro
    ) {
        return "INSERT INTO Usuarios(
            usuario, 
            password, 
            admin, 
            maestro
            ) VALUE(
                '$usuario',
                '$password',
                '$admin',
                '$maestro'
            )";
    }

    //Crear Agente
    public static function CreateAgente(
        $nombre,
        $apellido,
        $documento,
        $telefono,
        $direccion,
        $correo,
        $creador,
        $usuario
    ) {
        return "INSERT INTO Agentes(
                nombre,
                apellido,
                documento,
                telefono,
                direccion,
                correo,
                creador,
                usuario
            ) VALUES(
                '$nombre',
                '$apellido',
                '$documento',
                '$telefono',
                '$direccion',
                '$correo',
                '$creador',
                '$usuario'
            )";
    }

    //Consultar Todos los Agentes y usuarios
    public static function ReadAgentes()
    {
        return "
            SELECT * FROM Agentes
            INNER JOIN Usuarios ON 
            Agentes.usuario = Usuarios.usuario
            ";
    }

    //Cambiar Contraseña del Agente
    public static function UpdatePassword($user, $pass)
    {
        return "UPDATE usuarios SET password = '$pass' WHERE usuario = '$user'";
    }

    //Insertar dialogs
    public static function CreateDialogs($id,$name,$image,$last_time)
    {
        return "INSERT INTO dialogs(id,name,image,last_time) VALUES('$id','$name','$image','$last_time')";
    }

    public static function ReadDialogs()
    {
        return "SELECT * FROM dialogs";
    }

    //Crear AccesWebToken
    public static function CreateAwebT($instance, $token)
    {
        return "INSERT INTO TokenChatApi(Instance, Token) VALUES('$instance','$token')";
    }

    //Consultar AccesWebToken
    public static function ReadAwebT()
    {
        return "SELECT * FROM TokenChatApi ORDER BY idToken DESC Limit 1";
    }

    //Modificar Dialogs
    public static function UpdateDialogs($idAgente, $name){
        return "UPDATE dialogs set idAgentes = '$idAgente' WHERE name = '$name'";
    }
}
