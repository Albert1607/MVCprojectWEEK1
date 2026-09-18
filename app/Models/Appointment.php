<?php

namespace App\Models;

use PDO;

class Appointment extends BaseModel
{
    /**
     * Get all appointments with doctor and patient details
     */
    public function getAll(?string $status = null): array
    {
        $sql = "SELECT a.*, 
                       d.name AS doctor_name, d.specialty AS doctor_specialty,
                       p.name AS patient_name, p.email AS patient_email, p.phone AS patient_phone
                FROM appointments a
                JOIN doctors d ON a.doctor_id = d.id
                JOIN patients p ON a.patient_id = p.id ";

        $params = [];
        if ($status && in_array($status, ['Scheduled', 'Completed', 'Cancelled'])) {
            $sql .= "WHERE a.status = ? ";
            $params[] = $status;
        }

        $sql .= "ORDER BY a.appointment_date DESC, a.appointment_time DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get single appointment by ID
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT a.*, 
                       d.name AS doctor_name, d.specialty AS doctor_specialty, d.email AS doctor_email,
                       p.name AS patient_name, p.email AS patient_email, p.phone AS patient_phone
                FROM appointments a
                JOIN doctors d ON a.doctor_id = d.id
                JOIN patients p ON a.patient_id = p.id
                WHERE a.id = ? LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    /**
     * Business Logic: Check if a slot is available for a doctor.
     * Prevents double-booking if an active (non-cancelled) appointment already exists.
     */
    public function isSlotAvailable(int $doctorId, string $date, string $time, ?int $excludeAppointmentId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM appointments 
                WHERE doctor_id = ? 
                  AND appointment_date = ? 
                  AND appointment_time = ? 
                  AND status != 'Cancelled'";
        
        $params = [$doctorId, $date, $time];

        if ($excludeAppointmentId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeAppointmentId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $count = (int) $stmt->fetchColumn();

        return $count === 0;
    }

    /**
     * Create a new appointment
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO appointments (doctor_id, patient_id, appointment_date, appointment_time, status, notes)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['doctor_id'],
            $data['patient_id'],
            $data['appointment_date'],
            $data['appointment_time'],
            $data['status'] ?? 'Scheduled',
            $data['notes'] ?? null
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Update the status of an appointment ('Scheduled', 'Completed', 'Cancelled')
     */
    public function updateStatus(int $id, string $status): bool
    {
        if (!in_array($status, ['Scheduled', 'Completed', 'Cancelled'])) {
            return false;
        }

        $stmt = $this->db->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    /**
     * Retrieve clinic KPI statistics for the dashboard
     */
    public function getStats(): array
    {
        $stats = [
            'total' => (int) $this->db->query("SELECT COUNT(*) FROM appointments")->fetchColumn(),
            'scheduled' => (int) $this->db->query("SELECT COUNT(*) FROM appointments WHERE status = 'Scheduled'")->fetchColumn(),
            'completed' => (int) $this->db->query("SELECT COUNT(*) FROM appointments WHERE status = 'Completed'")->fetchColumn(),
            'cancelled' => (int) $this->db->query("SELECT COUNT(*) FROM appointments WHERE status = 'Cancelled'")->fetchColumn(),
            'today' => (int) $this->db->query("SELECT COUNT(*) FROM appointments WHERE appointment_date = DATE('now') AND status = 'Scheduled'")->fetchColumn()
        ];

        return $stats;
    }

    /**
     * Get upcoming scheduled appointments
     */
    public function getUpcoming(int $limit = 5): array
    {
        $sql = "SELECT a.*, 
                       d.name AS doctor_name, d.specialty AS doctor_specialty,
                       p.name AS patient_name, p.phone AS patient_phone
                FROM appointments a
                JOIN doctors d ON a.doctor_id = d.id
                JOIN patients p ON a.patient_id = p.id
                WHERE a.appointment_date >= DATE('now') AND a.status = 'Scheduled'
                ORDER BY a.appointment_date ASC, a.appointment_time ASC
                LIMIT ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
