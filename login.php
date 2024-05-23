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
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Consultar la base de datos para verificar las credenciales
$sql = "SELECT * FROM Usuario WHERE Correo = ? AND Contrasena = ?";
$params = array($correo, $contrasena);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
}

// Verificar si se encontró un usuario con las credenciales proporcionadas
if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // Inicio de sesión exitoso
    echo "<script>alert('Inicio de sesión exitoso');</script>";
    // Redirigir al usuario al calendario
    header("Location: calendario.php");
    exit();
} else {
    // Usuario o contraseña incorrectos
    echo "<script>alert('Correo o contraseña incorrectos. Inténtalo de nuevo');</script>";
    // Redirigir al usuario de vuelta al formulario de inicio de sesión
    header("Location: index.php");
    exit();
}

// Cerrar la conexión
sqlsrv_close($conn);
?>
