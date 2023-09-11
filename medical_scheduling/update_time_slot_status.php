<?php
require_once 'db_connection.php';

$id = $_POST['id'];
$status = $_POST['status'];
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $_SESSION['user_id'];

$sql = "UPDATE time_slots SET status = ? WHERE id = ? AND user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('sii', $status, $id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['result' => 'error', 'message' => $conn->error]);
}

// // Add the approved appointment to the appointments table
// if ($status == 'approved') {
//   $sql = "INSERT INTO appointments (user_id, time_slot_id) VALUES (?, ?)";
//   $stmt = $conn->prepare($sql);
//   $stmt->bind_param("ii", $user_id, $id);
//   $stmt->execute();
// }


$stmt->close();
$conn->close();
