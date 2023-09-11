<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_connection.php';

$id = $_POST['id'];
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $_SESSION['user_id'];

$sql = "DELETE FROM time_slots WHERE id = ? AND user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}

$stmt->close();
$conn->close();
