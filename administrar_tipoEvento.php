<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos de Evento</title>
</head>
<body>
    <h2>Tipos de Evento</h2>
    <form action="crear_usuario.php" method="POST">
        <label for="nombre">Nombre:</label><br>
        <input type="submit" id="nombre" name="nombre" required><br>
        <label for="correo">Correo electrónico:</label><br>
        <input type="email" id="correo" name="correo" required><br>
        <label for="contrasena">Contraseña:</label><br>
        <input type="password" id="contrasena" name="contrasena" required><br><br>
        <input type="submit" name="NuevoTipo" class="button" value="Crear Usuario">
    </form>
</body>
</html>