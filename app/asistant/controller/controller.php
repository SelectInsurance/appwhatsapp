<?php



//Funciones para requerir encabezado, pie de pagina y menu
function higher()
{
    require_once 'app\asistant\views\assets\header.html';
}

function Nav()
{
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
}
