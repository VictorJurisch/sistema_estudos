<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário Interativo</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 900px;
            width: 100%;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        button {
            background: #ff69b4; /* Rosa */
            color: #fff;
            border: none;
            padding: 5px 10px; /* Diminuir tamanho dos botões */
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #ff1493; /* Rosa mais escuro */
        }
        #calendar {
            max-width: 100%;
        }
        .fc-day-today {
            background: #ff69b4 !important; /* Rosa para o dia atual */
            color: #fff !important;
        }
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            border-radius: 8px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        #back-home {
            background-color: #ff66b2; /* Pink color for back button */
            position: absolute;
            top: 10px;
            left: 10px;
            border: none;
            color: white;
            padding: 5px 10px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 3px;
            text-decoration: none;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<a id="back-home" href="home.php">Voltar para Home</a>
    <div class="container">
        <div class="controls">
            <button onclick="showForm()">Criar Rotina</button>
        </div>
        <div id="calendar"></div>
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeForm()">&times;</span>
            <h2>Criar Rotina</h2>
            <form id="routineForm" action="process_form.php" method="post" onsubmit="formatDates()">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required><br><br>
                <label for="dias_da_semana">Dias da Semana:</label><br>
                <input type="checkbox" id="segunda" name="dias_da_semana[]" value="Segunda">
                <label for="segunda">Segunda</label><br>
                <input type="checkbox" id="terca" name="dias_da_semana[]" value="Terça">
                <label for="terca">Terça</label><br>
                <input type="checkbox" id="quarta" name="dias_da_semana[]" value="Quarta">
                <label for="quarta">Quarta</label><br>
                <input type="checkbox" id="quinta" name="dias_da_semana[]" value="Quinta">
                <label for="quinta">Quinta</label><br>
                <input type="checkbox" id="sexta" name="dias_da_semana[]" value="Sexta">
                <label for="sexta">Sexta</label><br>
                <input type="checkbox" id="sabado" name="dias_da_semana[]" value="Sábado">
                <label for="sabado">Sábado</label><br>
                <input type="checkbox" id="domingo" name="dias_da_semana[]" value="Domingo">
                <label for="domingo">Domingo</label><br><br>
                <label for="data_inicio">Data de Início (dd/mm/yyyy):</label>
                <input type="text" id="data_inicio" name="data_inicio" required><br><br>
                <label for="data_termino">Data de Término (dd/mm/yyyy):</label>
                <input type="text" id="data_termino" name="data_termino" required><br><br>
                <button type="submit">Salvar</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                events: []
            });
            calendar.render();

            // Apply input mask to date fields
            $('#data_inicio').inputmask('99/99/9999');
            $('#data_termino').inputmask('99/99/9999');

            // Fetch and display routines
            fetch('get_rotinas.php')
                .then(response => response.json())
                .then(data => {
                    data.forEach(event => {
                        calendar.addEvent({
                            title: event.nome,
                            start: event.data_inicio,
                            end: event.data_termino
                        });
                    });
                });
        });

        function prevMonth() {
            alert('Mudar para mês anterior');
        }

        function nextMonth() {
            alert('Mudar para próximo mês');
        }

        function showForm() {
            document.getElementById('myModal').style.display = "block";
        }

        function closeForm() {
            document.getElementById('myModal').style.display = "none";
        }

        // Close the modal when the user clicks anywhere outside of the modal
        window.onclick = function(event) {
            var modal = document.getElementById('myModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Format dates to yyyy-mm-dd before submitting the form
        function formatDates() {
            var dataInicio = document.getElementById('data_inicio').value;
            var dataTermino = document.getElementById('data_termino').value;

            document.getElementById('data_inicio').value = formatDate(dataInicio);
            document.getElementById('data_termino').value = formatDate(dataTermino);
        }

        function formatDate(date) {
            var parts = date.split("/");
            return parts[2] + "-" + parts[1] + "-" + parts[0];
        }
    </script>
    <img src="gatinho.gif" alt="GIF animado" style="position: fixed; bottom: 10px; right: 10px; width: 100px; height: 100px;">
</body>
</html>