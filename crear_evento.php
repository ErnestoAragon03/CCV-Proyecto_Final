<?php
session_start();
// Verifica si el ID de usuario está presente en la sesión
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

// Consultar los tipos de eventos
$sql_tipos = "SELECT ID_Tipo, Nombre_Evento FROM Tipo_Evento";
$stmt_tipos = sqlsrv_query($conn, $sql_tipos);
$tipos_eventos = [];
while ($row = sqlsrv_fetch_array($stmt_tipos, SQLSRV_FETCH_ASSOC)) {
    $tipos_eventos[] = $row;
}

// Comprobar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $titulo = $_POST['titulo'];
    $fecha = $_GET['fecha'];
    $hora = $_POST['hora'];
    $descripcion = $_POST['descripcion'];
    $id_tipo = $_POST['id_tipo'];

    // Verificar si el tipo de evento es "Otro"
    if ($id_tipo == "otro") {
        $nuevo_tipo = $_POST['nuevo_tipo'];
        // Insertar el nuevo tipo de evento en la tabla Tipo_Evento
        $sql_last_id_tipo = "SELECT MAX(ID_Tipo) AS MaxID FROM Tipo_Evento";
        $stmt_last_id_tipo = sqlsrv_query($conn, $sql_last_id_tipo);
        $row_last_id_tipo = sqlsrv_fetch_array($stmt_last_id_tipo, SQLSRV_FETCH_ASSOC);
        $next_id_tipo = $row_last_id_tipo['MaxID'] + 1;

        $sql_insert_tipo = "INSERT INTO Tipo_Evento (ID_Tipo, Nombre_Evento, Frecuencia, ID_Usuario) VALUES (?, ?, 'U', ?)";
        $params_insert_tipo = array($next_id_tipo, $nuevo_tipo, $id_usuario);
        $stmt_insert_tipo = sqlsrv_query($conn, $sql_insert_tipo, $params_insert_tipo);

        if ($stmt_insert_tipo === false) {
            die("Error al insertar el nuevo tipo de evento: " . print_r(sqlsrv_errors(), true));
        }
        $id_tipo = $next_id_tipo; // Asignar el nuevo ID_Tipo al evento
    }

    // Obtener el último ID_Evento y generar uno nuevo
    $sql_last_id_evento = "SELECT MAX(ID_Evento) AS MaxID FROM Evento";
    $stmt_last_id_evento = sqlsrv_query($conn, $sql_last_id_evento);
    $row_last_id_evento = sqlsrv_fetch_array($stmt_last_id_evento, SQLSRV_FETCH_ASSOC);
    $next_id_evento = $row_last_id_evento['MaxID'] + 1;

    // Insertar el nuevo evento
    $sql = "INSERT INTO Evento (ID_Evento, Titulo, Fecha, Hora, Descripcion, ID_Usuario, ID_Tipo) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $params = array($next_id_evento, $titulo, $fecha, $hora, $descripcion, $id_usuario, $id_tipo);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
    } else {
        echo "Evento creado correctamente.";
        // Redirigir al calendario después de crear el evento
        header("Location: calendario.php");
        exit;
    }
}

// Cerrar la conexión
sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Evento</title>
    <script>
        function toggleNuevoTipo() {
            var selectTipo = document.getElementById('id_tipo');
            var nuevoTipoDiv = document.getElementById('nuevo_tipo_div');
            if (selectTipo.value === 'otro') {
                nuevoTipoDiv.style.display = 'block';
            } else {
                nuevoTipoDiv.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <h2>Crear Evento</h2>
    <form action="crear_evento.php?fecha=<?php echo $_GET['fecha']; ?>" method="POST">
        <label for="titulo">Título:</label><br>
        <input type="text" id="titulo" name="titulo" required><br>
        <label for="hora">Hora:</label><br>
        <input type="time" id="hora" name="hora" required><br>
        <label for="descripcion">Descripción:</label><br>
        <textarea id="descripcion" name="descripcion" required></textarea><br>
        <label for="id_tipo">Tipo de Evento:</label><br>
        <select id="id_tipo" name="id_tipo" onchange="toggleNuevoTipo()" required>
            <?php foreach ($tipos_eventos as $tipo) : ?>
                <option value="<?php echo $tipo['ID_Tipo']; ?>"><?php echo $tipo['Nombre_Evento']; ?></option>
            <?php endforeach; ?>
            <option value="otro">Otro</option>
        </select><br><br>
        <div id="nuevo_tipo_div" style="display: none;">
            <label for="nuevo_tipo">Nuevo Tipo de Evento:</label><br>
            <input type="text" id="nuevo_tipo" name="nuevo_tipo"><br><br>
        </div>
        <button type="submit">Crear Evento</button>
    </form>
    <p><a href="calendario.php">Volver al calendario</a></p>
</body>
</html>
