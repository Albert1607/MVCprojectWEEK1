<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'MediCare Clinic Portal') ?> | Pure PHP MVC</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <a href="/" class="brand">
                <span class="logo-icon">&#10010;</span>
                <span>MediCare Clinic</span>
            </a>
            <ul class="nav-links">
                <li><a href="/" class="nav-link <?= ($_SERVER['REQUEST_URI'] ?? '/') === '/' ? 'active' : '' ?>">Dashboard</a></li>
                <li><a href="/doctors" class="nav-link <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/doctors') === 0 ? 'active' : '' ?>">Doctors</a></li>
                <li><a href="/appointments" class="nav-link <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/appointments') === 0 && strpos($_SERVER['REQUEST_URI'] ?? '', '/create') === false ? 'active' : '' ?>">Appointments</a></li>
                <li><a href="/appointments/create" class="btn btn-primary" style="font-size:0.85rem; padding: 6px 14px;">+ Book Appointment</a></li>
            </ul>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <?php if (!empty($flash)): ?>
                <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
                    <span>
                        <?php if ($flash['type'] === 'success'): ?>&#10003;
                        <?php elseif ($flash['type'] === 'danger'): ?>&#9888;
                        <?php else: ?>&#9432;
                        <?php endif; ?>
                    </span>
                    <span><?= htmlspecialchars($flash['message']) ?></span>
                </div>
            <?php endif; ?>
