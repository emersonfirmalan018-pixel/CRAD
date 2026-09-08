<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'db.php';
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Research Analytics & Reporting</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="logo.png">
<link rel="stylesheet" href="assets/style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
* {
    box-sizing: border-box;
}

html,
body {
    margin: 0;
    padding: 0;
    width: 100%;
}

body {
    background: #eef2f5;
    color: #1e293b;
    font-family: Arial, Helvetica, sans-serif;
    overflow-x: hidden;
}

.main {
    margin-left: 260px;
    min-height: 100vh;
    background: #eef2f5;
    transition: all .3s ease;
}

.content {
    padding: 0 24px 20px;
}

/* TOP BAR */
.analytics-topbar {
    height: 48px;
    background: rgba(255,255,255,.88);
    border-bottom: 1px solid #dfe5eb;
    display: flex;
    align-items: center;
    padding: 0 18px;
    position: sticky;
    top: 0;
    z-index: 100;
}

.menu-button {
    width: 32px;
    height: 32px;
    border: none;
    background: transparent;
    color: #4b5563;
    font-size: 21px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.topbar-spacer {
    flex: 1;
}

.notification-icon {
    position: relative;
    font-size: 19px;
    color: #344054;
    margin-right: 20px;
    cursor: pointer;
}

.notification-count {
    position: absolute;
    top: -6px;
    right: -8px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: #c83c45;
    color: white;
    font-size: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.admin-profile {
    display: flex;
    align-items: center;
    gap: 8px;
}

.admin-profile img {
    width: 29px;
    height: 29px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #d7dce2;
}

.admin-info {
    line-height: 1.1;
}

.admin-name {
    font-size: 11px;
    font-weight: 800;
    color: #273142;
}

.admin-role {
    font-size: 8px;
    color: #7b8492;
    margin-top: 2px;
}

.admin-arrow {
    font-size: 12px;
    color: #6b7280;
}

/* PAGE HEADER */
.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 12px 0 10px;
}

.page-heading h1 {
    margin: 0;
    font-size: 21px;
    color: #101828;
    font-weight: 800;
    letter-spacing: -.3px;
}

.page-heading p {
    margin: 3px 0 0;
    font-size: 10px;
    color: #5f6978;
}

.semester-select {
    height: 32px;
    min-width: 170px;
    padding: 0 10px;
    border: 1px solid #d5dbe2;
    border-radius: 5px;
    background: white;
    color: #333d4d;
    font-size: 10px;
    outline: none;
    cursor: pointer;
}

/* TOP DASHBOARD */
.top-dashboard {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 14px;
    margin-bottom: 14px;
}

/* WELCOME */
.welcome-banner {
    min-height: 150px;
    border-radius: 7px;
    padding: 20px;
    position: relative;
    overflow: hidden;
    background: linear-gradient(
        110deg,
        #123558 0%,
        #15506d 55%,
        #16717a 100%
    );
    box-shadow: 0 3px 10px rgba(15,43,65,.12);
}

.welcome-content {
    position: relative;
    z-index: 3;
    width: 54%;
}

.welcome-title {
    margin: 0;
    color: white;
    font-size: 25px;
    font-weight: 800;
}

.welcome-text {
    margin: 5px 0 0;
    color: rgba(255,255,255,.9);
    font-size: 12px;
}

.semester-message {
    font-size: 9px;
    color: rgba(255,255,255,.72);
    margin-top: 7px;
}

.learn-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-top: 17px;
    padding: 8px 17px;
    background: #e0bd63;
    color: #17263b;
    border-radius: 5px;
    font-size: 10px;
    font-weight: 800;
    text-decoration: none;
    box-shadow: 0 2px 4px rgba(0,0,0,.12);
}

/* NETWORK */
.network-decoration {
    position: absolute;
    right: 0;
    top: 0;
    width: 48%;
    height: 100%;
    opacity: .9;
}

.network-line {
    position: absolute;
    height: 1px;
    background: rgba(255,255,255,.35);
    transform-origin: left center;
}

.network-dot {
    position: absolute;
    width: 17px;
    height: 17px;
    border-radius: 50%;
    background: #7657aa;
    border: 3px solid rgba(255,255,255,.75);
}

.network-document {
    position: absolute;
    width: 35px;
    height: 43px;
    background: white;
    border-radius: 2px;
    transform: rotate(12deg);
    box-shadow: 0 3px 8px rgba(0,0,0,.15);
    opacity: .9;
}

.network-document:before {
    content: "";
    position: absolute;
    left: 7px;
    right: 7px;
    top: 12px;
    height: 2px;
    background: #b9c4cf;
    box-shadow:
        0 6px #b9c4cf,
        0 12px #b9c4cf;
}

/* HEXAGON STATISTICS */
.hex-stat-area {
    min-height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    position: relative;
    overflow: hidden;
    padding: 5px;
}

.hex-stat {
    width: 82px;
    height: 74px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    font-weight: 800;
    font-size: 13px;
    clip-path: polygon(
        25% 0%,
        75% 0%,
        100% 50%,
        75% 100%,
        25% 100%,
        0% 50%
    );
    flex-shrink: 0;
}

.hex-stat-inner {
    line-height: 1.05;
}

.hex-stat-number {
    font-size: 20px;
    margin-top: 3px;
}

.hex-stat-label {
    font-size: 8px;
    font-weight: 700;
    opacity: .95;
}

.hex-total {
    background: #17274c;
}

.hex-approved {
    background: #4eaa98;
}

.hex-ongoing {
    background: #19737b;
}

.hex-completed {
    background: #c29b43;
}

.hex-published {
    background: #23817f;
}

/* CARDS */
.analytics-card {
    background: #ffffff;
    border: 1px solid #dfe5ea;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(31,41,55,.06);
    overflow: hidden;
}

.card-header {
    padding: 11px 13px 6px;
}

.card-title {
    margin: 0;
    font-size: 13px;
    font-weight: 800;
    color: #1f2937;
}

/* MIDDLE */
.middle-grid {
    display: grid;
    grid-template-columns: 1fr 1.25fr;
    gap: 14px;
    margin-bottom: 14px;
}

.status-card,
.trend-card {
    min-height: 190px;
}

/* STATUS */
.status-flow {
    padding: 23px 14px 5px;
}

.status-flow-row {
    display: flex;
    align-items: center;
    width: 100%;
    overflow: hidden;
}

.status-step {
    height: 35px;
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
    font-weight: 800;
    margin-right: -1px;
    clip-path: polygon(
        0 0,
        88% 0,
        100% 50%,
        88% 100%,
        0 100%,
        12% 50%
    );
}

.status-step:nth-child(1) {
    background: #7952ad;
}

.status-step:nth-child(2) {
    background: #3d83b6;
}

.status-step:nth-child(3) {
    background: #3d8b8a;
}

.status-step:nth-child(4) {
    background: #d5a442;
}

.status-step:nth-child(5) {
    background: #945187;
}

.status-count {
    position: absolute;
    top: -29px;
    left: 50%;
    transform: translateX(-50%);
    padding: 5px 8px;
    border-radius: 4px;
    color: white;
    font-size: 10px;
    font-weight: 800;
}

.status-step:nth-child(1) .status-count {
    background: #7651a7;
}

.status-step:nth-child(2) .status-count {
    background: #397db0;
}

.status-step:nth-child(3) .status-count {
    background: #3b8887;
}

.status-step:nth-child(4) .status-count {
    background: #c99d3f;
}

.status-step:nth-child(5) .status-count {
    background: #955180;
}

.status-labels {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 4px;
    margin-top: 19px;
}

.status-label {
    font-size: 9px;
    color: #4c5563;
    text-align: center;
    white-space: nowrap;
}

.status-label:before {
    content: "";
    width: 8px;
    height: 8px;
    display: inline-block;
    border-radius: 50%;
    margin-right: 4px;
    background: #7952ad;
}

.status-label:nth-child(2):before {
    background: #3d83b6;
}

.status-label:nth-child(3):before {
    background: #3d8b8a;
}

.status-label:nth-child(4):before {
    background: #d5a442;
}

.status-label:nth-child(5):before {
    background: #945187;
}

/* CHART */
.trend-chart {
    height: 142px;
    padding: 2px 13px 10px;
}

/* BOTTOM */
.bottom-grid {
    display: grid;
    grid-template-columns: .92fr 1.18fr;
    gap: 14px;
}

/* CUSTOM REPORT */
.custom-report {
    min-height: 242px;
    background: linear-gradient(
        110deg,
        #143b60,
        #166576
    );
    border-radius: 8px;
    padding: 15px 17px;
    color: white;
    box-shadow: 0 3px 10px rgba(15,43,65,.12);
}

.custom-report-title {
    font-size: 14px;
    font-weight: 800;
    margin-bottom: 11px;
}

.report-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    column-gap: 14px;
    row-gap: 9px;
}

.form-group label {
    display: block;
    font-size: 9px;
    font-weight: 600;
    margin-bottom: 5px;
}

.report-select,
.report-date {
    width: 100%;
    height: 29px;
    padding: 0 9px;
    background: white;
    border: 1px solid #d6dde5;
    border-radius: 5px;
    color: #3a4654;
    font-size: 9px;
    outline: none;
}

.generate-button {
    grid-column: 1 / -1;
    height: 32px;
    margin-top: 4px;
    border: none;
    border-radius: 5px;
    background: #dfbd62;
    color: #17263a;
    font-size: 10px;
    font-weight: 800;
    cursor: pointer;
}

.generate-button:hover {
    background: #e9cb7b;
}

/* QUICK REPORTS */
.quick-card {
    min-height: 242px;
}

.quick-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 11px 13px 8px;
}

.quick-header .card-title {
    font-size: 14px;
}

.view-all {
    color: #667085;
    font-size: 9px;
    text-decoration: underline;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.report-table th {
    height: 30px;
    padding: 0 8px;
    background: #f5f7f9;
    color: #364152;
    font-size: 8px;
    font-weight: 800;
    text-align: left;
    border-top: 1px solid #e5e9ee;
    border-bottom: 1px solid #e5e9ee;
}

.report-table td {
    height: 29px;
    padding: 0 8px;
    color: #46515f;
    font-size: 8px;
    border-bottom: 1px solid #edf0f3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.report-table tr:last-child td {
    border-bottom: none;
}

.report-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 15px;
    height: 15px;
    margin-right: 5px;
    border-radius: 2px;
    background: #edf1f5;
    color: #54748f;
    font-size: 8px;
}

.report-action {
    display: flex;
    gap: 4px;
    justify-content: center;
}

.report-action button {
    width: 27px;
    height: 21px;
    border: 1px solid #d8e1e9;
    border-radius: 3px;
    background: #edf7fa;
    color: #286e7e;
    font-size: 10px;
    cursor: pointer;
}

/* SIDEBAR */
.sidebar-hidden .sidebar {
    transform: translateX(-100%);
}

.sidebar-hidden .main {
    margin-left: 0;
}

/* RESPONSIVE */
@media (max-width: 1100px) {
    .main {
        margin-left: 230px;
    }

    .top-dashboard,
    .middle-grid,
    .bottom-grid {
        grid-template-columns: 1fr;
    }

    .hex-stat-area {
        min-height: 125px;
    }
}

@media (max-width: 800px) {
    .main {
        margin-left: 0;
    }

    .content {
        padding: 0 12px 20px;
    }

    .page-header {
        flex-direction: column;
        gap: 10px;
    }

    .semester-select {
        width: 100%;
    }

    .welcome-content {
        width: 100%;
    }

    .network-decoration {
        opacity: .2;
    }

    .hex-stat-area {
        flex-wrap: wrap;
    }

    .hex-stat {
        width: 72px;
        height: 66px;
    }

    .report-form {
        grid-template-columns: 1fr;
    }

    .generate-button {
        grid-column: 1;
    }

    .quick-card {
        overflow-x: auto;
    }

    .report-table {
        min-width: 550px;
    }
}

@media (max-width: 500px) {
    .admin-info,
    .admin-arrow {
        display: none;
    }

    .page-heading h1 {
        font-size: 18px;
    }

    .welcome-title {
        font-size: 22px;
    }

    .status-flow-row {
        overflow-x: auto;
        padding-bottom: 5px;
    }

    .status-step {
        min-width: 90px;
    }
}
</style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main">

    <!-- TOP BAR -->
    <div class="analytics-topbar">

        <button
            class="menu-button"
            onclick="toggleSidebar()"
            title="Toggle Sidebar">
            ☰
        </button>

        <div class="topbar-spacer"></div>

        <div class="notification-icon">
            ♧
            <span class="notification-count">3</span>
        </div>

        <div class="admin-profile">

            <img src="logo.png" alt="Admin">

            <div class="admin-info">
                <div class="admin-name">CRAD Admin</div>
                <div class="admin-role">Administrator</div>
            </div>

            <span class="admin-arrow">⌄</span>

        </div>

    </div>

    <section class="content">

        <!-- PAGE HEADER -->
        <div class="page-header">

            <div class="page-heading">

                <h1>
                    Research Analytics & Reporting
                </h1>

                <p>
                    Overview of research activities, performance, and reports.
                </p>

            </div>

            <select
                class="semester-select"
                id="semesterSelect"
                onchange="changeSemester()">

                <option value="first">
                    First Semester
                </option>

                <option value="second">
                    Second Semester
                </option>

            </select>

        </div>

        <!-- TOP DASHBOARD -->
        <div class="top-dashboard">

            <!-- WELCOME -->
            <div class="welcome-banner">

                <div class="welcome-content">

                    <h2 class="welcome-title">
                        Welcome!
                    </h2>

                    <p class="welcome-text" id="welcomeText">
                        Your First Semester research pulse is strong.
                    </p>

                    <div class="semester-message" id="semesterMessage">
                        Research analytics for the First Semester.
                    </div>

                    <a href="#" class="learn-button">
                        Learn More
                    </a>

                </div>

                <!-- DECORATION -->
                <div class="network-decoration">

                    <div class="network-line"
                         style="width:120px;top:55px;left:40px;transform:rotate(-18deg);"></div>

                    <div class="network-line"
                         style="width:100px;top:85px;left:85px;transform:rotate(25deg);"></div>

                    <div class="network-line"
                         style="width:90px;top:42px;left:125px;transform:rotate(65deg);"></div>

                    <div class="network-line"
                         style="width:110px;top:105px;left:125px;transform:rotate(-28deg);"></div>

                    <div class="network-line"
                         style="width:80px;top:70px;left:170px;transform:rotate(25deg);"></div>

                    <div class="network-dot"
                         style="top:43px;left:37px;"></div>

                    <div class="network-dot"
                         style="top:77px;left:84px;"></div>

                    <div class="network-dot"
                         style="top:25px;left:130px;"></div>

                    <div class="network-dot"
                         style="top:95px;left:126px;"></div>

                    <div class="network-dot"
                         style="top:65px;left:177px;"></div>

                    <div class="network-document"
                         style="top:50px;left:72px;"></div>

                    <div class="network-document"
                         style="top:92px;left:165px;"></div>

                    <div class="network-document"
                         style="top:20px;left:205px;"></div>

                </div>

            </div>

            <!-- HEX STATISTICS -->
            <div class="hex-stat-area">

                <div class="hex-stat hex-total">
                    <div class="hex-stat-inner">
                        <div class="hex-stat-label">Total</div>
                        <div class="hex-stat-label">Proposals</div>
                        <div class="hex-stat-number" id="totalProposals">128</div>
                    </div>
                </div>

                <div class="hex-stat hex-approved">
                    <div class="hex-stat-inner">
                        <div class="hex-stat-label">Approved</div>
                        <div class="hex-stat-label">Proposals</div>
                        <div class="hex-stat-number" id="approvedProposals">84</div>
                    </div>
                </div>

                <div class="hex-stat hex-ongoing">
                    <div class="hex-stat-inner">
                        <div class="hex-stat-label">Ongoing</div>
                        <div class="hex-stat-label">Researches</div>
                        <div class="hex-stat-number" id="ongoingResearches">63</div>
                    </div>
                </div>

                <div class="hex-stat hex-completed">
                    <div class="hex-stat-inner">
                        <div class="hex-stat-label">Completed</div>
                        <div class="hex-stat-label">Researches</div>
                        <div class="hex-stat-number" id="completedResearches">41</div>
                    </div>
                </div>

                <div class="hex-stat hex-published">
                    <div class="hex-stat-inner">
                        <div class="hex-stat-label">Published</div>
                        <div class="hex-stat-label">Researches</div>
                        <div class="hex-stat-number" id="publishedResearches">22</div>
                    </div>
                </div>

            </div>

        </div>

        <!-- MIDDLE SECTION -->
        <div class="middle-grid">

            <!-- STATUS -->
            <div class="analytics-card status-card">

                <div class="card-header">
                    <h3 class="card-title">
                        Research Status Overview
                    </h3>
                </div>

                <div class="status-flow">

                    <div class="status-flow-row">

                        <div class="status-step">
                            <span class="status-count" id="reviewCount">31</span>
                        </div>

                        <div class="status-step">
                            <span class="status-count" id="approvedCount">25</span>
                        </div>

                        <div class="status-step">
                            <span class="status-count" id="ongoingCount">42</span>
                        </div>

                        <div class="status-step">
                            <span class="status-count" id="completedCount">25</span>
                        </div>

                        <div class="status-step">
                            <span class="status-count" id="disapprovedCount">10</span>
                        </div>

                    </div>

                    <div class="status-labels">

                        <div class="status-label">
                            For Review
                        </div>

                        <div class="status-label">
                            Approved
                        </div>

                        <div class="status-label">
                            Ongoing
                        </div>

                        <div class="status-label">
                            Completed
                        </div>

                        <div class="status-label">
                            Disapproved
                        </div>

                    </div>

                </div>

            </div>

            <!-- MONTHLY TREND -->
            <div class="analytics-card trend-card">

                <div class="card-header">
                    <h3 class="card-title">
                        Monthly Proposal Trend
                    </h3>
                </div>

                <div class="trend-chart">
                    <canvas id="proposalTrend"></canvas>
                </div>

            </div>

        </div>

        <!-- BOTTOM SECTION -->
        <div class="bottom-grid">

            <!-- CUSTOM REPORT -->
            <div class="custom-report">

                <div class="custom-report-title">
                    Generate Custom Report
                </div>

                <div class="report-form">

                    <div class="form-group">

                        <label>
                            Report Type
                        </label>

                        <select class="report-select">

                            <option>
                                Select Type
                            </option>

                            <option>
                                Research Summary Report
                            </option>

                            <option>
                                Department Research Performance
                            </option>

                            <option>
                                Funding Utilization Report
                            </option>

                            <option>
                                Publication and Citation Report
                            </option>

                        </select>

                    </div>

                    <div class="form-group">

                        <label>
                            Date Range
                        </label>

                        <input
                            type="text"
                            class="report-date"
                            id="reportDate"
                            value="August 1, 2026 - December 31, 2026"
                            readonly>

                    </div>

                    <div class="form-group">

                        <label>
                            Department
                        </label>

                        <select class="report-select">

                            <option>
                                All Department
                            </option>

                            <option>
                                BSIT
                            </option>

                            <option>
                                BSIS
                            </option>

                            <option>
                                BSCS
                            </option>

                        </select>

                    </div>

                    <div></div>

                    <button class="generate-button">
                        Generate Report
                    </button>

                </div>

            </div>

            <!-- QUICK REPORTS -->
            <div class="analytics-card quick-card">

                <div class="quick-header">

                    <h3 class="card-title">
                        Quick Reports & Insights
                    </h3>

                    <a href="#" class="view-all">
                        View All
                    </a>

                </div>

                <table class="report-table">

                    <thead>

                        <tr>

                            <th style="width:43%">
                                Report Title
                            </th>

                            <th style="width:21%">
                                Report Type
                            </th>

                            <th style="width:22%">
                                Date Generated
                            </th>

                            <th style="width:14%">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody id="reportTableBody">

                    </tbody>

                </table>

            </div>

        </div>

    </section>

</main>

<script>

/* =========================================================
   SEMESTER DATA
   ========================================================= */

const semesterData = {

    first: {

        name: "First Semester",

        message:
            "Your First Semester research pulse is strong.",

        description:
            "Research analytics for the First Semester.",

        stats: {
            total: 128,
            approved: 84,
            ongoing: 63,
            completed: 41,
            published: 22
        },

        status: {
            review: 31,
            approved: 25,
            ongoing: 42,
            completed: 25,
            disapproved: 10
        },

        chart: {
            submitted: [15, 20, 36, 25, 30, 28],
            approved: [12, 18, 31, 23, 27, 26],
            completed: [7, 10, 18, 14, 19, 17]
        },

        dates: [
            "August 1, 2026 - December 31, 2026",
            "August 1, 2026 - December 31, 2026",
            "August 1, 2026 - December 31, 2026",
            "August 1, 2026 - December 31, 2026",
            "August 1, 2026 - December 31, 2026",
            "August 1, 2026 - December 31, 2026"
        ],

        reports: [
            [
                "Research Summary Report",
                "Summary",
                "December 20, 2026"
            ],
            [
                "Department Research Performance",
                "Performance",
                "December 18, 2026"
            ],
            [
                "Funding Utilization Report",
                "Financial",
                "December 15, 2026"
            ],
            [
                "Publication and Citation Report",
                "Publication",
                "December 12, 2026"
            ],
            [
                "Research Progress Report",
                "Monitoring",
                "December 10, 2026"
            ],
            [
                "Monthly Research Monitoring",
                "Monitoring",
                "December 5, 2026"
            ]
        ]

    },

    second: {

        name: "Second Semester",

        message:
            "Your Second Semester research pulse is strong.",

        description:
            "Research analytics for the Second Semester.",

        stats: {
            total: 156,
            approved: 102,
            ongoing: 71,
            completed: 48,
            published: 29
        },

        status: {
            review: 36,
            approved: 32,
            ongoing: 47,
            completed: 31,
            disapproved: 12
        },

        chart: {
            submitted: [18, 24, 31, 34, 39, 42],
            approved: [14, 20, 27, 29, 34, 37],
            completed: [8, 13, 17, 21, 25, 30]
        },

        dates: [
            "January 1, 2027 - May 31, 2027",
            "January 1, 2027 - May 31, 2027",
            "January 1, 2027 - May 31, 2027",
            "January 1, 2027 - May 31, 2027",
            "January 1, 2027 - May 31, 2027",
            "January 1, 2027 - May 31, 2027"
        ],

        reports: [
            [
                "Second Semester Research Summary",
                "Summary",
                "May 30, 2027"
            ],
            [
                "Department Research Performance",
                "Performance",
                "May 28, 2027"
            ],
            [
                "Funding Utilization Report",
                "Financial",
                "May 25, 2027"
            ],
            [
                "Publication and Citation Report",
                "Publication",
                "May 22, 2027"
            ],
            [
                "Research Completion Report",
                "Completion",
                "May 20, 2027"
            ],
            [
                "Semester Research Monitoring",
                "Monitoring",
                "May 15, 2027"
            ]
        ]

    }

};


/* =========================================================
   CHART
   ========================================================= */

let proposalChart = null;

function createChart(data) {

    const canvas =
        document.getElementById("proposalTrend");

    if (!canvas) return;

    if (proposalChart) {
        proposalChart.destroy();
    }

    const ctx = canvas.getContext("2d");

    const gradient =
        ctx.createLinearGradient(0, 0, 0, 150);

    gradient.addColorStop(
        0,
        "rgba(44, 126, 146, 0.30)"
    );

    gradient.addColorStop(
        1,
        "rgba(44, 126, 146, 0.02)"
    );

    proposalChart = new Chart(
        ctx,
        {
            type: "line",

            data: {

                labels: [
                    "Month 1",
                    "Month 2",
                    "Month 3",
                    "Month 4",
                    "Month 5",
                    "Month 6"
                ],

                datasets: [

                    {
                        label: "Submitted",

                        data: data.submitted,

                        borderColor: "#286b80",

                        backgroundColor: gradient,

                        fill: true,

                        borderWidth: 2,

                        pointRadius: 2.5,

                        pointHoverRadius: 5,

                        tension: .35
                    },

                    {
                        label: "Approved",

                        data: data.approved,

                        borderColor: "#4b8c85",

                        backgroundColor: "transparent",

                        borderWidth: 1.5,

                        pointRadius: 2,

                        tension: .35
                    },

                    {
                        label: "Completed",

                        data: data.completed,

                        borderColor: "#c69b45",

                        backgroundColor: "transparent",

                        borderWidth: 1.5,

                        pointRadius: 2,

                        tension: .35
                    }

                ]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                interaction: {
                    intersect: false,
                    mode: "index"
                },

                plugins: {

                    legend: {

                        position: "top",

                        align: "center",

                        labels: {

                            boxWidth: 8,

                            boxHeight: 8,

                            padding: 12,

                            font: {
                                size: 9
                            }

                        }

                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        max: 50,

                        ticks: {

                            stepSize: 10,

                            font: {
                                size: 8
                            },

                            color: "#4b5563"

                        },

                        grid: {
                            color: "#e5e9ed"
                        },

                        border: {
                            display: false
                        }

                    },

                    x: {

                        ticks: {

                            font: {
                                size: 8
                            },

                            color: "#4b5563"

                        },

                        grid: {
                            display: false
                        },

                        border: {
                            display: false
                        }

                    }

                }

            }

        }
    );
}


/* =========================================================
   REPORT TABLE
   ========================================================= */

function updateReports(reports) {

    const tbody =
        document.getElementById("reportTableBody");

    tbody.innerHTML = "";

    reports.forEach(function(report, index) {

        const row =
            document.createElement("tr");

        row.innerHTML = `

            <td>
                <span class="report-icon">
                    ${index % 2 === 0 ? "▧" : "▤"}
                </span>
                ${report[0]}
            </td>

            <td>
                ${report[1]}
            </td>

            <td>
                ${report[2]}
            </td>

            <td>
                <div class="report-action">

                    <button
                        title="View"
                        onclick="viewReport('${report[0]}')">
                        ◉
                    </button>

                    <button
                        title="Download"
                        onclick="downloadReport('${report[0]}')">
                        ⇩
                    </button>

                </div>
            </td>

        `;

        tbody.appendChild(row);

    });

}


/* =========================================================
   UPDATE DASHBOARD
   ========================================================= */

function updateDashboard(semester) {

    const data =
        semesterData[semester];

    if (!data) return;

    /* Welcome */

    document.getElementById("welcomeText")
        .textContent = data.message;

    document.getElementById("semesterMessage")
        .textContent = data.description;


    /* Statistics */

    document.getElementById("totalProposals")
        .textContent = data.stats.total;

    document.getElementById("approvedProposals")
        .textContent = data.stats.approved;

    document.getElementById("ongoingResearches")
        .textContent = data.stats.ongoing;

    document.getElementById("completedResearches")
        .textContent = data.stats.completed;

    document.getElementById("publishedResearches")
        .textContent = data.stats.published;


    /* Status */

    document.getElementById("reviewCount")
        .textContent = data.status.review;

    document.getElementById("approvedCount")
        .textContent = data.status.approved;

    document.getElementById("ongoingCount")
        .textContent = data.status.ongoing;

    document.getElementById("completedCount")
        .textContent = data.status.completed;

    document.getElementById("disapprovedCount")
        .textContent = data.status.disapproved;


    /* Date */

    document.getElementById("reportDate")
        .value = data.dates[0];


    /* Chart */

    createChart(data.chart);


    /* Reports */

    updateReports(data.reports);


    /* Save selected semester */

    localStorage.setItem(
        "researchSemester",
        semester
    );

}


/* =========================================================
   SEMESTER CHANGE
   ========================================================= */

function changeSemester() {

    const select =
        document.getElementById("semesterSelect");

    updateDashboard(select.value);

}


/* =========================================================
   REPORT BUTTONS
   ========================================================= */

function viewReport(reportName) {

    alert(
        "Viewing Report:\n\n" +
        reportName
    );

}

function downloadReport(reportName) {

    alert(
        "Download requested for:\n\n" +
        reportName
    );

}


/* =========================================================
   SIDEBAR
   ========================================================= */

function toggleSidebar() {

    document.body.classList.toggle(
        "sidebar-hidden"
    );

}


/* =========================================================
   INITIALIZE
   ========================================================= */

document.addEventListener(
    "DOMContentLoaded",
    function() {

        let savedSemester =
            localStorage.getItem(
                "researchSemester"
            );

        if (
            savedSemester !== "first" &&
            savedSemester !== "second"
        ) {
            savedSemester = "first";
        }

        document.getElementById(
            "semesterSelect"
        ).value = savedSemester;

        updateDashboard(
            savedSemester
        );

    }
);

</script>

</body>
</html>