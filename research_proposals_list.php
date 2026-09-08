<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
require_once 'db.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Proposals</title>
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
                <h2>Proposals</h2>
                <p>Research proposals currently being managed</p>
            </div>
            <div><a href="research_proposals.php">← Back to Overview</a></div>
        </div>

        <div class="card" style="margin-top:12px">
            <div class="card-header"><div><h3>Research Proposal List</h3><span>Latest proposal records</span></div></div>
            <div class="proposal-list" style="margin-top:12px">
                <!-- replicate original proposal entries briefly -->
                <div class="proposal"><div class="proposal-top"><div class="proposal-left"><div class="proposal-icon">01</div><div><h4 class="proposal-title">Smart Campus Management System</h4><div class="proposal-researcher">Research Team Alpha</div></div></div><span class="status status-approved">APPROVED</span></div><div class="proposal-meta"><span class="meta-tag">Information Technology</span><span class="meta-tag">System Development</span><span class="meta-tag">Reviewer Assigned</span></div><div class="proposal-footer"><div class="progress-wrapper"><div class="progress-label"><span>Research Progress</span><strong>75%</strong></div><div class="progress"><div class="progress-bar" style="width:75%;"></div></div></div><a href="#" class="view-btn">View Details →</a></div></div>
                <div class="proposal"><div class="proposal-top"><div class="proposal-left"><div class="proposal-icon">02</div><div><h4 class="proposal-title">AI-Based Student Performance Monitoring</h4><div class="proposal-researcher">Research Team Beta</div></div></div><span class="status status-review">FOR REVIEW</span></div><div class="proposal-meta"><span class="meta-tag">Artificial Intelligence</span><span class="meta-tag">Education Technology</span><span class="meta-tag">Pending Review</span></div><div class="proposal-footer"><div class="progress-wrapper"><div class="progress-label"><span>Research Progress</span><strong>35%</strong></div><div class="progress"><div class="progress-bar" style="width:35%;"></div></div></div><a href="#" class="view-btn">View Details →</a></div></div>
                <div class="proposal"><div class="proposal-top"><div class="proposal-left"><div class="proposal-icon">03</div><div><h4 class="proposal-title">Digital Records Management Platform</h4><div class="proposal-researcher">Research Team Gamma</div></div></div><span class="status status-ongoing">ONGOING</span></div><div class="proposal-meta"><span class="meta-tag">Database Systems</span><span class="meta-tag">Records Management</span><span class="meta-tag">Adviser Assigned</span></div><div class="proposal-footer"><div class="progress-wrapper"><div class="progress-label"><span>Research Progress</span><strong>58%</strong></div><div class="progress"><div class="progress-bar" style="width:58%;"></div></div></div><a href="#" class="view-btn">View Details →</a></div></div>
            </div>

            <div class="notice" style="margin-top:12px"><strong>Note:</strong> Proposal information displayed above can be connected to your database so that submitted proposals, researchers, reviewers, statuses, and progress are loaded dynamically.</div>
        </div>

    </section>
</main>

<script>function toggleSidebar(){document.body.classList.toggle('sidebar-hidden')}</script>
</body>
</html>