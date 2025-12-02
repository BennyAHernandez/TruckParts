<?php
require '../config/database.php';
require '../config/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();


$sql = "SELECT usuario.id_usuario, CONCAT(cliente.nombre, ' ', cliente.apellido_pat, ' ', cliente.apellido_mat) 
AS cliente, usuario.usuario, usuario.activo, 
CASE 
WHEN usuario.activo= 1 THEN 'Activo'
WHEN usuario.activo= 0 THEN 'No activo'
ELSE 'Deshabilitado'
END AS estatus
FROM usuario
INNER JOIN cliente ON usuario.id_cliente = cliente.id_cliente";

$resultado = $con->query($sql);

require '../header.php';

?>

<main>
    <div class="container">
        <hr>
        <h4>Usuarios:</h4>

        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Estado</th>
                    <th>Detalles</th>

                </tr>
            </thead>

            <tbody>


                <?php while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo $row['cliente'] ?></td>
                        <td><?php echo $row['usuario'] ?></td>
                        <td><?php echo $row['estatus'] ?></td>
                        <td>

                            <a href="cambiar_password.php?user_id=<?php echo $row['id_usuario']; ?>"
                                class="btn btn-warning btn-sm">Cambiar Contraseña</a>
                            
                            <?php if ($row['activo'] == 1) : ?>

                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#eliminaModal" data-bs-user="<?php echo $row['id_usuario']; ?>">
                                    Dar de baja</button>

                            <?php else : ?>
                            
                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                    data-bs-target="#activaModal" data-bs-user="<?php echo $row['id_usuario']; ?>">
                                    Activar usuario</button>

                            <?php endif; ?>
                        </td>

                    </tr>

                <?php } ?>
            </tbody>
        </table>
    </div>
</main>

<!-- eliminaModal -->
<div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="eliminaModalLabel">Alerta:</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Desea desactivar a este usuario?
            
            </div>
            <div class="modal-footer">
                <form action="desactiva.php" method="post">
                    <input type="hidden" name="id_usuario">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-danger">Desactivar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- activaModal -->
<div class="modal fade" id="activaModal" tabindex="-1" aria-labelledby="activaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="activaModalLabel">Alerta:</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Desea activar a este usuario?
            
            </div>
            <div class="modal-footer">
                <form action="activa.php" method="post">
                    <input type="hidden" name="id_usuario">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-success">Activar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const eliminaModal = document.getElementById('eliminaModal')
    eliminaModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const user = button.getAttribute('data-bs-user')
        const inputId = eliminaModal.querySelector('.modal-footer input')

        inputId.value = user
   
    })

    const activaModal = document.getElementById('activaModal')
    activaModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const user = button.getAttribute('data-bs-user')
        const inputId = activaModal.querySelector('.modal-footer input')

        inputId.value = user
   
    })


</script>
<?php include '../footer.php'; ?>