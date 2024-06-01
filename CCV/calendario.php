<?php
// Obtenemos el número del mes y del año actual
$month = date('n');
$year = date('Y');

// Obtenemos el número de días en el mes actual
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// Obtenemos el día de la semana en el que empieza el mes (0 para domingo, 1 para lunes, etc.)
$first_day_of_month = date('w', mktime(0, 0, 0, $month, 1, $year));

// Creamos la tabla del calendario
echo "<table border='1'>";
echo "<tr><th colspan='7'>" . date('F Y', mktime(0, 0, 0, $month, 1, $year)) . "</th></tr>";
echo "<tr><th>Domingo</th><th>Lunes</th><th>Martes</th><th>Miércoles</th><th>Jueves</th><th>Viernes</th><th>Sábado</th></tr>";

echo "<tr>";
// Rellenamos los espacios en blanco hasta llegar al primer día del mes
for ($i = 0; $i < $first_day_of_month; $i++) {
    echo "<td></td>";
}

// Rellenamos los días del mes
for ($day = 1; $day <= $days_in_month; $day++) {
    echo "<td><a href='crear_evento.php?fecha=$year-$month-$day'>$day</a></td>";
    // Si es sábado (6), cerramos la fila y abrimos una nueva
    if (($first_day_of_month + $day) % 7 == 6) {
        echo "</tr><tr>";
    }
}

// Rellenamos los espacios en blanco hasta el final de la semana
for ($i = ($first_day_of_month + $days_in_month) % 7; $i < 7; $i++) {
    echo "<td></td>";
}

echo "</tr>";
echo "</table>";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario</title>
    <!-- Aquí irían los estilos y scripts necesarios para tu calendario -->
</head>
<body>
    <h2>Calendario</h2>
    <!-- Aquí iría el código de tu calendario -->
    <p><a href="crear_contacto.php">Crear Contacto</a></p>
    <p><a href="listar_contactos.php">Listar Contactos</a></p>
</body>
</html>
