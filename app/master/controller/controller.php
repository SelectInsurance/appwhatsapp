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

    //Salas de chat almacenadas en base de datos
    $consulta = crud::Read(query::ReadDialogs());
    require_once 'app/master/views/assets/menu.phtml';
    require_once 'app/master/views/assets/contentheader.phtml';
}

function lower()
{
    require_once 'app/master/views/assets/contentfooter.phtml';
    require_once 'app/master/views/assets/footer.html';
}

class controller
{
    //DashBoard
    public static function Inicio()
    {
        if (isset($_SESSION['Master'])) {
            higher();
            Nav();

            require_once 'app/master/views/modules/dashboard/dashboard.phtml';
            lower();
        } else {
            header('Location:Login');
        }
    }

    //Validacion cuando ingresan al login logeados
    public static function Login()
    {
        header('Location:Inicio');
    }

    //Cantidad Salas de Chat
    public static function CantidadSalasChat()
    {
        $consulta = crud::Read(query::ReadDialogs());
        $i = 0;
        while ($row = mysqli_fetch_array($consulta)) {
            $Array[$i]['id'] = $row['id'];
            $Array[$i]['name'] = $row['name'];
            $Array[$i]['image'] = $row['image'];
            $Array[$i]['last_name'] = $row['last_name'];
            $i++;
        }
        $conteo = count($Array);
        echo $conteo;
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
            $id = $_POST['btnAbrirChat'];
            $SalaChat = str_replace('@c.us', '', $_POST['btnAbrirChat']);

            //Imagen Guardada
            $resultado = crud::Read(query::ReadImageDialogs($id));
            $image = mysqli_fetch_assoc($resultado);

            //ChatAbiertos
            crud::Update(query::UpdateDialogsAbrirChat($_POST['btnAbrirChat']));


            higher();
            Nav();
            require_once 'app/master/views/modules/chat/chat.phtml';
            lower();
        } else {
            header('Location:./');
        }
    }

    //Mostrar Mensajes de chat individual
    public static function MostrarMensajesChat()
    {
        if (!empty($_POST['chatId'])) {

            $id =  $_POST['chatId'];
            $url = mysqli_fetch_assoc(crud::Read(query::ReadAwebT()));
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
                    $sender[$i] = $_SESSION['Master'];
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
                $Array[$i]['sender']          =   str_replace('@c.us', '', $row['sender']);
                $i++;
            }

            print json_encode($Array, JSON_PRETTY_PRINT);
        }
    }

    //Mostrar Mensajes en seguimiento
    public static function MostrarMensajesSeguimiento()
    {
    }

    //Enviar Mensajes de chat individual
    public static function EnviarMensajesChat()
    {


        $UrlToken = mysqli_fetch_assoc(crud::Read(query::ReadAwebT()));
        $Api = new ChatApi($UrlToken['Instance'], $UrlToken['Token']);
        $Phone = $_POST['chatId'];
        $message = $_POST['txtCuerpoMensage'];
        echo $Api->SendMenssage($Phone, $message);
        //echo $Phone.' '.$message;
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
    public static function TransferirChat()
    {
        higher();
        Nav();
        require_once 'app/master/views/modules/TransferenciaChat/TransferenciaChat.phtml';
        lower();
    }

    //Consultando Datos del Modulo Transferir
    public static function ConsultandoUsuarioATransferir()
    {
        $Consulta = crud::Read(query::ReadAgentes());
        while ($Resultado = mysqli_fetch_assoc($Consulta)) {
            $rows["data"][] = $Resultado;
        }
        echo json_encode($rows);
    }

    //Consultando Salas de chat en etiqueta Select
    public static function ConsultandoSalasChatSelector()
    {
        $consulta = crud::Read(query::ReadDialogs());
        $i = 0;
        while ($rows = mysqli_fetch_assoc($consulta)) {
            $Array[$i]['name'] = $rows['name'];
            $i++;
        }
        $json = json_encode($Array, JSON_PRETTY_PRINT);
        print $json;
    }

    //Transfiriendo Sala Chat a un Agente
    public static function UpdateDialogs()
    {
        if (isset($_POST)) {
            $idAgente = $_POST['IdAgenteTransferir'];
            $name = $_POST['SeleccionSalaChat'];
            crud::Update(query::UpdateDialogs($idAgente, $name));
            echo 'Transferencia Exitosa';
        } else {
            echo 'No se pudo Transferir';
        }
    }

    //Mostrar Cantidad Chats Asignados a Agente
    public static function MostrarDialogsAsignadosChat()
    {
    }

    //Mostrando Cantidad chat abiertos
    public static function MostrandoChatAbiertos()
    {
        $consulta = crud::Read(query::ReadChatAbiertos());
        $i = 0;
        while ($row = mysqli_fetch_array($consulta)) {
            $Array[$i]['abierto'] = $row['abierto'];
            $i++;
        }
        $conteo = count($Array);
        echo $conteo;
    }

    //Mostrando Cantidad Chat Asignado a Agentes
    public static function MostrandoChatAsignados()
    {
        $consulta = crud::Read(query::ReadChatAsignados());
        $row = mysqli_fetch_assoc($consulta);
        echo $row['count(idAgentes)'];
    }

    //Tabla para mostrar cantidad de chat asignados a cada agente
    public static function TablaChatAsignadoAgente()
    {
        $consulta = crud::Read(query::ReadChatAsignadosAgentes());

        $i = 0;
        while ($row = mysqli_fetch_assoc($consulta)) {

            //Logica para sacar la consulta con la funcion count de mysql
            $Resultado = crud::Read(query::ReadConteoChatAsignadosAgentes($row['usuario']));
            foreach (mysqli_fetch_assoc($Resultado) as $count) {
                $conteo = $count;
            }


            $ArrayAgentes[$i]['count'] = $conteo;
            $ArrayAgentes[$i]['id'] = $row['id'];
            $ArrayAgentes[$i]['usuario'] = $row['usuario'];
            $ArrayAgentes[$i]['nombre'] = $row['nombre'];
            $ArrayAgentes[$i]['apellido'] = $row['apellido'];
            $i++;
        }
        $json = json_encode($ArrayAgentes, JSON_PRETTY_PRINT);
        print $json;
    }


    //Modulo mostrar Conversacion Agente Seleccionado desde Dashboard
    public static function MostrandoConversacionAgente()
    {
        $idAgente = $_POST['idAgente'];

        higher();
        Nav();
        require_once 'app\master\views\modules\conversacion\conversacion.php';
        lower();
    }

    //Consulta para dirigir al DAtatable de mostrar conversaciones
    public static function MostrarConversacionesConsulta()
    {
        //if (!empty($_POST['id'])) {
            //$ReadAgente = crud::Read(query::ReadAgentes());
            //while ($Resultado = mysqli_fetch_assoc($ReadAgente)) {
            //    $rows["data"][] = $Resultado;
            //}
            //echo json_encode($rows);
    
    
    
    
            //$id = $_POST['id'];
            $id = '1';
            $Resultados = crud::Read(query::ReadChatAgente($id));
            var_dump($Resultados);
            while ($conversacion = mysqli_fetch_assoc($Resultados)) {
                $rows['data'][]       =    $conversacion;
            }
            //var_dump($rows);

            //echo json_encode($rows);
            print json_encode($rows);
        //}

    }
}
