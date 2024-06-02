<?php
include_once 'conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Obtener el último ID de usuario
    $sql_last_id = "SELECT MAX(ID_Usuario) AS last_id FROM Usuario";
    $stmt_last_id = sqlsrv_query($conn, $sql_last_id);
    $row_last_id = sqlsrv_fetch_array($stmt_last_id, SQLSRV_FETCH_ASSOC);
    $last_id = $row_last_id['last_id'];
    $next_id = $last_id + 1;

    // Insertar nuevo usuario en la base de datos
    $sql_insert = "INSERT INTO Usuario (ID_Usuario, Nombre, Correo, Contrasena) VALUES (?, ?, ?, ?)";
    $params = array($next_id, $nombre, $correo, $contrasena);
    $stmt_insert = sqlsrv_query($conn, $sql_insert, $params);

    if ($stmt_insert === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <h2>Registrarse</h2>
        <form action="registro.php" method="POST">
            <div class="field">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="field">
                <label for="correo">Correo electrónico:</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            <div class="field">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            <button class="my-button" type="submit">Registrarse</button>
        </form>
        <p>¿Ya tienes una cuenta? <a href="index.php">Inicia sesión aquí</a>.</p>
    </div>
</body>
</html>
