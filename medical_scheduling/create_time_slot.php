<?php
session_start();

require_once 'db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['result' => 'error', 'message' => 'Not logged in']);
    exit();
}

$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $_SESSION['user_id'];

$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];

// Change the status value to 'no request'
$query = "INSERT INTO time_slots (user_id, start_time, end_time, status) VALUES (?, ?, ?, 'no request')";

$stmt = $conn->prepare($query);
$stmt->bind_param("iss", $user_id, $start_time, $end_time);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Time slot created successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error creating time slot.']);
}

$stmt->close();
$conn->close();
?>
