<?php
require '../config/database.php';
require '../config/config.php';
require '../header.php';


if (!isset($_SESSION['user_type'])) {
    header('Location: index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

//conexión a la base de datos
$db = new Database();
$con = $db->conectar();

$id = $_GET['id_pieza'];

//consulta a la bd de productos para colocar sus datos en cada input correspondiente.
$sql = $con->prepare("SELECT id_pieza, nombre_pieza, marca, codigo, descripcion, precio, cantidad_disponible, id_cat, spin 
FROM productos WHERE id_pieza= ? AND activo= 1");
$sql->execute([$id]);
$producto = $sql->fetch(PDO::FETCH_ASSOC);

//consulta a la bd de categorias para habilitar el botón de categorias en el formulario
$sql = "SELECT id_cat, nombre FROM categoria WHERE activo = 1";
$resultado = $con->query($sql);
$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);

//variable para traer las imagenes1 de los productos ingresados
$rutaImagenes = '../../negocio/imagenes1/productos/' . $id . '/';
$imagenPrincipal = $rutaImagenes . 'principal.jpg';

//arreglo para traer las otras imagenes1 insertadas
$imagenes1 = [];
$dirInit = dir($rutaImagenes);

//traer todos los archivos que no sean la imagen principal y cumplan con la extension validada por medio de un while
while (($archivo = $dirInit->read()) !== false) {
    if ($archivo != 'principal.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg') || strpos($archivo, 'png'))) {
        $image = $rutaImagenes . $archivo;
        //el arreglo contndrá todas las imagenes1 extra del producto en cuestion
        $imagenes1[] = $image;
    }
}
$dirInit->close();

?>

<style>
    .ck-editor__editable[role="textbox"] {
        min-height: 250px;
    }
</style>

<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.3.0/ckeditor5.css" />

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Modificar producto</h2>

        <form action="actualiza.php" method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="id_pieza" value="<?php echo $producto['id_pieza']; ?>">
            <div class="mb-3">
                <label for="nombre_pieza" class="form-label">Nombre</label>
                <input
                    type="text"
                    class="form-control"
                    name="nombre_pieza"
                    id="nombre_pieza"
                    value="<?php echo htmlspecialchars($producto['nombre_pieza'], ENT_QUOTES); ?>"
                    required
                    autofocus />
            </div>
            <div class="mb-3">
                <label for="marca" class="form-label">Marca</label>
                <input
                    type="text"
                    class="form-control"
                    name="marca"
                    id="marca"
                    value="<?php echo $producto['marca']; ?>"
                    required />
            </div>
            <div class="mb-3">
                <label for="codigo" class="form-label">Codigo</label>
                <input
                    type="text"
                    class="form-control"
                    name="codigo"
                    id="codigo"
                    value="<?php echo $producto['codigo']; ?>"
                    required />
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripcion</label>
                <textarea
                    class="form-control"
                    name="descripcion"
                    id="editor"><?php echo $producto['descripcion']; ?>
                </textarea>
            </div>

            <div class="row">
                <div class="col mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input
                        type="number"
                        class="form-control"
                        name="precio"
                        id="precio"
                        value="<?php echo $producto['precio']; ?>"
                        required />
                </div>
                <div class="mb-3">
                <label for="spin" class="form-label">Link video 3D</label>
                <input
                    type="text"
                    class="form-control"
                    name="spin"
                    id="spin"
                    value="<?php echo $producto['spin']; ?>"
                    />
            </div>

                <div class="row mb-2">
                    <div class="col-12 col-md-6">
                        <label for="imagen_principal" class="form-label">Imagen Principal</label>
                        <input
                            type="file"
                            class="form-control"
                            name="imagen_principal"
                            id="imagen_principal"
                            accept="image/jpeg" />
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="otras_imagenes" class="form-label">Otras_imagenes</label>
                        <input
                            type="file"
                            class="form-control"
                            name="otras_imagenes[]"
                            id="otras_imagenes"
                            accept="image/jpeg"
                            multiple />
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12 col-md-6">
                        <?php if (file_exists($imagenPrincipal)) { ?>
                            <img src="<?php echo $imagenPrincipal . '?id=' . time(); ?>" class="img-thumbnail my-3"><br>
                        <?php } ?>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="row">
                            <?php foreach ($imagenes1 as $imagen) { ?>
                                <div class="col-4">
                                    <img src="<?php echo $imagen . '?id=' . time(); ?>" class="img-thumbnail my-3"><br>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                </div>

                <div class="col mb-3">
                    <label for="cantidad_disponible" class="form-label">Cantidad Disponible</label>
                    <input
                        type="number"
                        class="form-control"
                        name="cantidad_disponible"
                        id="cantidad_disponible"
                        value="<?php echo $producto['cantidad_disponible']; ?>"
                        required />
                </div>

            </div>

            <div class="row">
                <div class="col-4 mb-3">
                    <label for="id_cat" class="form-label">Categoría</label>
                    <select
                        class="form-select"
                        name="id_cat"
                        id="id_cat" required>
                        <option value="">Seleccionar</option>
                        <?php foreach ($categorias as $categoria) { ?>
                            <option value="<?php echo $categoria['id_cat']; ?>" <?php if (
                                                                                    $categoria['id_cat'] ==
                                                                                    $producto['id_cat']
                                                                                ) echo 'selected'; ?>><?php echo $categoria['nombre']; ?></option>
                        <?php } ?>
                    </select>

                </div>
            </div>

            <button
                type="submit"
                class="btn btn-primary">Guardar
            </button>

        </form>

    </div>
</main>

<script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.3.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.3.0/"
        }
    }
</script>

<script type="module">
    import {
        ClassicEditor,
        Essentials,
        Bold,
        Italic,
        Font,
        Paragraph
    } from 'ckeditor5';

    ClassicEditor
        .create(document.querySelector('#editor'), {
            plugins: [Essentials, Bold, Italic, Font, Paragraph],
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
            ]
        })
        .then( /* ... */ )
        .catch( /* ... */ );

    /* Realizamos la eliminacion de imagenes1 por medio de AJAX */
    function eliminaImagen(urlImagen) {
        let url = 'eliminar_imagen.php'
        let formData = new FormData()
        formData.append('urlImagen', urlImagen)

        fetch(url, {
            method: 'POST',
            body: formData
        }).then((response) => {
            if (response.ok) {
                location.reload()
            }
        })
    }
</script>

<?php require '../footer.php'; ?>