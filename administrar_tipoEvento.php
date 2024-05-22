<?php
// Verifica que se haya enviado el ID de Usuario como parámetro POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_usuario'])) {
    $id_usuario = $_POST['id_usuario'];

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

    function mapFrecuencia($frecuencia) {
        $map = array(
            'U' => 'Único',
            'D' => 'Diaria',
            'S' => 'Semanal',
            'M' => 'Mensual',
            'A' => 'Anual'
        );
        return isset($map[$frecuencia]) ? $map[$frecuencia] : 'Desconocido';
    }
    // Obtener eventos del usuario
    $sql = "SELECT ID_Tipo, Nombre_Evento, Frecuencia FROM Tipo_Evento WHERE ID_Usuario = ?";
    $params = array($id_usuario);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
    }

    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Eventos</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            .evento {
                border: 1px solid #ccc;
                padding: 10px;
                margin: 10px 0;
            }
            .evento button {
                margin: 5px;
            }
        </style>
    </head>
    <body>
        <h2>Tipos de Evento</h2>
        <div id="eventos">';

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo '<div class="evento">';
        echo '<h3>' . htmlspecialchars($row['Nombre_Evento']) . '</h3>';
        echo '<p>Frecuencia: ' . htmlspecialchars(mapFrecuencia($row['Frecuencia'])) . '</p>';
        echo '<form action="modificar_tipoEvento.html" method="POST" style="display:inline;">
                <input type="hidden" name="id_tipo" value="' . $row['ID_Tipo'] . '">
                <input type="hidden" name="nombre_evento_actual" value="' . htmlspecialchars($row['Nombre_Evento']) . '">
                <input type="submit" value="Modificar">
              </form>';
        echo '<form action="eliminar_tipoEvento.php" method="POST" style="display:inline;">
                <input type="hidden" name="id_tipo" value="' . $row['ID_Tipo'] . '">
                <input type="submit" value="Eliminar">
              </form>';
        echo '</div>';
    }

    echo '</div>';
    echo '<br><form action="crear_tipoEvento.html" method="POST">
            <input type="hidden" name="id_usuario" value="' . $id_usuario . '">
            <input type="submit" value="Crear Nuevo Evento">
          </form>';
    echo '</body></html>';

    // Cerrar la conexión
    sqlsrv_close($conn);
} else {
    echo "ID de Usuario no proporcionado.";
}
?>
