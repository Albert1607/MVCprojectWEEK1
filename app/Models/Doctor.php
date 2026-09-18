<?php

namespace App\Models;

use PDO;

class Doctor extends BaseModel
{
    /**
     * Get all doctors, optionally filtered by specialty
     */
    public function getAll(?string $specialty = null): array
    {
        if ($specialty && trim($specialty) !== '') {
            $stmt = $this->db->prepare("SELECT * FROM doctors WHERE specialty = ? ORDER BY name ASC");
            $stmt->execute([trim($specialty)]);
            return $stmt->fetchAll();
        }

        $stmt = $this->db->query("SELECT * FROM doctors ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    /**
     * Get single doctor by ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM doctors WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $doctor = $stmt->fetch();
        return $doctor ?: null;
    }

    /**
     * Get list of unique specialties available in the clinic
     */
    public function getDistinctSpecialties(): array
    {
        $stmt = $this->db->query("SELECT DISTINCT specialty FROM doctors ORDER BY specialty ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Get doctor's upcoming appointments
     */
    public function getUpcomingAppointments(int $doctorId): array
    {
        $sql = "SELECT a.*, p.name AS patient_name 
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                WHERE a.doctor_id = ? 
                  AND a.appointment_date >= DATE('now') 
                  AND a.status = 'Scheduled'
                ORDER BY a.appointment_date ASC, a.appointment_time ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$doctorId]);
        return $stmt->fetchAll();
    }

    /**
     * Count total registered doctors
     */
    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM doctors")->fetchColumn();
    }
}
