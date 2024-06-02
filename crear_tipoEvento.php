<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre_evento']) && isset($_POST['frecuencia'])){
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
$nombre_evento = htmlspecialchars($_POST['nombre_evento']);
$frecuencia = htmlspecialchars($_POST['frecuencia']);

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
    $_SESSION['notification'] = 'El tipo de evento se ha creado correctamente.';
    header('Location: administrar_tipoEvento.php');
    exit();
}

// Cerrar la conexión
sqlsrv_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Tipo de Evento</title>
</head>
<body>
    <h2>Crear Nuevo Tipo de Evento</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="handleFormSubmit(event)">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre_evento" name="nombre_evento" required><br>
        <label for="frecuencia">Frecuencia:</label><br>
        <select id="frecuencia" name="frecuencia" required>
            <option value="U">Una sola vez</option>
            <option value="D">Diario</option>
            <option value="S">Semanal</option>
            <option value="M">Mensual</option>
            <option value="A">Anual</option>
        </select><br><br>
        <input type="submit" value="Crear Tipo de Evento">
    </form>
</body>
</html>