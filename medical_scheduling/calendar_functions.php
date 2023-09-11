<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'vendor/autoload.php';

function getEvents($client) {
    $service = new Google_Service_Calendar($client);
    $calendarId = 'primary';
    $optParams = array(
        'orderBy' => 'startTime',
        'singleEvents' => true,
        'timeMin' => date('c'),
    );
    $results = $service->events->listEvents($calendarId, $optParams);
    return $results->getItems();
}

function createEvent($client, $eventData) {
    $service = new Google_Service_Calendar($client);
    $calendarId = 'primary';
    $event = new Google_Service_Calendar_Event($eventData);
    $results = $service->events->insert($calendarId, $event);
    return $results;
}
?>
