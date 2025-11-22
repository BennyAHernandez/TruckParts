<?php

require '../config/database.php';
require '../config/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}
$orden = $_POST['orden'] ?? null;

if($orden == null){
    exit;
}
$db = new Database();
$con = $db->conectar();


$sqlCompra = $con->prepare("SELECT id_compra, id_transaccion, fecha, total, CONCAT(nombre,' ',apellido_pat,' ',apellido_mat) 
AS cliente FROM compra 
INNER JOIN cliente ON compra.id_cliente = cliente.id_cliente WHERE id_transaccion = ? LIMIT 1");
$sqlCompra->execute([$orden]);

//traer el resultado de la consulta
$rowCompra = $sqlCompra->fetch(PDO::FETCH_ASSOC);

if(!$rowCompra){
    exit;
}

//extraer id de compra
$idCompra = $rowCompra['id_compra'];

$fecha = new DateTime($rowCompra['fecha']);
$fecha = $fecha->format('d-m-Y H:i');

//extraer el detalle de la compra
$sqlDetalle = $con->prepare("SELECT id_detalle, id_compra, nombre_pieza, precio, cantidad FROM detalle_compra WHERE id_compra = ?");
$sqlDetalle->execute([$idCompra]);

//hacemos peticion ajax
$html = '<p><strong>Fecha: </strong></p>'.$fecha.'</p>';
$html .= '<p><strong>Pedido: </strong></p>'.$rowCompra['id_transaccion'].'</p>';
$html .= '<p><strong>Total: </strong></p>'.number_format($rowCompra['total'], 2, '.', ',').'</p>';

$html .='<table class="table">
<thead>
<tr>
<th>Producto:</th>
<th>Precio:</th>
<th>Cantidad:</th>
<th>Subtotal:</th>
</tr>
</thead>';

$html .= '<tbody>';
                                while($row = $sqlDetalle->fetch(PDO::FETCH_ASSOC)) {
                                    $precio = $row['precio'];
                                    $cantidad = $row['cantidad'];
                                    $subtotal = $precio * $cantidad;
$html .= '<tr>';
$html .= '<td>'.$row['nombre_pieza'].'</td>';
$html .= '<td>'. MONEDA . ' ' . number_format($precio,2,'.',',').'</td>';
$html .= '<td>'. $cantidad.'</td>';
$html .= '<td>'. MONEDA . ' ' . number_format($subtotal,2,'.',',').'</td>';

$html .= '</tr>';
                                }
$html .= '</tbody></table>';
echo json_encode($html, JSON_UNESCAPED_UNICODE);
