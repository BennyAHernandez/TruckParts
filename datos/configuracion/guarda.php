<?php
require '../config/database.php';
require '../config/config.php';
require '../header.php';
require '../clases/cifrado.php';

//conexión a la base de datos
$db = new Database();
$con = $db->conectar();

$smtp = $_POST['smtp'];
$puerto = $_POST['puerto'];
$correo = $_POST['correo'];
$password = cifrar($_POST['password']);

$sql = $con->prepare("UPDATE configuracion SET valor = ? WHERE nombre = ?");
$sql->execute([$smtp, 'correo_smtp']);
$sql->execute([$puerto, 'correo_puerto']);
$sql->execute([$correo, 'correo_email']);
$sql->execute([$password, 'correo_password']);



?>
<main>
    <div class="container-fluid px-4">
        <h2 class="mt-4">Configuracion actualizada</h2>
        <a href="index.php" class="btn btn-secondary">Regresar</a>



    </div>
</main>

<?php include '../footer.php' ?>