<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_tipo']) && isset($_POST['nombre_evento_actual']) && isset($_POST['frecuencia'])) {
    $id_tipo = $_POST['id_tipo'];
    $nombre_evento_actual = $_POST['nombre_evento_actual'];
    $frecuencia = $_POST['frecuencia'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirigir a Modificar Evento</title>
</head>
<body>
    <form id="redirectForm" action="modificar_tipoEvento.html" method="POST">
        <input type="hidden" name="id_tipo" value="<?php echo htmlspecialchars($id_tipo); ?>">
        <input type="hidden" name="nombre_evento_actual" value="<?php echo htmlspecialchars($nombre_evento_actual); ?>">
        <input type="hidden" name="frecuencia" value="<?php echo htmlspecialchars($frecuencia); ?>">
    </form>
    <script type="text/javascript">
        document.getElementById('redirectForm').submit();
    </script>
</body>
</html>
