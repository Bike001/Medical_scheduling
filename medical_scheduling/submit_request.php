<?php
session_start();

require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$time_slot_id = $_POST['time_slot_id'];
$user_id = $_POST['user_id'];

// Insert the appointment request with 'pending' status
$query = "INSERT INTO appointment_requests (time_slot_id, user_id, status) VALUES (?, ?, 'pending')";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $time_slot_id, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Update the time slot status to 'pending'
    $update_query = "UPDATE time_slots SET status = 'pending' WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("i", $time_slot_id);
    $update_stmt->execute();

    if ($update_stmt->affected_rows > 0) {
        header('Location: request_appointment.php?user_id=' . $user_id);
    } else {
        echo "Error updating time slot status.";
    }
    $update_stmt->close();
} else {
    echo "Error submitting request.";
}

$stmt->close();
$conn->close();
?>
