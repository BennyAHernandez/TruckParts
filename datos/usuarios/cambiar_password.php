<?php
require '../config/database.php';
require '../config/config.php';
require '../clases/adminFunciones.php';


if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

//validacion de exitencia de atributos por metodo GET POST
//si no existe id en get, buscará user_id por POST, SIENDO UNA VALIDACIÓN MULTIPLE.
$user_id = $_GET['user_id'] ?? $_POST['user_id'] ?? '';


if ($user_id == '') {
    header("Location: index.html");
    exit;
}

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {

    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$user_id, $password, $repassword])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }

    if (empty($errors)) {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        if (actualizaPassword($user_id, $pass_hash, $con)) {
            $errors[] = "Contraseña modificada exitosamente.";
        } else {
            $errors[] = "Error al hacer el cambio de su contraseña. Favor de intentarlo nuevamente.";
        }
    }
}

//consulta a la base de datos de usuario para traer el usuario que se le realizará el cambio de contraseña
$sql = "SELECT id_usuario, usuario FROM usuario WHERE id_usuario= ?";
$sql = $con->prepare($sql);
$sql->execute([$user_id]);

$usuario = $sql->fetch(PDO::FETCH_ASSOC);

require '../header.php';

?>

<main class="form-login m-auto pt-4">
    <h3>Cambiar contraseña</h3>

    <?php mostrarMensajes($errors); ?>

    <form action="cambiar_password.php" method="post" class="row g-3" autocomplete="off">

        <input type="hidden" name="user_id" value="<?php echo $usuario['id_usuario']; ?>">

        <div class="form-floating">
            <input class="form-control" type="text"  id="usuario" value="<?php echo $usuario['usuario']; ?>" disabled>
            <label for="usuario">Usuario</label>
        </div>

        <div class="form-floating">
            <input class="form-control" type="password" name="password" id="password" placeholder="Nueva contraseña" required>
            <label for="password">Nueva Contraseña</label>
        </div>

        <div class="form-floating">
            <input class="form-control" type="password" name="repassword" id="repassword" placeholder="Confirmar contraseña" required>
            <label for="repassword">Confirmar Contraseña</label>
        </div>

        <div class="d-grid gap-3 col-12">
            <button type="submit" class="btn btn-primary">Actualizar contraseña</button>
        </div>

    </form>
</main>

<?php include '../footer.php';

