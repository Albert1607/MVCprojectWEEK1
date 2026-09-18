<div class="page-header">
    <div>
        <h1 class="page-title">Appointment Bookings</h1>
        <p class="page-subtitle">Track, filter, and manage patient visits across all doctors</p>
    </div>
    <div>
        <a href="/appointments/create" class="btn btn-primary">+ New Booking</a>
    </div>
</div>

<!-- Status Filter Tabs -->
<div class="filter-tabs">
    <a href="/appointments" class="filter-tab <?= empty($currentFilter) ? 'active' : '' ?>">
        All (<?= (int)$stats['total'] ?>)
    </a>
    <a href="/appointments?status=Scheduled" class="filter-tab <?= ($currentFilter === 'Scheduled') ? 'active' : '' ?>">
        Scheduled (<?= (int)$stats['scheduled'] ?>)
    </a>
    <a href="/appointments?status=Completed" class="filter-tab <?= ($currentFilter === 'Completed') ? 'active' : '' ?>">
        Completed (<?= (int)$stats['completed'] ?>)
    </a>
    <a href="/appointments?status=Cancelled" class="filter-tab <?= ($currentFilter === 'Cancelled') ? 'active' : '' ?>">
        Cancelled (<?= (int)$stats['cancelled'] ?>)
    </a>
</div>

<!-- Appointments Table -->
<div class="card" style="padding: 0; overflow: hidden;">
    <?php if (empty($appointments)): ?>
        <div style="padding: 50px 20px; text-align: center;">
            <p class="text-muted">No appointments found matching this status.</p>
            <a href="/appointments/create" class="btn btn-primary" style="margin-top: 12px;">Create Booking</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date & Time</th>
                        <th>Notes</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $apt): ?>
                        <tr>
                            <td><span class="text-muted">#<?= (int)$apt['id'] ?></span></td>
                            <td>
                                <strong><?= htmlspecialchars($apt['patient_name']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($apt['patient_phone']) ?> &bull; <?= htmlspecialchars($apt['patient_email']) ?></small>
                            </td>
                            <td>
                                <div><strong><?= htmlspecialchars($apt['doctor_name']) ?></strong></div>
                                <span class="badge badge-specialty"><?= htmlspecialchars($apt['doctor_specialty']) ?></span>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($apt['appointment_date']) ?></strong><br>
                                <span style="color: var(--primary); font-weight: 600; font-size: 0.85rem;"><?= htmlspecialchars($apt['appointment_time']) ?></span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= htmlspecialchars($apt['notes'] ? (strlen($apt['notes']) > 40 ? substr($apt['notes'], 0, 40) . '...' : $apt['notes']) : '—') ?>
                                </small>
                            </td>
                            <td>
                                <?php if ($apt['status'] === 'Scheduled'): ?>
                                    <span class="badge badge-scheduled">Scheduled</span>
                                <?php elseif ($apt['status'] === 'Completed'): ?>
                                    <span class="badge badge-completed">Completed</span>
                                <?php else: ?>
                                    <span class="badge badge-cancelled">Cancelled</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <?php if ($apt['status'] === 'Scheduled'): ?>
                                    <div style="display: inline-flex; gap: 6px;">
                                        <form method="POST" action="/appointments/<?= (int)$apt['id'] ?>/complete" style="display:inline;">
                                            <button type="submit" class="btn btn-success-outline" title="Mark as Completed">&#10003; Complete</button>
                                        </form>
                                        <form method="POST" action="/appointments/<?= (int)$apt['id'] ?>/cancel" style="display:inline;" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                            <button type="submit" class="btn btn-danger-outline" title="Cancel Appointment">&#10005; Cancel</button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted" style="font-size: 0.82rem;">No actions</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
