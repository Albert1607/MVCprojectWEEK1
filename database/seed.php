<?php

require_once __DIR__ . '/../app/Config/Database.php';

use App\Config\Database;

echo "--- Initializing Clinic Database ---\n";

try {
    $db = Database::getConnection();

    // 1. Run Schema
    $schemaSql = file_get_contents(__DIR__ . '/schema.sql');
    $db->exec($schemaSql);
    echo "✓ Tables checked / created successfully.\n";

    // 2. Check if doctors already exist
    $doctorCount = $db->query("SELECT COUNT(*) FROM doctors")->fetchColumn();

    if ($doctorCount == 0) {
        $doctors = [
            [
                'name' => 'Dr. Sarah Jenkins, MD',
                'specialty' => 'Cardiology',
                'email' => 'sarah.jenkins@mediclinic.com',
                'phone' => '+1 (555) 234-5678',
                'bio' => 'Board-certified cardiologist with over 12 years of clinical experience specializing in preventive cardiology, hypertension, and heart rhythm disorders.',
                'available_days' => 'Monday, Wednesday, Friday',
                'start_time' => '09:00',
                'end_time' => '16:00'
            ],
            [
                'name' => 'Dr. Marcus Vance, DO',
                'specialty' => 'Dermatology',
                'email' => 'marcus.vance@mediclinic.com',
                'phone' => '+1 (555) 345-6789',
                'bio' => 'Specializes in medical and cosmetic dermatology, skin cancer screenings, eczema treatments, and modern laser therapy.',
                'available_days' => 'Tuesday, Thursday, Saturday',
                'start_time' => '10:00',
                'end_time' => '17:00'
            ],
            [
                'name' => 'Dr. Emily Chen, MD',
                'specialty' => 'Pediatrics',
                'email' => 'emily.chen@mediclinic.com',
                'phone' => '+1 (555) 456-7890',
                'bio' => 'Dedicated pediatrician offering newborn care, childhood immunizations, developmental assessments, and adolescent medicine.',
                'available_days' => 'Monday, Tuesday, Thursday, Friday',
                'start_time' => '08:30',
                'end_time' => '15:30'
            ],
            [
                'name' => 'Dr. Robert Taylor, MD',
                'specialty' => 'General Practice',
                'email' => 'robert.taylor@mediclinic.com',
                'phone' => '+1 (555) 567-8901',
                'bio' => 'Primary care physician focusing on holistic wellness, chronic disease management, annual physical exams, and geriatrics.',
                'available_days' => 'Monday, Tuesday, Wednesday, Thursday, Friday',
                'start_time' => '09:00',
                'end_time' => '17:00'
            ],
            [
                'name' => 'Dr. Lisa Ray, MD',
                'specialty' => 'Neurology',
                'email' => 'lisa.ray@mediclinic.com',
                'phone' => '+1 (555) 678-9012',
                'bio' => 'Expert in neurological evaluations, migraine management, peripheral neuropathies, and sleep medicine.',
                'available_days' => 'Wednesday, Friday',
                'start_time' => '09:00',
                'end_time' => '15:00'
            ]
        ];

        $stmt = $db->prepare("INSERT INTO doctors (name, specialty, email, phone, bio, available_days, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($doctors as $doc) {
            $stmt->execute([
                $doc['name'],
                $doc['specialty'],
                $doc['email'],
                $doc['phone'],
                $doc['bio'],
                $doc['available_days'],
                $doc['start_time'],
                $doc['end_time']
            ]);
        }
        echo "✓ Inserted " . count($doctors) . " doctors.\n";

        // Seed Sample Patients
        $patients = [
            ['name' => 'Alice Walker', 'email' => 'alice.w@example.com', 'phone' => '+1 (555) 888-1234', 'date_of_birth' => '1992-05-14'],
            ['name' => 'David Kim', 'email' => 'david.kim@example.com', 'phone' => '+1 (555) 777-5678', 'date_of_birth' => '1985-11-23']
        ];

        $pStmt = $db->prepare("INSERT INTO patients (name, email, phone, date_of_birth) VALUES (?, ?, ?, ?)");
        foreach ($patients as $p) {
            $pStmt->execute([$p['name'], $p['email'], $p['phone'], $p['date_of_birth']]);
        }
        echo "✓ Inserted sample patients.\n";

        // Seed Sample Appointments
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $dayAfter = date('Y-m-d', strtotime('+2 days'));

        $appointments = [
            [
                'doctor_id' => 1,
                'patient_id' => 1,
                'appointment_date' => $tomorrow,
                'appointment_time' => '10:00',
                'status' => 'Scheduled',
                'notes' => 'Annual cardiovascular routine checkup and blood pressure monitoring.'
            ],
            [
                'doctor_id' => 2,
                'patient_id' => 2,
                'appointment_date' => $dayAfter,
                'appointment_time' => '14:00',
                'status' => 'Scheduled',
                'notes' => 'Skin rash examination on forearms.'
            ]
        ];

        $aStmt = $db->prepare("INSERT INTO appointments (doctor_id, patient_id, appointment_date, appointment_time, status, notes) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($appointments as $a) {
            $aStmt->execute([
                $a['doctor_id'],
                $a['patient_id'],
                $a['appointment_date'],
                $a['appointment_time'],
                $a['status'],
                $a['notes']
            ]);
        }
        echo "✓ Inserted sample appointments.\n";
    } else {
        echo "ℹ Doctors already seeded ($doctorCount records found). Skipping sample seeding.\n";
    }

    echo "--- Database Initialization Complete! ---\n";
} catch (Exception $e) {
    echo "Error during database seeding: " . $e->getMessage() . "\n";
    exit(1);
}
