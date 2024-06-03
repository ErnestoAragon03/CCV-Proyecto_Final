<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    // Si no está presente, redirige al usuario al login o maneja el error
    header('Location: login.php');
    exit();
}

// Obtener el ID de usuario de la sesión
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

// Procesar el formulario enviado para crear o modificar el tipo de evento
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Si se envió el formulario de creación de un nuevo tipo de evento
    if (isset($_POST['nombre_evento']) && isset($_POST['frecuencia'])) {
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
            // Redirigir a la página de modificación del tipo de evento recién creado
            header('Location: administrar_tipoEvento.php?id_tipo=' . $next_id);
            exit();
        }
    }
    // Si se envió el formulario de modificación de un tipo de evento existente
    elseif (isset($_POST['id_tipo'], $_POST['nombre_evento'], $_POST['frecuencia'])) {
        // Procesar el formulario enviado para modificar el tipo de evento
        $id_tipo = htmlspecialchars($_POST['id_tipo']);
        $nuevo_nombre_evento = htmlspecialchars($_POST['nombre_evento']);
        $nueva_frecuencia = htmlspecialchars($_POST['frecuencia']);
        
        // Actualizar registro en la base de datos
        $sql = "UPDATE Tipo_Evento SET Nombre_Evento = ?, Frecuencia = ? WHERE ID_Tipo = ?";
        $params = array($nuevo_nombre_evento, $nueva_frecuencia, $id_tipo);
        $stmt = sqlsrv_query($conn, $sql, $params);
        
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        } else {
            $_SESSION['notification'] = "El tipo de evento se ha actualizado correctamente.";
            header('Location: administrar_tipoEvento.php');
            exit();
        }
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
    <title>Modificar Tipo de Evento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
        }
        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function prefillPlaceholders() {
            var nombreEventoInput = document.getElementById('nombre_evento');
            if (nombreEventoInput.value === "") {
                nombreEventoInput.value = nombreEventoInput.placeholder;
            }
        }

        function handleFormSubmit(event) {
            prefillPlaceholders();
        }
    </script>
</head>
<body>
    <h2>Modificar Tipo de Evento</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="handleFormSubmit(event)">
        <?php if (isset($_POST['id_tipo'])): ?>
            <input type="hidden" name="id_tipo" value="<?php echo htmlspecialchars($_POST['id_tipo']); ?>">
            <input type="submit" value="Modificar">
        <?php else: ?>
            <label for="nombre_evento">Nombre del Evento:</label>
            <input type="text" id="nombre_evento" name="nombre_evento" placeholder="<?php echo isset($nombre_evento_actual) ? $nombre_evento_actual : ''; ?>"><br>
            <label for="frecuencia">Frecuencia:</label>
            <select id="frecuencia" name="frecuencia">
                <option value="U" <?php echo (isset($frecuencia) && $frecuencia == 'U') ? 'selected' : ''; ?>>Unica</option>
                <option value="D" <?php echo (isset($frecuencia) && $frecuencia == 'D') ? 'selected' : ''; ?>>Diaria</option>
                <option value="S" <?php echo (isset($frecuencia) && $frecuencia == 'S') ? 'selected' : ''; ?>>Semanal</option>
                <option value="M" <?php echo (isset($frecuencia) && $frecuencia == 'M') ? 'selected' : ''; ?>>Mensual</option>
                <option value="A" <?php echo (isset($frecuencia) && $frecuencia == 'A') ? 'selected' : ''; ?>>Anual</option>
            </select><br>
            <input type="submit" value="Crear">
        <?php endif; ?>
    </form>
</body>
</html>