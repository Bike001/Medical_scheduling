<?php




ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once 'database_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['time_slot_id'])) {
        error_log("Deleting appointment requests for time_slot_id: " . $_POST['time_slot_id']);
        $time_slot_id = $_POST['time_slot_id'];

        try {
            // Delete appointment requests associated with the time_slot_id
            $sql = "DELETE FROM appointment_requests WHERE time_slot_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $time_slot_id);
            $stmt->execute();

            // Check if any appointment requests were deleted
            if ($stmt->affected_rows > 0) {
                $response = ['success' => true, 'message' => 'Appointment requests deleted successfully.'];
            } else {
                $response = ['success' => false, 'message' => 'No appointment requests found for the given time_slot_id.'];
            }
        } catch (Exception $e) {
            $response = ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    } else {
        $response = ['success' => false, 'message' => 'Missing time_slot_id parameter.'];
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid request method.'];
}

echo json_encode($response);
