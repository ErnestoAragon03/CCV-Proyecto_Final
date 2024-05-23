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
    $nombre_evento_actual = $_POST['nombre_evento_actual'];
    $nuevo_nombre_evento = $_POST['nuevo_nombre_evento'];
    $nueva_frecuencia = $_POST['nueva_frecuencia'];

    // Validar que los campos no estén vacíos
    if (empty($nombre_evento_actual) || empty($nuevo_nombre_evento) || empty($nueva_frecuencia)) {
        die("Por favor, completa todos los campos.");
    }

    // Modificar el evento en la tabla Tipo_Evento
    $sql = "UPDATE Tipo_Evento SET Nombre_Evento = ?, Frecuencia = ? WHERE Nombre_Evento = ?";
    $params = array($nuevo_nombre_evento, $nueva_frecuencia, $nombre_evento_actual);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
    } else {
        echo "Evento modificado correctamente.";
    }

    // Cerrar la conexión
    sqlsrv_close($conn);
} else {
    echo "Método de solicitud no válido.";
}
?>
