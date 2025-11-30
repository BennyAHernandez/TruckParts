<?php

require_once '../config/database.php';
require_once 'clienteFunciones.php';

//validamos la existencia de usuario por medio de la existencia de su correo por metodo AJAX
$datos=[];

if(isset($_POST['action'])){
    $action = $_POST['action'];
    
    $db = new Database();
    $con = $db->conectar();

    if($action == 'existeUsuario'){
        $datos['ok'] = usuarioExiste($_POST['usuario'], $con);

    }elseif($action = 'existeEmail'){
        $datos['ok'] = emailExiste($_POST['correo'], $con);

    }
}

echo json_encode($datos);