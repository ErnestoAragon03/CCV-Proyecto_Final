<?php
// Verificar si se recibió el ID del contacto por GET
if (!isset($_GET['id'])) {
    die("ID de contacto no especificado.");
}

$id_contacto = $_GET['id'];

// Conexión a la base de datos
$serverName = "localhost";
$connectionOptions = array(
    "Database" => "CCVDB",
    "ReturnDatesAsStrings" => true
);
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Verificar la conexión
if (!$conn) {
    die("La conexión falló: " . print_r(sqlsrv_errors(), true));
}

// Obtener el ID del evento asociado al contacto
$sql_contacto = "SELECT ID_Evento FROM Contacto WHERE ID_Contacto = ?";
$params_contacto = array($id_contacto);
$stmt_contacto = sqlsrv_query($conn, $sql_contacto, $params_contacto);
$row_contacto = sqlsrv_fetch_array($stmt_contacto, SQLSRV_FETCH_ASSOC);
$id_evento = $row_contacto['ID_Evento'];

// Eliminar el evento asociado
$sql_delete_evento = "DELETE FROM Evento WHERE ID_Evento = ?";
$params_delete_evento = array($id_evento);
$stmt_delete_evento = sqlsrv_query($conn, $sql_delete_evento, $params_delete_evento);

if ($stmt_delete_evento === false) {
    die("Error al eliminar el evento: " . print_r(sqlsrv_errors(), true));
}

// Eliminar el contacto
$sql_delete_contacto = "DELETE FROM Contacto WHERE ID_Contacto = ?";
$params_delete_contacto = array($id_contacto);
$stmt_delete_contacto = sqlsrv_query($conn, $sql_delete_contacto, $params_delete_contacto);

if ($stmt_delete_contacto === false) {
    die("Error al eliminar el contacto: " . print_r(sqlsrv_errors(), true));
} else {
    echo "Contacto y evento de cumpleaños eliminados correctamente.";
    header("Location: listar_contactos.php");
    exit;
}

// Cerrar la conexión
sqlsrv_close($conn);
?>
