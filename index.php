<?php
// to show the errors that might happen, including where in this program, 
// meaning - which lines in this program we have the error 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// connecting with another php file - database.php
include("database.php");

$message = '';

// run if user interacted with the form and hit the submit button
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // extract the data from the input text named "task"
    // saved to variable -> $task
    $task = $_POST["task"];

    // sql syntax to insert $task to the table named "tasks"
    // saved to variable -> $sql
    $sql = "INSERT INTO tasks (task) VALUES ('$task')";

    // get database connection and insert our add-task query
    if ($conn->query($sql) === TRUE) {
        // if that is true, then show code below
        $message = "\n\nNew task added successfully!";
    } else {
        // else, run this code and show error
        $message = "\n\nError: " . $sql . "<br>" . $conn->error;
    }
}


if (isset($_GET['task_id'])) {
    $taskId = $_GET['task_id'];

    $taskStatus = "SELECT completed FROM tasks WHERE id = $taskId";
    $checkComplete = "UPDATE tasks SET completed = 1 WHERE id = $taskId";

    if ($conn->query($checkComplete) === TRUE) {
        $message = "Task marked as completed!";
    } else {
        $message = "Error updating record: " . $conn->error;
    }
}

?>

<!-- start of the HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
</head>

<body>

    <h2>Todo List</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="task">Task:</label>
        <input type="text" id="task" name="task" required>
        <button type="submit">Add Task</button>
    </form>

    <!-- display messages -->
    <p><?php $message ?></p>

    <?php
    $sql = "SELECT * FROM tasks";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>";
            if (!$row["completed"]) {
                echo '<a href="?task_id=' . $row["id"] . '">Check</a> - ';
            }
            echo $row["task"];
            echo "</li>";
        }
        echo "</ul>";
    }

    $conn->close();
    ?>
</body>

</html>
<!-- end of HTML -->