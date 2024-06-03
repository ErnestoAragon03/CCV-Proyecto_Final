<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$notification = "";
if (isset($_SESSION['notification'])) {
    $notification = $_SESSION['notification'];
    unset($_SESSION['notification']);
}

function mapFrecuencia($frecuencia) {
    $map = array(
        'U' => 'Único',
        'D' => 'Diaria',
        'S' => 'Semanal',
        'M' => 'Mensual',
        'A' => 'Anual'
    );
    return isset($map[$frecuencia]) ? $map[$frecuencia] : 'Desconocido';
}

$serverName = "localhost";
$connectionOptions = array(
    "Database" => "CCVDB",
    "ReturnDatesAsStrings" => true
);
$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die("La conexión falló: " . print_r(sqlsrv_errors(), true));
}

$sql = "SELECT ID_Tipo, Nombre_Evento, Frecuencia FROM Tipo_Evento WHERE ID_Usuario = ?";
$params = array($id_usuario);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }
        .evento {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .evento h3 {
            margin-top: 0;
        }
        .evento p {
            margin-bottom: 0;
        }
        .evento form {
            display: inline;
        }
        .evento form input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        .evento form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        footer {
            margin-top: 20px;
            text-align: center;
        }
        footer form input[type="submit"] {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        footer form input[type="submit"]:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tipos de Evento</h2>
        <div id="eventos">
            <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                <div class="evento">
                    <h3><?php echo htmlspecialchars($row['Nombre_Evento']); ?></h3>
                    <p>Frecuencia: <?php echo htmlspecialchars(mapFrecuencia($row['Frecuencia'])); ?></p>
                    <form action="modificar_tipoEvento.php" method="POST">
                        <input type="hidden" name="id_tipo" value="<?php echo $row['ID_Tipo']; ?>">
                        <input type="hidden" name="nombre_evento_actual" value="<?php echo htmlspecialchars($row['Nombre_Evento']); ?>">
                        <input type="submit" value="Modificar">
                    </form>
                    <form action="eliminar_tipoEvento.php" method="POST">
                        <input type="hidden" name="id_tipo" value="<?php echo $row['ID_Tipo']; ?>">
                        <input type="submit" value="Eliminar">
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
        <br>
        <form action="crear_tipoEvento.php" method="POST">
            <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
            <input type="submit" value="Crear Nuevo Evento">
        </form>
        <footer>
            <form action="calendario.php">
                <input type="submit" value="Regresar">
            </form>
        </footer>
    </div>
</body>
</html>

<?php
sqlsrv_close($conn);
?>
