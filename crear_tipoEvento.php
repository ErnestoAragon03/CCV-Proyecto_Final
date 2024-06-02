<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    // Si no está presente, redirige al usuario al login o maneja el error
    header('Location: login.php');
    exit();
}
$id_usuario = $_SESSION['id_usuario'];
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
$frecuencia = $_POST['frecuencia'];

// Obtener el último ID_Tipo y generar uno nuevo
$sql_last_id = "SELECT MAX(ID_Tipo) AS MaxID FROM Tipo_Evento";
$stmt_last_id = sqlsrv_query($conn, $sql_last_id);
$row_last_id = sqlsrv_fetch_array($stmt_last_id, SQLSRV_FETCH_ASSOC);
$next_id = $row_last_id['MaxID'] + 1;

// Insertar datos en la tabla Tipo_Evento
$sql = "INSERT INTO Tipo_Evento (ID_Tipo, Nombre_Evento, Frecuencia, ID_Usuario) VALUES (?, ?, ?, ?)";
$params = array($next_id, $nombre_evento, $frecuencia, $id_usuario);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
} else {
    echo "Evento creado correctamente.";
}

// Cerrar la conexión
sqlsrv_close($conn);
?>
