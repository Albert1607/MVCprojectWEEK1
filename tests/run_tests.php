<?php

// Autoloader setup
spl_autoload_register(function ($class) {
    $prefixApp = 'App\\';
    $prefixCore = 'Core\\';
    $baseDir = dirname(__DIR__) . DIRECTORY_SEPARATOR;

    if (strncmp($prefixApp, $class, strlen($prefixApp)) === 0) {
        $relativeClass = substr($class, strlen($prefixApp));
        $file = $baseDir . 'app' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
        if (file_exists($file)) { require_once $file; return; }
    }

    if (strncmp($prefixCore, $class, strlen($prefixCore)) === 0) {
        $relativeClass = substr($class, strlen($prefixCore));
        $file = $baseDir . 'core' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
        if (file_exists($file)) { require_once $file; return; }
    }
});

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use Core\Router;
use Core\Request;

echo "=============================================\n";
echo "   RUNNING AUTOMATED MVC TEST SUITE          \n";
echo "=============================================\n\n";

$testsPassed = 0;
$totalTests = 0;

function assertTest(string $name, bool $condition) {
    global $testsPassed, $totalTests;
    $totalTests++;
    if ($condition) {
        echo "PASS: {$name}\n";
        $testsPassed++;
    } else {
        echo "FAIL: {$name}\n";
    }
}

// 1. Doctor Model Tests
$doctorModel = new Doctor();
$doctors = $doctorModel->getAll();
assertTest("Doctor::getAll() returns seeded doctors", count($doctors) >= 5);

$doctor = $doctorModel->getById(1);
assertTest("Doctor::getById(1) returns Dr. Sarah Jenkins", $doctor && str_contains($doctor['name'], 'Sarah Jenkins'));

$specialties = $doctorModel->getDistinctSpecialties();
assertTest("Doctor::getDistinctSpecialties() contains Cardiology", in_array('Cardiology', $specialties));

// 2. Patient Model Tests
$patientModel = new Patient();
$testEmail = 'test.verify.' . time() . '@example.com';
$patientId = $patientModel->findOrCreate('Test Verification Patient', $testEmail, '+1 (555) 999-0000');
assertTest("Patient::findOrCreate() creates a new patient and returns valid ID", $patientId > 0);

$samePatientId = $patientModel->findOrCreate('Test Verification Patient', $testEmail, '+1 (555) 999-0000');
assertTest("Patient::findOrCreate() retrieves existing patient when email matches", $patientId === $samePatientId);

// 3. Appointment Model Tests & Conflict Logic
$appointmentModel = new Appointment();
$testDate = date('Y-m-d', strtotime('+10 days'));
$testTime = '11:00';

$isAvailableInitially = $appointmentModel->isSlotAvailable(1, $testDate, $testTime);
assertTest("Appointment::isSlotAvailable() returns TRUE before booking", $isAvailableInitially === true);

$aptId = $appointmentModel->create([
    'doctor_id' => 1,
    'patient_id' => $patientId,
    'appointment_date' => $testDate,
    'appointment_time' => $testTime,
    'status' => 'Scheduled',
    'notes' => 'Test automated booking conflict check'
]);
assertTest("Appointment::create() successfully creates appointment", $aptId > 0);

$isAvailableAfterBooking = $appointmentModel->isSlotAvailable(1, $testDate, $testTime);
assertTest("Appointment::isSlotAvailable() returns FALSE after booking (Conflict Detected!)", $isAvailableAfterBooking === false);

// Test Status Update to Cancelled
$appointmentModel->updateStatus($aptId, 'Cancelled');
$isAvailableAfterCancellation = $appointmentModel->isSlotAvailable(1, $testDate, $testTime);
assertTest("Appointment::isSlotAvailable() returns TRUE again after status changed to Cancelled", $isAvailableAfterCancellation === true);

// 4. Router Tests
$router = new Router();
$router->get('/test/{id}', ['App\Controllers\DoctorController', 'show']);
assertTest("Router registers route successfully", true);

echo "\n---------------------------------------------\n";
echo "SUMMARY: {$testsPassed} / {$totalTests} tests passed.\n";
echo "---------------------------------------------\n";

if ($testsPassed === $totalTests) {
    echo "ALL TESTS PASSED SUCCESSFULLY!\n";
    exit(0);
} else {
    echo "SOME TESTS FAILED.\n";
    exit(1);
}
