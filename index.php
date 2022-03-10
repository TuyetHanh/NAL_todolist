<?php
require_once("Controllers/IndexController.php");
$indexController = new \Controllers\IndexController();

$listTodo = $indexController->index();

?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8' />
    <title>Home page</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>

    <script>

        $(document).ready(function() {
            let todos = <?php echo json_encode($listTodo); ?>;
            let dataEvent = [];
            // for (let [index, todo] of Object.entries(todos)) {
            //     let color = "";
            //     switch(todo['status']) {
            //       case "Planning":
            //         color = "#17a2b8";
            //         break;
            //       case "Doing":
            //         color = "#28a745";
            //         break;
            //       case "Complete":
            //         color = "#ffc107";
            //         break;
            //     }
            //     dataEvent.push({
            //         id : todo['id'],
            //         title : todo['taskName'],
            //         start : todo['startDate'],
            //         end : todo['endDate'],
            //         color : color
            //     });
            // }

            for (let [index, todo] of Object.entries(todos)) {
                let color = "";
                switch(todo['status']) {
                    case "planning":
                        color = "#17a2b8";
                        break;
                    case "doing":
                        color = "#ffc107";
                        break;
                    case "complete":
                        color = "#28a745";
                        break;
                }

                var startDate = new Date(todo['startDate']);
                var endDateTemp = new Date(todo['endDate']);
                endDate = new Date(endDateTemp).setDate(endDateTemp.getDate()+1);

                startDate = formatDate(startDate);
                endDate = formatDate(endDate);

                dataEvent.push({
                    id : todo['id'],
                    title : todo['taskName'],
                    start : startDate ,
                    end : endDate,
                    color: color
                });
            }

           /* var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    center: 'dayGridMonth, dayGridWeek, dayGridDay'
                },
                events: dataEvent,
            });

            calendar.render();

            calendar.on('dateClick', function(info) {
                window.location.href = './view/todo/list.php?date='+info.dateStr;
            });*/

            console.log(dataEvent)

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: dataEvent,
                dayClick: function dayClick(date, jsEvent, view) {
                    window.location.href = './views/todo/list.php?date='+ date.format();
                },
            });

        });

        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [year, month, day].join('-');
        }



    </script>
    <style>
        body {
            margin: 8px;
        }
    </style>

</head>
<body>
<div id='calendar'></div>

<div class="note">
    <div class="form-check">
        <button type="button" class="btn btn-info btn-sm"></button>
        <label class="form-check-label" for="status-planning-edit">
            Planning
        </label>
    </div>
    <div class="form-check">
        <button type="button" class="btn btn-warning btn-sm"></button>
        <label class="form-check-label" for="status-doing-edit">
            Doing
        </label>
    </div>
    <div class="form-check">
        <button type="button" class="btn btn-success btn-sm"></button>
        <label class="form-check-label" for="status-complete-edit">
            Complete
        </label>
    </div>
</div>

</body>
</html>