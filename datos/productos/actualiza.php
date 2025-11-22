<?php
require '../config/database.php';
require '../config/config.php';

if (!isset($_SESSION['user_type'])) {
    header('Location: index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

// Conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Datos del formulario
$id = $_POST['id_pieza'];
$nombre_pieza = $_POST['nombre_pieza'];
$marca = $_POST['marca'];
$codigo = $_POST['codigo'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$cantidad_disponible = $_POST['cantidad_disponible'];
$id_cat = $_POST['id_cat'];
$spin = $_POST['spin'];

// Llamar al Stored Procedure para actualizar el producto
$sql = "CALL sp_mod_prod_jesus(?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stm = $con->prepare($sql);
$stm->execute([$id, $nombre_pieza, $marca, $codigo, $descripcion, $precio, $cantidad_disponible, $id_cat, $spin]);

// Subir imagen principal
if ($_FILES['imagen_principal']['error'] == UPLOAD_ERR_OK) {
    $dir = '../../negocio/imagenes1/productos/' . $id . '/';
    $permitidos = ['jpeg', 'jpg', 'png'];
    
    $arregloImagen = explode('.', $_FILES['imagen_principal']['name']);
    $extension = strtolower(end($arregloImagen));

    if (in_array($extension, $permitidos)) {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $ruta_img = $dir . 'principal.' . $extension;
        move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $ruta_img);
    }
}

// Subir otras imágenes
if (isset($_FILES['otras_imagenes'])) {
    $dir = '../../negocio/imagenes1/productos/' . $id . '/';
    $permitidos = ['jpeg', 'jpg', 'png'];

    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }

    foreach ($_FILES['otras_imagenes']['tmp_name'] as $key => $tmp_name) {
        $fileName = $_FILES['otras_imagenes']['name'][$key];
        $arregloImagen = explode('.', $fileName);
        $extension = strtolower(end($arregloImagen));

        $nuevoNombre = $dir . uniqid() . '.' . $extension;

        if (in_array($extension, $permitidos)) {
            move_uploaded_file($tmp_name, $nuevoNombre);
        }
    }
}

header('Location: index.php');
?>
