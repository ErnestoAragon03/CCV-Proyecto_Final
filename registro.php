<?php
include_once 'conexion.php'; 

if($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $sql_insert = "INSERT INTO Usuario (ID_Usuario, Nombre, Correo, Contraseña) VALUES (?, ?, ?, ?)";
    $params = array($next_id, $nombre, $correo, $contrasena);
    $stmt_insert = sqlsrv_query($conn, $sql_insert, $params);

    if($stmt_insert === false) {
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
</head>
<body>
    <h2>Registrarse</h2>
    <?php if(isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <form action="registro.php" method="post">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="correo">Correo electrónico:</label><br>
        <input type="email" id="correo" name="correo" required><br>
        <label for="contrasena">Contraseña:</label><br>
        <input type="password" id="contrasena" name="contrasena" required><br><br>
        <button type="submit">Registrarse</button>
    </form>
    <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
</body>
</html>
