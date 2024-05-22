<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_tipo']) && isset($_POST['nombre_evento']) && isset($_POST['frecuencia'])) {
    // Procesar el formulario enviado para modificar el tipo de evento
    $id_tipo = htmlspecialchars($_POST['id_tipo']);
    $nuevo_nombre_evento = htmlspecialchars($_POST['nombre_evento']);
    $nueva_frecuencia = htmlspecialchars($_POST['frecuencia']);
    
    // Conectar a SQL Server
    $serverName = "localhost";
    $connectionOptions = array("Database" => "CCVDB", "UID" => "", "PWD" => "");
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    
    // Verificar conexión
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    // Actualizar registro
    $sql = "UPDATE Tipo_Evento SET Nombre_Evento = ?, Frecuencia = ? WHERE ID_Tipo = ?";
    $params = array($nuevo_nombre_evento, $nueva_frecuencia, $id_tipo);
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "Tipo de evento actualizado correctamente.";
    }
    
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_tipo'])) {
    // Mostrar el formulario con los valores actuales
    $id_tipo = htmlspecialchars($_POST['id_tipo']);
    
    // Conectar a SQL Server
    $serverName = "localhost";
    $connectionOptions = array("Database" => "CCVDB", "UID" => "", "PWD" => "");
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    
    // Verificar conexión
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    $sql = "SELECT Nombre_Evento, Frecuencia FROM Tipo_Evento WHERE ID_Tipo = ?";
    $params = array($id_tipo);
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $nombre_evento_actual = $row['Nombre_Evento'];
    $frecuencia = $row['Frecuencia'];
    
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
    
    // Función para mapear valores de frecuencia
    function mapFrecuencia($frecuencia) {
        $map = array(
            'U' => 'Unica',
            'D' => 'Diaria',
            'S' => 'Semanal',
            'M' => 'Mensual',
            'A' => 'Anual'
        );
        return isset($map[$frecuencia]) ? $map[$frecuencia] : 'Desconocido';
    }
    $frecuencia_mapeada = mapFrecuencia($frecuencia);
} else {
    echo "Datos no recibidos correctamente.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Tipo de Evento</title>
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
        <input type="hidden" name="id_tipo" value="<?php echo $id_tipo; ?>">
        <label for="nombre_evento">Nombre del Evento:</label>
        <input type="text" id="nombre_evento" name="nombre_evento" placeholder="<?php echo $nombre_evento_actual; ?>"><br>
        <label for="frecuencia">Frecuencia:</label>
        <select id="frecuencia" name="frecuencia">
            <option value="U" <?php echo ($frecuencia == 'U') ? 'selected' : ''; ?>>Unica</option>
            <option value="D" <?php echo ($frecuencia == 'D') ? 'selected' : ''; ?>>Diaria</option>
            <option value="S" <?php echo ($frecuencia == 'S') ? 'selected' : ''; ?>>Semanal</option>
            <option value="M" <?php echo ($frecuencia == 'M') ? 'selected' : ''; ?>>Mensual</option>
            <option value="A" <?php echo ($frecuencia == 'A') ? 'selected' : ''; ?>>Anual</option>
        </select><br>
        <input type="submit" value="Modificar">
    </form>
</body>
</html>
