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

// Obtener el ID del contacto a modificar
$id_contacto = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consultar la información del contacto
$sql_contacto = "SELECT * FROM Contacto WHERE ID_Contacto = ?";
$params_contacto = array($id_contacto);
$stmt_contacto = sqlsrv_query($conn, $sql_contacto, $params_contacto);
$contacto = sqlsrv_fetch_array($stmt_contacto, SQLSRV_FETCH_ASSOC);

// Comprobar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['email'];
    $fecha_cumple = $_POST['fecha_cumple'];
    
    // Verificar longitud del teléfono y rellenar con ceros si es necesario
    if (strlen($telefono) > 8) {
        die("El número de teléfono no puede tener más de 8 caracteres.");
    }
    $telefono = str_pad($telefono, 8, "0", STR_PAD_LEFT);
    
    // Actualizar el contacto
    $sql = "UPDATE Contacto SET Nombre = ?, Direccion = ?, Telefono = ?, Correo = ?, Fecha_Na = ? WHERE ID_Contacto = ?";
    $params = array($nombre, $direccion, $telefono, $correo, $fecha_cumple, $id_contacto);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
    } else {
        echo "Contacto actualizado correctamente.";
        header("Location: listar_contactos.php");
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
    <title>Modificar Contacto</title>
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <h2>Modificar Contacto</h2>
        <form action="modificar_contacto.php?id=<?php echo $id_contacto; ?>" method="POST">
            <div class="field">
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($contacto['Nombre']); ?>" required>
            </div>
            <div class="field">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($contacto['Direccion']); ?>" required>
            </div>
            <div class="field">
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($contacto['Telefono']); ?>" required>
            </div>
            <div class="field">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($contacto['Correo']); ?>" required>
            </div>
            <div class="field">
                <label for="fecha_cumple">Fecha de Cumpleaños:</label>
                <input type="date" id="fecha_cumple" name="fecha_cumple" value="<?php echo htmlspecialchars($contacto['Fecha_Na']); ?>" required>
            </div>
            <button type="submit">Guardar Cambios</button>
           
        </form>
        <p><a href="listar_contactos.php">Volver a la lista de contactos</a></p>
    </div>
</body>
</html>
