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

// Obtener la lista de contactos
$sql = "SELECT * FROM Contacto";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Contactos</title>
</head>
<body>
    <h2>Lista de Contactos</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Fecha de Nacimiento</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $row['ID_Contacto']; ?></td>
            <td><?php echo $row['Nombre']; ?></td>
            <td><?php echo $row['Direccion']; ?></td>
            <td><?php echo $row['Telefono']; ?></td>
            <td><?php echo $row['Correo']; ?></td>
            <td><?php echo $row['Fecha_Na']; ?></td>
            <td>
                <a href="modificar_contacto.php?id=<?php echo $row['ID_Contacto']; ?>">Modificar</a>
                <a href="eliminar_contacto.php?id=<?php echo $row['ID_Contacto']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este contacto?');">Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <p><a href="crear_contacto.php?fecha=<?php echo date('Y-m-d'); ?>">Crear Contacto</a></p>
</body>
</html>

<?php
// Cerrar la conexión
sqlsrv_close($conn);
?>
