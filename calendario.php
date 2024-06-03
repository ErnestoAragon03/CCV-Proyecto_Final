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
        .centered {
            display: block;
            margin: 0 auto;
            text-align: center;
        }
    </style>

    <script>
        $(document).ready(function() {
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
                            data.forEach(function(event) {
                                // Format date and time
                                var fechaHoraInicio = moment(event.start).format('YYYY-MM-DDTHH:mm:ss');
                                var fechaHoraFin = event.end ? moment(event.end).format('YYYY-MM-DDTHH:mm:ss') : null;

                                // Assign formatted dates and times
                                event.start = fechaHoraInicio;
                                event.end = fechaHoraFin;

                                // Other event adjustments if necessary
                                if (event.frecuencia === 'diario') {
                                    event.className = 'event-diario';
                                } else if (event.frecuencia === 'semanal') {
                                    event.className = 'event-semanal';
                                } else if (event.frecuencia === 'mensual') {
                                    event.className = 'event-mensual';
                                } else if (event.frecuencia === 'anual') {
                                    event.className = 'event-anual';
                                }

                                if (event.tipo === 'contacto') {
                                    event.allDay = true;
                                } else {
                                    event.allDay = false;
                                }
                            });
                            
                            callback(data);
                        }
                    });
                },
                selectable: true,
                selectHelper: true,
                select: function(start, end) {
                    var date = start.format('YYYY-MM-DD HH:mm:ss');
                    window.location.href = 'elegir_opcion.php?fecha=' + date;
                },

                dayRender: function(date, cell) {
                    var currentDate = moment().format('YYYY-MM-DD');

                    <?php
                    session_start();
                    if (!isset($_SESSION['id_usuario'])) {
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

                    if (!$conn) {
                        die("La conexión falló: " . print_r(sqlsrv_errors(), true));
                    }

                    // Consulta de eventos
                    $sqlEvent = "SELECT E.Fecha, E.Hora, T.Nombre_Evento, E.Titulo FROM Evento E JOIN Tipo_Evento T ON E.ID_Tipo = T.ID_Tipo WHERE E.ID_Usuario = ?";
                    $params = array($id_usuario);
                    $stmtEvent = sqlsrv_query($conn, $sqlEvent, $params);
                    if ($stmtEvent === false) {
                        die("Error al ejecutar la consulta de eventos: " . print_r(sqlsrv_errors(), true));
                    }

                    // Generar script JS para cada evento
                    while ($rowEvent = sqlsrv_fetch_array($stmtEvent, SQLSRV_FETCH_ASSOC)) {
                        $eventDate = date('Y-m-d', strtotime($rowEvent['Fecha']));
                        $horaEvento = date('H:i:s', strtotime($rowEvent['Hora']));
                        $titulo = $rowEvent['Titulo'];
                        $nombreEvento = $rowEvent['Nombre_Evento'];
                        $backgroundColor = '';
                        $icon = '';

                        switch ($nombreEvento) {
                            case 'Cumpleaños':
                                $backgroundColor = 'red';
                                $icon = 'birthday_icon.png';
                                break;
                            case 'Reunión':
                                $backgroundColor = 'blue';
                                $icon = 'meeting_icon.png';
                                break;
                            case 'Fiesta':
                                $backgroundColor = 'orange';
                                $icon = 'party_icon.png';
                                break;
                            case 'Navidad':
                                $backgroundColor = 'green';
                                $icon = 'christmas_icon.png';
                                break;
                            case 'Año Nuevo':
                                $backgroundColor = 'grey';
                                $icon = 'newyear_icon.png';
                                break;
                            case 'Día del Cariño':
                                $backgroundColor = 'pink';
                                $icon = 'valentine_icon.png';
                                break;
                            case 'Aniversario':
                                $backgroundColor = 'purple';
                                $icon = 'anniversary_icon.png';
                                break;
                            default:
                                $backgroundColor = 'yellow';
                                $icon = 'default_icon.png';
                                break;
                        }

                        echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                        echo "cell.css('background-color', '$backgroundColor');";
                        // Agregamos la hora al título del evento
                        echo "cell.append('<div class=\"event-container\"><img src=\"icons/$icon\" class=\"event-image\" style=\"width: 30px; height: auto;\"><span class=\"event-label\">$titulo - $horaEvento</span></div>');";
                        echo "}";
                    }

                    sqlsrv_close($conn);
                    ?>
                },

                eventRender: function(event, element) {
                    element.qtip({
                        content: event.title + '<br>' + event.start.format('YYYY-MM-DD HH:mm:ss') + (event.end ? ' - ' + event.end.format('YYYY-MM-DD HH:mm:ss') : ''),
                        style: {
                            classes: 'qtip-dark'
                        },
                        position: {
                            my: 'top left',
                            at: 'bottom right'
                        }
                    });
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                }
            });

            var currentYear = new Date().getFullYear();
            var yearSelector = $('#yearSelector');

            for (var year = currentYear - 124; year <= currentYear + 75; year++) {
                yearSelector.append($('<option>', {
                    value: year,
                    text: year
                }));
            }

            yearSelector.val(currentYear);

            yearSelector.change(function() {
                var selectedYear = $(this).val();
                $('#calendar').fullCalendar('gotoDate', moment().year(selectedYear).startOf('year'));
            });
        });
    </script>
</head>
<body>
    <select id="yearSelector" class="centered"></select>
    <div style="max-width: 1000px; margin: auto" id='calendar'></div>
    <form action="administrar_tipoEvento.php">
        <input type="submit" value="Tipos de Evento" class="centered"/>
    </form>
</body>
</html>
