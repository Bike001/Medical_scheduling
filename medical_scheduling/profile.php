<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: login.html');
  exit();
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

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

// Get user information
$stmt = $conn->prepare("SELECT name, email, zip_code, user_type FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $zip_code, $user_type);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Medical Scheduling Platform - Profile</title>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
 <header>
   <nav class="navbar navbar-expand-lg navbar-light bg-light">
     <a class="navbar-brand" href="dashboard.php?user_id=<?php echo $user_id; ?>">Medical Scheduling Platform</a>
     <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
       <span class="navbar-toggler-icon"></span>
     </button>
     <div class="collapse navbar-collapse" id="navbarNav">
       <!-- Removed navbar links -->
     </div>
   </nav>
 </header>
 <main>
   <div class="container">
     <h1 class="my-4">Profile</h1>
      <form action="update_profile.php" method="POST">
        <div class="form-group">
          <label for="name">Name</label>
<input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
</div>
<div class="form-group">
<label for="email">Email</label>
<input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
</div>
<div class="form-group">
<label for="zip_code">Zip Code</label>
<input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php echo $zip_code; ?>">
</div>
<div class="form-group">
<label for="user_type">User Type</label>
<select class="form-control" id="user_type" name="user_type">
<option value="Doctor" <?php if ($user_type == 'Doctor') echo 'selected'; ?>>Doctor</option>
<option value="Nurse" <?php if ($user_type == 'Nurse') echo 'selected'; ?>>Nurse</option>
<option value="Pharmaceutical Supplier" <?php if ($user_type == 'Pharmaceutical Supplier') echo 'selected'; ?>>Pharmaceutical Supplier</option>
<option value="Medical Supplier" <?php if ($user_type == 'Medical Supplier') echo 'selected'; ?>>Medical Supplier</option>
</select>
</div>
<button type="submit" class="btn btn-primary">Update</button>
</form>
</div>

  </main>
  <footer>
    <!-- Add your footer content here -->
  </footer>
</body>
</html>
