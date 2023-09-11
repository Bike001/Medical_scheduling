<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.html');
  exit();
}

if (!isset($_GET['user_id'])) {
  header('Location: search.php');
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

$user_id = $_GET['user_id'];

$sql = "SELECT * FROM time_slots WHERE user_id = ? AND status = 'available'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Medical Scheduling Platform - View Time Slots</title>
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
  <h1 class="my-4">Available Time Slots</h1>
  <div class="row">
    <?php
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $start_time = date('Y-m-d H:i', strtotime($row['start_time']));
          $end_time = date('Y-m-d H:i', strtotime($row['end_time']));
          echo '<div class="col-md-4 mb-4">';
          echo '  <div class="card">';
          echo '    <div class="card-body">';
          echo '      <h5 class="card-title">Start: ' . $start_time . '</h5>';
          echo '      <p class="card-text">End: ' . $end_time . '</p>';
          echo '      <a href="request_appointment.php?time_slot_id=' . $row['id'] . '&user_id=' . $user_id . '" class="btn btn-primary">Request Appointment</a>';
          echo '    </div>';
          echo '  </div>';
          echo '</div>';
        }
      } else {
        echo '<div class="col-12">';
        echo '  <p>No time slots are available for this user.</p>';
        echo '</div>';
        }
        ?>
        
          </div>
        </div>
        </main>
        <footer>
        <!-- Add your footer content here -->
        </footer>
        </body>
        </html>
        <?php
        $stmt->close();
        $conn->close();
        ?>
