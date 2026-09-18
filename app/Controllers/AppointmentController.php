<?php

namespace App\Controllers;

use Core\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;

class AppointmentController extends BaseController
{
    private Doctor $doctorModel;
    private Patient $patientModel;
    private Appointment $appointmentModel;

    public function __construct()
    {
        $this->doctorModel = new Doctor();
        $this->patientModel = new Patient();
        $this->appointmentModel = new Appointment();
    }

    /**
     * List all appointments with status filtering
     */
    public function index(Request $request): void
    {
        $status = $request->input('status');
        $appointments = $this->appointmentModel->getAll($status);
        $stats = $this->appointmentModel->getStats();

        $this->view('appointments/index', [
            'pageTitle' => 'Appointment Bookings',
            'appointments' => $appointments,
            'stats' => $stats,
            'currentFilter' => $status
        ]);
    }

    /**
     * Show appointment booking form
     */
    public function create(Request $request): void
    {
        $selectedDoctorId = (int) $request->input('doctor_id', 0);
        $doctors = $this->doctorModel->getAll();

        $this->view('appointments/create', [
            'pageTitle' => 'Book an Appointment',
            'doctors' => $doctors,
            'selectedDoctorId' => $selectedDoctorId,
            'todayDate' => date('Y-m-d')
        ]);
    }

    /**
     * Handle submission of booking form (Validations + Business Rules)
     */
    public function store(Request $request): void
    {
        $doctorId = (int) $request->input('doctor_id');
        $date = trim($request->input('appointment_date', ''));
        $time = trim($request->input('appointment_time', ''));
        $patientName = trim($request->input('patient_name', ''));
        $patientEmail = trim($request->input('patient_email', ''));
        $patientPhone = trim($request->input('patient_phone', ''));
        $notes = trim($request->input('notes', ''));

        // Basic Validation
        if (!$doctorId || empty($date) || empty($time) || empty($patientName) || empty($patientEmail) || empty($patientPhone)) {
            $this->setFlash('danger', 'Please fill in all required fields.');
            $this->redirect('/appointments/create' . ($doctorId ? '?doctor_id=' . $doctorId : ''));
        }

        if (!filter_var($patientEmail, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('danger', 'Please enter a valid email address.');
            $this->redirect('/appointments/create?doctor_id=' . $doctorId);
        }

        // Validate date: cannot be in the past
        if ($date < date('Y-m-d')) {
            $this->setFlash('danger', 'Appointment date cannot be in the past.');
            $this->redirect('/appointments/create?doctor_id=' . $doctorId);
        }

        // Check Doctor Existence
        $doctor = $this->doctorModel->getById($doctorId);
        if (!$doctor) {
            $this->setFlash('danger', 'Selected doctor not found.');
            $this->redirect('/appointments/create');
        }

        // Business Rule: Check Slot Availability (prevent double-booking)
        $isAvailable = $this->appointmentModel->isSlotAvailable($doctorId, $date, $time);
        if (!$isAvailable) {
            $this->setFlash('danger', "Sorry, Dr. {$doctor['name']} already has an active appointment on {$date} at {$time}. Please pick a different time slot.");
            $this->redirect('/appointments/create?doctor_id=' . $doctorId);
        }

        // Save or retrieve patient
        $patientId = $this->patientModel->findOrCreate($patientName, $patientEmail, $patientPhone);

        // Create Appointment
        $this->appointmentModel->create([
            'doctor_id' => $doctorId,
            'patient_id' => $patientId,
            'appointment_date' => $date,
            'appointment_time' => $time,
            'status' => 'Scheduled',
            'notes' => $notes
        ]);

        $this->setFlash('success', "Appointment with {$doctor['name']} on {$date} at {$time} has been successfully booked!");
        $this->redirect('/appointments');
    }

    /**
     * Mark an appointment as Cancelled
     */
    public function cancel(Request $request, string|int $id): void
    {
        $appointmentId = (int) $id;
        $appointment = $this->appointmentModel->getById($appointmentId);

        if (!$appointment) {
            $this->setFlash('danger', 'Appointment not found.');
            $this->redirect('/appointments');
        }

        $this->appointmentModel->updateStatus($appointmentId, 'Cancelled');
        $this->setFlash('warning', "Appointment #{$appointmentId} has been cancelled.");
        $this->redirect('/appointments');
    }

    /**
     * Mark an appointment as Completed
     */
    public function complete(Request $request, string|int $id): void
    {
        $appointmentId = (int) $id;
        $appointment = $this->appointmentModel->getById($appointmentId);

        if (!$appointment) {
            $this->setFlash('danger', 'Appointment not found.');
            $this->redirect('/appointments');
        }

        $this->appointmentModel->updateStatus($appointmentId, 'Completed');
        $this->setFlash('success', "Appointment #{$appointmentId} marked as completed.");
        $this->redirect('/appointments');
    }
}
