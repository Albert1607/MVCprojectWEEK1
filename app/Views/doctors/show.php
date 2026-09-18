<div class="page-header">
    <div>
        <a href="/doctors" class="text-muted" style="text-decoration: none; font-size: 0.88rem;">&larr; Back to all doctors</a>
        <h1 class="page-title" style="margin-top: 6px;"><?= htmlspecialchars($doctor['name']) ?></h1>
        <div style="margin-top: 4px;">
            <span class="badge badge-specialty" style="font-size: 0.88rem;"><?= htmlspecialchars($doctor['specialty']) ?></span>
        </div>
    </div>
    <div>
        <a href="/appointments/create?doctor_id=<?= (int)$doctor['id'] ?>" class="btn btn-primary">Book an Appointment</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- Doctor Summary Card -->
    <div class="card">
        <h2 class="card-title">Practice Details</h2>
        <div class="doctor-info-row">
            <span>&#128197;</span>
            <div>
                <strong>Available Days:</strong><br>
                <span><?= htmlspecialchars($doctor['available_days']) ?></span>
            </div>
        </div>
        <div class="doctor-info-row" style="margin-top: 12px;">
            <span>&#9200;</span>
            <div>
                <strong>Shift Hours:</strong><br>
                <span><?= htmlspecialchars($doctor['start_time']) ?> &ndash; <?= htmlspecialchars($doctor['end_time']) ?></span>
            </div>
        </div>
        <div class="doctor-info-row" style="margin-top: 12px;">
            <span>&#9993;</span>
            <div>
                <strong>Contact Email:</strong><br>
                <span><?= htmlspecialchars($doctor['email']) ?></span>
            </div>
        </div>
        <div class="doctor-info-row" style="margin-top: 12px;">
            <span>&#128222;</span>
            <div>
                <strong>Direct Phone:</strong><br>
                <span><?= htmlspecialchars($doctor['phone']) ?></span>
            </div>
        </div>

        <div style="margin-top: 24px;">
            <a href="/appointments/create?doctor_id=<?= (int)$doctor['id'] ?>" class="btn btn-primary" style="width: 100%;">
                Schedule with Dr. <?= htmlspecialchars(explode(' ', $doctor['name'])[1] ?? 'Doctor') ?>
            </a>
        </div>
    </div>

    <!-- Doctor Bio & Booked Schedule -->
    <div>
        <div class="card">
            <h2 class="card-title">Professional Background</h2>
            <p style="color: var(--text-main); font-size: 0.98rem; line-height: 1.7;">
                <?= nl2br(htmlspecialchars($doctor['bio'] ?? 'Comprehensive care specialist.')) ?>
            </p>
        </div>

        <div class="card">
            <h2 class="card-title">Upcoming Reserved Slots</h2>
            <p class="text-muted" style="font-size: 0.88rem; margin-bottom: 16px;">
                Below are current active bookings for this doctor. New appointments must not conflict with these slots.
            </p>

            <?php if (empty($upcomingAppointments)): ?>
                <p class="text-muted" style="padding: 15px 0;">No active reservations right now. All shift hours are currently open!</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reserved Time</th>
                                <th>Patient</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($upcomingAppointments as $apt): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($apt['appointment_date']) ?></strong></td>
                                    <td><span style="color: var(--primary); font-weight: 600;"><?= htmlspecialchars($apt['appointment_time']) ?></span></td>
                                    <td><?= htmlspecialchars($apt['patient_name']) ?></td>
                                    <td><span class="badge badge-scheduled"><?= htmlspecialchars($apt['status']) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
