<?php

namespace App\Controllers;

use Core\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;

class HomeController extends BaseController
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
     * Dashboard homepage
     */
    public function index(Request $request): void
    {
        $stats = $this->appointmentModel->getStats();
        $stats['total_doctors'] = $this->doctorModel->count();
        $stats['total_patients'] = $this->patientModel->count();

        $upcomingAppointments = $this->appointmentModel->getUpcoming(5);
        $doctors = $this->doctorModel->getAll();

        $this->view('home/index', [
            'pageTitle' => 'Clinic Dashboard',
            'stats' => $stats,
            'upcomingAppointments' => $upcomingAppointments,
            'doctors' => array_slice($doctors, 0, 4) // Show 4 featured doctors
        ]);
    }
}
