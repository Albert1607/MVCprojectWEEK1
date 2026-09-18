<div class="page-header">
    <div>
        <h1 class="page-title">Clinic Dashboard</h1>
        <p class="page-subtitle">Overview of appointments, doctors on duty, and clinic activity</p>
    </div>
    <div>
        <a href="/appointments/create" class="btn btn-primary">+ Schedule New Appointment</a>
    </div>
</div>

<!-- Key Performance Indicators -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon blue">&#128104;&#8205;&#9877;&#65039;</div>
        <div>
            <div class="stat-value"><?= (int)$stats['total_doctors'] ?></div>
            <div class="stat-label">Available Doctors</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">&#128197;</div>
        <div>
            <div class="stat-value"><?= (int)$stats['scheduled'] ?></div>
            <div class="stat-label">Upcoming Scheduled</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">&#9989;</div>
        <div>
            <div class="stat-value"><?= (int)$stats['completed'] ?></div>
            <div class="stat-label">Completed Visits</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">&#128101;</div>
        <div>
            <div class="stat-value"><?= (int)$stats['total_patients'] ?></div>
            <div class="stat-label">Registered Patients</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <!-- Upcoming Appointments Table -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h2 class="card-title" style="margin-bottom: 0;">Upcoming Appointments</h2>
            <a href="/appointments" class="btn btn-outline" style="font-size: 0.82rem; padding: 4px 10px;">View All</a>
        </div>

        <?php if (empty($upcomingAppointments)): ?>
            <p class="text-muted" style="padding: 20px 0; text-align: center;">No upcoming scheduled appointments found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Doctor & Specialty</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($upcomingAppointments as $apt): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($apt['patient_name']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($apt['patient_phone']) ?></small>
                                </td>
                                <td>
                                    <div><?= htmlspecialchars($apt['doctor_name']) ?></div>
                                    <span class="badge badge-specialty"><?= htmlspecialchars($apt['doctor_specialty']) ?></span>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($apt['appointment_date']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($apt['appointment_time']) ?></small>
                                </td>
                                <td>
                                    <span class="badge badge-scheduled"><?= htmlspecialchars($apt['status']) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Featured Doctors Quick List -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h2 class="card-title" style="margin-bottom: 0;">Doctors on Staff</h2>
            <a href="/doctors" class="btn btn-outline" style="font-size: 0.82rem; padding: 4px 10px;">All</a>
        </div>

        <div style="display: flex; flex-direction: column; gap: 14px;">
            <?php foreach ($doctors as $doc): ?>
                <div style="padding-bottom: 12px; border-bottom: 1px solid var(--border);">
                    <div style="font-weight: 600; color: var(--dark);"><?= htmlspecialchars($doc['name']) ?></div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px;">
                        <span class="badge badge-specialty"><?= htmlspecialchars($doc['specialty']) ?></span>
                        <a href="/appointments/create?doctor_id=<?= (int)$doc['id'] ?>" class="btn btn-outline" style="font-size: 0.78rem; padding: 2px 8px;">Book</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
