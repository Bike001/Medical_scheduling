<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message_id = $_POST['message_id'];
    $reply_message = $_POST['message'];

    // Add your code to save the reply message in the database
    // Redirect to the messages page after saving the reply
    header('Location: messages.php');
} else {
    header('Location: messages.php');
}
?>
