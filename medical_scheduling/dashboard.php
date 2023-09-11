<?php
session_start();

// Get user_id from the URL and set it to the session variable
if (isset($_GET['user_id'])) {
  $_SESSION['user_id'] = $_GET['user_id'];
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: login.html');
  exit();
}

$user_id = $_SESSION['user_id'];

$user_type = $_SESSION['user_type'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Scheduling Platform - Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://apis.google.com/js/api.js"></script>
    <style>
        body {
            background-image: url('https://media.istockphoto.com/id/1369987284/photo/close-up-of-a-stethoscope-and-digital-tablet-with-virtual-electronic-medical-record-of.jpg?s=1024x1024&w=is&k=20&c=yVQAXb6m0Cdm5krTO-rwkEbFw2jeZUGjvNQcPhUGl1k=');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="dashboard.php?user_id=<?php echo $user_id; ?>">Medical Scheduling Platform</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="calendar.php">Calendar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="messages.php">Messages</a>
                        <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Account
  </a>
  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
    <a class="dropdown-item" href="profile.php?user_id=<?php echo $user_id; ?>">Profile</a>
    <a class="dropdown-item" href="logout.php">Logout</a>
  </div>
</li>


                </ul>
            </div>
        </nav>
    </header>
    <main>
        <div class="container">
            <h1 class="my-4">Welcome to the Platform</h1>
            <form action="search.php" method="GET" class="mb-4">
                <div class="form-row">
                    <div class="col">
                        <input type="text" class="form-control" name="name" placeholder="Name">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="zip_code" placeholder="Zip Code">
                    </div>
                    <div class="col">
                <select class="form-control" name="title">
                    <option value="">Title</option>
                    <option value="Doctor">Doctor</option>
                    <option value="Nurse">Nurse</option>
                    <option value="Pharmaceutical Supplier">Pharmaceutical Supplier</option>
                    <option value="Medical Supplier">Medical Supplier</option>
                </select>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>
    <!-- Add the functionality to list the search results and display appointment and message buttons -->
</div>
</main>
<footer>
    <!-- Add your footer content here -->
</footer>
<script>
$(document).ready(function() {
  // Get user_id from the PHP session variable
  const user_id = <?php echo $_SESSION['user_id']; ?>;

  // Update the links in the navbar
  $('a.nav-link').each(function() {
    const href = $(this).attr('href');
    $(this).attr('href', href + '?user_id=' + user_id);
  });
});
</script>





</body>
</html>
