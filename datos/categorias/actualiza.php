<?php 
require '../config/database.php';
require '../config/config.php';

if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin') {
    header('Location: ../../index.php');
    exit;
}

// Conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Obtener los datos del formulario
$id = $_POST['id_cat'];
$nombre = $_POST['nombre'];

// Llamar al Stored Procedure
$sql = $con->prepare("CALL sp_mod_cat_jesus(?, ?)");
$sql->execute([$id, $nombre]);

// Redirigir después de la modificación
header('Location: index.php');
?>
