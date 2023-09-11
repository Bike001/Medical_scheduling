<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: login.html');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Scheduling Platform - Send Message</title>
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
        <h1 class="my-4">Send Message</h1>
        <!-- <p>Receiver ID: <?php echo $_GET['user_id']; ?></p>  -->

        <form action="send_message_handler.php" method="post">
            <input type="hidden" name="receiver_id" value="<?php echo $_GET['user_id']; ?>">
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
    </div>
</main>
<footer>
    <!-- Add your footer content here -->
</footer>
</body>
</html>
