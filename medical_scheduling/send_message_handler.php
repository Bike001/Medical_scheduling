<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: login.html');
  exit();
}

// Connection variables
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medical_scheduling";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data
$sender_id = $_SESSION['user_id']; // Use the session variable here
$receiver_id = $_POST['receiver_id'];
echo 'Receiver ID: ' . $receiver_id . '<br>';
$message = $_POST['message'];
echo 'Sender ID: ' . $sender_id . '<br>';
echo 'Receiver ID: ' . $receiver_id . '<br>';

// Prepare and bind the SQL statement
$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);

// Execute the statement and check the result
if ($stmt->execute()) {
  echo "Message sent successfully.";
} else {
  echo "Error: " . $stmt->error;
}

// Close the connection
$stmt->close();
$conn->close();
?>
