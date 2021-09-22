<?php
require_once 'app/master/models/app_autoload.php';

//Funciones para requerir encabezado, pie de pagina y menu
function higher()
{
    require_once 'app/master/views/assets/header.html';
}
function Nav()
{
    //Recibiendo Salas de chat abiertas desde la app de whatsapp
    $AwebT = mysqli_fetch_assoc(crud::Read(query::ReadAwebT()));
    $ChatApi = new ChatApi($AwebT['Instance'], $AwebT['Token']);
    $array = $ChatApi->Dialogs();

    //logica para sacar cantidad de indices y recorrer el array con la cantidad de indices
    foreach ($array as $key => $value) {
        $j = count($value);
        $i = 0;

        while ($i < $j) {
            crud::Create(query::CreateDialogs($value[$i]['id'],$value[$i]['name'],$value[$i]['image'],$value[$i]['last_time']));
            $i++;
        }
    }

    //Salas de chat almacenadas en base de datos
    $consulta = crud::Read(query::ReadDialogs());
    require_once 'app/master/views/assets/menu.phtml';
}

function lower()
{
    require_once 'app/master/views/assets/footer.html';
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
        $Resultado = crud::Read(query::ReadAgentes());
        require_once 'app/master/views/modules/preferencias/preferences.phtml';
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

    //Cambiando Contraseña de los Agentes usando Ajax por metodo post
    public static function CambiarContrasena()
    {
        if (isset($_POST['btnCambiarContrasenaAgente'])) {
            $User = $_POST['UsuarioAgenteCambioContrasena'];
            $NuevaContrasena = $_POST['NuevaContrasena'];
            $confirmarNuevaContrasena = $_POST['ConfirmarNuevaContrasena'];
            if ($NuevaContrasena === $confirmarNuevaContrasena) {
                crud::Update(query::UpdatePassword($User, md5($NuevaContrasena)));
                header('Location:./Preferences');
            }
        }
    }

    //Sala de chat individual
    public static function SalaChat()
    {
        //Condicion para obligar a tener si o si una sala de chat
        if (!empty($_POST['btnAbrirChat'])) {
            $SalaChat = str_replace('@c.us','',$_POST['btnAbrirChat']);
            higher();
            Nav();
            require_once 'app/master/views/modules/chat/chat.phtml';
            lower();
        } else {
            header('Location:./');
        }
    }

    //form para insertar accesweb token
    public static function formAccesWebToken()
    {
        higher();
        Nav();
        require_once 'app/master/views/modules/config/config.html';

        lower();
    }

    //ingreso de AccesWebToken por ajax por metodo post
    public static function InsertAccesWebToken()
    {
        $instance = trim($_POST['instancia']);
        $token = trim($_POST['token']);
        crud::Read(query::CreateAwebT($instance, $token));
        echo 'Registro Exitoso';
    }

    //Mostrando AccesWebToken por ajax en la tabla
    public static function ReadAccesWebToken()
    {
        $Consulta = crud::Read(query::ReadAwebT());
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

    //Modulo Transferir chat
    public static function TransferirChat(){
        higher();
        Nav();
        require_once 'app/master/views/modules/TransferenciaChat/TransferenciaChat.phtml';
        lower();
    }

    //Consultando Datos del Modulo Transferir
    public static function ConsultandoUsuarioATransferir(){
        $Consulta = crud::Read(query::ReadAgentes());
        while ($Resultado = mysqli_fetch_assoc($Consulta)) {
            $rows["data"][] = $Resultado;
        }
        echo json_encode($rows);
    }

    //Consultando Salas de chat en etiqueta Select
    public static function ConsultandoSalasChatSelector(){
        $consulta = crud::Read(query::ReadDialogs());
        $i = 0;
        while ($rows = mysqli_fetch_assoc($consulta)) {
            $Array[$i]['name'] = $rows['name'];
            $i++;
        }
        $json = json_encode($Array, JSON_PRETTY_PRINT);
        print $json;

    }
}
