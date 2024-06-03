<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    // Si no está presente, redirige al usuario al login o maneja el error
    header('Location: login.php');
    exit();
}
$id_usuario = $_SESSION['id_usuario'];
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

// Obtener la lista de eventos del usuario
$sql = "SELECT * FROM Evento WHERE ID_Usuario = ?";
$params = array($id_usuario);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Eventos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table.contacts-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.contacts-table th, 
        table.contacts-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        table.contacts-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        table.contacts-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table.contacts-table tr:hover {
            background-color: #ddd;
        }

        button[type="submit"],
        input[type="submit"] {
            padding: 5px 10px;
            background: #007bff;
            border: none;
            color: white;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover,
        input[type="submit"]:hover {
            background: #0056b3;
        }

        p {
            text-align: center;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h2>Lista de Eventos</h2>
        <table class="contacts-table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Descripción</th>
                    <th>Tipo de Evento</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $row['Titulo']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($row['Fecha'])); ?></td>
                    <td><?php echo date('H:i', strtotime($row['Hora'])); ?></td>
                    <td><?php echo $row['Descripcion']; ?></td>
                    <td><?php echo obtenerNombreTipoEvento($row['ID_Tipo'], $conn); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p><a href="crear_evento.php">Crear Evento</a></p>
        <form action="calendario.php">
                <input type="submit" class="btn" value="Regresar">
        </form>
    </div>
</body>
</html>


<?php
// Función para obtener el nombre del tipo de evento
function obtenerNombreTipoEvento($id_tipo, $conn) {
    $sql = "SELECT Nombre_Evento FROM Tipo_Evento WHERE ID_Tipo = ?";
    $params = array($id_tipo);
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        return "Tipo no encontrado";
    }
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if ($row === false) {
        return "Tipo no encontrado";
    }
    return $row['Nombre_Evento'];
}

// Cerrar la conexión
sqlsrv_close($conn);
?>
