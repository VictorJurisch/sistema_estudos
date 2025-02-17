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
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #ffb3d9;
            width: 30%;
            max-width: 350px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-family: 'Autour One', cursive;
        }
        .close {
            color: #ff66b2;
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
            color: #ff3385;
            text-decoration: none;
            cursor: pointer;
        }
        .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }
        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ffb3d9;
            border-radius: 4px;
            margin-top: 5px;
            box-sizing: border-box;
            font-family: 'Autour One', cursive;
        }
        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-top: 5px;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9em;
        }
        .btn-submit {
            background-color: #ff66b2;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            font-family: 'Autour One', cursive;
            width: 100%;
            margin-top: 10px;
        }
        .btn-submit:hover {
            background-color: #ff3385;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn-delete {
            background-color: #ff3385;
        }
        .btn-delete:hover {
            background-color: #ff1a75;
        }
        @media (max-width: 600px) {
            .modal-content {
                width: 85%;
                margin: 10% auto;
            }
            
            .checkbox-group {
                grid-template-columns: repeat(1, 1fr);
            }
        }
        .modal h2 {
            color: #ff66b2;
            margin-bottom: 20px;
            text-align: center;
        }
        .alert {
            position: relative;
            padding: 10px 30px 10px 10px; /* Aumentado padding direito para o botão de fechar */
            margin-bottom: 15px;
            border-radius: 4px;
            text-align: center;
        }
        .alert-close {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: inherit;
            font-size: 20px;
            font-weight: bold;
            opacity: 0.7;
        }
        .alert-close:hover {
            opacity: 1;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
<a id="back-home" href="home.php">Voltar para Home</a>
    <div class="container">
        <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
            <div class="alert alert-<?php echo $_GET['status'] === 'success' ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($_GET['message']); ?>
                <span class="alert-close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>
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
            <form id="routineForm" action="process_form.php" method="post" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="nome">Nome da Rotina:</label>
                    <input type="text" id="nome" name="nome" required class="form-control">
                </div>

                <div class="form-group">
                    <label>Dias da Semana:</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" id="segunda" name="dias[]" value="1">
                            <label for="segunda">Segunda</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="terca" name="dias[]" value="2">
                            <label for="terca">Terça</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="quarta" name="dias[]" value="3">
                            <label for="quarta">Quarta</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="quinta" name="dias[]" value="4">
                            <label for="quinta">Quinta</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="sexta" name="dias[]" value="5">
                            <label for="sexta">Sexta</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="sabado" name="dias[]" value="6">
                            <label for="sabado">Sábado</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="domingo" name="dias[]" value="0">
                            <label for="domingo">Domingo</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="data_inicio">Data de Início:</label>
                    <input type="text" id="data_inicio" name="data_inicio" required class="form-control" placeholder="dd/mm/aaaa">
                </div>

                <div class="form-group">
                    <label for="data_termino">Data de Término:</label>
                    <input type="text" id="data_termino" name="data_termino" required class="form-control" placeholder="dd/mm/aaaa">
                </div>

                <button type="submit" class="btn-submit">Salvar Rotina</button>
            </form>
        </div>
    </div>

    <!-- Modal de Edição -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditForm()">&times;</span>
            <h2>Editar Rotina</h2>
            <form id="editForm" onsubmit="return updateRoutine(event)">
                <input type="hidden" id="edit_id" name="id">
                <div class="form-group">
                    <label for="edit_nome">Nome da Rotina:</label>
                    <input type="text" id="edit_nome" name="nome" required class="form-control">
                </div>

                <div class="form-group">
                    <label>Dias da Semana:</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" id="edit_segunda" name="dias[]" value="1">
                            <label for="edit_segunda">Segunda</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="edit_terca" name="dias[]" value="2">
                            <label for="edit_terca">Terça</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="edit_quarta" name="dias[]" value="3">
                            <label for="edit_quarta">Quarta</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="edit_quinta" name="dias[]" value="4">
                            <label for="edit_quinta">Quinta</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="edit_sexta" name="dias[]" value="5">
                            <label for="edit_sexta">Sexta</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="edit_sabado" name="dias[]" value="6">
                            <label for="edit_sabado">Sábado</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="edit_domingo" name="dias[]" value="0">
                            <label for="edit_domingo">Domingo</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit_data_inicio">Data de Início:</label>
                    <input type="text" id="edit_data_inicio" name="data_inicio" required class="form-control" placeholder="dd/mm/aaaa">
                </div>

                <div class="form-group">
                    <label for="edit_data_termino">Data de Término:</label>
                    <input type="text" id="edit_data_termino" name="data_termino" required class="form-control" placeholder="dd/mm/aaaa">
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-submit">Atualizar Rotina</button>
                    <button type="button" class="btn-delete" onclick="deleteRoutine()">Excluir Rotina</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let calendar;
        
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                locale: 'pt-br',
                events: {
                    url: 'get_rotinas.php',
                    method: 'GET',
                    failure: function(error) {
                        alert('Erro ao carregar eventos: ' + error);
                    }
                },
                selectable: true,
                editable: true,
                eventClick: function(info) {
                    console.log('Evento clicado:', info.event);
                    fetchRoutineDetails(info.event.id);
                },
                dateClick: function(info) {
                    console.log('Data clicada:', info.dateStr);
                    document.getElementById('data_inicio').value = info.dateStr;
                    document.getElementById('data_termino').value = info.dateStr;
                    showForm();
                },
                eventDrop: function(info) {
                    console.log('Evento movido:', info.event);
                    if (confirm("Confirmar alteração?")) {
                        updateEventDates(info.event);
                    } else {
                        info.revert();
                    }
                },
                eventResize: function(info) {
                    console.log('Evento redimensionado:', info.event);
                    if (confirm("Confirmar alteração?")) {
                        updateEventDates(info.event);
                    } else {
                        info.revert();
                    }
                },
                eventDidMount: function(info) {
                    console.log('Evento montado:', info.event);
                },
                loading: function(isLoading) {
                    console.log('Carregando eventos:', isLoading);
                }
            });
            
            calendar.render();
            
            // Teste inicial para verificar se há eventos
            fetch('get_rotinas.php')
                .then(response => response.json())
                .then(data => {
                    console.log('Eventos carregados:', data);
                })
                .catch(error => {
                    console.error('Erro ao carregar eventos:', error);
                });

            // Formata as datas ao carregar o formulário
            formatDatesOnLoad();

            // Adiciona evento para formatar as datas ao enviar o formulário
            document.getElementById('routineForm').addEventListener('submit', formatDatesOnSubmit);
            document.getElementById('editForm').addEventListener('submit', formatDatesOnSubmit);
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

        function validateForm() {
            const dias = document.querySelectorAll('input[name="dias[]"]:checked');
            if (dias.length === 0) {
                alert('Por favor, selecione pelo menos um dia da semana');
                return false;
            }

            const dataInicio = new Date(document.getElementById('data_inicio').value);
            const dataTermino = new Date(document.getElementById('data_termino').value);
            
            if (dataTermino < dataInicio) {
                alert('A data de término não pode ser anterior à data de início');
                return false;
            }

            return true;
        }

        // Funções para manipulação de rotinas
        function fetchRoutineDetails(id) {
            fetch(`get_routine_details.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    fillEditForm(data);
                    document.getElementById('editModal').style.display = "block";
                })
                .catch(error => console.error('Erro:', error));
        }

        function fillEditForm(data) {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_nome').value = data.nome;
            document.getElementById('edit_data_inicio').value = data.data_inicio;
            document.getElementById('edit_data_termino').value = data.data_termino;

            // Limpa todos os checkboxes
            document.querySelectorAll('#editForm input[type="checkbox"]').forEach(cb => cb.checked = false);
            
            // Marca os dias selecionados
            data.dias.forEach(dia => {
                document.getElementById(`edit_${getDayName(dia)}`).checked = true;
            });
        }

        function getDayName(day) {
            const days = ['domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado'];
            return days[day];
        }

        function updateRoutine(event) {
            event.preventDefault();
            const formData = new FormData(document.getElementById('editForm'));

            fetch('update_routine.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeEditForm();
                    refreshCalendar();
                } else {
                    alert('Erro ao atualizar rotina: ' + data.message);
                }
            })
            .catch(error => console.error('Erro:', error));

            return false;
        }

        function deleteRoutine() {
            if (confirm('Tem certeza que deseja excluir esta rotina?')) {
                const id = document.getElementById('edit_id').value;

                fetch('delete_routine.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeEditForm();
                        refreshCalendar();
                    } else {
                        alert('Erro ao excluir rotina: ' + data.message);
                    }
                })
                .catch(error => console.error('Erro:', error));
            }
        }

        function updateEventDates(event) {
            fetch('update_routine_dates.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: event.id,
                    start: event.start.toISOString(),
                    end: event.end ? event.end.toISOString() : event.start.toISOString()
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Erro ao atualizar datas: ' + data.message);
                    refreshCalendar();
                }
            })
            .catch(error => console.error('Erro:', error));
        }

        function closeEditForm() {
            document.getElementById('editModal').style.display = "none";
        }

        // Atualiza o calendário após operações
        function refreshCalendar() {
            if (calendar) {
                calendar.refetchEvents();
            }
        }

        function formatDatesOnLoad() {
            const dataInicio = document.getElementById('data_inicio');
            const dataTermino = document.getElementById('data_termino');
            const editDataInicio = document.getElementById('edit_data_inicio');
            const editDataTermino = document.getElementById('edit_data_termino');

            if (dataInicio) dataInicio.value = formatDateToDisplay(dataInicio.value);
            if (dataTermino) dataTermino.value = formatDateToDisplay(dataTermino.value);
            if (editDataInicio) editDataInicio.value = formatDateToDisplay(editDataInicio.value);
            if (editDataTermino) editDataTermino.value = formatDateToDisplay(editDataTermino.value);
        }

        function formatDatesOnSubmit(event) {
            const form = event.target;
            const dataInicio = form.querySelector('[name="data_inicio"]');
            const dataTermino = form.querySelector('[name="data_termino"]');

            if (dataInicio) dataInicio.value = formatDateToSubmit(dataInicio.value);
            if (dataTermino) dataTermino.value = formatDateToSubmit(dataTermino.value);
        }

        function formatDateToDisplay(date) {
            if (!date) return '';
            const [year, month, day] = date.split('-');
            return `${day}/${month}/${year}`;
        }

        function formatDateToSubmit(date) {
            if (!date) return '';
            const [day, month, year] = date.split('/');
            return `${year}-${month}-${day}`;
        }

        $(document).ready(function(){
            // Aplica máscara nos campos de data
            $("#data_inicio, #data_termino, #edit_data_inicio, #edit_data_termino").inputmask("99/99/9999");
        });
    </script>
    <img src="gatinho.gif" alt="GIF animado" style="position: fixed; bottom: 10px; right: 10px; width: 100px; height: 100px;">
</body>
</html>