<?php
session_start();


if (!isset($_SESSION['user_id'])) {
   header('Location: login.html');
   exit();
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medical_scheduling";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}






$sender_id = $_POST['user_id'];




$receiver_id = $_POST['receiver_id'];

$message = $_POST['message'];


$sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);
$stmt->execute();


$stmt->close();
$conn->close();


header('Location: messages.php?user_id=' . $_SESSION['viewing_user_id']);
exit();
?>