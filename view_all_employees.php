<?php
require_once 'client.php';

/**
 * API endpoint to show a list of employees
 */
$endpoint = "https://353f6527123646.as.deputy.com/api/v1/supervise/employee";  // Replace with the actual endpoint
$response = Client::request('GET', $endpoint);

$responseData = json_decode($response->getBody(), true);
        echo "<pre>";
        print_r($responseData);
        echo "</pre> <br>";
