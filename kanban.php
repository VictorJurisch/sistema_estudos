<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'create') {
        $name = $_POST['name'];
        $column = $_POST['column'];
        $position = $_POST['position'];

        $stmt = $conn->prepare("INSERT INTO kanban (name, column_name, position) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $column, $position);
        $stmt->execute();
        echo $conn->insert_id;
    } elseif ($action === 'update') {
        $id = $_POST['id'];
        $column = $_POST['column'];
        $position = $_POST['position'];

        $stmt = $conn->prepare("UPDATE kanban SET column_name = ?, position = ? WHERE id = ?");
        $stmt->bind_param("sii", $column, $position, $id);
        $stmt->execute();
    } elseif ($action === 'delete') {
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM kanban WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    exit;
}

$tasks = [];
$result = $conn->query("SELECT * FROM kanban ORDER BY position ASC");
if ($result) {
    $tasks = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Portal de Estudos | Kanban </title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Autour+One&display=swap');

        h1, h2 {
            font-family: 'Autour One', cursive;
        }

        .hide {
            display: none;
        }

        .drag-over {
            background-color: #f0f0f0;
        }

        .kanban-item button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            margin-left: 5px;
            font-size: 16px; /* Adjust the font size to make icons smaller */
            color: black; /* Set icon color to black */
        }

        .kanban-item button.edit-task::before {
            content: '\270E'; /* Unicode for pencil icon */
        }

        .kanban-item button.delete-task::before {
            content: '\1F5D1'; /* Unicode for trash can icon */
        }

        #create-task {
            background: #ff69b4; /* Pink color for create task button */
            border: none;
            color: white;
            padding: 5px 10px;
            font-size: 14px; /* Smaller font size */
            cursor: pointer;
            border-radius: 3px;
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
    </style>
</head>
<body>
    <a id="back-home" href="home.php">Voltar para Home</a>
    <h1>Quadro Kanban</h1>
    <button id="create-task">Criar Task</button>
    <div class="kanban-board">
        <div class="kanban-column" data-column="To Do">
            <h2>To Do</h2>
            <?php foreach ($tasks as $task): ?>
                <?php if ($task['column_name'] === 'To Do'): ?>
                    <div class="kanban-item" id="task-<?= $task['id'] ?>" draggable="true">
                        <?= $task['name'] ?>
                        <button class="edit-task"></button>
                        <button class="delete-task"></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="kanban-column" data-column="In Progress">
            <h2>In Progress</h2>
            <?php foreach ($tasks as $task): ?>
                <?php if ($task['column_name'] === 'In Progress'): ?>
                    <div class="kanban-item" id="task-<?= $task['id'] ?>" draggable="true">
                        <?= $task['name'] ?>
                        <button class="edit-task"></button>
                        <button class="delete-task"></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="kanban-column" data-column="Done">
            <h2>Done</h2>
            <?php foreach ($tasks as $task): ?>
                <?php if ($task['column_name'] === 'Done'): ?>
                    <div class="kanban-item" id="task-<?= $task['id'] ?>" draggable="true">
                        <?= $task['name'] ?>
                        <button class="edit-task"></button>
                        <button class="delete-task"></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const items = document.querySelectorAll('.kanban-item');
            const columns = document.querySelectorAll('.kanban-column');
            const createTaskButton = document.getElementById('create-task');

            createTaskButton.addEventListener('click', () => {
                const taskName = prompt('Nome da nova task:');
                if (taskName) {
                    const column = 'To Do';
                    const position = document.querySelectorAll('.kanban-column[data-column="To Do"] .kanban-item').length;

                    fetch('kanban.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=create&name=${taskName}&column=${column}&position=${position}`
                    })
                    .then(response => response.text())
                    .then(id => {
                        const newTask = document.createElement('div');
                        newTask.classList.add('kanban-item');
                        newTask.id = `task-${id}`;
                        newTask.draggable = true;
                        newTask.innerHTML = `${taskName} <button class="edit-task"></button> <button class="delete-task"></button>`;
                        document.querySelector('.kanban-column[data-column="To Do"]').appendChild(newTask);
                        addDragAndDropEvents(newTask);
                        addTaskButtonEvents(newTask);
                    });
                }
            });

            items.forEach(item => {
                item.draggable = true;
                addDragAndDropEvents(item);
                addTaskButtonEvents(item);
            });

            columns.forEach(column => {
                column.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    const afterElement = getDragAfterElement(column, e.clientY);
                    const draggable = document.querySelector('.dragging');
                    if (afterElement == null) {
                        column.appendChild(draggable);
                    } else {
                        column.insertBefore(draggable, afterElement);
                    }
                });

                column.addEventListener('dragenter', (e) => {
                    e.preventDefault();
                    column.classList.add('drag-over');
                });

                column.addEventListener('dragleave', (e) => {
                    column.classList.remove('drag-over');
                });

                column.addEventListener('drop', (e) => {
                    e.preventDefault();
                    const id = e.dataTransfer.getData('text/plain');
                    const draggable = document.getElementById(id);
                    column.appendChild(draggable);
                    column.classList.remove('drag-over');

                    const column_name = column.getAttribute('data-column');
                    const position = Array.from(column.children).indexOf(draggable);

                    fetch('kanban.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=update&id=${id.split('-')[1]}&column=${column_name}&position=${position}`
                    });
                });
            });

            function addDragAndDropEvents(item) {
                item.addEventListener('dragstart', (e) => {
                    e.dataTransfer.setData('text/plain', e.target.id);
                    item.classList.add('dragging');
                    setTimeout(() => {
                        e.target.classList.add('hide');
                    }, 0);
                });

                item.addEventListener('dragend', (e) => {
                    e.target.classList.remove('hide');
                    item.classList.remove('dragging');
                });
            }

            function addTaskButtonEvents(item) {
                const editButton = item.querySelector('.edit-task');
                const deleteButton = item.querySelector('.delete-task');

                editButton.addEventListener('click', () => {
                    const newTaskName = prompt('Editar nome da task:', item.childNodes[0].nodeValue.trim());
                    if (newTaskName) {
                        item.childNodes[0].nodeValue = newTaskName + ' ';
                        const id = item.id.split('-')[1];

                        fetch('kanban.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `action=update&id=${id}&name=${newTaskName}`
                        });
                    }
                });

                deleteButton.addEventListener('click', () => {
                    const id = item.id.split('-')[1];

                    fetch('kanban.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=delete&id=${id}`
                    })
                    .then(() => {
                        item.remove();
                    });
                });
            }

            function getDragAfterElement(container, y) {
                const draggableElements = [...container.querySelectorAll('.kanban-item:not(.dragging)')];

                return draggableElements.reduce((closest, child) => {
                    const box = child.getBoundingClientRect();
                    const offset = y - box.top - box.height / 2;
                    if (offset < 0 && offset > closest.offset) {
                        return { offset: offset, element: child };
                    } else {
                        return closest;
                    }
                }, { offset: Number.NEGATIVE_INFINITY }).element;
            }
        });
    </script>
    <img src="gatinho.gif" alt="GIF animado" style="position: fixed; bottom: 10px; right: 10px; width: 100px; height: 100px;">
</body>
</html>