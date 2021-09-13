<?php
require_once 'app\master\models\app_autoload.php';

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

    //Cerrar Session
    public static function Cerrar()
    {
        session_destroy();
        header('Location:./');
    }


    //configuracion
    public static function Preferences()
    {
        higher();
        Nav();
        require_once 'app\master\views\modules\preferencias\preferences.phtml';
        lower();
    }

    //Json que se muestra en el dataTable para consultar Agente
    public static function Datatable()
    {

        $ReadAgente = crud::Read(query::ReadAgentes());
        while ($Resultado = mysqli_fetch_assoc($ReadAgente)) {
            $rows["data"][] = $Resultado;
        }

        echo json_encode($rows);
    }


    //Insertando Datos por metodo post usando Ajax de jquery
    public static function AgregarAgente()
    {
        $creador = $_SESSION['Master'];
        $user = $_POST['user'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $documento = $_POST['documento'];
        $admin = boolval($_POST['admin']);
        $master = boolval($_POST['master']);
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $correo = $_POST['correo'];
        $password = md5($_POST['password']);
        $ConfirmacionPassword = md5($_POST['ConfirmacionPassword']);

        //Validacion de pass identica
        if ($password === $ConfirmacionPassword) {


            //Validacion de usuario master o no
            if ($master === TRUE) {
                crud::Create(query::CreateUsuario(
                    $user,
                    $password,
                    TRUE,
                    TRUE
                ));

                crud::Create(query::CreateAgente(
                    $nombre,
                    $apellido,
                    $documento,
                    $telefono,
                    $direccion,
                    $correo,
                    $creador,
                    $user
                ));
            } else {
                crud::Create(query::CreateUsuario(
                    $user,
                    $password,
                    $admin,
                    $master
                ));

                crud::Create(query::CreateAgente(
                    $nombre,
                    $apellido,
                    $documento,
                    $telefono,
                    $direccion,
                    $correo,
                    $creador,
                    $user
                ));
            }
            echo 'Agente Registrado Correctamente';
        } else {
            echo 'Las contraseñas no coinciden';
        }
    }
}
