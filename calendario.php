<!DOCTYPE html>
<html>
<head>
    <title>FullCalendar PHP Example</title>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
    <script src='https://code.jquery.com/jquery-3.5.1.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
    <style>
        .event-urgente {
            background-color: red;
            color: white;
        }
        .event-diario {
            background-color: blue;
            color: white;
        }
        .event-semanal {
            background-color: green;
            color: white;
        }
        .event-mensual {
            background-color: orange;
            color: white;
        }
        .event-anual {
            background-color: purple;
            color: white;
        }
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
                            callback(data);
                        }
                    });
                },
                selectable: true,
                selectHelper: true,
                select: function(start, end) {
                    var date = start.format('YYYY-MM-DD');
                    window.location.href = 'elegir_opcion.php?fecha=' + date;
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
    <div id='calendar'></div>
</body>
</html>
