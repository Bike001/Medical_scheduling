<?php
session_start();

header('Content-Type: application/json');

// Get the user ID from the request
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : $_SESSION['user_id'];


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medical_scheduling";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// $sql = "SELECT time_slots.id, time_slots.start_time, time_slots.end_time, time_slots.status
//        FROM time_slots
//        WHERE time_slots.user_id = ?";


$sql = "SELECT time_slots.id, time_slots.start_time, time_slots.end_time, time_slots.status, users.name, appointment_requests.status AS appointment_status
       FROM time_slots
       LEFT JOIN appointment_requests ON time_slots.id = appointment_requests.time_slot_id
       LEFT JOIN users ON appointment_requests.user_id = users.id
       WHERE time_slots.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$time_slots = [];
while ($row = $result->fetch_assoc()) {
  $time_slots[] = $row;
}

echo json_encode($time_slots);

$stmt->close();
$conn->close();
?>
