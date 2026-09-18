<?php

namespace App\Controllers;

use Core\Request;
use App\Models\Doctor;

class DoctorController extends BaseController
{
    private Doctor $doctorModel;

    public function __construct()
    {
        $this->doctorModel = new Doctor();
    }

    /**
     * Display doctors directory with specialty filtering
     */
    public function index(Request $request): void
    {
        $specialty = $request->input('specialty');
        $doctors = $this->doctorModel->getAll($specialty);
        $specialties = $this->doctorModel->getDistinctSpecialties();

        $this->view('doctors/index', [
            'pageTitle' => 'Our Doctors & Specialists',
            'doctors' => $doctors,
            'specialties' => $specialties,
            'selectedSpecialty' => $specialty
        ]);
    }

    /**
     * View doctor profile, bios, and active schedule
     */
    public function show(Request $request, string|int $id): void
    {
        $doctorId = (int) $id;
        $doctor = $this->doctorModel->getById($doctorId);

        if (!$doctor) {
            $this->setFlash('danger', 'Doctor not found.');
            $this->redirect('/doctors');
        }

        $upcomingAppointments = $this->doctorModel->getUpcomingAppointments($doctorId);

        $this->view('doctors/show', [
            'pageTitle' => $doctor['name'] . ' - Profile',
            'doctor' => $doctor,
            'upcomingAppointments' => $upcomingAppointments
        ]);
    }
}
