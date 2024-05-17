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

// Consulta para obtener los usuarios
$sql = "SELECT * FROM Usuario";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
}

// Mostrar los usuarios
echo "<h2>Usuarios</h2>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Correo</th></tr>";
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . $row['ID_Usuario'] . "</td>";
    echo "<td>" . $row['Nombre'] . "</td>";
    echo "<td>" . $row['Correo'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Cerrar la conexión
sqlsrv_close($conn);
?>
