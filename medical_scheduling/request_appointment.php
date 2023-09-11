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

// Get the user_id from the URL
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Fetch time slots with "no_request" status for the user
$sql = "SELECT * FROM time_slots WHERE user_id = ? AND status = 'no request'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$time_slots = [];
while ($row = $result->fetch_assoc()) {
 $time_slots[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Request Appointment</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
 <div class="container">
   <h1>Request Appointment</h1>
   <h2>Available Time Slots</h2>
   <?php if (count($time_slots) > 0): ?>
     <ul>
       <?php foreach ($time_slots as $time_slot): ?>
           <li>
 <?php echo $time_slot['start_time'] . ' - ' . $time_slot['end_time']; ?>
 <form action="submit_request.php" method="post" style="display:inline;">
   <input type="hidden" name="time_slot_id" value="<?php echo $time_slot['id']; ?>">
   <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
   <button type="submit" class="btn btn-sm btn-primary">Request</button>
 </form>
</li>
       <?php endforeach; ?>
     </ul>
   <?php else: ?>
     <p>No available time slots</p>
   <?php endif; ?>
 </div>
</body>
</html>
