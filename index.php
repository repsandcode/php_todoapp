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


// if anchor tag "check" is clicked, run this
if (isset($_GET['task_id'])) {

    // get ID of the task
    $taskId = $_GET['task_id'];
    // sql query that will return 1 if task is complete else 0 
    $taskStatus = "SELECT completed FROM tasks WHERE id = $taskId";
    // sql query that will update a task as completed by changing 0 to 1
    $checkComplete = "UPDATE tasks SET completed = 1 WHERE id = $taskId";

    // apply sql query $checkComplete
    if ($conn->query($checkComplete) === TRUE) {
        // if query is successful, run this
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
    // sql query to get all rows from table TASKS
    $sql = "SELECT * FROM tasks";
    // run query above and save the result
    $result = $conn->query($sql);

    // run code block IF there are rows or tasks inside table TASKS
    if ($result->num_rows > 0) {
        // all rows as a list of tasks
        echo "<ul>";
        // looping over each row
        while ($row = $result->fetch_assoc()) {
            // each row inside a list
            echo "<li>";
            // if a row is not completed yet, run code block below
            if (!$row["completed"]) {
                echo '<a href="?task_id=' . $row["id"] . '">Check</a> - ';
            }
            // name of the task
            echo $row["task"];
            // closing a list
            echo "</li>";
        }
        // closing the list of tasks
        echo "</ul>";
    } else {
        // if there are no data inside the table tasks
        echo "<h3>Please add a task.</h3>";
    }

    // close connection to the database
    $conn->close();
    ?>
</body>

</html>
<!-- end of HTML -->