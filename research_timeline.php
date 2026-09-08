<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
require_once 'db.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Timeline</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="assets/style.css">
    <style>.content{padding:28px 40px}</style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main">
    <section class="content">
        <div style="display:flex;justify-content:space-between;align-items:flex-end">
            <div>
                <h2>Timeline</h2>
                <p>Research proposal workflow and milestones</p>
            </div>
            <div><a href="research_proposals.php">← Back to Overview</a></div>
        </div>

        <div class="card" style="margin-top:12px">
            <div class="timeline">
                <div class="timeline-item completed"><div class="timeline-dot"></div><div class="timeline-date">June 01</div><div class="timeline-title">Proposal Submission Opens</div><div class="timeline-description">Researchers may begin submitting their proposals.</div></div>
                <div class="timeline-item completed"><div class="timeline-dot"></div><div class="timeline-date">June 15</div><div class="timeline-title">Initial Screening</div><div class="timeline-description">Submitted proposals undergo initial checking.</div></div>
                <div class="timeline-item current"><div class="timeline-dot"></div><div class="timeline-date">June 20</div><div class="timeline-title">Panel Review</div><div class="timeline-description">Reviewers evaluate proposals based on the rubric.</div></div>
                <div class="timeline-item"><div class="timeline-dot"></div><div class="timeline-date">June 25</div><div class="timeline-title">Revision Period</div><div class="timeline-description">Researchers respond to comments and requested revisions.</div></div>
                <div class="timeline-item"><div class="timeline-dot"></div><div class="timeline-date">June 30</div><div class="timeline-title">Final Evaluation</div><div class="timeline-description">Final evaluation and approval of proposals.</div></div>
            </div>
        </div>
    </section>
</main>

<script>function toggleSidebar(){document.body.classList.toggle('sidebar-hidden')}</script>
</body>
</html>