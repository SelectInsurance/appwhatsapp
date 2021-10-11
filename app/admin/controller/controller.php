<?php

require_once 'app\admin\models\app_autoload.php';

//Funciones para requerir encabezado, pie de pagina y menu
function higher()
{
    require_once 'app\admin\views\assets\header.html';
}

function Nav()
{
    require_once 'app\admin\views\assets\menu.phtml';
}

function lower()
{
    require_once 'app\admin\views\assets\footer.html';
}


class controller
{
    //DashBoard
    public static function Inicio()
    {
        higher();
        Nav();
        require_once 'app\admin\views\modules\Inicio\inicio.phtml';

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

    //Preferencias
    public static function Preferences()
    {
        higher();
        Nav();

        require_once 'app\admin\views\modules\Preferencias\preferences.phtml';
        lower();
    }

    //Json que se muestra en el dataTable para consultar Agente
    public static function Datatable()
    {
        $creador = $_SESSION['Admin'];
        $ReadAgente = crud::Read(query::ReadAgentes($creador));
        while ($Resultado = mysqli_fetch_assoc($ReadAgente)) {
            $rows["data"][] = $Resultado;
        }
        echo json_encode($rows);
    }

    //form para insertar accesweb token
    public static function formAccesWebToken()
    {
        higher();
        Nav();
        require_once 'app\admin\views\modules\config\config.html';

        lower();
    }

    //ingreso de AccesWebToken por ajax por metodo post
    public static function InsertAccesWebToken()
    {
        $user = $_SESSION['Admin'];
        $instance = trim($_POST['instancia']);
        $token = trim($_POST['token']);
        crud::Read(query::CreateAwebT($instance, $token, $user));
        echo 'Registro Exitoso';
    }

    //Mostrando AccesWebToken por ajax en la tabla
    public static function ReadAccesWebToken()
    {
        $user = $_SESSION['Admin'];
        $Consulta = crud::Read(query::ReadAwebT($user));
        $i = 0;
        while ($rows = mysqli_fetch_assoc($Consulta)) {

            $Array[$i]['idToken'] = $rows['idToken'];
            $Array[$i]['Instance'] = $rows['Instance'];
            $Array[$i]['Token'] = $rows['Token'];
            $i++;
        }
        $json = json_encode($Array, JSON_PRETTY_PRINT);
        print $json;
    }
}
