<?php
include_once 'conexion.php'; 
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Obtener el último ID de usuario
    $stmt = $conn->prepare("SELECT MAX(ID_Usuario) AS last_id FROM Usuario");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $last_id = $row['last_id'];
    $next_id = $last_id + 1;

    // Insertar nuevo usuario en la base de datos
    $stmt = $conn->prepare("INSERT INTO Usuario (ID_Usuario, Nombre, Correo, Contraseña) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $next_id, $nombre, $correo, $contrasena);
    
    if($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Error al registrar el usuario";
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
