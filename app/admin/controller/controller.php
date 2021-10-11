<?php



//Funciones para requerir encabezado, pie de pagina y menu
function higher()
{
    require_once 'app\admin\views\assets\header.html';
}

function Nav()
{
    require_once 'app\admin\views\assets\nav.phtml';
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
        require_once 'app\admin\views\modules\inicio.phtml';

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
