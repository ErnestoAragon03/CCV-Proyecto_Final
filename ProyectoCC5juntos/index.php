<?php
session_start();
include_once 'conexion.php'; 

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Consultar si las credenciales son válidas
    $stmt = $conn->prepare("SELECT ID_Usuario, Correo, Contraseña FROM Usuario WHERE Correo = ? LIMIT 1");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1) {
        $usuario = $result->fetch_assoc();

        // Verificar la contraseña
        if(password_verify($contrasena, $usuario['Contraseña'])) {
            // Iniciar sesión 
            $_SESSION['usuario_id'] = $usuario['ID_Usuario'];
            header("Location: calendario.php");
            exit;
        } else {
            // Mostrar mensaje de error si la contraseña es incorrecta
            $error = "Correo electrónico o contraseña incorrectos";
        }
    } else {
        // Mostrar mensaje de error si el correo electrónico no está registrado
        $error = "Correo electrónico o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
</head>
<body>
    <h2>Iniciar sesión</h2>
    <?php if(isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <form action="calendario.php" method="post">
        <label for="correo">Correo electrónico:</label><br>
        <input type="email" id="correo" name="correo" required><br>
        <label for="contrasena">Contraseña:</label><br>
        <input type="password" id="contrasena" name="contrasena" required><br><br>
        <button type="submit">Iniciar sesión</button>
    </form>
    <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a>.</p>
</body>
</html>
