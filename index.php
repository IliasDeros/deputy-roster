<?php
require 'vendor/autoload.php';  // Load Guzzle
require_once 'client.php';
try {


    /**
     * Employee data to create (change as needed)
     */
    $newEmployeeData = [
           "strFirstName" => "Amer",
           "strLastName" => "Chaudhuri",
           "displayName" => "Amer Amer",
           "intCompanyId" => 1
    ];

    /**
     * API endpoint for creating a new employee
     */
    $endpoint = "https://353f6527123646.as.deputy.com/api/v1/supervise/employee";  // Replace with the actual endpoint

//    extracted($endpoint, $newEmployeeData);


} catch (Throwable $e) {
    die(var_export($e->getMessage()));
}

