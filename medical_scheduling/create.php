<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
 die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS medical_scheduling";
if ($conn->query($sql) === TRUE) {
 echo "Database created successfully<br>";
} else {
 echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db("medical_scheduling");

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
 id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(255) NOT NULL,
 email VARCHAR(255) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 zip_code VARCHAR(10) NOT NULL,
 user_type VARCHAR(50) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
 echo "Table 'users' created successfully<br>";
} else {
 echo "Error creating table: " . $conn->error . "<br>";
}

// Create time_slots table
$sql = "CREATE TABLE IF NOT EXISTS time_slots (
 id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id INT(11) UNSIGNED NOT NULL,
 start_time DATETIME NOT NULL,
 end_time DATETIME NOT NULL,
 status VARCHAR(50) NOT NULL,
 FOREIGN KEY (user_id) REFERENCES users(id)
)";

if ($conn->query($sql) === TRUE) {
 echo "Table 'time_slots' created successfully<br>";
} else {
 echo "Error creating table: " . $conn->error . "<br>";
}



// Create appointments table
$sql = "CREATE TABLE IF NOT EXISTS appointments (
 id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id INT(11) UNSIGNED NOT NULL,
 time_slot_id INT(11) UNSIGNED NOT NULL,
 status VARCHAR(50) NOT NULL,
 FOREIGN KEY (user_id) REFERENCES users(id),
 FOREIGN KEY (time_slot_id) REFERENCES time_slots(id)
)";

if ($conn->query($sql) === TRUE) {
 echo "Table 'appointments' created successfully<br>";
} else {
 echo "Error creating table: " . $conn->error . "<br>";
}

// Create appointment_requests table
$sql = "CREATE TABLE IF NOT EXISTS appointment_requests (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  time_slot_id INT(11) UNSIGNED NOT NULL,
  user_id INT(11) UNSIGNED NOT NULL,
  status ENUM('pending', 'accepted', 'declined') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (id),
  FOREIGN KEY (time_slot_id) REFERENCES time_slots(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
 
 if ($conn->query($sql) === TRUE) {
  echo "Table 'appointment_requests' created successfully<br>";
 } else {
  echo "Error creating table: " . $conn->error . "<br>";
 }
 
 


// Create event_list table
$sql = "CREATE TABLE IF NOT EXISTS event_list (
 id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 appointment_request_id INT(11) UNSIGNED NOT NULL,
 user_id INT(11) UNSIGNED NOT NULL,
 status ENUM('pending', 'accepted', 'declined') NOT NULL DEFAULT 'pending',
 FOREIGN KEY (appointment_request_id) REFERENCES appointment_requests(id),
 FOREIGN KEY (user_id) REFERENCES users(id)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql) === TRUE) {
 echo "Table 'event_list' created successfully<br>";
} else {
 echo "Error creating table: " . $conn->error . "<br>";
}

$conn->close();
?>

