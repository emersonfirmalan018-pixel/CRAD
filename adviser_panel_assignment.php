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
    <title>Adviser & Panel Assignment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="assets/style.css">

    <style>
        /* =====================================================
           PAGE
        ===================================================== */

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f7f9fc;
            color: #202b3c;
            font-family: Arial, Helvetica, sans-serif;
            overflow-x: hidden;
        }

        /* =====================================================
           MAIN
        ===================================================== */

        .main {
            margin-left: 260px;
            min-height: 100vh;
            background: #f8fafc;
            transition: .3s ease;
        }

        .content {
            padding: 0 26px 28px;
        }

        /* =====================================================
           TOP BAR
        ===================================================== */

        .assignment-topbar {
            height: 48px;
            width: 100%;
            background: #ffffff;
            border-bottom: 1px solid #e6eaf0;

            display: flex;
            align-items: center;

            padding: 0 20px;

            position: sticky;
            top: 0;
            z-index: 50;
        }

        .assignment-menu {
            width: 30px;
            height: 30px;

            border: none;
            background: transparent;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #5d6b7e;
            font-size: 18px;

            cursor: pointer;
        }

        .assignment-top-space {
            flex: 1;
        }

        .notification {
            position: relative;
            margin-right: 20px;

            color: #41516a;
            font-size: 19px;
        }

        .notification-badge {
            position: absolute;
            top: -7px;
            right: -8px;

            width: 15px;
            height: 15px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #d7374f;
            color: white;

            border-radius: 50%;

            font-size: 8px;
            font-weight: 800;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .admin-avatar {
            width: 30px;
            height: 30px;

            border-radius: 50%;

            object-fit: cover;

            background: #dce5f2;
        }

        .admin-details {
            line-height: 1.1;
        }

        .admin-name {
            font-size: 11px;
            font-weight: 800;
            color: #283446;
        }

        .admin-role {
            font-size: 9px;
            color: #7c8797;
            margin-top: 2px;
        }

        /* =====================================================
           PAGE HEADER
        ===================================================== */

        .page-header {
            padding: 17px 0 14px;
        }

        .page-title {
            margin: 0;

            font-size: 19px;
            line-height: 1.2;

            color: #172033;
            font-weight: 800;
        }

        .page-description {
            margin: 6px 0 0;

            color: #6c7788;

            font-size: 10px;
        }

        /* =====================================================
           STATISTICS
        ===================================================== */

        .stats-grid {
            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 12px;

            margin-bottom: 14px;
        }

        .stat-card {
            min-height: 78px;

            background: #ffffff;

            border: 1px solid #e8edf3;

            border-radius: 6px;

            padding: 13px 14px;

            display: flex;
            align-items: center;

            box-shadow: 0 2px 8px rgba(30, 50, 80, .035);
        }

        .stat-icon {
            width: 42px;
            height: 42px;

            flex: 0 0 42px;

            border-radius: 7px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 20px;

            margin-right: 12px;
        }

        .stat-icon.purple {
            background: #f0eaff;
            color: #7650c8;
        }

        .stat-icon.green {
            background: #e7f8ed;
            color: #3eaa6a;
        }

        .stat-icon.yellow {
            background: #fff6d9;
            color: #d9a72c;
        }

        .stat-icon.blue {
            background: #e5f5fc;
            color: #2995bd;
        }

        .stat-content {
            min-width: 0;
        }

        .stat-number {
            font-size: 18px;
            line-height: 1;

            color: #202b3c;

            font-weight: 800;

            margin-bottom: 5px;
        }

        .stat-label {
            color: #4d596a;

            font-size: 10px;

            font-weight: 600;

            line-height: 1.2;
        }

        .stat-sub {
            color: #8993a1;

            font-size: 8px;

            margin-top: 3px;
        }

        /* =====================================================
           PROPOSALS CARD
        ===================================================== */

        .proposal-card {
            background: #ffffff;

            border: 1px solid #e7ebf0;

            border-radius: 6px;

            box-shadow:
                0 2px 8px
                rgba(30, 50, 80, .035);

            overflow: hidden;
        }

        .proposal-header {
            min-height: 52px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 13px;

            border-bottom: 1px solid #eef1f5;
        }

        .proposal-title {
            font-size: 12px;

            font-weight: 800;

            color: #242e3e;
        }

        /* =====================================================
           FILTERS
        ===================================================== */

        .filters {
            display: flex;
            align-items: center;

            gap: 9px;
        }

        .filter-select,
        .search-box {
            height: 29px;

            border: 1px solid #dfe5ed;

            background: #ffffff;

            border-radius: 4px;

            color: #5e6978;

            font-size: 9px;

            outline: none;
        }

        .filter-select {
            min-width: 112px;

            padding: 0 25px 0 9px;
        }

        .search-box {
            width: 145px;

            padding: 0 9px;
        }

        .filter-select:focus,
        .search-box:focus {
            border-color: #9bb9db;
        }

        /* =====================================================
           TABLE
        ===================================================== */

        .table-wrapper {
            width: 100%;

            overflow-x: auto;
        }

        .proposals {
            width: 100%;

            border-collapse: collapse;

            table-layout: fixed;

            font-size: 9px;
        }

        .proposals thead {
            background: #fbfcfe;
        }

        .proposals th {
            height: 34px;

            padding: 0 8px;

            text-align: left;

            color: #263244;

            font-size: 9px;

            font-weight: 800;

            border-bottom: 1px solid #edf0f4;
        }

        .proposals td {
            height: 58px;

            padding: 7px 8px;

            color: #374151;

            vertical-align: middle;

            border-bottom: 1px solid #eef1f5;

            line-height: 1.35;
        }

        .proposals tbody tr:last-child td {
            border-bottom: none;
        }

        .proposals tbody tr:hover {
            background: #fafcff;
        }

        /* Column widths */

        .col-number {
            width: 38px;
            text-align: center !important;
        }

        .col-title {
            width: 22%;
        }

        .col-researchers {
            width: 14%;
        }

        .col-program {
            width: 9%;
        }

        .col-status {
            width: 13%;
        }

        .col-adviser {
            width: 14%;
        }

        .col-panel {
            width: 11%;
        }

        .col-actions {
            width: 90px;
        }

        .proposal-name {
            color: #293444;

            font-weight: 500;

            line-height: 1.35;
        }

        .researcher-name {
            color: #303b4b;

            font-size: 9px;
        }

        .researcher-more {
            color: #7b8593;

            font-size: 8px;
        }

        .program-text {
            font-weight: 600;
        }

        .muted {
            color: #667181;
        }

        /* =====================================================
           STATUS BADGES
        ===================================================== */

        .badge {
            display: inline-flex;

            align-items: center;

            padding: 5px 8px;

            border-radius: 4px;

            font-size: 8px;

            font-weight: 700;

            white-space: nowrap;
        }

        .badge.warning {
            background: #fff5df;
            color: #d19623;
        }

        .badge.review {
            background: #f2eafd;
            color: #7853b4;
        }

        .badge.scheduled {
            background: #e5f4fc;
            color: #2983b1;
        }

        .badge.success {
            background: #e5f8ec;
            color: #399761;
        }

        /* =====================================================
           ACTION BUTTONS
        ===================================================== */

        .action-area {
            display: flex;

            align-items: center;

            gap: 7px;
        }

        .action-btn {
            height: 25px;

            padding: 0 10px;

            border: 1px solid #d8e5f3;

            border-radius: 4px;

            background: #edf6fd;

            color: #3278ae;

            font-size: 8px;

            font-weight: 700;

            cursor: pointer;
        }

        .action-btn:hover {
            background: #e0f0fb;
        }

        .more-btn {
            width: 25px;
            height: 25px;

            border: 1px solid #e1e6ec;

            border-radius: 4px;

            background: #ffffff;

            color: #687485;

            cursor: pointer;

            font-size: 14px;

            line-height: 1;
        }

        .more-btn:hover {
            background: #f5f7fa;
        }

        /* =====================================================
           BOTTOM TWO CARDS
        ===================================================== */

        .bottom-grid {
            display: grid;

            grid-template-columns: 1fr 1.1fr;

            gap: 13px;

            margin-top: 13px;
        }

        .bottom-card {
            background: #ffffff;

            border: 1px solid #e7ebf0;

            border-radius: 6px;

            box-shadow:
                0 2px 8px
                rgba(30, 50, 80, .035);

            overflow: hidden;
        }

        .bottom-card-header {
            min-height: 43px;

            display: flex;

            align-items: center;

            padding: 0 13px;

            border-bottom: 1px solid #eef1f5;
        }

        .bottom-card-title {
            font-size: 11px;

            font-weight: 800;

            color: #273243;
        }

        .count-badge {
            margin-left: 9px;

            padding: 3px 7px;

            background: #fff0f0;

            color: #d47777;

            border-radius: 4px;

            font-size: 8px;

            font-weight: 800;
        }

        /* =====================================================
           PENDING LIST
        ===================================================== */

        .pending-list,
        .assigned-list {
            margin: 0;

            padding: 0 13px;

            list-style: none;
        }

        .pending-item,
        .assigned-item {
            min-height: 52px;

            display: flex;

            align-items: center;

            gap: 9px;

            border-bottom: 1px solid #f0f2f5;
        }

        .pending-item:last-child,
        .assigned-item:last-child {
            border-bottom: none;
        }

        .item-icon {
            width: 25px;
            height: 25px;

            flex: 0 0 25px;

            border-radius: 4px;

            background: #edf6ff;

            color: #4286c1;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 12px;
        }

        .item-icon.green {
            background: #eaf8ef;
            color: #3c9f68;
        }

        .item-icon.purple {
            background: #f1ebff;
            color: #7853bc;
        }

        .item-main {
            flex: 1;

            min-width: 0;
        }

        .item-title {
            color: #303a4a;

            font-size: 8.5px;

            font-weight: 600;

            line-height: 1.3;
        }

        .item-meta {
            color: #858f9d;

            font-size: 8px;

            margin-top: 3px;
        }

        .assign-now {
            flex: 0 0 auto;

            padding: 6px 9px;

            background: #edf6fd;

            border: 1px solid #d8e7f4;

            border-radius: 4px;

            color: #3278ae;

            font-size: 8px;

            font-weight: 700;

            cursor: pointer;
        }

        .assign-now:hover {
            background: #e1f0fb;
        }

        /* =====================================================
           BOTTOM CARD FOOTER
        ===================================================== */

        .card-footer {
            height: 35px;

            padding: 0 13px;

            display: flex;

            align-items: center;

            justify-content: space-between;
        }

        .card-footer a {
            color: #317db6;

            text-decoration: none;

            font-size: 8.5px;

            font-weight: 700;
        }

        .card-footer a:hover {
            text-decoration: underline;
        }

        .footer-arrow {
            color: #317db6;

            font-size: 15px;
        }

        /* =====================================================
           SIDEBAR COLLAPSE
        ===================================================== */

        .sidebar-hidden .sidebar {
            transform: translateX(-100%);
        }

        .sidebar-hidden .main {
            margin-left: 0;
        }

        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 1100px) {

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .proposal-header {
                flex-direction: column;
                align-items: flex-start;

                padding: 12px;

                gap: 10px;
            }

            .filters {
                width: 100%;
                flex-wrap: wrap;
            }

            .bottom-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 800px) {

            .main {
                margin-left: 0;
            }

            .content {
                padding: 0 15px 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .filters {
                width: 100%;
            }

            .filter-select {
                flex: 1;
                min-width: 100px;
            }

            .search-box {
                width: 100%;
            }

            .assignment-topbar {
                padding: 0 12px;
            }
        }

        @media (max-width: 500px) {

            .admin-details {
                display: none;
            }

            .stats-grid {
                gap: 8px;
            }

            .stat-card {
                min-height: 70px;
            }

            .stat-icon {
                width: 38px;
                height: 38px;
                flex-basis: 38px;
                font-size: 17px;
            }

            .page-title {
                font-size: 17px;
            }

            .page-description {
                font-size: 9px;
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

    <div class="assignment-topbar">

        <button
            class="assignment-menu"
            onclick="toggleSidebar()"
            title="Toggle Sidebar">
            ☰
        </button>

        <div class="assignment-top-space"></div>

        <div class="notification">
            ♧
            <span class="notification-badge">3</span>
        </div>

        <div class="admin-profile">

            <img
                src="logo.png"
                class="admin-avatar"
                alt="Admin">

            <div class="admin-details">

                <div class="admin-name">
                    CRAD Admin
                </div>

                <div class="admin-role">
                    Administrator
                </div>

            </div>

        </div>

    </div>


    <section class="content">


        <!-- =================================================
             PAGE HEADER
        ================================================== -->

        <div class="page-header">

            <h1 class="page-title">
                Adviser & Panel Assignment System
            </h1>

            <p class="page-description">
                Manage and assign advisers and panel members to student research proposals.
            </p>

        </div>


        <!-- =================================================
             STATISTICS
        ================================================== -->

        <div class="stats-grid">


            <!-- Active Advisers -->

            <div class="stat-card">

                <div class="stat-icon purple">
                    ♧
                </div>

                <div class="stat-content">

                    <div class="stat-number">
                        48
                    </div>

                    <div class="stat-label">
                        Active Advisers
                    </div>

                    <div class="stat-sub">
                        12 available
                    </div>

                </div>

            </div>


            <!-- Panel Members -->

            <div class="stat-card">

                <div class="stat-icon green">
                    ♧
                </div>

                <div class="stat-content">

                    <div class="stat-number">
                        36
                    </div>

                    <div class="stat-label">
                        Panel Members
                    </div>

                    <div class="stat-sub">
                        8 available
                    </div>

                </div>

            </div>


            <!-- Assigned Proposals -->

            <div class="stat-card">

                <div class="stat-icon yellow">
                    ▣
                </div>

                <div class="stat-content">

                    <div class="stat-number">
                        56
                    </div>

                    <div class="stat-label">
                        Assigned Proposals
                    </div>

                    <div class="stat-sub">
                        This semester
                    </div>

                </div>

            </div>


            <!-- Completed Defenses -->

            <div class="stat-card">

                <div class="stat-icon blue">
                    ✓
                </div>

                <div class="stat-content">

                    <div class="stat-number">
                        14
                    </div>

                    <div class="stat-label">
                        Completed Defenses
                    </div>

                    <div class="stat-sub">
                        This semester
                    </div>

                </div>

            </div>

        </div>


        <!-- =================================================
             RESEARCH PROPOSALS
        ================================================== -->

        <div class="proposal-card">


            <div class="proposal-header">

                <div class="proposal-title">
                    Research Proposals
                </div>


                <div class="filters">

                    <select class="filter-select">
                        <option>All Status</option>
                        <option>For Assignment</option>
                        <option>For Review</option>
                        <option>Scheduled</option>
                        <option>Completed</option>
                    </select>


                    <select class="filter-select">

                        <option>All Programs</option>
                        <option>BSIT</option>
                        <option>BSIS</option>
                        <option>BSCS</option>

                    </select>


                    <select class="filter-select">

                        <option>All Year Level</option>
                        <option>1st Year</option>
                        <option>2nd Year</option>
                        <option>3rd Year</option>
                        <option>4th Year</option>

                    </select>


                    <input
                        type="search"
                        class="search-box"
                        placeholder="Search proposal...">

                </div>

            </div>


            <!-- TABLE -->

            <div class="table-wrapper">

                <table class="proposals">

                    <thead>

                        <tr>

                            <th class="col-number">
                                #
                            </th>

                            <th class="col-title">
                                Proposal Title
                            </th>

                            <th class="col-researchers">
                                Researchers
                            </th>

                            <th class="col-program">
                                Program
                            </th>

                            <th class="col-status">
                                Status
                            </th>

                            <th class="col-adviser">
                                Adviser
                            </th>

                            <th class="col-panel">
                                Panel
                            </th>

                            <th class="col-actions">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <!-- ROW 1 -->

                        <tr>

                            <td class="col-number">
                                1
                            </td>

                            <td>
                                <div class="proposal-name">
                                    Mobile Application for Campus Event Management System
                                </div>
                            </td>

                            <td>

                                <div class="researcher-name">
                                    Juan Dela Cruz
                                </div>

                                <div class="researcher-name">
                                    Maria Santos
                                </div>

                                <div class="researcher-more">
                                    And 2 more
                                </div>

                            </td>

                            <td class="program-text">
                                BSIT
                            </td>

                            <td>
                                <span class="badge warning">
                                    For Assignment
                                </span>
                            </td>

                            <td class="muted">
                                —
                            </td>

                            <td class="muted">
                                —
                            </td>

                            <td>

                                <div class="action-area">

                                    <button class="action-btn">
                                        Assign
                                    </button>

                                    <button class="more-btn">
                                        ⋮
                                    </button>

                                </div>

                            </td>

                        </tr>


                        <!-- ROW 2 -->

                        <tr>

                            <td class="col-number">
                                2
                            </td>

                            <td>
                                <div class="proposal-name">
                                    IoT-Based Smart Attendance System
                                </div>
                            </td>

                            <td>

                                <div class="researcher-name">
                                    Anna Reyes
                                </div>

                                <div class="researcher-name">
                                    Mark Dizon
                                </div>

                                <div class="researcher-more">
                                    And 1 more
                                </div>

                            </td>

                            <td class="program-text">
                                BSIT
                            </td>

                            <td>
                                <span class="badge warning">
                                    For Assignment
                                </span>
                            </td>

                            <td class="muted">
                                —
                            </td>

                            <td class="muted">
                                —
                            </td>

                            <td>

                                <div class="action-area">

                                    <button class="action-btn">
                                        Assign
                                    </button>

                                    <button class="more-btn">
                                        ⋮
                                    </button>

                                </div>

                            </td>

                        </tr>


                        <!-- ROW 3 -->

                        <tr>

                            <td class="col-number">
                                3
                            </td>

                            <td>
                                <div class="proposal-name">
                                    E-Commerce Website for Local Products
                                </div>
                            </td>

                            <td>

                                <div class="researcher-name">
                                    John Villanueva
                                </div>

                                <div class="researcher-name">
                                    Rhea Garcia
                                </div>

                                <div class="researcher-more">
                                    And 2 more
                                </div>

                            </td>

                            <td class="program-text">
                                BSIT
                            </td>

                            <td>
                                <span class="badge review">
                                    For Review
                                </span>
                            </td>

                            <td>
                                Dr. Maria Santos
                            </td>

                            <td class="muted">
                                —
                            </td>

                            <td>

                                <div class="action-area">

                                    <button class="action-btn">
                                        Manage
                                    </button>

                                    <button class="more-btn">
                                        ⋮
                                    </button>

                                </div>

                            </td>

                        </tr>


                        <!-- ROW 4 -->

                        <tr>

                            <td class="col-number">
                                4
                            </td>

                            <td>
                                <div class="proposal-name">
                                    Library Management System with QR Tracking
                                </div>
                            </td>

                            <td>

                                <div class="researcher-name">
                                    Kyle Tan
                                </div>

                                <div class="researcher-name">
                                    Lorraine Pobre
                                </div>

                            </td>

                            <td class="program-text">
                                BSIS
                            </td>

                            <td>
                                <span class="badge scheduled">
                                    Scheduled
                                </span>
                            </td>

                            <td>
                                Prof. Carlo Ramirez
                            </td>

                            <td>
                                3 members
                            </td>

                            <td>

                                <div class="action-area">

                                    <button class="action-btn">
                                        View
                                    </button>

                                    <button class="more-btn">
                                        ⋮
                                    </button>

                                </div>

                            </td>

                        </tr>


                        <!-- ROW 5 -->

                        <tr>

                            <td class="col-number">
                                5
                            </td>

                            <td>
                                <div class="proposal-name">
                                    AI-Powered Chatbot for Student Support
                                </div>
                            </td>

                            <td>

                                <div class="researcher-name">
                                    Patricia Gomez
                                </div>

                                <div class="researcher-name">
                                    Miguel Lim
                                </div>

                                <div class="researcher-more">
                                    And 1 more
                                </div>

                            </td>

                            <td class="program-text">
                                BSCS
                            </td>

                            <td>
                                <span class="badge success">
                                    Completed
                                </span>
                            </td>

                            <td>
                                Dr. Ana Reyes
                            </td>

                            <td>
                                4 members
                            </td>

                            <td>

                                <div class="action-area">

                                    <button class="action-btn">
                                        View
                                    </button>

                                    <button class="more-btn">
                                        ⋮
                                    </button>

                                </div>

                            </td>

                        </tr>


                    </tbody>

                </table>

            </div>

        </div>


        <!-- =================================================
             BOTTOM SECTION
        ================================================== -->

        <div class="bottom-grid">


            <!-- =================================================
                 PENDING ASSIGNMENTS
            ================================================== -->

            <div class="bottom-card">

                <div class="bottom-card-header">

                    <div class="bottom-card-title">
                        Pending Assignments
                    </div>

                    <span class="count-badge">
                        2
                    </span>

                </div>


                <ul class="pending-list">


                    <li class="pending-item">

                        <div class="item-icon">
                            ▧
                        </div>

                        <div class="item-main">

                            <div class="item-title">
                                Mobile Application for Campus Event Management System
                            </div>

                            <div class="item-meta">
                                BSIT · Submitted: May 18, 2024
                            </div>

                        </div>

                        <button class="assign-now">
                            Assign Now
                        </button>

                    </li>


                    <li class="pending-item">

                        <div class="item-icon">
                            ▧
                        </div>

                        <div class="item-main">

                            <div class="item-title">
                                IoT-Based Smart Attendance System
                            </div>

                            <div class="item-meta">
                                BSIT · Submitted: May 17, 2024
                            </div>

                        </div>

                        <button class="assign-now">
                            Assign Now
                        </button>

                    </li>


                </ul>


                <div class="card-footer">

                    <a href="#">
                        View all pending assignments
                    </a>

                    <span class="footer-arrow">
                        →
                    </span>

                </div>

            </div>


            <!-- =================================================
                 RECENTLY ASSIGNED
            ================================================== -->

            <div class="bottom-card">

                <div class="bottom-card-header">

                    <div class="bottom-card-title">
                        Recently Assigned
                    </div>

                </div>


                <ul class="assigned-list">


                    <li class="assigned-item">

                        <div class="item-icon green">
                            ♧
                        </div>

                        <div class="item-main">

                            <div class="item-title">
                                E-Commerce Website for Local Products
                            </div>

                            <div class="item-meta">
                                Adviser: Dr. Maria Santos · Panel: 3 members · May 19, 2024
                            </div>

                        </div>

                    </li>


                    <li class="assigned-item">

                        <div class="item-icon purple">
                            ▣
                        </div>

                        <div class="item-main">

                            <div class="item-title">
                                Library Management System with QR Tracking
                            </div>

                            <div class="item-meta">
                                Adviser: Prof. Carlo Ramirez · Panel: 3 members · May 18, 2024
                            </div>

                        </div>

                    </li>


                </ul>


                <div class="card-footer">

                    <a href="#">
                        View all assignments
                    </a>

                    <span class="footer-arrow">
                        →
                    </span>

                </div>

            </div>


        </div>


    </section>

</main>


<script>

function toggleSidebar() {

    document.body.classList.toggle('sidebar-hidden');

}

</script>

</body>
</html>