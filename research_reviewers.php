<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
require_once 'db.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reviewers</title>
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
                <h2>Reviewers</h2>
                <p>Assigned research proposal reviewers</p>
            </div>
            <div><a href="research_proposals.php">← Back to Overview</a></div>
        </div>

        <div class="card" style="margin-top:12px">
            <div class="card-header"><div><h3>Research Review Panel</h3><span>Active reviewers</span></div></div>
            <div class="reviewer-list" style="margin-top:12px">
                <div class="reviewer"><div class="reviewer-avatar">DR</div><div class="reviewer-info"><strong>Research Reviewer 01</strong><span>Research Methodology</span></div><div class="reviewer-status">ACTIVE</div></div>
                <div class="reviewer"><div class="reviewer-avatar">RV</div><div class="reviewer-info"><strong>Research Reviewer 02</strong><span>Information Technology</span></div><div class="reviewer-status">ACTIVE</div></div>
                <div class="reviewer"><div class="reviewer-avatar">RP</div><div class="reviewer-info"><strong>Research Reviewer 03</strong><span>Project Evaluation</span></div><div class="reviewer-status">ACTIVE</div></div>
                <div class="reviewer"><div class="reviewer-avatar">QA</div><div class="reviewer-info"><strong>Research Reviewer 04</strong><span>Quality Assurance</span></div><div class="reviewer-status">ACTIVE</div></div>
            </div>
        </div>
    </section>
</main>

<script>function toggleSidebar(){document.body.classList.toggle('sidebar-hidden')}</script>
</body>
</html>