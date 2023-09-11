<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: login.html');
  exit();
}

// Get user_id from the session variable
$user_id = $_SESSION['user_id'];

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
$name = $_POST['name'];
$email = $_POST['email'];
$zip_code = $_POST['zip_code'];
$user_type = $_POST['user_type'];

// Prepare and bind the SQL statement
$stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, zip_code = ?, user_type = ? WHERE id = ?");
$stmt->bind_param("ssssi", $name, $email, $zip_code, $user_type, $user_id);

// Execute the statement and check the result
if ($stmt->execute()) {
  echo "Profile updated successfully.";
  $_SESSION['user_name'] = $name;
  $_SESSION['user_type'] = $user_type;
  header('Location: profile.php?user_id=' . $user_id);
} else {
  echo "Error: " . $stmt->error;
}

// Close the connection
$stmt->close();
$conn->close();
?>