<?php
session_start();

function getTimeSlotsForUser($conn, $user_id) {
  $sql = "SELECT * FROM time_slots WHERE user_id = ? AND status = 'available'";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  $time_slots = [];
  while ($row = $result->fetch_assoc()) {
    $time_slots[] = $row;
  }

  return $time_slots;
}

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

// Get search data
$name = isset($_GET['name']) ? $_GET['name'] : '';
$zip_code = isset($_GET['zip_code']) ? $_GET['zip_code'] : '';
$title = isset($_GET['title']) ? $_GET['title'] : '';

// Prepare the SQL statement
$sql = "SELECT id, name, user_type FROM users WHERE 1=1";

if (!empty($name)) {
   $sql .= " AND name LIKE ?";
}

if (!empty($zip_code)) {
   $sql .= " AND zip_code = ?";
}

if (!empty($title)) {
   $sql .= " AND user_type = ?";
}

$stmt = $conn->prepare($sql);

if (!empty($name) && !empty($zip_code) && !empty($title)) {
   $name = '%' . $name . '%';
   $stmt->bind_param("sss", $name, $zip_code, $title);
} elseif (!empty($name) && !empty($zip_code)) {
   $name = '%' . $name . '%';
   $stmt->bind_param("ss", $name, $zip_code);
} elseif (!empty($name) && !empty($title)) {
   $name = '%' . $name . '%';
   $stmt->bind_param("ss", $name, $title);
} elseif (!empty($zip_code) && !empty($title)) {
   $stmt->bind_param("ss", $zip_code, $title);
} elseif (!empty($name)) {
   $name = '%' . $name . '%';
   $stmt->bind_param("s", $name);
} elseif (!empty($zip_code)) {
   $stmt->bind_param("s", $zip_code);
} elseif (!empty($title)) {
   $stmt->bind_param("s", $title);
}

// Execute the statement and fetch the results
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Medical Scheduling Platform - Search Results</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<header>
<!-- Add your navigation bar here -->
</header>
<main>
<div class="container">
  <h1 class="my-4">Search Results</h1>
  <div class="row">
  <?php
// ... (previous code)

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $time_slots = getTimeSlotsForUser($conn, $row['id']);

        echo '<div class="col-md-4 mb-4">';
        echo ' <div class="card">';
        echo ' <div class="card-body">';
        echo ' <h5 class="card-title">' . $row['name'] . '</h5>';
        echo ' <p class="card-text">' . $row['user_type'] . '</p>';

        if (count($time_slots) > 0) {
            echo '<ul>';
            foreach ($time_slots as $time_slot) {
                echo '<li>' . $time_slot['start_time'] . ' - ' . $time_slot['end_time'] . '</li>';
            }
            echo '</ul>';
        } 

        echo ' <a href="request_appointment.php?user_id=' . $row['id'] . '" class="btn btn-primary">Make Appointment</a>';
        echo ' <a href="send_message.php?user_id=' . $row['id'] . '" class="btn btn-secondary">Send Message</a>';
        echo ' (User ID: ' . $row['id'] . ')';
        echo ' </div>';
        echo ' </div>';
        echo '</div>'; // Close the col-md-4 div
    }
} else {
    echo '<div class="col-12">';
    echo ' <p>No results found.</p>';
    echo '</div>';
}
$stmt->close();
$conn->close();
?>

    </div> <!-- Close the row div -->
</div>
</main>
<footer>
    <!-- Add your footer content here -->
</footer>
</body>
</html>
