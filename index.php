<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

  $db_host = 'localhost';
  $db_user = 'root';
  $db_password = 'root';
  $db_name = 'todo_app';
 
  $conn = @new mysqli(
    $db_host,
    $db_user,
    $db_password,
    $db_name,
  );
	
  if ($conn->connect_error) {
    echo 'Errno: '.$conn->connect_errno;
    echo '<br>';
    echo 'Error: '.$conn->connect_error;
    exit();
  }

  echo 'Success: A proper connection to MySQL was made.';
  echo '<br>';
  echo 'Host information: '.$conn->host_info;
  echo '<br>';
  echo 'Protocol version: '.$conn->protocol_version;


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $task = $_POST["task"];
        $sql = "INSERT INTO tasks (task) VALUES ('$task')";
        if ($conn->query($sql) === TRUE) {
            echo "\n\nNew task added successfully!";
        } else {
            echo "\n\nError: " . $sql . "<br>" . $conn->error;
        }
    }

    if (isset($_GET['task_id'])) {
        $taskId = $_GET['task_id'];
        $sql = "UPDATE tasks SET completed = 1 WHERE id = $taskId";
        if ($conn->query($sql) === TRUE) {
            echo "Task marked as completed!";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
?>

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

    <h3>Tasks:</h3>
    <ul>
        <?php
            $sql = "SELECT * FROM tasks";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li>";
                    if (!$row["completed"]) {
                        echo '<a href="?task_id=' . $row["id"] . '">Mark as Completed</a> - ';
                    }
                    echo $row["task"];
                    echo "</li>";
                }
            } else {
                echo "No tasks yet.";
            }
        ?>
    </ul>

    <?php
        $conn->close();
    ?>
</body>
</html>