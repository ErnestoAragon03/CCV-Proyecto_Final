

<!DOCTYPE html>
<html>
<head>
    <title>FullCalendar PHP Example</title>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
    <link href='https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.css' rel='stylesheet' />
    <script src='https://code.jquery.com/jquery-3.5.1.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.js'></script>
    <style>
        /* Your existing CSS styles */
    </style>
    <script>
        $(document).ready(function() {
            // Initialize the calendar
            $('#calendar').fullCalendar({
                header: {
                    left: 'prevYear,prev,next,nextYear today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                defaultView: 'month',
                events: function(start, end, timezone, callback) {
                    $.ajax({
                        url: 'index.php',
                        dataType: 'json',
                        data: {
                            action: 'fetch',
                            start: start.format(),
                            end: end.format()
                        },
                        success: function(data) {
                            // Modificar los eventos para agregar clases según su frecuencia
                            data.forEach(function(event) {
                                if (event.frecuencia === 'diario') {
                                    event.className = 'event-diario';
                                } else if (event.frecuencia === 'semanal') {
                                    event.className = 'event-semanal';
                                } else if (event.frecuencia === 'mensual') {
                                    event.className = 'event-mensual';
                                } else if (event.frecuencia === 'anual') {
                                    event.className = 'event-anual';
                                }
                            });
                            
                            callback(data);
                        }
                    });
                },
                selectable: true,
                selectHelper: true,
                select: function(start, end) {
                    var date = start.format('YYYY-MM-DD');
                    window.location.href = 'elegir_opcion.php?fecha=' + date;
                },
                dayRender: function(date, cell) {
    // Obtener la fecha actual en formato YYYY-MM-DD
    var currentDate = moment().format('YYYY-MM-DD');

    <?php
    // Database connection
    $serverName = "localhost";
    $connectionOptions = array(
        "Database" => "CCVDB",
        "ReturnDatesAsStrings" => true
    );
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if (!$conn) {
        die("La conexión falló: " . print_r(sqlsrv_errors(), true));
    }

    // Fetch event dates with event type
    $sqlEvent = "SELECT E.Fecha, T.Nombre_Evento, E.Titulo FROM Evento E JOIN Tipo_Evento T ON E.ID_Tipo = T.ID_Tipo";
    $stmtEvent = sqlsrv_query($conn, $sqlEvent);
    if ($stmtEvent === false) {
        die("Error al ejecutar la consulta de eventos: " . print_r(sqlsrv_errors(), true));
    }
    while ($rowEvent = sqlsrv_fetch_array($stmtEvent, SQLSRV_FETCH_ASSOC)) {
        // Convert the string date to YYYY-MM-DD format
        $eventDate = date('Y-m-d', strtotime($rowEvent['Fecha']));
        $titulo = $rowEvent['Titulo'];

        // Depending on the event type, assign different background colors
        switch ($rowEvent['Nombre_Evento']) {
            case 'Cumpleaños':
                echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                echo "cell.css('background-color', 'blue');";
                echo "cell.append('<span class=\"event-label\">$titulo</span>');";
                echo "}";
                break;
            case 'Reunión':
                echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                echo "cell.css('background-color', 'green');";
                echo "cell.append('<span class=\"event-label\">$titulo</span>');";
                echo "}";
                break;
            case 'Fiesta':
                echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                echo "cell.css('background-color', 'orange');";
                echo "cell.append('<span class=\"event-label\">$titulo</span>');";
                echo "}";
                break;
            case 'Navidad':
                echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                echo "cell.css('background-color', 'red');";
                echo "cell.append('<span class=\"event-label\">$titulo</span>');";
                echo "}";
                break;
            case 'Año Nuevo':
                echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                echo "cell.css('background-color', 'yellow');";
                echo "cell.append('<span class=\"event-label\">$titulo</span>');";
                echo "}";
                break;
            case 'Día del Cariño':
                echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                echo "cell.css('background-color', 'pink');";
                echo "cell.append('<span class=\"event-label\">$titulo</span>');";
                echo "}";
                break;
            case 'Aniversario':
                echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                echo "cell.css('background-color', 'purple');";
                echo "cell.append('<span class=\"event-label\">$titulo</span>');";
                echo "}";
                break;
            default:
                echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                echo "cell.css('background-color', 'grey');";
                echo "cell.append('<span class=\"event-label\">$titulo</span>');";
                echo "}";
                break;
        }
    }

    // Close connection
    sqlsrv_close($conn);
    ?>
},

                eventRender: function(event, element) {
                    // Agregar tooltip para mostrar información adicional sobre el evento
                    element.qtip({
                        content: event.title + '<br>' + event.start.format('YYYY-MM-DD HH:mm') + ' - ' + event.end.format('YYYY-MM-DD HH:mm'),
                        style: {
                            classes: 'qtip-dark'
                        },
                        position: {
                            my: 'top left',
                            at: 'bottom right'
                        }
                    });
                }
            });

            // Populate year selector
            var currentYear = moment().year();
            var yearSelector = $('#yearSelector');
            for (var year = currentYear - 10; year <= currentYear + 10; year++) {
                yearSelector.append($('<option>', {
                    value: year,
                    text: year
                }));
            }
            yearSelector.val(currentYear);

            // Update calendar when year is changed
            yearSelector.change(function() {
                var selectedYear = $(this).val();
                $('#calendar').fullCalendar('gotoDate', moment().year(selectedYear).startOf('year'));
            });
        });
    </script>
</head>
<body>
    <select id="yearSelector"></select>
    <div style="max-width: 1000px; margin: auto" id='calendar'></div>
</body>
</html>
