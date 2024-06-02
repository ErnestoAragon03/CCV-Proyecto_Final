<?php
// Verificar si se ha enviado el formulario de eliminar
if(isset($_POST['eliminar_contacto'])) {
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

    // Obtener el ID del contacto a eliminar
    $id_contacto = $_POST['id_contacto'];

    // Consulta SQL para eliminar el contacto
    $sql = "DELETE FROM Contacto WHERE ID_Contacto = ?";
    $params = array($id_contacto);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
    } else {
        // Cerrar la conexión
        sqlsrv_close($conn);

        // Redirigir de vuelta a listar_contactos.php
        header("Location: listar_contactos.php");
        exit();
    }
}
?>
