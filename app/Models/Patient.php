<?php

namespace App\Models;

class Patient extends BaseModel
{
    /**
     * Find patient by email or create a new patient record
     * Returns the patient ID
     */
    public function findOrCreate(string $name, string $email, string $phone, ?string $dob = null): int
    {
        $existing = $this->getByEmail($email);
        if ($existing) {
            // Optionally update phone if changed
            $update = $this->db->prepare("UPDATE patients SET name = ?, phone = ? WHERE id = ?");
            $update->execute([$name, $phone, $existing['id']]);
            return (int) $existing['id'];
        }

        $stmt = $this->db->prepare("INSERT INTO patients (name, email, phone, date_of_birth) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $dob]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Get patient by email
     */
    public function getByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM patients WHERE LOWER(email) = LOWER(?) LIMIT 1");
        $stmt->execute([trim($email)]);
        $patient = $stmt->fetch();
        return $patient ?: null;
    }

    /**
     * Get patient by ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM patients WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $patient = $stmt->fetch();
        return $patient ?: null;
    }

    /**
     * Count total registered patients
     */
    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM patients")->fetchColumn();
    }
}
