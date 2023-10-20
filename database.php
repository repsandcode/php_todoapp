<?php
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
  die("Connection failed" . $conn->connect_error);
}

$result = $conn->query("SELECT DATABASE()");
$row = $result->fetch_row();
printf("Connected now to database - %s.\n", $row[0]);
