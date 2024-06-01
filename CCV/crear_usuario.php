<?php
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
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Obtener el último ID_Usuario y generar uno nuevo
$sql_last_id = "SELECT MAX(ID_Usuario) AS MaxID FROM Usuario";
$stmt_last_id = sqlsrv_query($conn, $sql_last_id);
$row_last_id = sqlsrv_fetch_array($stmt_last_id, SQLSRV_FETCH_ASSOC);
$next_id = $row_last_id['MaxID'] + 1;

// Insertar datos en la tabla Usuario
$sql = "INSERT INTO Usuario (ID_Usuario, Nombre, Correo, Contrasena) VALUES (?, ?, ?, ?)";
$params = array($next_id, $nombre, $correo, $contrasena);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
} else {
    echo "Usuario creado correctamente.";
}

// Cerrar la conexión
sqlsrv_close($conn);
?>
