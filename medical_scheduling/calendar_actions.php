<?php
require_once 'vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig([
    'client_id' => '402394936170-euum54ctdmei4k5f5du5j33oarau6fnq.apps.googleusercontent.com',
    'client_secret' => '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',
    'redirect_uri' => 'http://localhost/medical_scheduling/manage_calendar.php',
    'scope' => ['https://www.googleapis.com/auth/calendar'],
]);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    $calendar_service = new Google_Service_Calendar($client);

    $action = $_POST['action'];

    if ($action === 'add') {
        $event = new Google_Service_Calendar_Event([
            'summary' => $_POST['title'],
            'start' => ['dateTime' => $_POST['start']],
            'end' => ['dateTime' => $_POST['end']],
        ]);

        $calendarId = 'primary';
        try {
            $event = $calendar_service->events->insert($calendarId, $event);
            echo json_encode(['status' => 'success', 'eventId' => $event->getId()]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }

    } elseif ($action === 'delete') {
        $calendarId = 'primary';
        $eventId = $_POST['eventId'];
        try {
            $calendar_service->events->delete($calendarId, $eventId);
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
} else {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
}
?>
