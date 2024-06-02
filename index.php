<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Inicio de Sesión</title>
</head>
<body>
    <div class="wrapper">
        <h2>Inicio de Sesión</h2>
        <form action="login.php" method="POST">
            <div class="field">
                <label for="correo">Correo electrónico:</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            <div class="field">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            
            <button class="my-button"type="submit">Iniciar Sesión</button>
            
        </form>
        <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a>.</p>
    </div>
</body>
</html>


