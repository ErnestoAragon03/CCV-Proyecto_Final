<?php
// Verifica que el formulario haya sido enviado
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

    // Obtener datos del formulario
    $nombre_evento = $_POST['nombre_evento'];

    // Validar que el nombre del evento no esté vacío
    if (empty($nombre_evento)) {
        die("Por favor, proporciona el nombre del evento a eliminar.");
    }

    // Eliminar el evento de la tabla Tipo_Evento
    $sql = "DELETE FROM Tipo_Evento WHERE Nombre_Evento = ?";
    $params = array($nombre_evento);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
    } else {
        echo "Evento eliminado correctamente.";
    }

    // Cerrar la conexión
    sqlsrv_close($conn);
} else {
    echo "Método de solicitud no válido.";
}
?>
