<?php
require_once 'client.php';

/**
 * Reading time sheet json file
 */
$employeeShifts = json_decode(file_get_contents('timeSheet.json'), 1);

/**
 * API endpoint for creating a shift
 */
$endpoint = "https://353f6527123646.as.deputy.com/api/v1/supervise/roster";  // Replace with the actual endpoint
foreach ($employeeShifts as $shift) {
    $shift['intStartTimestamp'] = strtotime($shift['intStartTimestamp']);
    $shift['intEndTimestamp'] = strtotime($shift['intEndTimestamp']);

    $response = Client::request('Post', $endpoint, $shift);
    $responseData = json_decode($response->getBody(), true);

    die(var_export($responseData));

}


die(var_export($employeeShifts));