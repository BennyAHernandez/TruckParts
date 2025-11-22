<?php

function esNulo(array $parametros){
    foreach($parametros as $parametro){
        if(strlen(trim($parametro)) < 1){
            return true;
        }
    }
    return false;
}

function validaPassword($password, $repassword){
    if(strcmp($password, $repassword) === 0){
        return true;
    }
    return false;
}


function usuarioExiste($usuario, $con) {
    $sql = $con->prepare("CALL sp_usuario_existe_jesus(?, @p_existe)");
    $sql->execute([$usuario]);

    // Obtener el resultado del procedimiento almacenado
    $result = $con->query("SELECT @p_existe AS existe");
    $row = $result->fetch(PDO::FETCH_ASSOC);

    return $row['existe'] == 1;
}


function emailExiste($correo, $con) {
    $sql = $con->prepare("CALL sp_email_existe_jesus(?, @p_existe)");
    $sql->execute([$correo]);

    // Obtener el resultado del procedimiento almacenado
    $result = $con->query("SELECT @p_existe AS existe");
    $row = $result->fetch(PDO::FETCH_ASSOC);

    return $row['existe'] == 1;
}


function mostrarMensajes(array $errors){
    if(count($errors) > 0){
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach($errors as $error){
            echo '<li>'. $error . '</li>';
        }
        echo '</ul>';
        echo '  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}

function validaToken($id, $token, $con) {
    $sql = $con->prepare("CALL sp_valida_token_jesus(?, ?, @p_msg)");
    $sql->execute([$id, $token]);

    // Obtener el mensaje de salida del procedimiento almacenado
    $result = $con->query("SELECT @p_msg AS mensaje");
    $row = $result->fetch(PDO::FETCH_ASSOC);

    return $row['mensaje'];
}

function activarUsuario($id, $con) {
    $sql = $con->prepare("CALL sp_activar_usuario_jesus(?, @p_resultado)");
    $sql->execute([$id]);

    // Obtener el resultado del procedimiento almacenado
    $result = $con->query("SELECT @p_resultado AS resultado");
    $row = $result->fetch(PDO::FETCH_ASSOC);

    return $row['resultado'];
}


//Funciones de login
function login($usuario, $password, $con){
    $sql = $con->prepare("SELECT id_admin, usuario, password, nombre FROM administrador WHERE usuario LIKE ? 
    AND activo = 1 LIMIT 1");
    $sql->execute([$usuario]);
    if($row= $sql->fetch(PDO::FETCH_ASSOC)){
            if(password_verify($password, $row['password'])){
                $_SESSION['user_id'] = $row['id_admin'];
                $_SESSION['user_name'] = $row['nombre'];
                $_SESSION['user_type'] = 'admin';
                header('Location: inicio.php');
                exit;
            }
    }
    return 'El usuario y/o contraseña son incorrectos.';
}

function solicitaPassword($user_id, $con){

    $token = generarToken();

    $sql = $con->prepare("UPDATE usuario SET token_password=?, password_request=1 WHERE id_usuario = ?");
    if($sql->execute([$token, $user_id])){
        return $token;
    }
    return null;
}

function verificaTokenRequest($user_id, $token, $con){
    $sql = $con->prepare("SELECT id_usuario FROM usuario WHERE id_usuario = ? 
    AND token_password LIKE ? AND password_request=1 LIMIT 1");
    $sql->execute([$user_id, $token]);
    if($sql->fetchColumn() > 0){
        return true;
    }
    return false;

}

function actualizaPassword($user_id, $password, $con){
    $sql = $con->prepare("UPDATE usuario SET password=?, 
    token_password = '', password_request = 0 WHERE id_usuario = ?");
    if($sql->execute([$password, $user_id])){
        return true;
    }
    return false;
}

function actualizaPasswordAdmin($user_id, $password, $con){
    $sql = $con->prepare("UPDATE administrador SET password=? WHERE id_admin = ?");
    if($sql->execute([$password, $user_id])){
        return true;
    }
    return false;
}