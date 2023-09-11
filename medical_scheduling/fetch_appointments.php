<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'config.php';

$user_id = $_GET['user_id'];

function getAppointmentsForUser($conn, $user_id)
{
    $sql = "SELECT time_slots.id, time_slots.start_time, time_slots.end_time, time_slots.status, users.name, appointment_requests.status AS appointment_status
    FROM time_slots
    LEFT JOIN appointment_requests ON time_slots.id = appointment_requests.time_slot_id
    LEFT JOIN users ON appointment_requests.user_id = users.id
    WHERE time_slots.user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $appointments = [];
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }

    return $appointments;
}

header('Content-Type: application/json');
echo json_encode(getAppointmentsForUser($conn, $user_id));
