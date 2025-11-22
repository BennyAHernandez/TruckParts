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

$id = $_POST['id_cat'];

// Llamar al Stored Procedure
$sql = $con->prepare("CALL sp_del_cat_jesus(?)");
$sql->execute([$id]);

header('Location: index.php');
?>
