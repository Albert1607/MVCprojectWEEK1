<div class="page-header">
    <div>
        <h1 class="page-title">Clinic Doctors & Specialists</h1>
        <p class="page-subtitle">Meet our certified healthcare providers and browse their schedules</p>
    </div>
    <div>
        <a href="/appointments/create" class="btn btn-primary">+ Book Appointment</a>
    </div>
</div>

<!-- Specialty Filter Tabs -->
<div class="filter-tabs">
    <a href="/doctors" class="filter-tab <?= empty($selectedSpecialty) ? 'active' : '' ?>">All Specialties</a>
    <?php foreach ($specialties as $sp): ?>
        <a href="/doctors?specialty=<?= urlencode($sp) ?>" 
           class="filter-tab <?= ($selectedSpecialty === $sp) ? 'active' : '' ?>">
            <?= htmlspecialchars($sp) ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- Doctors Card Grid -->
<div class="doctors-grid">
    <?php if (empty($doctors)): ?>
        <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
            <p class="text-muted">No doctors found matching the selected specialty.</p>
            <a href="/doctors" class="btn btn-outline" style="margin-top: 10px;">Clear Filter</a>
        </div>
    <?php else: ?>
        <?php foreach ($doctors as $doc): ?>
            <div class="doctor-card">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 8px;">
                        <h2 class="doctor-name"><?= htmlspecialchars($doc['name']) ?></h2>
                        <span class="badge badge-specialty"><?= htmlspecialchars($doc['specialty']) ?></span>
                    </div>

                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 16px;">
                        <?= htmlspecialchars($doc['bio'] ?? 'Dedicated practitioner providing comprehensive patient care.') ?>
                    </p>

                    <div class="doctor-info-row">
                        <span>&#128197;</span>
                        <span><strong>Days:</strong> <?= htmlspecialchars($doc['available_days']) ?></span>
                    </div>
                    <div class="doctor-info-row">
                        <span>&#9200;</span>
                        <span><strong>Hours:</strong> <?= htmlspecialchars($doc['start_time']) ?> - <?= htmlspecialchars($doc['end_time']) ?></span>
                    </div>
                    <div class="doctor-info-row">
                        <span>&#9993;</span>
                        <span><?= htmlspecialchars($doc['email']) ?></span>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 20px; border-top: 1px solid var(--border); padding-top: 16px;">
                    <a href="/doctors/<?= (int)$doc['id'] ?>" class="btn btn-outline" style="flex: 1;">Profile</a>
                    <a href="/appointments/create?doctor_id=<?= (int)$doc['id'] ?>" class="btn btn-primary" style="flex: 1;">Book Now</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
