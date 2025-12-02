<?php

require '../config/config.php';


if (!isset($_SESSION['user_type'])) {
    header('Location: index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}
    

//Realizamos una validacionpara ver si es que la imagen realmente existe
$urlImagen = $_POST['urlImagen'] ?? '';

//si la imagen existe, de elimina la imagen del link que se está pasando
if($urlImagen !== '' && file_exists($urlImagen)){
    unlink($urlImagen);
}