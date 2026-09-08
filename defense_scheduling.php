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

    <title>Research Defense Scheduling System</title>

    <link rel="icon" type="image/png" href="logo.png">

    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="stylesheet" href="assets/style.css">


<style>

/* =========================================================
   RESEARCH DEFENSE SCHEDULING SYSTEM
   ========================================================= */

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #eef2f5;
    color: #263445;
    font-family: Arial, Helvetica, sans-serif;
    overflow-x: hidden;
}


/* =========================================================
   MAIN
   ========================================================= */

.main {
    margin-left: 260px;
    min-height: 100vh;
    background: #eef2f5;
    transition: .3s ease;
}

.content {
    padding: 0 24px 25px;
}


/* =========================================================
   TOP BAR
   ========================================================= */

.defense-topbar {
    height: 48px;

    background: rgba(255,255,255,.94);

    border-bottom: 1px solid #dce3e8;

    display: flex;
    align-items: center;

    padding: 0 18px;
}

.menu-button {
    border: 0;
    background: transparent;

    font-size: 21px;
    color: #344054;

    cursor: pointer;

    width: 32px;
    height: 32px;
}

.top-space {
    flex: 1;
}

.notification {
    position: relative;

    font-size: 19px;

    margin-right: 20px;

    color: #344054;
}

.notification-count {
    position: absolute;

    top: -6px;
    right: -7px;

    width: 15px;
    height: 15px;

    background: #c63f4c;

    color: white;

    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 8px;
    font-weight: 800;
}

.admin {
    display: flex;
    align-items: center;

    gap: 8px;
}

.admin img {
    width: 29px;
    height: 29px;

    border-radius: 50%;

    object-fit: cover;
}

.admin-name {
    font-size: 11px;
    font-weight: 800;
}

.admin-role {
    font-size: 8px;
    color: #7b8492;

    margin-top: 2px;
}


/* =========================================================
   HEADER
   ========================================================= */

.page-header {
    display: flex;

    align-items: center;
    justify-content: space-between;

    padding: 10px 0 13px;
}

.page-title h1 {
    margin: 0;

    font-size: 21px;

    color: #101828;

    font-weight: 800;
}

.page-title p {
    margin: 4px 0 0;

    font-size: 10px;

    color: #667085;
}

.semester-select {
    width: 150px;

    height: 30px;

    border: 1px solid #d4dbe2;

    background: white;

    border-radius: 5px;

    padding: 0 9px;

    font-size: 10px;

    color: #344054;
}


/* =========================================================
   STATISTICS
   ========================================================= */

.stats-grid {
    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 12px;

    margin-bottom: 14px;
}

.stat-card {
    background: white;

    border: 1px solid #e0e6eb;

    border-radius: 7px;

    padding: 14px;

    min-height: 84px;

    display: flex;
    align-items: center;

    gap: 12px;

    box-shadow:
        0 2px 7px rgba(31,41,55,.05);
}

.stat-icon {
    width: 43px;
    height: 43px;

    border-radius: 8px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 20px;

    background: #e7f1f5;

    color: #23677a;
}

.stat-card:nth-child(2) .stat-icon {
    background: #eee9f8;
    color: #7653a6;
}

.stat-card:nth-child(3) .stat-icon {
    background: #e7f5ec;
    color: #3c8b68;
}

.stat-card:nth-child(4) .stat-icon {
    background: #fff4db;
    color: #ad812e;
}

.stat-number {
    font-size: 20px;

    font-weight: 800;

    color: #1f2937;
}

.stat-label {
    font-size: 9px;

    color: #697586;

    margin-top: 3px;
}

.stat-small {
    font-size: 8px;

    color: #89929d;

    margin-top: 2px;
}


/* =========================================================
   GRID
   ========================================================= */

.top-grid {
    display: grid;

    grid-template-columns: 1.35fr .65fr;

    gap: 14px;

    margin-bottom: 14px;
}


/* =========================================================
   CARD
   ========================================================= */

.card {
    background: white;

    border: 1px solid #dfe5ea;

    border-radius: 8px;

    box-shadow:
        0 2px 8px rgba(31,41,55,.05);

    overflow: hidden;
}

.card-header {
    display: flex;

    align-items: center;
    justify-content: space-between;

    padding: 12px 14px 9px;

    border-bottom: 1px solid #edf0f3;
}

.card-title {
    margin: 0;

    font-size: 13px;

    font-weight: 800;

    color: #202938;
}

.card-subtitle {
    font-size: 8px;

    color: #7b8491;

    margin-top: 3px;
}

.view-all {
    font-size: 9px;

    color: #337b91;

    text-decoration: underline;
}


/* =========================================================
   SCHEDULE CALENDAR
   ========================================================= */

.calendar-header {
    display: flex;

    align-items: center;
    justify-content: space-between;

    padding: 10px 13px;

    border-bottom: 1px solid #edf0f3;
}

.calendar-month {
    font-size: 11px;

    font-weight: 800;

    color: #263445;
}

.calendar-controls {
    display: flex;

    gap: 5px;
}

.calendar-btn {
    border: 1px solid #d8e0e5;

    background: white;

    width: 25px;
    height: 23px;

    border-radius: 4px;

    cursor: pointer;

    color: #52606d;
}

.calendar-btn:hover {
    background: #f3f7f8;
}

.calendar-grid {
    display: grid;

    grid-template-columns:
        repeat(7, 1fr);

    padding: 8px;

    gap: 3px;
}

.day-name {
    text-align: center;

    font-size: 7px;

    font-weight: 800;

    color: #7b8491;

    padding: 4px 0;
}

.day {
    min-height: 38px;

    border: 1px solid #edf0f3;

    border-radius: 4px;

    padding: 4px;

    background: #fff;

    position: relative;
}

.day-number {
    font-size: 8px;

    color: #536170;

    font-weight: 700;
}

.day.today {
    background: #edf7f9;

    border-color: #9fcbd4;
}

.day.today .day-number {
    color: #226a7c;
}

.day.has-defense {
    background: #f6f2fb;

    border-color: #d7c8ea;
}

.event-dot {
    display: block;

    margin-top: 5px;

    width: 100%;

    height: 4px;

    border-radius: 4px;

    background: #337f91;
}

.event-dot.final {
    background: #7654a7;
}

.event-dot.completed {
    background: #4d9a70;
}


/* =========================================================
   SCHEDULE FORM
   ========================================================= */

.schedule-form-card {
    background:
        linear-gradient(
            110deg,
            #143a5c,
            #166778
        );

    border: 0;

    color: white;
}

.schedule-form-card .card-header {
    border-bottom:
        1px solid rgba(255,255,255,.15);
}

.schedule-form-card .card-title {
    color: white;
}

.schedule-form-card .card-subtitle {
    color: rgba(255,255,255,.7);
}

.schedule-form {
    padding: 13px 15px 15px;
}

.form-grid {
    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 10px;
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-group label {
    display: block;

    font-size: 8px;

    font-weight: 700;

    color: rgba(255,255,255,.9);

    margin-bottom: 4px;
}

.form-control {
    width: 100%;

    height: 29px;

    padding: 0 8px;

    border: 1px solid #d7dee5;

    border-radius: 5px;

    background: white;

    color: #344054;

    font-size: 9px;

    outline: none;
}

textarea.form-control {
    height: 48px;

    padding-top: 7px;

    resize: vertical;
}

.schedule-submit {
    width: 100%;

    height: 31px;

    margin-top: 11px;

    border: none;

    border-radius: 5px;

    background: #dfbd62;

    color: #18263a;

    font-size: 9px;

    font-weight: 800;

    cursor: pointer;
}

.schedule-submit:hover {
    background: #e9cc7b;
}


/* =========================================================
   PANELIST AVAILABILITY
   ========================================================= */

.availability-card {
    margin-bottom: 14px;
}

.availability-list {
    padding: 8px 13px 10px;
}

.panelist {
    display: flex;

    align-items: center;

    gap: 9px;

    padding: 9px 2px;

    border-bottom: 1px solid #edf0f3;
}

.panelist:last-child {
    border-bottom: 0;
}

.panel-avatar {
    width: 31px;
    height: 31px;

    border-radius: 50%;

    background: #e9f3f5;

    color: #286d7e;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 9px;

    font-weight: 800;
}

.panel-info {
    flex: 1;
}

.panel-name {
    font-size: 9px;

    font-weight: 800;

    color: #293544;
}

.panel-role {
    font-size: 7px;

    color: #87919c;

    margin-top: 2px;
}

.availability-status {
    font-size: 7px;

    font-weight: 800;

    padding: 4px 7px;

    border-radius: 15px;
}

.available {
    background: #e5f6eb;

    color: #28734e;
}

.busy {
    background: #fdecec;

    color: #a83d48;
}

.pending {
    background: #fff4df;

    color: #a56e12;
}


/* =========================================================
   DEFENSE SCHEDULE TABLE
   ========================================================= */

.schedule-card {
    margin-bottom: 14px;
}

.table-wrapper {
    overflow-x: auto;
}

.schedule-table {
    width: 100%;

    border-collapse: collapse;

    table-layout: fixed;
}

.schedule-table th {
    height: 31px;

    padding: 0 9px;

    background: #f5f7f9;

    color: #344054;

    font-size: 8px;

    text-align: left;

    font-weight: 800;

    border-bottom: 1px solid #e1e6ea;
}

.schedule-table td {
    padding: 9px;

    font-size: 8px;

    color: #4b5563;

    border-bottom: 1px solid #edf0f3;

    vertical-align: middle;
}

.schedule-table tr:last-child td {
    border-bottom: 0;
}

.project-title {
    font-size: 9px;

    font-weight: 800;

    color: #263445;
}

.project-researcher {
    font-size: 7px;

    color: #89929d;

    margin-top: 3px;
}

.date-main {
    font-weight: 800;

    color: #374151;
}

.date-time {
    font-size: 7px;

    color: #89929d;

    margin-top: 3px;
}


/* =========================================================
   TYPE BADGES
   ========================================================= */

.type-badge {
    display: inline-flex;

    padding: 4px 7px;

    border-radius: 15px;

    font-size: 7px;

    font-weight: 800;
}

.proposal {
    background: #e8f3f6;

    color: #286d7e;
}

.final {
    background: #eee8f8;

    color: #714e9e;
}


/* =========================================================
   STATUS
   ========================================================= */

.status {
    display: inline-flex;

    padding: 4px 7px;

    border-radius: 15px;

    font-size: 7px;

    font-weight: 800;

    white-space: nowrap;
}

.status-scheduled {
    background: #e4f2f6;

    color: #276d7c;
}

.status-confirmed {
    background: #e6f6eb;

    color: #28734e;
}

.status-completed {
    background: #e9e9ea;

    color: #5d6670;
}

.status-pending {
    background: #fff3dd;

    color: #a36e12;
}

.status-cancelled {
    background: #fdebec;

    color: #a83d48;
}


/* =========================================================
   ACTION BUTTONS
   ========================================================= */

.action-btn {
    border: 1px solid #cbdde3;

    background: #edf7f9;

    color: #286d7d;

    padding: 5px 7px;

    border-radius: 4px;

    font-size: 7px;

    font-weight: 700;

    cursor: pointer;
}

.action-btn:hover {
    background: #dceff3;
}


/* =========================================================
   BOTTOM GRID
   ========================================================= */

.bottom-grid {
    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 14px;
}


/* =========================================================
   UPCOMING DEFENSES
   ========================================================= */

.upcoming-list {
    padding: 7px 13px 10px;
}

.upcoming-item {
    display: grid;

    grid-template-columns: 42px 1fr auto;

    gap: 9px;

    align-items: center;

    padding: 9px 2px;

    border-bottom: 1px solid #edf0f3;
}

.upcoming-item:last-child {
    border-bottom: 0;
}

.date-box {
    width: 38px;
    height: 39px;

    border-radius: 6px;

    background: #edf6f8;

    display: flex;

    flex-direction: column;

    justify-content: center;

    align-items: center;
}

.date-month {
    font-size: 7px;

    color: #337b8b;

    font-weight: 800;
}

.date-day {
    font-size: 15px;

    color: #263445;

    font-weight: 800;
}

.upcoming-title {
    font-size: 9px;

    font-weight: 800;

    color: #293544;
}

.upcoming-details {
    font-size: 7px;

    color: #858f9b;

    margin-top: 3px;
}

.reminder {
    background: #fff4dd;

    color: #a16c12;

    border-radius: 15px;

    padding: 4px 6px;

    font-size: 7px;

    font-weight: 800;
}


/* =========================================================
   NOTIFICATIONS
   ========================================================= */

.notification-list {
    padding: 7px 13px 10px;
}

.notification-item {
    display: flex;

    gap: 9px;

    padding: 9px 2px;

    border-bottom: 1px solid #edf0f3;
}

.notification-item:last-child {
    border-bottom: 0;
}

.notification-icon {
    width: 29px;
    height: 29px;

    border-radius: 6px;

    background: #edf6f8;

    color: #286d7d;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 12px;
}

.notification-icon.warning {
    background: #fff4dd;

    color: #a16c12;
}

.notification-icon.success {
    background: #e6f6eb;

    color: #28734e;
}

.notification-text {
    flex: 1;
}

.notification-title {
    font-size: 8px;

    font-weight: 800;

    color: #354052;
}

.notification-description {
    font-size: 7px;

    color: #858f9b;

    margin-top: 3px;
}

.notification-time {
    font-size: 7px;

    color: #a0a8b1;

    margin-top: 3px;
}


/* =========================================================
   SIDEBAR TOGGLE
   ========================================================= */

.sidebar-hidden .sidebar {
    transform: translateX(-100%);
}

.sidebar-hidden .main {
    margin-left: 0;
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media(max-width:1100px) {

    .main {
        margin-left: 230px;
    }

    .stats-grid {
        grid-template-columns: repeat(2,1fr);
    }

    .top-grid {
        grid-template-columns: 1fr;
    }

    .bottom-grid {
        grid-template-columns: 1fr;
    }

}

@media(max-width:800px) {

    .main {
        margin-left: 0;
    }

    .content {
        padding: 0 12px 20px;
    }

    .page-header {
        flex-direction: column;

        align-items: flex-start;

        gap: 8px;
    }

    .semester-select {
        width: 100%;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full {
        grid-column: auto;
    }

    .schedule-table {
        min-width: 950px;
    }

}

</style>

</head>


<body>


<?php include 'includes/sidebar.php'; ?>


<main class="main">


    <!-- =====================================================
         TOP BAR
    ====================================================== -->

    <div class="defense-topbar">

        <button
            class="menu-button"
            onclick="toggleSidebar()">
            ☰
        </button>

        <div class="top-space"></div>


        <div class="notification">

            ♧

            <span class="notification-count">
                3
            </span>

        </div>


        <div class="admin">

            <img
                src="logo.png"
                alt="CRAD Admin">

            <div>

                <div class="admin-name">
                    CRAD Admin
                </div>

                <div class="admin-role">
                    Administrator
                </div>

            </div>

            <span>⌄</span>

        </div>

    </div>


    <section class="content">


        <!-- =================================================
             PAGE HEADER
        ================================================== -->

        <div class="page-header">

            <div class="page-title">

                <h1>
                    Research Defense Scheduling System
                </h1>

                <p>
                    Schedule proposal and final defenses, manage panelist availability, and track defense status.
                </p>

            </div>


            <select class="semester-select">

                <option>
                    This Semester
                </option>

                <option>
                    First Semester
                </option>

                <option>
                    Second Semester
                </option>

            </select>

        </div>


        <!-- =================================================
             STATISTICS
        ================================================== -->

        <div class="stats-grid">


            <div class="stat-card">

                <div class="stat-icon">
                    ▣
                </div>

                <div>

                    <div class="stat-number">
                        32
                    </div>

                    <div class="stat-label">
                        Scheduled Defenses
                    </div>

                    <div class="stat-small">
                        This semester
                    </div>

                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon">
                    ◷
                </div>

                <div>

                    <div class="stat-number">
                        8
                    </div>

                    <div class="stat-label">
                        Upcoming Defenses
                    </div>

                    <div class="stat-small">
                        Within the next 7 days
                    </div>

                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon">
                    ✓
                </div>

                <div>

                    <div class="stat-number">
                        21
                    </div>

                    <div class="stat-label">
                        Completed Defenses
                    </div>

                    <div class="stat-small">
                        This semester
                    </div>

                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon">
                    !
                </div>

                <div>

                    <div class="stat-number">
                        5
                    </div>

                    <div class="stat-label">
                        Pending Confirmation
                    </div>

                    <div class="stat-small">
                        Requires attention
                    </div>

                </div>

            </div>


        </div>


        <!-- =================================================
             CALENDAR + SCHEDULE FORM
        ================================================== -->

        <div class="top-grid">


            <!-- CALENDAR -->

            <div class="card">


                <div class="card-header">

                    <div>

                        <h3 class="card-title">
                            Defense Schedule Calendar
                        </h3>

                        <div class="card-subtitle">
                            View scheduled proposal and final defenses
                        </div>

                    </div>

                    <span class="view-all">
                        May 2025
                    </span>

                </div>


                <div class="calendar-header">

                    <div class="calendar-month">
                        May 2025
                    </div>

                    <div class="calendar-controls">

                        <button class="calendar-btn">
                            ‹
                        </button>

                        <button class="calendar-btn">
                            ›
                        </button>

                    </div>

                </div>


                <div class="calendar-grid">


                    <div class="day-name">
                        Sun
                    </div>

                    <div class="day-name">
                        Mon
                    </div>

                    <div class="day-name">
                        Tue
                    </div>

                    <div class="day-name">
                        Wed
                    </div>

                    <div class="day-name">
                        Thu
                    </div>

                    <div class="day-name">
                        Fri
                    </div>

                    <div class="day-name">
                        Sat
                    </div>


                    <!-- WEEK 1 -->

                    <div class="day">
                        <span class="day-number">27</span>
                    </div>

                    <div class="day">
                        <span class="day-number">28</span>
                    </div>

                    <div class="day">
                        <span class="day-number">29</span>
                    </div>

                    <div class="day">
                        <span class="day-number">30</span>
                    </div>

                    <div class="day">
                        <span class="day-number">1</span>
                    </div>

                    <div class="day">
                        <span class="day-number">2</span>
                    </div>

                    <div class="day">
                        <span class="day-number">3</span>
                    </div>


                    <!-- WEEK 2 -->

                    <div class="day">
                        <span class="day-number">4</span>
                    </div>

                    <div class="day">
                        <span class="day-number">5</span>
                    </div>

                    <div class="day">
                        <span class="day-number">6</span>
                    </div>

                    <div class="day has-defense">
                        <span class="day-number">7</span>

                        <span class="event-dot"></span>
                    </div>

                    <div class="day">
                        <span class="day-number">8</span>
                    </div>

                    <div class="day">
                        <span class="day-number">9</span>
                    </div>

                    <div class="day">
                        <span class="day-number">10</span>
                    </div>


                    <!-- WEEK 3 -->

                    <div class="day">
                        <span class="day-number">11</span>
                    </div>

                    <div class="day">
                        <span class="day-number">12</span>
                    </div>

                    <div class="day has-defense">
                        <span class="day-number">13</span>

                        <span class="event-dot final"></span>
                    </div>

                    <div class="day">
                        <span class="day-number">14</span>
                    </div>

                    <div class="day">
                        <span class="day-number">15</span>
                    </div>

                    <div class="day has-defense">
                        <span class="day-number">16</span>

                        <span class="event-dot"></span>
                    </div>

                    <div class="day">
                        <span class="day-number">17</span>
                    </div>


                    <!-- WEEK 4 -->

                    <div class="day">
                        <span class="day-number">18</span>
                    </div>

                    <div class="day">
                        <span class="day-number">19</span>
                    </div>

                    <div class="day has-defense">
                        <span class="day-number">20</span>

                        <span class="event-dot final"></span>
                    </div>

                    <div class="day">
                        <span class="day-number">21</span>
                    </div>

                    <div class="day today">
                        <span class="day-number">22</span>

                        <span class="event-dot"></span>
                    </div>

                    <div class="day">
                        <span class="day-number">23</span>
                    </div>

                    <div class="day has-defense">
                        <span class="day-number">24</span>

                        <span class="event-dot completed"></span>
                    </div>


                    <!-- WEEK 5 -->

                    <div class="day">
                        <span class="day-number">25</span>
                    </div>

                    <div class="day">
                        <span class="day-number">26</span>
                    </div>

                    <div class="day">
                        <span class="day-number">27</span>
                    </div>

                    <div class="day has-defense">
                        <span class="day-number">28</span>

                        <span class="event-dot final"></span>
                    </div>

                    <div class="day">
                        <span class="day-number">29</span>
                    </div>

                    <div class="day">
                        <span class="day-number">30</span>
                    </div>

                    <div class="day">
                        <span class="day-number">31</span>
                    </div>


                </div>

            </div>


            <!-- SCHEDULE DEFENSE -->

            <div class="card schedule-form-card">


                <div class="card-header">

                    <div>

                        <h3 class="card-title">
                            Schedule Defense
                        </h3>

                        <div class="card-subtitle">
                            Assign date, time, room and panel
                        </div>

                    </div>

                </div>


                <form class="schedule-form">


                    <div class="form-grid">


                        <div class="form-group full">

                            <label>
                                Research Proposal
                            </label>

                            <select class="form-control">

                                <option>
                                    Select Research Proposal
                                </option>

                                <option>
                                    Smart Campus Attendance System
                                </option>

                                <option>
                                    AI-Based Student Support System
                                </option>

                                <option>
                                    Library Management System with QR Tracking
                                </option>

                                <option>
                                    E-Commerce Website for Local Products
                                </option>

                            </select>

                        </div>


                        <div class="form-group">

                            <label>
                                Defense Type
                            </label>

                            <select class="form-control">

                                <option>
                                    Proposal Defense
                                </option>

                                <option>
                                    Final Defense
                                </option>

                            </select>

                        </div>


                        <div class="form-group">

                            <label>
                                Defense Date
                            </label>

                            <input
                                type="date"
                                class="form-control">

                        </div>


                        <div class="form-group">

                            <label>
                                Start Time
                            </label>

                            <input
                                type="time"
                                class="form-control">

                        </div>


                        <div class="form-group">

                            <label>
                                End Time
                            </label>

                            <input
                                type="time"
                                class="form-control">

                        </div>


                        <div class="form-group">

                            <label>
                                Defense Room
                            </label>

                            <select class="form-control">

                                <option>
                                    Select Room
                                </option>

                                <option>
                                    Research Room 101
                                </option>

                                <option>
                                    Conference Room A
                                </option>

                                <option>
                                    AVR 1
                                </option>

                                <option>
                                    AVR 2
                                </option>

                            </select>

                        </div>


                        <div class="form-group">

                            <label>
                                Panel
                            </label>

                            <select class="form-control">

                                <option>
                                    Select Panel
                                </option>

                                <option>
                                    Panel A
                                </option>

                                <option>
                                    Panel B
                                </option>

                                <option>
                                    Panel C
                                </option>

                            </select>

                        </div>


                    </div>


                    <button
                        type="button"
                        class="schedule-submit">

                        Schedule Defense

                    </button>


                </form>

            </div>


        </div>


        <!-- =================================================
             PANELIST AVAILABILITY
        ================================================== -->

        <div class="card availability-card">


            <div class="card-header">

                <div>

                    <h3 class="card-title">
                        Panelist Availability
                    </h3>

                    <div class="card-subtitle">
                        Check panel member availability before scheduling
                    </div>

                </div>

                <a href="#" class="view-all">
                    View All Panelists
                </a>

            </div>


            <div class="availability-list">


                <div class="panelist">

                    <div class="panel-avatar">
                        MS
                    </div>

                    <div class="panel-info">

                        <div class="panel-name">
                            Dr. Maria Santos
                        </div>

                        <div class="panel-role">
                            Panel Chair · Information Technology
                        </div>

                    </div>

                    <span class="availability-status available">
                        Available
                    </span>

                </div>


                <div class="panelist">

                    <div class="panel-avatar">
                        CR
                    </div>

                    <div class="panel-info">

                        <div class="panel-name">
                            Prof. Carlo Ramirez
                        </div>

                        <div class="panel-role">
                            Panel Member · Computer Science
                        </div>

                    </div>

                    <span class="availability-status available">
                        Available
                    </span>

                </div>


                <div class="panelist">

                    <div class="panel-avatar">
                        AR
                    </div>

                    <div class="panel-info">

                        <div class="panel-name">
                            Dr. Ana Reyes
                        </div>

                        <div class="panel-role">
                            Panel Member · Information Systems
                        </div>

                    </div>

                    <span class="availability-status busy">
                        Busy
                    </span>

                </div>


                <div class="panelist">

                    <div class="panel-avatar">
                        JD
                    </div>

                    <div class="panel-info">

                        <div class="panel-name">
                            Prof. John Dela Cruz
                        </div>

                        <div class="panel-role">
                            Panel Member · Software Engineering
                        </div>

                    </div>

                    <span class="availability-status pending">
                        Checking
                    </span>

                </div>


            </div>

        </div>


        <!-- =================================================
             DEFENSE SCHEDULE TABLE
        ================================================== -->

        <div class="card schedule-card">


            <div class="card-header">

                <div>

                    <h3 class="card-title">
                        Defense Schedule
                    </h3>

                    <div class="card-subtitle">
                        Scheduled proposal and final defense sessions
                    </div>

                </div>

                <a href="#" class="view-all">
                    View Calendar
                </a>

            </div>


            <div class="table-wrapper">


                <table class="schedule-table">


                    <thead>

                        <tr>

                            <th style="width:24%">
                                Research Proposal
                            </th>

                            <th style="width:11%">
                                Type
                            </th>

                            <th style="width:13%">
                                Date & Time
                            </th>

                            <th style="width:13%">
                                Room
                            </th>

                            <th style="width:16%">
                                Panel
                            </th>

                            <th style="width:11%">
                                Status
                            </th>

                            <th style="width:12%">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <tr>

                            <td>

                                <div class="project-title">
                                    Smart Campus Attendance System
                                </div>

                                <div class="project-researcher">
                                    Juan Dela Cruz · BSIT
                                </div>

                            </td>

                            <td>

                                <span class="type-badge proposal">
                                    Proposal Defense
                                </span>

                            </td>

                            <td>

                                <div class="date-main">
                                    May 22, 2025
                                </div>

                                <div class="date-time">
                                    9:00 AM – 10:00 AM
                                </div>

                            </td>

                            <td>
                                Research Room 101
                            </td>

                            <td>
                                Dr. Maria Santos<br>
                                Prof. Carlo Ramirez<br>
                                Dr. Ana Reyes
                            </td>

                            <td>

                                <span class="status status-confirmed">
                                    Confirmed
                                </span>

                            </td>

                            <td>

                                <button class="action-btn">
                                    Manage
                                </button>

                            </td>

                        </tr>


                        <tr>

                            <td>

                                <div class="project-title">
                                    AI-Based Student Support System
                                </div>

                                <div class="project-researcher">
                                    Patricia Gomez · BSCS
                                </div>

                            </td>

                            <td>

                                <span class="type-badge final">
                                    Final Defense
                                </span>

                            </td>

                            <td>

                                <div class="date-main">
                                    May 23, 2025
                                </div>

                                <div class="date-time">
                                    1:00 PM – 2:30 PM
                                </div>

                            </td>

                            <td>
                                AVR 1
                            </td>

                            <td>
                                Dr. Ana Reyes<br>
                                Prof. John Dela Cruz<br>
                                Prof. Carlo Ramirez
                            </td>

                            <td>

                                <span class="status status-scheduled">
                                    Scheduled
                                </span>

                            </td>

                            <td>

                                <button class="action-btn">
                                    Manage
                                </button>

                            </td>

                        </tr>


                        <tr>

                            <td>

                                <div class="project-title">
                                    Library Management System with QR Tracking
                                </div>

                                <div class="project-researcher">
                                    Kyle Tan · BSIS
                                </div>

                            </td>

                            <td>

                                <span class="type-badge proposal">
                                    Proposal Defense
                                </span>

                            </td>

                            <td>

                                <div class="date-main">
                                    May 24, 2025
                                </div>

                                <div class="date-time">
                                    10:00 AM – 11:00 AM
                                </div>

                            </td>

                            <td>
                                Conference Room A
                            </td>

                            <td>
                                Dr. Maria Santos<br>
                                Prof. Carlo Ramirez<br>
                                Prof. John Dela Cruz
                            </td>

                            <td>

                                <span class="status status-pending">
                                    Pending
                                </span>

                            </td>

                            <td>

                                <button class="action-btn">
                                    Manage
                                </button>

                            </td>

                        </tr>


                        <tr>

                            <td>

                                <div class="project-title">
                                    E-Commerce Website for Local Products
                                </div>

                                <div class="project-researcher">
                                    John Villanueva · BSIT
                                </div>

                            </td>

                            <td>

                                <span class="type-badge final">
                                    Final Defense
                                </span>

                            </td>

                            <td>

                                <div class="date-main">
                                    May 28, 2025
                                </div>

                                <div class="date-time">
                                    2:00 PM – 3:30 PM
                                </div>

                            </td>

                            <td>
                                AVR 2
                            </td>

                            <td>
                                Dr. Maria Santos<br>
                                Dr. Ana Reyes<br>
                                Prof. John Dela Cruz
                            </td>

                            <td>

                                <span class="status status-scheduled">
                                    Scheduled
                                </span>

                            </td>

                            <td>

                                <button class="action-btn">
                                    Manage
                                </button>

                            </td>

                        </tr>


                        <tr>

                            <td>

                                <div class="project-title">
                                    Mobile Application for Campus Events
                                </div>

                                <div class="project-researcher">
                                    Maria Santos · BSIT
                                </div>

                            </td>

                            <td>

                                <span class="type-badge final">
                                    Final Defense
                                </span>

                            </td>

                            <td>

                                <div class="date-main">
                                    May 16, 2025
                                </div>

                                <div class="date-time">
                                    9:00 AM – 10:30 AM
                                </div>

                            </td>

                            <td>
                                AVR 1
                            </td>

                            <td>
                                Dr. Maria Santos<br>
                                Prof. Carlo Ramirez<br>
                                Dr. Ana Reyes
                            </td>

                            <td>

                                <span class="status status-completed">
                                    Completed
                                </span>

                            </td>

                            <td>

                                <button class="action-btn">
                                    View
                                </button>

                            </td>

                        </tr>


                    </tbody>

                </table>


            </div>

        </div>


        <!-- =================================================
             UPCOMING + NOTIFICATIONS
        ================================================== -->

        <div class="bottom-grid">


            <!-- UPCOMING -->

            <div class="card">


                <div class="card-header">

                    <div>

                        <h3 class="card-title">
                            Upcoming Defenses
                        </h3>

                        <div class="card-subtitle">
                            Defenses scheduled in the coming days
                        </div>

                    </div>

                    <a href="#" class="view-all">
                        View All
                    </a>

                </div>


                <div class="upcoming-list">


                    <div class="upcoming-item">

                        <div class="date-box">

                            <div class="date-month">
                                MAY
                            </div>

                            <div class="date-day">
                                22
                            </div>

                        </div>

                        <div>

                            <div class="upcoming-title">
                                Smart Campus Attendance System
                            </div>

                            <div class="upcoming-details">
                                Proposal Defense · 9:00 AM · Research Room 101
                            </div>

                        </div>

                        <span class="reminder">
                            Today
                        </span>

                    </div>


                    <div class="upcoming-item">

                        <div class="date-box">

                            <div class="date-month">
                                MAY
                            </div>

                            <div class="date-day">
                                23
                            </div>

                        </div>

                        <div>

                            <div class="upcoming-title">
                                AI-Based Student Support System
                            </div>

                            <div class="upcoming-details">
                                Final Defense · 1:00 PM · AVR 1
                            </div>

                        </div>

                        <span class="reminder">
                            1 Day
                        </span>

                    </div>


                    <div class="upcoming-item">

                        <div class="date-box">

                            <div class="date-month">
                                MAY
                            </div>

                            <div class="date-day">
                                24
                            </div>

                        </div>

                        <div>

                            <div class="upcoming-title">
                                Library Management System
                            </div>

                            <div class="upcoming-details">
                                Proposal Defense · 10:00 AM · Conference Room A
                            </div>

                        </div>

                        <span class="reminder">
                            2 Days
                        </span>

                    </div>


                </div>

            </div>


            <!-- NOTIFICATIONS -->

            <div class="card">


                <div class="card-header">

                    <div>

                        <h3 class="card-title">
                            Notifications & Reminders
                        </h3>

                        <div class="card-subtitle">
                            Defense scheduling alerts
                        </div>

                    </div>

                    <a href="#" class="view-all">
                        View All
                    </a>

                </div>


                <div class="notification-list">


                    <div class="notification-item">

                        <div class="notification-icon warning">
                            !
                        </div>

                        <div class="notification-text">

                            <div class="notification-title">
                                Panel confirmation required
                            </div>

                            <div class="notification-description">
                                Prof. John Dela Cruz has not confirmed the May 24 defense.
                            </div>

                            <div class="notification-time">
                                20 minutes ago
                            </div>

                        </div>

                    </div>


                    <div class="notification-item">

                        <div class="notification-icon">
                            ◷
                        </div>

                        <div class="notification-text">

                            <div class="notification-title">
                                Defense reminder
                            </div>

                            <div class="notification-description">
                                Smart Campus Attendance System defense is scheduled tomorrow at 9:00 AM.
                            </div>

                            <div class="notification-time">
                                1 hour ago
                            </div>

                        </div>

                    </div>


                    <div class="notification-item">

                        <div class="notification-icon success">
                            ✓
                        </div>

                        <div class="notification-text">

                            <div class="notification-title">
                                Defense schedule confirmed
                            </div>

                            <div class="notification-description">
                                AI-Based Student Support System panel has confirmed the May 23 schedule.
                            </div>

                            <div class="notification-time">
                                3 hours ago
                            </div>

                        </div>

                    </div>


                </div>

            </div>


        </div>


    </section>


</main>


<script>

function toggleSidebar() {

    document.body.classList.toggle(
        'sidebar-hidden'
    );

}

</script>


</body>

</html>