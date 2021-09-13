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
    ){
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
    ){
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
}
