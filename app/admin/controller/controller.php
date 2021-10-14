<?php

require_once 'app\admin\models\app_autoload.php';

//Funciones para requerir encabezado, pie de pagina y menu
function higher()
{
    require_once 'app\admin\views\assets\header.html';
}

function Nav()
{
    $user = $_SESSION['Admin'];
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

    $user = $_SESSION['Admin'];
    //Salas de chat almacenadas en base de datos
    $consulta = crud::Read(query::ReadDialogs($user));


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
        require_once 'app\admin\views\modules\dashboard\dashboard.phtml';

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
        $user = $_SESSION['Admin'];
        $Resultado = crud::Read(query::ReadAgentes($user));
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

    //Insertando Datos por metodo post usando Ajax de jquery
    public static function AgregarAgente()
    {
        $creador = $_SESSION['Admin'];
        $user = $_POST['user'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $documento = $_POST['documento'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $correo = $_POST['correo'];
        $password = md5($_POST['password']);
        $ConfirmacionPassword = md5($_POST['ConfirmacionPassword']);

        //Validacion de pass identica
        if ($password === $ConfirmacionPassword) {
            //Validacion de usuario master o no
            crud::Create(query::CreateUsuario(
                $user,
                $password,
                FALSE,
                FALSE
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






    //TODO LO RELACIONADO CON EL ACCES WEB TOKEN
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
    /////////////////////////////////////





    //tODO LO RELACIONADO CON LOS CONTEOS DE CHAT
    //Mostrando Cantidad chat abiertos
    public static function MostrandoChatAbiertos()
    {
        $user = $_SESSION['Admin'];
        $Resultados = crud::Read(query::ReadChatAbiertos($user));
        $Array = mysqli_fetch_assoc($Resultados);
        print $Array['v_conteo'];
    }

    //Mostrando Cantidad Chat Cerrados
    public static function MostrandoChatCerrados()
    {
        $user = $_SESSION['Admin'];
        $Consulta = crud::Read(query::ReadConteoChatCerrados($user));
        $resultado = mysqli_fetch_assoc($Consulta);
        echo $resultado['v_conteo'];
    }

    //Mostrando Cantidad Chat Asignado a Agentes
    public static function MostrandoChatAsignados()
    {
        $user = $_SESSION['Admin'];
        $consulta = crud::Read(query::ReadChatAsignados($user));
        $row = mysqli_fetch_assoc($consulta);
        print $row['v_conteo'];
    }
    ///////////////////////////////////////






    //TODO LO RELACIONADO CON EL CHAT
    //Sala de chat individual
    public static function SalaChat()
    {
        //Condicion para obligar a tener si o si una sala de chat
        if (!empty($_POST['btnAbrirChat'])) {
            $id = $_POST['btnAbrirChat'];
            $SalaChat = str_replace('@c.us', '', $_POST['btnAbrirChat']);

            //Imagen Guardada
            $resultado = crud::Read(query::ReadImageDialogs($id));
            $image = mysqli_fetch_assoc($resultado);

            //ChatAbiertos
            crud::Update(query::UpdateDialogsAbrirChat($_POST['btnAbrirChat']));


            higher();
            Nav();
            require_once 'app\admin\views\modules\chat\chat.phtml';
            lower();
        } else {
            header('Location:./');
        }
    }

    //Mostrar Mensajes de chat individual
    public static function MostrarMensajesChat()
    {
        if (!empty($_POST['chatId'])) {
            $user = $_SESSION['Admin'];
            $id =  $_POST['chatId'];
            $url = mysqli_fetch_assoc(crud::Read(query::ReadAwebT($user)));
            $api = new ChatApi($url['Instance'], $url['Token']);
            $data = $api->messages();

            //cambiando ciclo foreach por ciclo while para hacer insercion a la base de datos usando 
            //la cantidad de indices que tiene el array
            $contador = count($data['messages']);
            $i = 0;
            while ($i < $contador) {
                if ($data['messages'][$i]['author'] === $data['messages'][$i]['chatId']) {
                    $sender[$i] = $data['messages'][$i]['author'];
                } elseif ($data['messages'][$i]['author'] != $data['messages'][$i]['chatId']) {
                    $sender[$i] = $_SESSION['Admin'];
                }
                crud::Create(query::CreateAlmacenarMensajes(
                    $data['messages'][$i]['id'],
                    $data['messages'][$i]['body'],
                    $data['messages'][$i]['fromMe'],
                    $data['messages'][$i]['self'],
                    $data['messages'][$i]['isForwarded'],
                    $data['messages'][$i]['author'],
                    $data['messages'][$i]['time'],
                    $data['messages'][$i]['chatId'],
                    $data['messages'][$i]['messageNumber'],
                    $data['messages'][$i]['type'],
                    $data['messages'][$i]['senderName'],
                    $data['messages'][$i]['quotedMsgBody'],
                    $data['messages'][$i]['quotedMsgId'],
                    $data['messages'][$i]['quotedMsgType'],
                    $data['messages'][$i]['metadata'],
                    $data['messages'][$i]['ack'],
                    $data['messages'][$i]['chatName'],
                    $sender[$i]
                ));
                $i++;
            }

            $consulta = crud::Read(query::ReadMensajesChat($id));
            $i = 0;
            while ($row = mysqli_fetch_assoc($consulta)) {

                $Array[$i]['id']              =   $row['id'];
                $Array[$i]['body']            =   $row['body'];
                $Array[$i]['fromMe']          =   $row['fromMe'];
                $Array[$i]['isForwarded']     =   $row['isForwarded'];
                $Array[$i]['author']          =   $row['author'];
                $Array[$i]['time']            =   $row['time'];
                $Array[$i]['chatId']          =   $row['chatId'];
                $Array[$i]['messageNumber']   =   $row['messageNumber'];
                $Array[$i]['type']            =   $row['type'];
                $Array[$i]['senderName']      =   $row['senderName'];
                $Array[$i]['quotedMsgBody']   =   $row['quotedMsgBody'];
                $Array[$i]['quotedMsgId']     =   $row['quotedMsgId'];
                $Array[$i]['quotedMsgType']   =   $row['quotedMsgType'];
                $Array[$i]['metadata']        =   $row['metadata'];
                $Array[$i]['ack']             =   $row['ack'];
                $Array[$i]['chatName']        =   $row['chatName'];
                $Array[$i]['FechaHora']       =   $row['FechaHora'];
                $Array[$i]['sender']          =   str_replace('@c.us', '', $row['sender']);
                $i++;
            }

            print json_encode($Array, JSON_PRETTY_PRINT);
        }
    }

    //Enviar Mensajes de chat individual
    public static function EnviarMensajesChat()
    {

        $user = $_SESSION['Admin'];
        $UrlToken = mysqli_fetch_assoc(crud::Read(query::ReadAwebT($user)));
        $Api = new ChatApi($UrlToken['Instance'], $UrlToken['Token']);
        $Phone = $_POST['chatId'];
        $message = $_POST['txtCuerpoMensage'];
        echo $Api->SendMenssage($Phone, $message);
        //echo $Phone.' '.$message;
    }
    ///////////////////////////////////
}
