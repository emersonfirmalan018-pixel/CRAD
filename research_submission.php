<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
require_once 'db.php';

$studentProfile = null;
try {
    $profileLookup = $pdo->prepare(
        'SELECT sp.student_id, sp.first_name, sp.middle_name, sp.last_name,
                sp.student_status, sp.academic_program, sp.email
         FROM student_profiles sp
         WHERE sp.user_id = ?
         LIMIT 1'
    );
    $profileLookup->execute([$_SESSION['user_id']]);
    $studentProfile = $profileLookup->fetch(PDO::FETCH_ASSOC) ?: null;
} catch (PDOException $exception) {
    // Keep proposal submission available until the registrar table is installed.
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Submission</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .content{padding:28px 40px}
        .researcher-identity{margin:0 0 18px;padding:14px 16px;border:1px solid #dce8f6;border-radius:8px;background:#f7fbff}
        .identity-heading{display:flex;justify-content:space-between;gap:12px;margin-bottom:12px;color:#17376f;font-size:13px}
        .identity-heading span{color:#13845d;font-size:10px;font-weight:700}
        .identity-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
        .identity-grid small{display:block;color:#7b8798;font-size:10px}
        .identity-grid strong{display:block;margin-top:4px;color:#2e4365;font-size:11px}
        .identity-empty{color:#64748b;font-size:12px;line-height:1.5}
        .btn-primary[disabled]{opacity:.5;cursor:not-allowed}
        @media (max-width:700px){.identity-grid{grid-template-columns:repeat(2,1fr)}}
    </style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main">
    <section class="content">
        <div style="display:flex;justify-content:space-between;align-items:flex-end">
            <div>
                <h2>Submission</h2>
                <p>Submit and monitor research proposals</p>
            </div>
            <div><a href="research_proposals.php">← Back to Overview</a></div>
        </div>

        <div class="card" style="margin-top:12px">
            <div class="card-header"><div><h3>Proposal Submission</h3><span>Research proposal application</span></div><span class="submission-status">Open for Submission</span></div>
            <div class="submission-box" style="margin-top:12px">
                <div class="researcher-identity">
                    <div class="identity-heading"><strong>Researcher Identity</strong><span><?= $studentProfile ? 'Verified by Registrar' : 'Registrar record unavailable' ?></span></div>
                    <?php if ($studentProfile): ?>
                        <div class="identity-grid">
                            <div><small>Student ID</small><strong><?= e($studentProfile['student_id']) ?></strong></div>
                            <div><small>Full Name</small><strong><?= e(trim($studentProfile['first_name'] . ' ' . $studentProfile['middle_name'] . ' ' . $studentProfile['last_name'])) ?></strong></div>
                            <div><small>Academic Program</small><strong><?= e($studentProfile['academic_program'] ?: 'Not specified') ?></strong></div>
                            <div><small>Academic Status</small><strong><?= e($studentProfile['student_status']) ?></strong></div>
                        </div>
                    <?php else: ?>
                        <p class="identity-empty">Complete your Personal Information Database record before submitting a proposal.</p>
                        <a class="btn-primary" href="profile.php">Open Personal Information</a>
                    <?php endif; ?>
                </div>
                <p style="margin-top:0;color:#53657d;font-size:13px;line-height:1.7;">Researchers may submit their proposal documents for evaluation. Make sure that the proposal follows the required research format and evaluation criteria.</p>
                <div class="submission-info">
                    <div class="info-box"><small>Submission Period</small><strong>June 01 – June 30</strong></div>
                    <div class="info-box"><small>Required Document</small><strong>Research Proposal PDF</strong></div>
                    <div class="info-box"><small>Review Duration</small><strong>5–10 Working Days</strong></div>
                    <div class="info-box"><small>Maximum File Size</small><strong>20 MB</strong></div>
                </div>
                <button class="btn-primary" <?= $studentProfile ? '' : 'disabled' ?> onclick="alert('Proposal submission form can be connected here.')">+ Submit New Proposal</button>
            </div>
        </div>
    </section>
</main>

<script>function toggleSidebar(){document.body.classList.toggle('sidebar-hidden')}</script>
</body>
</html>