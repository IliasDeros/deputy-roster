<?php
require_once 'client.php';

/**
 * Setting the time and Memory Limits
 */
ini_set("memory_limit", "-1");
set_time_limit(0);

/**
 * Setting Default Timezone
 */
date_default_timezone_set("UTC");

/**
 * Reading Employees and Schedule json files
 */
$employees = json_decode(file_get_contents('json_files/employees.json'), 1);
$employeeShifts = json_decode(file_get_contents('json_files/schedule.json'), 1);

/**
 * Company ID
 */
$intCompanyId = 1; // replace it by your company id

foreach ($employees as $employee) {
    $employee['intCompanyId'] = $intCompanyId;
    initEmployee($employee, $employeeShifts); // add/update and get employee
}

/**
 * Initialize the Employee, Map the schedule and Update
 * @param array $newEmployeesData
 * @param array $employeeShifts
 * @return void
 * @throws Exception
 */
function initEmployee(array $newEmployeesData, array $employeeShifts)
{
    /**
     * API endpoint for creating a new employee
     */
    $endpoint = "https://353f6527123646.as.deputy.com/api/v1/supervise/employee";  // Replace with the actual endpoint

    /**
     * Send Request
     */
    $response = Client::request('Post', $endpoint, $newEmployeesData);

    /**
     *  Response Status Code
     */
    $statusCode = $response->getStatusCode();
    if ($statusCode === 200) {
        /**
         * Decode Response Data into PHP Array
         */
        $responseData = json_decode($response->getBody(), true);
        if(isset($responseData)) {
            echo $responseData['DisplayName'] . "  Initialized \n <br>";
            importEmployeeSchedule($newEmployeesData['id'], $responseData['Id'], $employeeShifts, $responseData['DisplayName']);
        }
    }
}

/**
 * Import Employee Schedule
 * @param $internalEmployeeId
 * @param $deputyEmployeeId
 * @param $employeeShifts
 * @param $employeeName
 * @return void
 */
function importEmployeeSchedule($internalEmployeeId,$deputyEmployeeId, $employeeShifts, $employeeName) {
    if(isset($employeeShifts[$internalEmployeeId])) {
        $shift = $employeeShifts[$internalEmployeeId];
        $endpoint = "https://353f6527123646.as.deputy.com/api/v1/supervise/roster";  // Replace with the actual endpoint

        /**
         * Create the request payload to schedule the employee
         */
        $empShift['intStartTimestamp'] = strtotime($shift['start']);
        $empShift['intEndTimestamp'] = strtotime($shift['end']);
        $empShift['intRosterEmployee'] = $deputyEmployeeId;
        $empShift['blnPublish'] = true;
        $empShift['intOpunitId'] = 1;
        $empShift['blnForceOverwrite'] = 0;
        $empShift['strComment'] =  "Roster via API";
        $empShift['blnOpen'] = 0;
        $empShift['intConfirmStatus'] = 1;

        try {

            $response = Client::request('Post', $endpoint, $empShift);

            /**
             * Employee TimeSlot Updated
             */
            if($response->getStatusCode() == 200) {
                echo "$employeeName's schedule added \n <br>";
            }
        } catch (Exception $e) {
            /**
             * If the employee is already working on the given timeslot
             */
            if($e->getMessage() == 'OverlapDetected') {
                echo "$employeeName is currently occupied during the specified time slot\n <br>";
            }
        }

    } else {
        echo "No Schedule found for $employeeName \n <br>";
    }
}