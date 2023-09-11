<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'calendar_functions.php';


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


if (isset($_GET['user_id'])) {
   $user_id = $_GET['user_id'];
 
  
} else {
   $user_id = $_SESSION['user_id'];
}


$sql = "SELECT m.id, m.sender_id, m.receiver_id, m.message, m.sent_at as received_at, u.name as sender_name
       FROM messages m
       JOIN users u ON m.sender_id = u.id
       WHERE m.receiver_id = ?
       ORDER BY m.sent_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$inbox_messages = $result->fetch_all(MYSQLI_ASSOC);


$sql = "SELECT m.id, m.sender_id, m.receiver_id, m.message, m.sent_at, u.name as receiver_name
       FROM messages m
       JOIN users u ON m.receiver_id = u.id
       WHERE m.sender_id = ?
       ORDER BY m.sent_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$sent_messages = $result->fetch_all(MYSQLI_ASSOC);


$stmt->close();
$conn->close();


?>







<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Medical Scheduling Platform - Messages</title>
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
  <section class="container mt-5">
    <a href="dashboard.php?user_id=<?php echo $user_id; ?>" class="btn btn-primary mb-3">Dashboard</a>
    <h1>Messages</h1>
    <h2>Inbox</h2>
    <?php if (count($inbox_messages) > 0): ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Sender</th>
            <th>Message</th>
            <th>Received at</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($inbox_messages as $message): ?>
            <tr>
              <td><?php echo htmlspecialchars($message['sender_name']); ?></td>
              <td><a href="message_details.php?message_id=<?php echo $message['id']; ?>&user_id=<?php echo $user_id; ?>&sender_id=<?php echo $message['sender_id']; ?>"><?php echo substr(htmlspecialchars($message['message']), 0, 30) . '...'; ?></a></td>
              <td><?php echo htmlspecialchars($message['received_at']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No messages found in the inbox.</p>
    <?php endif; ?>

    <h2>Sent</h2>
    <?php if (count($sent_messages) > 0): ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>To</th>
            <th>Message</th>
            <th>Sent at</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($sent_messages as $message): ?>
            <tr>
              <td><?php echo htmlspecialchars($message['receiver_name']); ?></td>
              <td><a href="message_details.php?message_id=<?php echo $message['id']; ?>"><?php echo substr(htmlspecialchars($message['message']), 0, 30) . '...'; ?></a></td>
              <td><?php echo htmlspecialchars($message['sent_at']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No messages found in the sent folder.</p>
    <?php endif; ?>
  </section>
</main>
<footer>
  <!-- Add your footer content here -->
</footer>
</body>
</html>