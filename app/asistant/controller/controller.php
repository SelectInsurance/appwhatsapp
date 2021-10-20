<?php

require_once 'app\asistant\models\app_autoload.php';

//Funciones para requerir encabezado, pie de pagina y menu
function higher()
{
    require_once 'app\asistant\views\assets\header.html';
}

function Nav()
{
    $user = $_SESSION['Asistant'];
    //Recibiendo Salas de chat abiertas desde la app de whatsapp
    $AwebT = mysqli_fetch_assoc(crud::Read(query::ReadAwebT($user)));
    $ChatApi = new ChatApi($AwebT['Instance'], $AwebT['Token']);
    $array = $ChatApi->Dialogs();

    //var_dump($array);

    //logica para sacar cantidad de indices y recorrer el array con la cantidad de indices
    foreach ($array as $key => $value) {
        $j = count($value);
        $i = 0;

        while ($i < $j) {
            crud::Create(query::CreateDialogs($value[$i]['id'], $value[$i]['name'], $value[$i]['image'], $value[$i]['last_time']));
            $i++;
        }
    }

    $user = $_SESSION['Asistant'];
    //Salas de chat almacenadas en base de datos
    $consulta = crud::Read(query::ReadDialogs($user));




    require_once 'app\asistant\views\assets\Nav.phtml';
}

function lower()
{
    require_once 'app\asistant\views\assets\footer.html';
}


class controller
{
    //DashBoard
    public static function Inicio()
    {
        higher();
        Nav();
        require_once 'app\asistant\views\modules\dashboard\dashboard.phtml';
        lower();
    }

    //Validacion cuando ingresan al login logeados
    public static function Login()
    {
        header('Location:Inicio');
    }
    
    //Cerrar Session
    public static function Cerrar()
    {
        session_destroy();
        header('Location:./');
    }
}
