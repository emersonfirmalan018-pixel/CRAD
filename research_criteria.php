<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
require_once 'db.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Criteria & Rubric</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .content{padding:28px 40px}
    </style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main">
    <section class="content">
        <div style="display:flex;justify-content:space-between;align-items:flex-end">
            <div>
                <h2>Criteria & Rubric</h2>
                <p>Evaluation standards for research proposals</p>
            </div>
            <div><a href="research_proposals.php">← Back to Overview</a></div>
        </div>

        <div class="card" style="margin-top:12px">
            <div class="card-header">
                <div>
                    <h3>Proposal Evaluation Rubric</h3>
                    <span>100-point evaluation system</span>
                </div>
            </div>

            <div class="criteria-list">
                <div class="criteria-item"><div class="criteria-left"><div class="criteria-number">01</div><div class="criteria-name">Research Problem & Objectives</div></div><div class="criteria-score">20%</div></div>
                <div class="criteria-item"><div class="criteria-left"><div class="criteria-number">02</div><div class="criteria-name">Review of Related Literature</div></div><div class="criteria-score">15%</div></div>
                <div class="criteria-item"><div class="criteria-left"><div class="criteria-number">03</div><div class="criteria-name">Research Methodology</div></div><div class="criteria-score">25%</div></div>
                <div class="criteria-item"><div class="criteria-left"><div class="criteria-number">04</div><div class="criteria-name">Significance of the Study</div></div><div class="criteria-score">15%</div></div>
                <div class="criteria-item"><div class="criteria-left"><div class="criteria-number">05</div><div class="criteria-name">Feasibility & Innovation</div></div><div class="criteria-score">15%</div></div>
                <div class="criteria-item"><div class="criteria-left"><div class="criteria-number">06</div><div class="criteria-name">Presentation & Documentation</div></div><div class="criteria-score">10%</div></div>
            </div>

            <div class="total-score" style="margin-top:12px"><span>Total Evaluation Score</span><span>100%</span></div>
        </div>
    </section>
</main>

<script>function toggleSidebar(){document.body.classList.toggle('sidebar-hidden')}</script>
</body>
</html>