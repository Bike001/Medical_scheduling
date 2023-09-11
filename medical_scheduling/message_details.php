<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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

$sender_id = isset($_GET['sender_id']) ? $_GET['sender_id'] : null;


$user_id = $_GET['user_id'];
echo "User ID: " . $user_id;






$message_id = $_GET['message_id'];
$sql = "SELECT m.id, m.sender_id, m.receiver_id, m.message, m.sent_at, u.name as sender_name
      FROM messages m
      JOIN users u ON m.sender_id = u.id
      WHERE m.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $message_id);
$stmt->execute();
$result = $stmt->get_result();
$message = $result->fetch_assoc();
$_SESSION['viewing_user_id'] = $message['receiver_id'];


$stmt->close();
$conn->close();


?>












<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Medical Scheduling Platform - Message Details</title>
   <style>
       .message-box {
           width: 100%;
           height: 200px;
           overflow: auto;
           border: 1px solid #ccc;
           margin-bottom: 20px;
           padding: 10px;
       }
       .reply-box {
           width: 100%;
           height: 100px;
           margin-bottom: 10px;
       }
   </style>
</head>
<body>
   <?php if ($message): ?>
   <main>
       <section>
           <h1>Message Details</h1>
           <h2>Message</h2>
           <div class="message-box">
               <?php echo htmlspecialchars($message['message']); ?>
           </div>
           <h2>Reply</h2>
           <form action="reply.php" method="POST">
   <input type="hidden" name="receiver_id" value="<?php echo $_SESSION['user_id'] == $message['sender_id'] ? $message['receiver_id'] : $message['sender_id']; ?>">
   <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
   <input type="hidden" name="receiver_id" value="<?php echo $sender_id; ?>">



   <textarea name="message" class="reply-box" placeholder="Type your reply..."></textarea>
   <br>
   <button type="submit">Reply</button>
</form>


       </section>
   </main>
   <?php else: ?>
       <p>Message not found.</p>
   <?php endif; ?>
</body>
</html>
