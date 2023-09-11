<?php
session_start();




function getRequestedAppointmentsForUser($conn, $user_id)
{
  $sql = "SELECT time_slots.start_time, time_slots.end_time, appointment_requests.status, users.name FROM time_slots
          JOIN appointment_requests ON time_slots.id = appointment_requests.time_slot_id
          JOIN users ON appointment_requests.user_id = users.id
          WHERE time_slots.user_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();




  $appointments = [];
  while ($row = $result->fetch_assoc()) {
      $appointments[] = $row;
  }




  return $appointments;
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
<title>Medical Scheduling Platform - Calendar</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<header>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
<a class="navbar-brand" href="#">Medical Scheduling Platform</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
<ul class="navbar-nav ml-auto">
 <li class="nav-item">
   <a class="nav-link" href="dashboard.php?user_id=<?php echo $user_id; ?>">Dashboard</a>
 </li>
</ul>
</div>
</nav>
</header>
<main>
<div class="container">
<h1 class="my-4">Time Slots</h1>
<div>
  <label>Start: <input type="datetime-local" id="start-time" /></label>
  <label>End: <input type="datetime-local" id="end-time" /></label>
  <button id="create-time-slot">Create Time Slot</button>
</div>
<h2>Time Slots List</h2>
<ul class="list-group" id="time-slots-list"></ul>


</div>
</main>
<footer>
<!-- Add your footer content here -->
</footer>




<script>
$(document).ready(function() {
// Get user_id from the URL
const urlParams = new URLSearchParams(window.location.search);
const user_id = urlParams.get('user_id');




// Update the links in the navbar
$('a.nav-link').each(function() {
  const href = $(this).attr('href');
  $(this).attr('href', href + '?user_id=' + user_id);
});




function loadTimeSlots() {
  $.getJSON('fetch_time_slots.php', { user_id: user_id }, function(data) {
    console.log("Time Slots Data:", data);
    displayTimeSlots(data);
  });
}




function displayTimeSlots(time_slots) {
  var $list = $("#time-slots-list");
  $list.empty();




  $.each(time_slots, function(index, time_slot) {
    var $item = $('<li class="list-group-item"></li>');
    $item.text(`[${time_slot.status}] ${time_slot.start_time} - ${time_slot.end_time}`);
    $item.append(' <button class="delete-time-slot" data-id="' + time_slot.id + '">Delete</button>');
    $item.append(' <button class="approve-time-slot" data-id="' + time_slot.id + '">Approve</button>');
    $item.append(' <button class="disapprove-time-slot" data-id="' + time_slot.id + '">Disapprove</button>');




    $list.append($item);
  });
}




$('#create-time-slot').on('click', function() {
  const start = $('#start-time').val();
  const end = $('#end-time').val();
  $.post('create_time_slot.php', { user_id: user_id, start_time: start, end_time: end }, function(response) {
    console.log("Create Time Slot Response:", response);
    if (response.success === true) {
      loadTimeSlots();
    } else {
      console.error('Error creating time slot:', response.message);
    }
  }, 'json');
});




$('#time-slots-list').on('click', '.delete-time-slot', function() {
  const id = $(this).data('id');
  console.log('Deleting time slot with ID:', id);
  $.post('delete_time_slot.php', { id, user_id }, function(response) {
    console.log('Delete time slot response:', response);
    if (response.success === true) {
      loadTimeSlots();
    } else {
      console.error('Error deleting time slot:', response.message);
    }
  }, 'json');
});




$('#time-slots-list').on('click', '.approve-time-slot, .disapprove-time-slot', function() {
  const id = $(this).data('id');
  const status = $(this).hasClass('approve-time-slot') ? 'approved' : 'disapproved';
  $.post('update_time_slot_status.php', { id, status, user_id }, function(response) {
    if (response.success === true) {
      loadTimeSlots();
    } else {
      console.error('Error updating time slot status:', response.message);
    }
  }, 'json');
});





loadTimeSlots();






//   function loadAppointments() {
//     $.getJSON('fetch_appointments.php', { user_id: user_id }, function(data) {
//         console.log("Appointments Data:", data);
//         displayAppointments(data);
//     });
// }




// function displayAppointments(appointments) {
//     // Display the appointments on the calendar
//     // Use a library like FullCalendar (https://fullcalendar.io/) to render the appointments
// }




// loadAppointments();




});
</script>








</body>
</html>