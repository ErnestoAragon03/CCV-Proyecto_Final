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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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

              
                    $sqlEvent = "SELECT E.Fecha, T.Nombre_Evento, E.Titulo FROM Evento E JOIN Tipo_Evento T ON E.ID_Tipo = T.ID_Tipo WHERE E.ID_Usuario = ?";
                    $params = array($id_usuario);
                    $stmtEvent = sqlsrv_query($conn, $sqlEvent, $params);
                    if ($stmtEvent === false) {
                        die("Error al ejecutar la consulta de eventos: " . print_r(sqlsrv_errors(), true));
                    }
                    while ($rowEvent = sqlsrv_fetch_array($stmtEvent, SQLSRV_FETCH_ASSOC)) {
                  
                        $eventDate = date('Y-m-d', strtotime($rowEvent['Fecha']));
                        $titulo = $rowEvent['Titulo'];

                       
                        switch ($rowEvent['Nombre_Evento']) {
                            case 'Cumpleaños':
                                echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                                echo "cell.css('background-color', 'red');";
                                echo "cell.append('<div class=\"event-container\"><img src=\"icons/birthday_icon.png\" class=\"event-image\" style=\"width: 10px; height: auto;\"><span class=\"event-label\">$titulo</span></div>');";
                                echo "}";
                                break;
                            case 'Reunión':
                                echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                                echo "cell.css('background-color', 'blue');";
                                echo "cell.append('<div class=\"event-container\"><img src=\"icons/meeting_icon.png\" class=\"event-image\" style=\"width: 30px; height: auto;\"><span class=\"event-label\">$titulo</span></div>');";
                                echo "}";
                                break;
                            case 'Fiesta':
                                echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                                    echo "cell.css('background-color', 'orange');";
                                    echo "cell.append('<div class=\"event-container\"><img src=\"icons/party_icon.png\" class=\"event-image\" style=\"width: 30px; height: auto;\"><span class=\"event-label\">$titulo</span></div>');";
                                    echo "}";
                                break;
                            case 'Navidad':
                                echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                                echo "cell.css('background-color', 'green');";
                                echo "cell.append('<div class=\"event-container\"><img src=\"icons/christmas_icon.png\" class=\"event-image\" style=\"width: 30px; height: auto;\"><span class=\"event-label\">$titulo</span></div>');";
                                echo "}";
                                break;
                            case 'Año Nuevo':
                                echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                                echo "cell.css('background-color', 'grey');";
                                echo "cell.append('<div class=\"event-container\"><img src=\"icons/newyear_icon.png\" class=\"event-image\" style=\"width: 30px; height: auto;\"><span class=\"event-label\">$titulo</span></div>');";
                                echo "}";
                                break;
                            case 'Día del Cariño':
                                echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                                echo "cell.css('background-color', 'pink');";
                                echo "cell.append('<div class=\"event-container\"><img src=\"icons/valentine_icon.png\" class=\"event-image\" style=\"width: 30px; height: auto;\"><span class=\"event-label\">$titulo</span></div><br>');";
                                echo "}";
                                break;
                            case 'Aniversario':
                                echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                                echo "cell.css('background-color', 'purple');";
                                echo "cell.append('<div class=\"event-container\"><img src=\"icons/anniversary_icon.png\" class=\"event-image\" style=\"width: 30px; height: auto;\"><span class=\"event-label\">$titulo</span></div><br>');";
                                echo "}";
                                break;
                            default:
                                echo "if (date.format('YYYY-MM-DD') === '$eventDate') {";
                                echo "cell.css('background-color', 'yellow');";
                                echo "cell.append('<div class=\"event-container\"><img src=\"icons/default_icon.png\" class=\"event-image\" style=\"width: 30px; height: auto;\"><span class=\"event-label\">$titulo</span></div>');";
                                echo "}";
                                break;
                        }
                    }

                  
                    sqlsrv_close($conn);
                    ?>
                },

                eventRender: function(event, element) {
                   
                    element.qtip({
                        content: event.title + '<br>' + event.start.format('YYYY-MM-DD HH:mm') + (event.end ? ' - ' + event.end.format('YYYY-MM-DD HH:mm') : ''),
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
<div class="centered">
<form action="administrar_tipoEvento.php" method="POST" style="display:inline;">
                <input type="submit" class="btn" value="Tipos de Evento">
</form>
<form action="listar_contactos.php" method="get" style="display:inline;">
        <input type="hidden" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>">
        <button type="submit">Contactos</button>
</form>
    </div>
</body>
</html>
