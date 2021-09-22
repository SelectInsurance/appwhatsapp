<?php
session_start();

//Master
if (isset($_SESSION['Master'])) {
    require_once 'app/master/controller/controller.php';
    if (isset($_GET['controller'])) {
        $controller = $_GET['controller'];
        switch ($_GET['controller']) {
            case $controller:
                controller::$controller();
                break;
        }
    }else {
        header('Location:Inicio');
    }



    //Admin
} elseif (isset($_SESSION['Admin'])) {

    echo $_SESSION['Admin'];

    //Asistente
} elseif (isset($_SESSION['Asistant'])) {

    echo $_SESSION['Asistant'];

    //Login
} else {
    require_once 'app/Login/controller/controller.php';
    $controller = $_GET['controller'];
    if (isset($_GET['controller'])) {
        switch ($_GET['controller']) {
            case $controller:
                controller::$controller();
                break;
        }
    } else {
        header('Location:Login');
    }
}
