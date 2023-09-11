<?php
session_start();

require 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Content-Type: application/json');
  echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
  exit();
}

$start = $_POST['start'];
$end = $_POST['end'];

// Insert the time slot into the database
$sql = "INSERT INTO appointments (user_id, start_time, end_time, is_available) VALUES (?, ?, ?, 1)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iss', $_SESSION['user_id'], $start, $end);

if ($stmt->execute()) {
  header('Content-Type: application/json');
  echo json_encode(['status' => 'success']);
} else {
  header('Content-Type: application/json');
  echo json_encode(['status' => 'error', 'message' => 'Error creating time slot']);
}

$stmt->close();
$conn->close();
?>
