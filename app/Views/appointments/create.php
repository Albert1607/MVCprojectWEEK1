<div class="page-header">
    <div>
        <a href="/appointments" class="text-muted" style="text-decoration: none; font-size: 0.88rem;">&larr; Back to appointments</a>
        <h1 class="page-title" style="margin-top: 6px;">Schedule a Clinic Visit</h1>
        <p class="page-subtitle">Select a specialist and pick your preferred appointment date & time</p>
    </div>
</div>

<div class="card" style="max-width: 760px; margin: 0 auto;">
    <form method="POST" action="/appointments">
        <h2 class="card-title" style="border-bottom: 1px solid var(--border); padding-bottom: 12px; margin-bottom: 20px;">
            1. Select Doctor & Time
        </h2>

        <div class="form-grid">
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="doctor_id" class="form-label">Consulting Doctor *</label>
                <select id="doctor_id" name="doctor_id" class="form-control" required>
                    <option value="">-- Choose a doctor --</option>
                    <?php foreach ($doctors as $doc): ?>
                        <option value="<?= (int)$doc['id'] ?>" <?= ($selectedDoctorId === (int)$doc['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($doc['name']) ?> &ndash; <?= htmlspecialchars($doc['specialty']) ?> (<?= htmlspecialchars($doc['available_days']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="appointment_date" class="form-label">Appointment Date *</label>
                <input type="date" id="appointment_date" name="appointment_date" class="form-control" min="<?= htmlspecialchars($todayDate) ?>" required value="<?= date('Y-m-d', strtotime('+1 day')) ?>">
            </div>

            <div class="form-group">
                <label for="appointment_time" class="form-label">Appointment Time *</label>
                <select id="appointment_time" name="appointment_time" class="form-control" required>
                    <option value="">-- Choose time slot --</option>
                    <option value="09:00">09:00 AM</option>
                    <option value="09:30">09:30 AM</option>
                    <option value="10:00">10:00 AM</option>
                    <option value="10:30">10:30 AM</option>
                    <option value="11:00">11:00 AM</option>
                    <option value="11:30">11:30 AM</option>
                    <option value="14:00">02:00 PM</option>
                    <option value="14:30">02:30 PM</option>
                    <option value="15:00">03:00 PM</option>
                    <option value="15:30">03:30 PM</option>
                    <option value="16:00">04:00 PM</option>
                </select>
            </div>
        </div>

        <h2 class="card-title" style="border-bottom: 1px solid var(--border); padding-bottom: 12px; margin-top: 20px; margin-bottom: 20px;">
            2. Patient Information
        </h2>

        <div class="form-grid">
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="patient_name" class="form-label">Patient Full Name *</label>
                <input type="text" id="patient_name" name="patient_name" class="form-control" placeholder="e.g. Jane Doe" required>
            </div>

            <div class="form-group">
                <label for="patient_email" class="form-label">Email Address *</label>
                <input type="email" id="patient_email" name="patient_email" class="form-control" placeholder="e.g. jane@example.com" required>
            </div>

            <div class="form-group">
                <label for="patient_phone" class="form-label">Phone Number *</label>
                <input type="tel" id="patient_phone" name="patient_phone" class="form-control" placeholder="e.g. +1 (555) 019-2834" required>
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="notes" class="form-label">Reason for Visit / Symptoms (Optional)</label>
                <textarea id="notes" name="notes" class="form-control" placeholder="Briefly describe the reason for your visit or any existing symptoms..."></textarea>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; border-top: 1px solid var(--border); padding-top: 20px;">
            <a href="/appointments" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">Confirm & Book Appointment</button>
        </div>
    </form>
</div>
