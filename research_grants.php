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

    <title>Research Grants & Funding Assistance</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="logo.png">

    <link rel="stylesheet" href="assets/style.css">

    <style>

        /* =========================================================
           RESEARCH GRANTS DASHBOARD
        ========================================================= */

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f7fa;
            font-family: Arial, Helvetica, sans-serif;
            color: #202938;
        }

        .main {
            margin-left: 250px;
            min-height: 100vh;
            transition: .3s ease;
        }

        .content {
            padding: 24px 28px 40px;
            max-width: 1650px;
            margin: auto;
        }


        /* =========================================================
           TOP HEADER
        ========================================================= */

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            gap: 20px;
        }

        .title-area {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .title-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: linear-gradient(135deg, #e5f1ff, #d4e8ff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 23px;
            color: #1769aa;
            border: 1px solid #d0e4fa;
        }

        .page-title h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #202b3c;
            letter-spacing: -.3px;
        }

        .page-title p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #7a8492;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .notification {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: white;
            border: 1px solid #e2e7ed;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 17px;
            position: relative;
        }

        .notification-dot {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #ef4444;
            border: 2px solid white;
        }

        .user-mini {
            display: flex;
            align-items: center;
            gap: 9px;
            padding-left: 8px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dbeafe, #c7d2fe);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #315a96;
            font-weight: 700;
            font-size: 13px;
        }

        .user-mini-text strong {
            display: block;
            font-size: 12px;
            color: #273244;
        }

        .user-mini-text span {
            display: block;
            font-size: 10px;
            color: #89929e;
            margin-top: 2px;
        }


        /* =========================================================
           ACTION BUTTONS
        ========================================================= */

        .header-buttons {
            display: flex;
            gap: 9px;
        }

        .btn {
            border: none;
            border-radius: 7px;
            padding: 10px 14px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
        }

        .btn-light {
            background: #ffffff;
            color: #34618f;
            border: 1px solid #dce4ec;
        }

        .btn-light:hover {
            background: #f2f7fc;
        }

        .btn-primary {
            background: #2f80c9;
            color: white;
            box-shadow: 0 3px 8px rgba(47, 128, 201, .18);
        }

        .btn-primary:hover {
            background: #246fae;
            transform: translateY(-1px);
        }


        /* =========================================================
           SUMMARY CARDS
        ========================================================= */

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 18px;
        }

        .summary-card {
            background: #ffffff;
            border: 1px solid #e6eaee;
            border-radius: 10px;
            min-height: 96px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 13px;
            box-shadow: 0 2px 8px rgba(20, 38, 63, .035);
            transition: .2s;
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 18px rgba(20, 38, 63, .08);
        }

        .summary-icon {
            width: 50px;
            height: 50px;
            min-width: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .icon-blue {
            background: #e7f1ff;
            color: #216eb4;
        }

        .icon-green {
            background: #e6f7ed;
            color: #29935c;
        }

        .icon-yellow {
            background: #fff5d8;
            color: #c18a15;
        }

        .icon-purple {
            background: #eee9ff;
            color: #6650b9;
        }

        .summary-info strong {
            display: block;
            font-size: 22px;
            color: #253044;
            line-height: 1;
            margin-bottom: 6px;
        }

        .summary-info span {
            display: block;
            font-size: 11px;
            color: #4c5665;
        }

        .summary-info small {
            display: block;
            color: #8b949e;
            font-size: 9px;
            margin-top: 4px;
        }


        /* =========================================================
           DASHBOARD GRID
        ========================================================= */

        .dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(310px, 1fr);
            gap: 16px;
            align-items: start;
        }

        .panel {
            background: white;
            border: 1px solid #e4e8ed;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(20, 38, 63, .035);
        }

        .panel-header {
            min-height: 55px;
            padding: 14px 16px;
            border-bottom: 1px solid #edf0f3;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .panel-title h2 {
            margin: 0;
            font-size: 14px;
            color: #273142;
        }

        .panel-title p {
            margin: 4px 0 0;
            font-size: 10px;
            color: #8a929d;
        }

        .view-link {
            font-size: 10px;
            color: #2874b7;
            text-decoration: none;
            font-weight: 700;
        }

        .view-link:hover {
            text-decoration: underline;
        }


        /* =========================================================
           AVAILABLE GRANTS TABLE
        ========================================================= */

        .grant-table-wrap {
            overflow-x: auto;
        }

        .grant-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        .grant-table th {
            text-align: left;
            background: #fafbfd;
            color: #727c89;
            font-size: 10px;
            font-weight: 700;
            padding: 11px 15px;
            border-bottom: 1px solid #e9edf1;
            white-space: nowrap;
        }

        .grant-table td {
            padding: 11px 15px;
            font-size: 11px;
            color: #3d4653;
            border-bottom: 1px solid #eef1f4;
            vertical-align: middle;
        }

        .grant-table tbody tr {
            transition: .15s;
        }

        .grant-table tbody tr:hover {
            background: #f8fbff;
        }

        .grant-table tbody tr:last-child td {
            border-bottom: none;
        }

        .grant-name {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 210px;
        }

        .grant-small-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #edf5ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .grant-name strong {
            display: block;
            font-size: 11px;
            color: #303a49;
            line-height: 1.3;
        }

        .grant-name span {
            display: block;
            font-size: 9px;
            color: #929aa5;
            margin-top: 2px;
        }

        .agency {
            color: #596371;
            font-size: 10px;
        }

        .amount {
            font-weight: 700;
            color: #27384e;
            white-space: nowrap;
        }

        .deadline {
            white-space: nowrap;
            color: #596371;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border-radius: 20px;
            padding: 5px 8px;
            font-size: 9px;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-open {
            color: #23804c;
            background: #e7f7ed;
        }

        .status-closing {
            color: #a16d08;
            background: #fff3d5;
        }

        .details-btn {
            background: white;
            border: 1px solid #cbdceb;
            color: #3273a9;
            padding: 5px 8px;
            border-radius: 5px;
            font-size: 9px;
            cursor: pointer;
            white-space: nowrap;
        }

        .details-btn:hover {
            background: #edf6ff;
        }


        /* =========================================================
           FUNDING OVERVIEW
        ========================================================= */

        .funding-overview {
            padding: 16px;
        }

        .overview-subtitle {
            font-size: 10px;
            color: #8a929d;
            margin-bottom: 12px;
        }

        .chart-area {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 5px 0 12px;
        }

        .donut {
            width: 145px;
            height: 145px;
            border-radius: 50%;
            background:
                conic-gradient(
                    #347fd2 0deg 83deg,
                    #f3ba28 83deg 221deg,
                    #32a66b 221deg 304deg,
                    #a9b2bd 304deg 360deg
                );
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .donut::after {
            content: "";
            position: absolute;
            width: 82px;
            height: 82px;
            border-radius: 50%;
            background: white;
        }

        .donut-center {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .donut-center strong {
            display: block;
            font-size: 22px;
            color: #273343;
        }

        .donut-center span {
            font-size: 9px;
            color: #89929c;
        }

        .legend {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 9px;
            margin: 2px 4px 15px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 10px;
            color: #5b6572;
        }

        .legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .dot-blue {
            background: #347fd2;
        }

        .dot-yellow {
            background: #f3ba28;
        }

        .dot-green {
            background: #32a66b;
        }

        .dot-gray {
            background: #a9b2bd;
        }

        .released-box {
            border-top: 1px solid #edf0f3;
            padding-top: 13px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .released-box span {
            display: block;
            font-size: 10px;
            color: #596371;
        }

        .released-box small {
            display: block;
            font-size: 9px;
            color: #9aa2ac;
            margin-top: 3px;
        }

        .released-amount {
            font-size: 17px;
            color: #319264;
            font-weight: 700;
        }


        /* =========================================================
           RECENT APPLICATIONS
        ========================================================= */

        .applications-panel {
            margin-top: 16px;
        }

        .application-table-wrap {
            overflow-x: auto;
        }

        .application-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        .application-table th {
            text-align: left;
            padding: 10px 14px;
            background: #fafbfd;
            color: #747e8a;
            font-size: 9px;
            border-bottom: 1px solid #e8edf1;
            white-space: nowrap;
        }

        .application-table td {
            padding: 11px 14px;
            font-size: 10px;
            color: #4c5664;
            border-bottom: 1px solid #edf0f3;
        }

        .application-table tbody tr:hover {
            background: #f9fbfd;
        }

        .application-table tbody tr:last-child td {
            border-bottom: none;
        }

        .project-name {
            font-weight: 700;
            color: #303a48;
        }

        .status-pill {
            display: inline-block;
            padding: 5px 8px;
            border-radius: 20px;
            font-size: 8px;
            font-weight: 700;
            white-space: nowrap;
        }

        .pending {
            background: #fff3d5;
            color: #a06b05;
        }

        .under-review {
            background: #e7f0ff;
            color: #376eae;
        }

        .approved {
            background: #e5f7ed;
            color: #25804d;
        }

        .released {
            background: #e5f7ed;
            color: #24804d;
        }


        /* =========================================================
           RECENT UPDATES
        ========================================================= */

        .updates-panel {
            margin-top: 16px;
        }

        .updates {
            padding: 15px 16px 8px;
        }

        .update-item {
            display: flex;
            position: relative;
            gap: 10px;
            padding-bottom: 16px;
        }

        .update-item:not(:last-child)::before {
            content: "";
            position: absolute;
            left: 4px;
            top: 11px;
            width: 1px;
            height: calc(100% - 3px);
            background: #e0e6eb;
        }

        .update-dot {
            width: 9px;
            height: 9px;
            min-width: 9px;
            border-radius: 50%;
            margin-top: 3px;
            position: relative;
            z-index: 2;
            border: 1px solid white;
            box-shadow: 0 0 0 1px #e1e6ea;
        }

        .update-green {
            background: #2eaa69;
        }

        .update-blue {
            background: #327fd1;
        }

        .update-yellow {
            background: #f0b529;
        }

        .update-red {
            background: #e35b55;
        }

        .update-content {
            flex: 1;
        }

        .update-content strong {
            display: block;
            color: #303a48;
            font-size: 10px;
            margin-bottom: 3px;
        }

        .update-content span {
            display: block;
            font-size: 9px;
            color: #6d7784;
            line-height: 1.35;
        }

        .update-content small {
            display: block;
            color: #9ba3ac;
            font-size: 8px;
            margin-top: 3px;
        }


        /* =========================================================
           FUNDING TRACKER
        ========================================================= */

        .tracker-panel {
            margin-top: 16px;
        }

        .tracker-content {
            padding: 4px 16px 12px;
        }

        .funding-item {
            padding: 12px 0;
            border-bottom: 1px solid #edf0f3;
        }

        .funding-item:last-child {
            border-bottom: none;
        }

        .funding-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 7px;
        }

        .funding-title strong {
            font-size: 10px;
            color: #374151;
        }

        .funding-title span {
            font-size: 8px;
            font-weight: 700;
            padding: 4px 7px;
            border-radius: 15px;
        }

        .funding-approved {
            background: #e7f7ed;
            color: #277f4d;
        }

        .funding-review {
            background: #fff3d7;
            color: #a36d09;
        }

        .funding-released {
            background: #e6f0ff;
            color: #3371ad;
        }

        .progress {
            height: 6px;
            background: #edf1f4;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            border-radius: 10px;
            background: linear-gradient(90deg, #347fc9, #58a4e8);
        }

        .funding-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
            font-size: 8px;
            color: #9199a3;
        }


        /* =========================================================
           INFORMATION BOX
        ========================================================= */

        .info-box {
            margin: 12px 16px 16px;
            padding: 12px;
            border-radius: 8px;
            background: #eef7ff;
            border: 1px solid #dceeff;
        }

        .info-box strong {
            display: block;
            font-size: 10px;
            color: #2c628f;
            margin-bottom: 4px;
        }

        .info-box p {
            margin: 0;
            font-size: 9px;
            color: #728294;
            line-height: 1.5;
        }


        /* =========================================================
           SEARCH FILTER
        ========================================================= */

        .filter-area {
            background: white;
            border: 1px solid #e4e8ed;
            border-radius: 10px;
            padding: 11px;
            margin-bottom: 16px;
            display: flex;
            gap: 9px;
            align-items: center;
            box-shadow: 0 2px 8px rgba(20, 38, 63, .025);
        }

        .search-container {
            position: relative;
            flex: 1;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa3ad;
            font-size: 12px;
        }

        #grantSearch {
            width: 100%;
            height: 36px;
            border: 1px solid #dfe5eb;
            border-radius: 7px;
            padding: 0 12px 0 32px;
            outline: none;
            font-size: 11px;
            color: #344054;
        }

        #grantSearch:focus {
            border-color: #7cb4e5;
            box-shadow: 0 0 0 3px rgba(47, 128, 201, .08);
        }

        .filter-select {
            height: 36px;
            min-width: 145px;
            border: 1px solid #dfe5eb;
            border-radius: 7px;
            padding: 0 10px;
            background: white;
            color: #596371;
            font-size: 10px;
            outline: none;
        }


        /* =========================================================
           EMPTY SEARCH MESSAGE
        ========================================================= */

        .no-results {
            display: none;
            padding: 35px;
            text-align: center;
            color: #8a929c;
            font-size: 11px;
        }

        .no-results-icon {
            font-size: 25px;
            margin-bottom: 7px;
        }


        /* =========================================================
           MODAL
        ========================================================= */

        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(19, 32, 50, .45);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .grant-modal {
            width: 100%;
            max-width: 480px;
            background: white;
            border-radius: 13px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,.18);
            animation: modalIn .2s ease;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 17px 19px;
            background: linear-gradient(135deg, #2e7fc4, #2563a0);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 15px;
        }

        .close-modal {
            border: none;
            background: rgba(255,255,255,.15);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 17px;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-body h3 {
            margin: 0 0 8px;
            color: #293548;
            font-size: 16px;
        }

        .modal-description {
            color: #737d89;
            font-size: 11px;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        .modal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 9px;
        }

        .modal-detail {
            background: #f7f9fb;
            border-radius: 7px;
            padding: 11px;
        }

        .modal-detail small {
            display: block;
            font-size: 8px;
            color: #929aa4;
            margin-bottom: 4px;
        }

        .modal-detail strong {
            font-size: 11px;
            color: #354052;
        }

        .modal-footer {
            padding: 13px 19px;
            border-top: 1px solid #edf0f3;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media(max-width: 1200px) {

            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

        }

        @media(max-width: 900px) {

            .main {
                margin-left: 0;
            }

            .content {
                padding: 20px;
            }

            .top-header {
                align-items: flex-start;
            }

            .header-buttons {
                display: none;
            }

        }

        @media(max-width: 650px) {

            .content {
                padding: 15px;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .top-header {
                flex-direction: column;
            }

            .top-actions {
                width: 100%;
                justify-content: flex-end;
            }

            .filter-area {
                flex-wrap: wrap;
            }

            .search-container {
                flex-basis: 100%;
            }

            .filter-select {
                flex: 1;
                min-width: 120px;
            }

            .page-title h1 {
                font-size: 20px;
            }

        }

    </style>
</head>


<body>

<?php include 'includes/sidebar.php'; ?>


<main class="main">

<section class="content">


    <!-- =========================================================
         TOP HEADER
    ========================================================== -->

    <div class="top-header">

        <div class="title-area">

            <div class="title-icon">
                📑
            </div>

            <div class="page-title">

                <h1>Research Grants & Funding Assistance</h1>

                <p>
                    Manage and track research grants and funding applications.
                </p>

            </div>

        </div>


        <div class="top-actions">

            <div class="notification" title="Notifications">
                🔔
                <span class="notification-dot"></span>
            </div>

            <div class="user-mini">

                <div class="user-avatar">
                    <?php
                    if (isset($_SESSION['username'])) {
                        echo strtoupper(substr($_SESSION['username'], 0, 2));
                    } else {
                        echo "CR";
                    }
                    ?>
                </div>

                <div class="user-mini-text">
                    <strong>CRAD Office</strong>
                    <span>Research Administrator</span>
                </div>

            </div>

        </div>

    </div>



    <!-- =========================================================
         SUMMARY CARDS
    ========================================================== -->

    <div class="summary-grid">


        <div class="summary-card">

            <div class="summary-icon icon-blue">
                📋
            </div>

            <div class="summary-info">

                <strong>12</strong>

                <span>Available Grants</span>

                <small>Currently Open</small>

            </div>

        </div>



        <div class="summary-card">

            <div class="summary-icon icon-green">
                📁
            </div>

            <div class="summary-info">

                <strong>18</strong>

                <span>My Applications</span>

                <small>All Time</small>

            </div>

        </div>



        <div class="summary-card">

            <div class="summary-icon icon-yellow">
                🕐
            </div>

            <div class="summary-info">

                <strong>5</strong>

                <span>Pending</span>

                <small>Under Review</small>

            </div>

        </div>



        <div class="summary-card">

            <div class="summary-icon icon-purple">
                ☑
            </div>

            <div class="summary-info">

                <strong>3</strong>

                <span>Approved</span>

                <small>This Year</small>

            </div>

        </div>


    </div>



    <!-- =========================================================
         SEARCH / FILTER
    ========================================================== -->

    <div class="filter-area">

        <div class="search-container">

            <span class="search-icon">🔍</span>

            <input
                type="search"
                id="grantSearch"
                placeholder="Search grants, funding programs..."
            >

        </div>


        <select id="categoryFilter" class="filter-select">

            <option value="">All Categories</option>

            <option value="institutional">
                Institutional
            </option>

            <option value="government">
                Government
            </option>

            <option value="external">
                External
            </option>

            <option value="international">
                International
            </option>

        </select>


        <select id="statusFilter" class="filter-select">

            <option value="">All Status</option>

            <option value="open">
                Open
            </option>

            <option value="closing">
                Closing Soon
            </option>

        </select>

    </div>



    <!-- =========================================================
         MAIN DASHBOARD
    ========================================================== -->

    <div class="dashboard-grid">


        <!-- =====================================================
             AVAILABLE GRANTS
        ====================================================== -->

        <div class="panel">

            <div class="panel-header">

                <div class="panel-title">

                    <h2>
                        Available Grants / Funding
                    </h2>

                    <p>
                        Research funding opportunities currently available
                    </p>

                </div>

                <a href="#" class="view-link">
                    View All Grants
                </a>

            </div>


            <div class="grant-table-wrap">

                <table class="grant-table">

                    <thead>

                        <tr>

                            <th>
                                Grant Title
                            </th>

                            <th>
                                Funding Agency
                            </th>

                            <th>
                                Amount (₱)
                            </th>

                            <th>
                                Deadline
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody id="grantTableBody">


                        <!-- GRANT 1 -->

                        <tr class="grant-row"
                            data-category="government"
                            data-status="open">

                            <td>

                                <div class="grant-name">

                                    <div class="grant-small-icon">
                                        🏛️
                                    </div>

                                    <div>

                                        <strong>
                                            DOST Undergraduate Research Grant
                                        </strong>

                                        <span>
                                            DOST-URGP
                                        </span>

                                    </div>

                                </div>

                            </td>

                            <td class="agency">
                                DOST
                            </td>

                            <td class="amount">
                                150,000.00
                            </td>

                            <td class="deadline">
                                Jun 30, 2025
                            </td>

                            <td>

                                <span class="status-badge status-open">
                                    ● Open
                                </span>

                            </td>

                            <td>

                                <button
                                    class="details-btn"
                                    onclick="showGrant(
                                        'DOST Undergraduate Research Grant',
                                        'DOST',
                                        '₱150,000.00',
                                        'June 30, 2025',
                                        'Government'
                                    )">

                                    View Details

                                </button>

                            </td>

                        </tr>



                        <!-- GRANT 2 -->

                        <tr class="grant-row"
                            data-category="government"
                            data-status="open">

                            <td>

                                <div class="grant-name">

                                    <div class="grant-small-icon">
                                        🎓
                                    </div>

                                    <div>

                                        <strong>
                                            CHED Research Grant
                                        </strong>

                                        <span>
                                            Faculty & Institutional Research
                                        </span>

                                    </div>

                                </div>

                            </td>

                            <td class="agency">
                                CHED
                            </td>

                            <td class="amount">
                                200,000.00
                            </td>

                            <td class="deadline">
                                Jul 15, 2025
                            </td>

                            <td>

                                <span class="status-badge status-open">
                                    ● Open
                                </span>

                            </td>

                            <td>

                                <button
                                    class="details-btn"
                                    onclick="showGrant(
                                        'CHED Research Grant',
                                        'CHED',
                                        '₱200,000.00',
                                        'July 15, 2025',
                                        'Government'
                                    )">

                                    View Details

                                </button>

                            </td>

                        </tr>



                        <!-- GRANT 3 -->

                        <tr class="grant-row"
                            data-category="government"
                            data-status="closing">

                            <td>

                                <div class="grant-name">

                                    <div class="grant-small-icon">
                                        🔬
                                    </div>

                                    <div>

                                        <strong>
                                            NRCP Research Grant
                                        </strong>

                                        <span>
                                            National Research Council
                                        </span>

                                    </div>

                                </div>

                            </td>

                            <td class="agency">
                                NRCP
                            </td>

                            <td class="amount">
                                100,000.00
                            </td>

                            <td class="deadline">
                                Aug 10, 2025
                            </td>

                            <td>

                                <span class="status-badge status-closing">
                                    ● Closing Soon
                                </span>

                            </td>

                            <td>

                                <button
                                    class="details-btn"
                                    onclick="showGrant(
                                        'NRCP Research Grant',
                                        'NRCP',
                                        '₱100,000.00',
                                        'August 10, 2025',
                                        'Government'
                                    )">

                                    View Details

                                </button>

                            </td>

                        </tr>



                        <!-- GRANT 4 -->

                        <tr class="grant-row"
                            data-category="institutional"
                            data-status="open">

                            <td>

                                <div class="grant-name">

                                    <div class="grant-small-icon">
                                        🏫
                                    </div>

                                    <div>

                                        <strong>
                                            Local University Research Fund
                                        </strong>

                                        <span>
                                            Institutional Funding
                                        </span>

                                    </div>

                                </div>

                            </td>

                            <td class="agency">
                                University
                            </td>

                            <td class="amount">
                                75,000.00
                            </td>

                            <td class="deadline">
                                May 31, 2025
                            </td>

                            <td>

                                <span class="status-badge status-open">
                                    ● Open
                                </span>

                            </td>

                            <td>

                                <button
                                    class="details-btn"
                                    onclick="showGrant(
                                        'Local University Research Fund',
                                        'University',
                                        '₱75,000.00',
                                        'May 31, 2025',
                                        'Institutional'
                                    )">

                                    View Details

                                </button>

                            </td>

                        </tr>



                        <!-- GRANT 5 -->

                        <tr class="grant-row"
                            data-category="external"
                            data-status="open">

                            <td>

                                <div class="grant-name">

                                    <div class="grant-small-icon">
                                        🤝
                                    </div>

                                    <div>

                                        <strong>
                                            Industry-Academe Collaboration Grant
                                        </strong>

                                        <span>
                                            Research Partnership Program
                                        </span>

                                    </div>

                                </div>

                            </td>

                            <td class="agency">
                                DOST-PCIEERD
                            </td>

                            <td class="amount">
                                250,000.00
                            </td>

                            <td class="deadline">
                                Jul 31, 2025
                            </td>

                            <td>

                                <span class="status-badge status-open">
                                    ● Open
                                </span>

                            </td>

                            <td>

                                <button
                                    class="details-btn"
                                    onclick="showGrant(
                                        'Industry-Academe Collaboration Grant',
                                        'DOST-PCIEERD',
                                        '₱250,000.00',
                                        'July 31, 2025',
                                        'External'
                                    )">

                                    View Details

                                </button>

                            </td>

                        </tr>


                    </tbody>

                </table>


                <div class="no-results" id="noResults">

                    <div class="no-results-icon">
                        🔎
                    </div>

                    No grants found matching your search.

                </div>

            </div>

        </div>



        <!-- =====================================================
             RIGHT COLUMN
        ====================================================== -->

        <div>


            <!-- FUNDING OVERVIEW -->

            <div class="panel">

                <div class="panel-header">

                    <div class="panel-title">

                        <h2>
                            Funding Overview
                        </h2>

                        <p>
                            Summary of funding applications
                        </p>

                    </div>

                </div>


                <div class="funding-overview">

                    <div class="overview-subtitle">
                        Application status distribution
                    </div>


                    <div class="chart-area">

                        <div class="donut">

                            <div class="donut-center">

                                <strong>
                                    13
                                </strong>

                                <span>
                                    Total
                                </span>

                            </div>

                        </div>

                    </div>


                    <div class="legend">

                        <div class="legend-item">

                            <span class="legend-dot dot-blue"></span>

                            Approved

                        </div>


                        <div class="legend-item">

                            <span class="legend-dot dot-yellow"></span>

                            Pending

                        </div>


                        <div class="legend-item">

                            <span class="legend-dot dot-green"></span>

                            Released

                        </div>


                        <div class="legend-item">

                            <span class="legend-dot dot-gray"></span>

                            Rejected

                        </div>

                    </div>


                    <div class="released-box">

                        <div>

                            <span>
                                Total Released Amount
                            </span>

                            <small>
                                This Year
                            </small>

                        </div>

                        <div class="released-amount">
                            ₱425,000
                        </div>

                    </div>

                </div>

            </div>



            <!-- RECENT UPDATES -->

            <div class="panel updates-panel">

                <div class="panel-header">

                    <div class="panel-title">

                        <h2>
                            Recent Updates
                        </h2>

                        <p>
                            Latest funding activities
                        </p>

                    </div>

                </div>


                <div class="updates">


                    <div class="update-item">

                        <div class="update-dot update-green"></div>

                        <div class="update-content">

                            <strong>
                                Funding Released
                            </strong>

                            <span>
                                Mobile Health Monitoring System
                            </span>

                            <small>
                                May 20, 2025 • ₱100,000 released
                            </small>

                        </div>

                    </div>



                    <div class="update-item">

                        <div class="update-dot update-blue"></div>

                        <div class="update-content">

                            <strong>
                                Application Under Review
                            </strong>

                            <span>
                                AI-Based Attendance System
                            </span>

                            <small>
                                May 15, 2025 • Under evaluation
                            </small>

                        </div>

                    </div>



                    <div class="update-item">

                        <div class="update-dot update-yellow"></div>

                        <div class="update-content">

                            <strong>
                                Application Submitted
                            </strong>

                            <span>
                                Smart Library Management System
                            </span>

                            <small>
                                Apr 28, 2025 • Successfully submitted
                            </small>

                        </div>

                    </div>



                    <div class="update-item">

                        <div class="update-dot update-red"></div>

                        <div class="update-content">

                            <strong>
                                Application Rejected
                            </strong>

                            <span>
                                Campus Security Research
                            </span>

                            <small>
                                Apr 18, 2025 • Review completed
                            </small>

                        </div>

                    </div>


                </div>

            </div>


        </div>

    </div>



    <!-- =========================================================
         MY FUNDING APPLICATIONS
    ========================================================== -->

    <div class="panel applications-panel">

        <div class="panel-header">

            <div class="panel-title">

                <h2>
                    My Funding Applications
                </h2>

                <p>
                    Overview of your submitted research funding applications
                </p>

            </div>

            <a href="#" class="view-link">
                View All Applications
            </a>

        </div>


        <div class="application-table-wrap">

            <table class="application-table">

                <thead>

                    <tr>

                        <th>
                            Application Title
                        </th>

                        <th>
                            Grant
                        </th>

                        <th>
                            Date Applied
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Amount (₱)
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <tr>

                        <td class="project-name">
                            AI-Based Attendance System
                        </td>

                        <td>
                            DOST-URGP
                        </td>

                        <td>
                            May 10, 2025
                        </td>

                        <td>

                            <span class="status-pill pending">
                                Pending
                            </span>

                        </td>

                        <td>
                            150,000.00
                        </td>

                        <td>

                            <button class="details-btn">
                                View
                            </button>

                        </td>

                    </tr>



                    <tr>

                        <td class="project-name">
                            Smart Library Management System
                        </td>

                        <td>
                            CHED Research Grant
                        </td>

                        <td>
                            Apr 28, 2025
                        </td>

                        <td>

                            <span class="status-pill under-review">
                                Under Review
                            </span>

                        </td>

                        <td>
                            200,000.00
                        </td>

                        <td>

                            <button class="details-btn">
                                View
                            </button>

                        </td>

                    </tr>



                    <tr>

                        <td class="project-name">
                            E-Learning Platform
                        </td>

                        <td>
                            Local University Fund
                        </td>

                        <td>
                            Mar 15, 2025
                        </td>

                        <td>

                            <span class="status-pill approved">
                                Approved
                            </span>

                        </td>

                        <td>
                            75,000.00
                        </td>

                        <td>

                            <button class="details-btn">
                                View
                            </button>

                        </td>

                    </tr>



                    <tr>

                        <td class="project-name">
                            Mobile Health Monitoring System
                        </td>

                        <td>
                            NRCP Research Grant
                        </td>

                        <td>
                            Feb 20, 2025
                        </td>

                        <td>

                            <span class="status-pill released">
                                Released
                            </span>

                        </td>

                        <td>
                            100,000.00
                        </td>

                        <td>

                            <button class="details-btn">
                                View
                            </button>

                        </td>

                    </tr>


                </tbody>

            </table>

        </div>

    </div>



    <!-- =========================================================
         FUNDING TRACKER
    ========================================================== -->

    <div class="panel tracker-panel">

        <div class="panel-header">

            <div class="panel-title">

                <h2>
                    Funding Progress
                </h2>

                <p>
                    Track approved and released research funds
                </p>

            </div>

            <a href="#" class="view-link">
                View Tracker
            </a>

        </div>


        <div class="tracker-content">


            <div class="funding-item">

                <div class="funding-title">

                    <strong>
                        AI-Based Student Support System
                    </strong>

                    <span class="funding-approved">
                        Approved
                    </span>

                </div>

                <div class="progress">

                    <div
                        class="progress-bar"
                        style="width:80%;">
                    </div>

                </div>

                <div class="funding-meta">

                    <span>
                        Approved: ₱250,000
                    </span>

                    <span>
                        80%
                    </span>

                </div>

            </div>



            <div class="funding-item">

                <div class="funding-title">

                    <strong>
                        Smart Campus Research
                    </strong>

                    <span class="funding-review">
                        Under Review
                    </span>

                </div>

                <div class="progress">

                    <div
                        class="progress-bar"
                        style="width:55%;">
                    </div>

                </div>

                <div class="funding-meta">

                    <span>
                        Requested: ₱180,000
                    </span>

                    <span>
                        55%
                    </span>

                </div>

            </div>



            <div class="funding-item">

                <div class="funding-title">

                    <strong>
                        Community Development Study
                    </strong>

                    <span class="funding-released">
                        Released
                    </span>

                </div>

                <div class="progress">

                    <div
                        class="progress-bar"
                        style="width:100%;">
                    </div>

                </div>

                <div class="funding-meta">

                    <span>
                        Released: ₱120,000
                    </span>

                    <span>
                        100%
                    </span>

                </div>

            </div>


        </div>


        <div class="info-box">

            <strong>
                💡 Funding Assistance
            </strong>

            <p>
                Submit your research funding application before the
                deadline. Approved funds will be recorded and tracked
                until the complete amount is released.
            </p>

        </div>

    </div>


</section>

</main>



<!-- =========================================================
     GRANT DETAILS MODAL
========================================================== -->

<div class="modal-overlay" id="grantModal">

    <div class="grant-modal">

        <div class="modal-header">

            <h2>
                Grant Details
            </h2>

            <button
                class="close-modal"
                onclick="closeGrant()">
                ×
            </button>

        </div>


        <div class="modal-body">

            <h3 id="modalTitle">
                Grant Title
            </h3>

            <p class="modal-description">

                This funding opportunity provides financial assistance
                for qualified research projects. Applicants should review
                the eligibility requirements, required documents, and
                submission deadline before applying.

            </p>


            <div class="modal-grid">

                <div class="modal-detail">

                    <small>
                        Funding Agency
                    </small>

                    <strong id="modalAgency">
                        —
                    </strong>

                </div>


                <div class="modal-detail">

                    <small>
                        Maximum Funding
                    </small>

                    <strong id="modalAmount">
                        —
                    </strong>

                </div>


                <div class="modal-detail">

                    <small>
                        Application Deadline
                    </small>

                    <strong id="modalDeadline">
                        —
                    </strong>

                </div>


                <div class="modal-detail">

                    <small>
                        Category
                    </small>

                    <strong id="modalCategory">
                        —
                    </strong>

                </div>

            </div>

        </div>


        <div class="modal-footer">

            <button
                class="btn btn-light"
                onclick="closeGrant()">

                Close

            </button>

            <button
                class="btn btn-primary"
                onclick="applyGrant()">

                Apply for Funding

            </button>

        </div>

    </div>

</div>



<script>

    /* =========================================================
       SIDEBAR
    ========================================================== */

    function toggleSidebar() {

        document.body.classList.toggle('sidebar-hidden');

    }


    /* =========================================================
       GRANT SEARCH + FILTER
    ========================================================== */

    const searchInput =
        document.getElementById('grantSearch');

    const categoryFilter =
        document.getElementById('categoryFilter');

    const statusFilter =
        document.getElementById('statusFilter');

    const grantRows =
        document.querySelectorAll('.grant-row');

    const noResults =
        document.getElementById('noResults');


    function filterGrants() {

        const search =
            searchInput.value.toLowerCase().trim();

        const category =
            categoryFilter.value.toLowerCase();

        const status =
            statusFilter.value.toLowerCase();

        let visible = 0;


        grantRows.forEach(function(row) {

            const text =
                row.innerText.toLowerCase();

            const rowCategory =
                row.dataset.category.toLowerCase();

            const rowStatus =
                row.dataset.status.toLowerCase();


            const matchesSearch =
                text.includes(search);

            const matchesCategory =
                category === '' ||
                rowCategory === category;

            const matchesStatus =
                status === '' ||
                rowStatus === status;


            if (
                matchesSearch &&
                matchesCategory &&
                matchesStatus
            ) {

                row.style.display = '';

                visible++;

            } else {

                row.style.display = 'none';

            }

        });


        if (visible === 0) {

            noResults.style.display = 'block';

        } else {

            noResults.style.display = 'none';

        }

    }


    searchInput.addEventListener(
        'input',
        filterGrants
    );


    categoryFilter.addEventListener(
        'change',
        filterGrants
    );


    statusFilter.addEventListener(
        'change',
        filterGrants
    );


    /* =========================================================
       GRANT DETAILS MODAL
    ========================================================== */

    function showGrant(
        title,
        agency,
        amount,
        deadline,
        category
    ) {

        document.getElementById(
            'modalTitle'
        ).textContent = title;

        document.getElementById(
            'modalAgency'
        ).textContent = agency;

        document.getElementById(
            'modalAmount'
        ).textContent = amount;

        document.getElementById(
            'modalDeadline'
        ).textContent = deadline;

        document.getElementById(
            'modalCategory'
        ).textContent = category;


        document.getElementById(
            'grantModal'
        ).classList.add('active');

    }


    function closeGrant() {

        document.getElementById(
            'grantModal'
        ).classList.remove('active');

    }


    function applyGrant() {

        alert(
            'You can now proceed with the funding application for this grant.'
        );

        closeGrant();

    }


    /* =========================================================
       CLOSE MODAL WHEN CLICKING OUTSIDE
    ========================================================== */

    document.getElementById(
        'grantModal'
    ).addEventListener(
        'click',
        function(event) {

            if (event.target === this) {

                closeGrant();

            }

        }
    );


    /* =========================================================
       ESC KEY
    ========================================================== */

    document.addEventListener(
        'keydown',
        function(event) {

            if (event.key === 'Escape') {

                closeGrant();

            }

        }
    );


    /* =========================================================
       MY APPLICATIONS BUTTON
    ========================================================== */

    document.querySelectorAll(
        '.application-table .details-btn'
    ).forEach(function(button) {

        button.addEventListener(
            'click',
            function() {

                alert(
                    'Application details will be displayed here.'
                );

            }
        );

    });


    /* =========================================================
       HEADER BUTTON ACTIONS
    ========================================================== */

    document.querySelectorAll(
        '.view-link'
    ).forEach(function(link) {

        link.addEventListener(
            'click',
            function(event) {

                if (this.getAttribute('href') === '#') {

                    event.preventDefault();

                }

            }
        );

    });

</script>


</body>
</html>