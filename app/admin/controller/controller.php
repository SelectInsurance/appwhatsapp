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
}
