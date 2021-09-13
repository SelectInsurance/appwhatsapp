<?php
//Funciones para requerir encabezado, pie de pagina y menu
function higher()
{
    require_once 'app\master\views\assets\header.html';
}
function lower()
{
    require_once 'app\master\views\assets\footer.html';
}
function Nav()
{
    require_once 'app\master\views\assets\menu.phtml';
}




class controller
{
    //DashBoard
    public static function Inicio()
    {
        higher();
        Nav();
        lower();
    }


    //configuracion
    public static function Preferences()
    {
        higher();
        Nav();
        require_once 'app\master\views\modules\preferencias\preferences.phtml';
        lower();
    }
}
