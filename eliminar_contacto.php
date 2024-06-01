<?php
// Verificar si se recibió el ID del contacto por GET
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

$id_contacto = $_POST['id_contacto'];
$id_evento = $_POST['id_evento'];

// Eliminar el evento asociado
$sql = "DELETE FROM Evento WHERE ID_Evento = ?
        DELETE FROM Contacto WHERE ID_Contacto =?";
$params = array($id_evento, $id_contacto);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("Error al eliminar el contacto: " . print_r(sqlsrv_errors(), true));
} else {
    echo "Contacto y evento de cumpleaños eliminados correctamente.";
    header("Location: listar_contactos.php");
    exit;
}

// Cerrar la conexión
sqlsrv_close($conn);
} else {
    echo "Método de solicitud no válido.";
}
?>

