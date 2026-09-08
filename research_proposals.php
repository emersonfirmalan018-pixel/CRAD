<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Research Proposals | CRAD</title>

    <link rel="stylesheet" href="assets/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>

        /* =========================================================
           CRAD RESEARCH PROPOSALS
           ========================================================= */

        :root {
            --primary: #2563eb;
            --primary-dark: #173b8f;
            --primary-light: #dbeafe;

            --secondary: #4f46e5;
            --secondary-light: #e0e7ff;

            --cyan: #06b6d4;
            --cyan-light: #cffafe;

            --purple: #8b5cf6;
            --purple-light: #ede9fe;

            --green: #10b981;
            --green-light: #d1fae5;

            --orange: #f59e0b;
            --orange-light: #fef3c7;

            --red: #ef4444;
            --red-light: #fee2e2;

            --text: #172554;
            --text-dark: #0f172a;
            --text-light: #64748b;

            --background: #eef5ff;
            --white: #ffffff;

            --border: #dbe7f7;

            --shadow:
                0 10px 30px rgba(37, 99, 235, 0.08);

            --shadow-hover:
                0 18px 45px rgba(37, 99, 235, 0.16);

            --radius: 18px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(37, 99, 235, 0.10),
                    transparent 35%
                ),
                linear-gradient(
                    135deg,
                    #f8fbff 0%,
                    #eef5ff 50%,
                    #eaf2ff 100%
                );

            font-family: 'Inter', sans-serif;
            color: var(--text);
        }

        /* =========================================================
           MAIN CONTENT
           ========================================================= */

        .main-content {
            padding: 30px;
            min-height: 100vh;
            transition: all .3s ease;
        }

        .content-wrapper {
            max-width: 1500px;
            margin: auto;
        }

        /* =========================================================
           DOCUMENTATION CARD
           ========================================================= */

        .documentation-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;

            padding: 24px 28px;
            margin-bottom: 25px;

            background:
                linear-gradient(
                    135deg,
                    #173b8f,
                    #2563eb 55%,
                    #06b6d4
                );

            color: white;
            border-radius: 22px;

            box-shadow:
                0 18px 40px rgba(37, 99, 235, .20);

            position: relative;
            overflow: hidden;
        }

        .documentation-card::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 50%;

            right: -80px;
            top: -100px;

            background: rgba(255,255,255,.10);
        }

        .doc-left {
            display: flex;
            align-items: center;
            gap: 18px;
            position: relative;
            z-index: 2;
        }

        .doc-icon {
            width: 58px;
            height: 58px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 16px;

            background: rgba(255,255,255,.16);

            font-size: 26px;
        }

        .doc-title {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .doc-description {
            font-size: 13px;
            opacity: .88;
        }

        .doc-button {
            position: relative;
            z-index: 2;

            padding: 11px 18px;
            border-radius: 10px;

            background: white;
            color: var(--primary);

            font-weight: 700;
            font-size: 13px;

            text-decoration: none;
            transition: .25s;
        }

        .doc-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,.15);
        }

        /* =========================================================
           HEADER
           ========================================================= */

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            margin: 0 0 8px;

            font-size: 32px;
            font-weight: 800;

            background:
                linear-gradient(
                    90deg,
                    #173b8f,
                    #2563eb,
                    #06b6d4
                );

            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .page-header p {
            margin: 0;
            color: var(--text-light);
            font-size: 14px;
        }

        /* =========================================================
           TABS
           ========================================================= */

        .tabs-container {
            background: rgba(255,255,255,.90);
            border: 1px solid var(--border);

            padding: 8px;

            border-radius: 16px;

            display: flex;
            gap: 7px;

            overflow-x: auto;

            box-shadow: var(--shadow);

            margin-bottom: 25px;
        }

        .tab-button {
            border: none;
            background: transparent;

            padding: 12px 18px;

            border-radius: 11px;

            color: var(--text-light);

            font-family: inherit;
            font-size: 13px;
            font-weight: 700;

            cursor: pointer;

            white-space: nowrap;

            transition: .25s;
        }

        .tab-button:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .tab-button.active {
            color: white;

            background:
                linear-gradient(
                    135deg,
                    var(--primary-dark),
                    var(--primary)
                );

            box-shadow:
                0 6px 15px rgba(37,99,235,.25);
        }

        /* =========================================================
           TAB CONTENT
           ========================================================= */

        .tab-content {
            display: none;
            animation: fadeIn .35s ease;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* =========================================================
           CARDS
           ========================================================= */

        .card {
            background: rgba(255,255,255,.96);

            border: 1px solid var(--border);

            border-radius: var(--radius);

            padding: 25px;

            box-shadow: var(--shadow);

            transition: .25s;
        }

        .card:hover {
            box-shadow: var(--shadow-hover);
        }

        .card-title {
            display: flex;
            align-items: center;
            gap: 10px;

            margin: 0 0 8px;

            color: var(--text-dark);

            font-size: 19px;
            font-weight: 800;
        }

        .card-subtitle {
            color: var(--text-light);
            font-size: 13px;
            margin-bottom: 20px;
        }

        /* =========================================================
           OVERVIEW
           ========================================================= */

        .overview-grid {
            display: grid;

            grid-template-columns:
                minmax(0, 1.3fr)
                minmax(300px, .7fr);

            gap: 22px;

            margin-bottom: 22px;
        }

        .overview-text {
            color: #475569;
            line-height: 1.8;
            font-size: 14px;
        }

        .highlight-box {
            margin-top: 20px;

            padding: 17px;

            border-left: 4px solid var(--primary);

            background:
                linear-gradient(
                    90deg,
                    #eff6ff,
                    #f8fbff
                );

            border-radius: 10px;
        }

        .highlight-box strong {
            color: var(--primary-dark);
        }

        /* =========================================================
           STATS
           ========================================================= */

        .stats-grid {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 15px;

            margin-bottom: 22px;
        }

        .stat-card {
            position: relative;
            overflow: hidden;

            background: white;

            border-radius: 16px;

            padding: 21px;

            border: 1px solid var(--border);

            box-shadow: var(--shadow);

            transition: .25s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        .stat-card::before {
            content: "";

            position: absolute;

            top: 0;
            left: 0;
            right: 0;

            height: 4px;

            background: var(--primary);
        }

        .stat-card:nth-child(2)::before {
            background: var(--orange);
        }

        .stat-card:nth-child(3)::before {
            background: var(--green);
        }

        .stat-card:nth-child(4)::before {
            background: var(--cyan);
        }

        .stat-icon {
            width: 43px;
            height: 43px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 12px;

            background: var(--primary-light);

            color: var(--primary);

            margin-bottom: 13px;

            font-size: 20px;
        }

        .stat-card:nth-child(2) .stat-icon {
            background: var(--orange-light);
            color: var(--orange);
        }

        .stat-card:nth-child(3) .stat-icon {
            background: var(--green-light);
            color: var(--green);
        }

        .stat-card:nth-child(4) .stat-icon {
            background: var(--cyan-light);
            color: var(--cyan);
        }

        .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-dark);
        }

        .stat-label {
            margin-top: 3px;
            font-size: 12px;
            color: var(--text-light);
        }

        /* =========================================================
           WORKFLOW
           ========================================================= */

        .workflow-grid {
            display: grid;

            grid-template-columns:
                repeat(5, 1fr);

            gap: 12px;

            margin-top: 20px;
        }

        .workflow-step {
            padding: 18px;

            border-radius: 14px;

            background: #f8fbff;

            border: 1px solid var(--border);

            position: relative;

            transition: .25s;
        }

        .workflow-step:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
        }

        .workflow-number {
            width: 32px;
            height: 32px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background:
                linear-gradient(
                    135deg,
                    var(--primary-dark),
                    var(--primary)
                );

            color: white;

            font-weight: 800;
            font-size: 13px;

            margin-bottom: 12px;
        }

        .workflow-step strong {
            display: block;
            font-size: 13px;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .workflow-step span {
            font-size: 11px;
            color: var(--text-light);
            line-height: 1.5;
        }

        /* =========================================================
           CTA
           ========================================================= */

        .cta-card {
            margin-top: 22px;

            padding: 28px;

            border-radius: 18px;

            background:
                linear-gradient(
                    135deg,
                    #172554,
                    #1e40af,
                    #0891b2
                );

            color: white;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 20px;

            box-shadow:
                0 15px 35px rgba(23,37,84,.20);
        }

        .cta-card h3 {
            margin: 0 0 7px;
            font-size: 20px;
        }

        .cta-card p {
            margin: 0;
            font-size: 13px;
            opacity: .85;
        }

        .btn-white {
            border: none;

            padding: 12px 20px;

            border-radius: 10px;

            background: white;
            color: var(--primary-dark);

            font-family: inherit;
            font-weight: 800;

            cursor: pointer;

            transition: .25s;
        }

        .btn-white:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,.15);
        }

        /* =========================================================
           RUBRIC
           ========================================================= */

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background:
                linear-gradient(
                    135deg,
                    #eff6ff,
                    #e0f2fe
                );

            color: var(--primary-dark);

            font-size: 12px;
            text-transform: uppercase;

            letter-spacing: .5px;

            padding: 15px;

            text-align: left;

            border-bottom: 2px solid var(--border);
        }

        td {
            padding: 16px 15px;

            font-size: 13px;

            color: #475569;

            border-bottom: 1px solid #edf2f7;
        }

        tr:hover td {
            background: #f8fbff;
        }

        .weight {
            font-weight: 800;
            color: var(--primary);
        }

        /* =========================================================
           SUBMISSION FORM
           ========================================================= */

        .form-grid {
            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            color: var(--text-dark);

            font-size: 12px;
            font-weight: 800;
        }

        input,
        textarea,
        select {
            width: 100%;

            border: 1px solid var(--border);

            background: #f8fbff;

            color: var(--text-dark);

            padding: 13px 14px;

            border-radius: 10px;

            font-family: inherit;
            font-size: 13px;

            outline: none;

            transition: .2s;
        }

        input:focus,
        textarea:focus,
        select:focus {
            background: white;

            border-color: var(--primary);

            box-shadow:
                0 0 0 4px rgba(37,99,235,.10);
        }

        textarea {
            min-height: 130px;
            resize: vertical;
        }

        input[type="file"] {
            padding: 11px;
        }

        .form-actions {
            margin-top: 20px;

            display: flex;
            justify-content: flex-end;

            gap: 10px;
        }

        .btn-primary {
            border: none;

            padding: 12px 20px;

            border-radius: 10px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    var(--primary-dark),
                    var(--primary)
                );

            font-family: inherit;

            font-weight: 800;

            cursor: pointer;

            transition: .25s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);

            box-shadow:
                0 9px 20px rgba(37,99,235,.25);
        }

        .btn-secondary {
            border: 1px solid var(--border);

            padding: 12px 20px;

            border-radius: 10px;

            color: var(--text-light);

            background: white;

            font-family: inherit;

            font-weight: 700;

            cursor: pointer;
        }

        /* =========================================================
           TIMELINE
           ========================================================= */

        .timeline {
            position: relative;

            margin: 25px 0 5px;

            padding-left: 32px;
        }

        .timeline::before {
            content: "";

            position: absolute;

            left: 10px;
            top: 5px;
            bottom: 5px;

            width: 3px;

            background:
                linear-gradient(
                    to bottom,
                    var(--primary),
                    var(--cyan)
                );

            border-radius: 10px;
        }

        .timeline-item {
            position: relative;

            padding: 0 0 28px 25px;
        }

        .timeline-dot {
            position: absolute;

            left: -29px;
            top: 3px;

            width: 20px;
            height: 20px;

            border-radius: 50%;

            background: white;

            border: 5px solid var(--primary);

            box-shadow:
                0 0 0 4px var(--primary-light);
        }

        .timeline-item:nth-child(2) .timeline-dot {
            border-color: var(--secondary);
        }

        .timeline-item:nth-child(3) .timeline-dot {
            border-color: var(--cyan);
        }

        .timeline-item:nth-child(4) .timeline-dot {
            border-color: var(--orange);
        }

        .timeline-item:nth-child(5) .timeline-dot {
            border-color: var(--green);
        }

        .timeline-day {
            color: var(--primary);

            font-size: 11px;
            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: .7px;
        }

        .timeline-title {
            margin: 5px 0;

            color: var(--text-dark);

            font-size: 16px;
            font-weight: 800;
        }

        .timeline-description {
            color: var(--text-light);

            font-size: 13px;

            line-height: 1.6;
        }

        /* =========================================================
           REVIEWERS
           ========================================================= */

        .reviewer-grid {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 18px;
        }

        .reviewer-card {
            padding: 21px;

            border: 1px solid var(--border);

            border-radius: 16px;

            background:
                linear-gradient(
                    145deg,
                    white,
                    #f8fbff
                );

            transition: .25s;

            position: relative;
            overflow: hidden;
        }

        .reviewer-card::before {
            content: "";

            position: absolute;

            top: 0;
            left: 0;
            right: 0;

            height: 4px;

            background: var(--primary);
        }

        .reviewer-card:nth-child(2)::before {
            background: var(--purple);
        }

        .reviewer-card:nth-child(3)::before {
            background: var(--cyan);
        }

        .reviewer-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        .reviewer-avatar {
            width: 52px;
            height: 52px;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            color: white;

            font-size: 17px;
            font-weight: 800;

            background:
                linear-gradient(
                    135deg,
                    var(--primary-dark),
                    var(--primary)
                );

            margin-bottom: 14px;
        }

        .reviewer-card:nth-child(2) .reviewer-avatar {
            background:
                linear-gradient(
                    135deg,
                    #6d28d9,
                    #8b5cf6
                );
        }

        .reviewer-card:nth-child(3) .reviewer-avatar {
            background:
                linear-gradient(
                    135deg,
                    #0891b2,
                    #06b6d4
                );
        }

        .reviewer-name {
            color: var(--text-dark);

            font-size: 15px;
            font-weight: 800;

            margin-bottom: 4px;
        }

        .reviewer-panel {
            color: var(--primary);

            font-size: 11px;
            font-weight: 800;

            text-transform: uppercase;

            margin-bottom: 12px;
        }

        .reviewer-specialty {
            color: var(--text-light);

            font-size: 12px;

            line-height: 1.6;
        }

        /* =========================================================
           PROPOSALS
           ========================================================= */

        .proposal-header {
            display: flex;

            align-items: center;
            justify-content: space-between;

            gap: 15px;

            margin-bottom: 20px;
        }

        .proposal-actions {
            display: flex;

            align-items: center;

            gap: 10px;
        }

        .search-box {
            min-width: 240px;

            position: relative;
        }

        .search-box input {
            padding-left: 38px;
        }

        .search-icon {
            position: absolute;

            left: 13px;
            top: 50%;

            transform: translateY(-50%);

            color: var(--text-light);
        }

        .filter-select {
            width: auto;
            min-width: 140px;
        }

        /* =========================================================
           STATUS BADGES
           ========================================================= */

        .status {
            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 6px 10px;

            border-radius: 30px;

            font-size: 11px;
            font-weight: 800;
        }

        .status::before {
            content: "";

            width: 6px;
            height: 6px;

            border-radius: 50%;

            background: currentColor;
        }

        .status-review {
            background: var(--orange-light);
            color: #b45309;
        }

        .status-submitted {
            background: var(--primary-light);
            color: var(--primary);
        }

        .status-approved {
            background: var(--green-light);
            color: #047857;
        }

        .status-rejected {
            background: var(--red-light);
            color: #b91c1c;
        }

        .action-btn {
            border: none;

            padding: 7px 11px;

            border-radius: 8px;

            background: var(--primary-light);

            color: var(--primary);

            font-size: 11px;

            font-weight: 800;

            cursor: pointer;

            transition: .2s;
        }

        .action-btn:hover {
            background: var(--primary);
            color: white;
        }

        /* =========================================================
           MODAL
           ========================================================= */

        .modal {
            display: none;

            position: fixed;

            z-index: 9999;

            inset: 0;

            background:
                rgba(15,23,42,.65);

            backdrop-filter: blur(5px);

            align-items: center;
            justify-content: center;

            padding: 20px;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            width: 100%;
            max-width: 700px;

            max-height: 90vh;

            overflow-y: auto;

            background: white;

            border-radius: 20px;

            box-shadow:
                0 25px 70px rgba(0,0,0,.25);

            animation: modalIn .25s ease;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(.95) translateY(10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-header {
            padding: 21px 25px;

            display: flex;

            align-items: center;
            justify-content: space-between;

            border-bottom: 1px solid var(--border);

            background:
                linear-gradient(
                    135deg,
                    #eff6ff,
                    #ecfeff
                );
        }

        .modal-header h2 {
            margin: 0;

            color: var(--text-dark);

            font-size: 19px;
        }

        .close-modal {
            width: 35px;
            height: 35px;

            border: none;

            border-radius: 50%;

            background: white;

            color: var(--text-light);

            cursor: pointer;

            font-size: 20px;

            transition: .2s;
        }

        .close-modal:hover {
            background: var(--red-light);
            color: var(--red);
        }

        .modal-body {
            padding: 25px;
        }

        .detail-row {
            margin-bottom: 18px;
        }

        .detail-label {
            color: var(--text-light);

            font-size: 11px;

            text-transform: uppercase;

            font-weight: 800;

            letter-spacing: .6px;

            margin-bottom: 5px;
        }

        .detail-value {
            color: var(--text-dark);

            font-size: 14px;

            line-height: 1.7;
        }

        /* =========================================================
           EMPTY STATE
           ========================================================= */

        .empty-state {
            display: none;

            text-align: center;

            padding: 40px;

            color: var(--text-light);
        }

        .empty-icon {
            font-size: 35px;
            margin-bottom: 10px;
        }

        /* =========================================================
           RESPONSIVE
           ========================================================= */

        @media (max-width: 1200px) {

            .stats-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .workflow-grid {
                grid-template-columns:
                    repeat(3, 1fr);
            }

            .reviewer-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }
        }

        @media (max-width: 900px) {

            .overview-grid {
                grid-template-columns: 1fr;
            }

            .workflow-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }
        }

        @media (max-width: 700px) {

            .main-content {
                padding: 18px;
            }

            .documentation-card {
                align-items: flex-start;
                flex-direction: column;
            }

            .page-header h1 {
                font-size: 25px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .workflow-grid {
                grid-template-columns: 1fr;
            }

            .reviewer-grid {
                grid-template-columns: 1fr;
            }

            .cta-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .proposal-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .proposal-actions {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                width: 100%;
            }

            .filter-select {
                width: 100%;
            }

            .tabs-container {
                border-radius: 12px;
            }

            .card {
                padding: 18px;
            }
        }

    </style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">

    <div class="content-wrapper">

        <!-- =====================================================
             DOCUMENTATION
             ====================================================== -->

        <div class="documentation-card">

            <div class="doc-left">

                <div class="doc-icon">
                    📘
                </div>

                <div>
                    <div class="doc-title">
                        Criteria Review & Approach Document
                    </div>

                    <div class="doc-description">
                        CRAD Research Proposal Evaluation and Review Guidelines
                    </div>
                </div>

            </div>

            <a href="#" class="doc-button">
                View Document →
            </a>

        </div>


        <!-- =====================================================
             PAGE HEADER
             ====================================================== -->

        <div class="page-header">

            <h1>
                Research Proposal Submission & Tracking
            </h1>

            <p>
                Manage research proposals, evaluation criteria, reviewers,
                submissions, and approval workflows through CRAD.
            </p>

        </div>


        <!-- =====================================================
             TABS
             ====================================================== -->

        <div class="tabs-container">

            <button
                class="tab-button active"
                data-tab="overview">
                📊 Overview
            </button>

            <button
                class="tab-button"
                data-tab="criteria">
                📋 Criteria & Rubric
            </button>

            <button
                class="tab-button"
                data-tab="submission">
                📤 Submission
            </button>

            <button
                class="tab-button"
                data-tab="timeline">
                🕒 Timeline
            </button>

            <button
                class="tab-button"
                data-tab="reviewers">
                👥 Reviewers
            </button>

            <button
                class="tab-button"
                data-tab="proposals">
                📑 Proposals
            </button>

        </div>


        <!-- =====================================================
             OVERVIEW
             ====================================================== -->

        <section
            id="overview"
            class="tab-content active">

            <div class="stats-grid">

                <div class="stat-card">

                    <div class="stat-icon">
                        📑
                    </div>

                    <div class="stat-number">
                        128
                    </div>

                    <div class="stat-label">
                        Total Submissions
                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon">
                        🔍
                    </div>

                    <div class="stat-number">
                        34
                    </div>

                    <div class="stat-label">
                        Currently in Review
                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon">
                        ✓
                    </div>

                    <div class="stat-number">
                        52
                    </div>

                    <div class="stat-label">
                        Approved Proposals
                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon">
                        ⚡
                    </div>

                    <div class="stat-number">
                        10
                    </div>

                    <div class="stat-label">
                        Average Review Days
                    </div>

                </div>

            </div>


            <div class="overview-grid">

                <div class="card">

                    <h2 class="card-title">
                        🔬 Research Proposal Management
                    </h2>

                    <div class="card-subtitle">
                        Centralized proposal submission and evaluation
                    </div>

                    <div class="overview-text">

                        The CRAD Research Proposal Management module provides
                        a centralized platform for submitting, tracking,
                        reviewing, and approving research proposals.

                        <br><br>

                        Researchers can submit project information and
                        supporting documents while authorized reviewers
                        evaluate proposals using standardized criteria and
                        rubrics.

                    </div>

                    <div class="highlight-box">

                        <strong>CRAD Objective:</strong><br>

                        Ensure every research proposal follows a transparent,
                        consistent, and accountable review process.

                    </div>

                </div>


                <div class="card">

                    <h2 class="card-title">
                        🚀 Quick Start
                    </h2>

                    <div class="card-subtitle">
                        Submit a new research proposal
                    </div>

                    <p class="overview-text">

                        Ready to submit your research project?

                        Complete the proposal information and upload the
                        required research document.

                    </p>

                    <button
                        class="btn-primary"
                        onclick="openSubmission()">

                        + New Research Proposal

                    </button>

                </div>

            </div>


            <div class="card">

                <h2 class="card-title">
                    🔄 Research Proposal Workflow
                </h2>

                <div class="card-subtitle">
                    Standard CRAD proposal evaluation process
                </div>


                <div class="workflow-grid">

                    <div class="workflow-step">

                        <div class="workflow-number">1</div>

                        <strong>Submission</strong>

                        <span>
                            Researcher submits the proposal and required
                            documents.
                        </span>

                    </div>


                    <div class="workflow-step">

                        <div class="workflow-number">2</div>

                        <strong>Screening</strong>

                        <span>
                            CRAD checks completeness and basic requirements.
                        </span>

                    </div>


                    <div class="workflow-step">

                        <div class="workflow-number">3</div>

                        <strong>Reviewer Assignment</strong>

                        <span>
                            Qualified reviewers are assigned to the proposal.
                        </span>

                    </div>


                    <div class="workflow-step">

                        <div class="workflow-number">4</div>

                        <strong>Evaluation</strong>

                        <span>
                            Reviewers assess the proposal using the rubric.
                        </span>

                    </div>


                    <div class="workflow-step">

                        <div class="workflow-number">5</div>

                        <strong>Decision</strong>

                        <span>
                            Final recommendation and approval decision.
                        </span>

                    </div>

                </div>

            </div>


            <div class="cta-card">

                <div>

                    <h3>
                        Have a new research idea?
                    </h3>

                    <p>
                        Submit your proposal and begin the CRAD research
                        evaluation process.
                    </p>

                </div>

                <button
                    class="btn-white"
                    onclick="openSubmission()">

                    Submit Proposal

                </button>

            </div>

        </section>


        <!-- =====================================================
             CRITERIA & RUBRIC
             ====================================================== -->

        <section
            id="criteria"
            class="tab-content">

            <div class="card">

                <h2 class="card-title">
                    📋 Evaluation Criteria & Rubric
                </h2>

                <div class="card-subtitle">
                    Standard scoring criteria used by CRAD research reviewers
                </div>


                <div class="table-container">

                    <table>

                        <thead>

                            <tr>
                                <th>Criteria</th>
                                <th>Weight</th>
                                <th>Description</th>
                                <th>Evaluation Focus</th>
                            </tr>

                        </thead>

                        <tbody>

                            <tr>
                                <td><strong>Relevance</strong></td>

                                <td class="weight">
                                    30%
                                </td>

                                <td>
                                    Alignment with institutional and
                                    community priorities.
                                </td>

                                <td>
                                    Research significance and impact.
                                </td>
                            </tr>


                            <tr>
                                <td><strong>Methodology</strong></td>

                                <td class="weight">
                                    25%
                                </td>

                                <td>
                                    Appropriateness of the research methods.
                                </td>

                                <td>
                                    Research design, data collection,
                                    and analysis.
                                </td>
                            </tr>


                            <tr>
                                <td><strong>Feasibility</strong></td>

                                <td class="weight">
                                    20%
                                </td>

                                <td>
                                    Practicality of completing the project.
                                </td>

                                <td>
                                    Resources, schedule, and implementation.
                                </td>
                            </tr>


                            <tr>
                                <td><strong>Innovation</strong></td>

                                <td class="weight">
                                    15%
                                </td>

                                <td>
                                    Originality and contribution of the study.
                                </td>

                                <td>
                                    New ideas, approaches, or solutions.
                                </td>
                            </tr>


                            <tr>
                                <td><strong>Collaboration</strong></td>

                                <td class="weight">
                                    10%
                                </td>

                                <td>
                                    Potential for interdisciplinary
                                    collaboration.
                                </td>

                                <td>
                                    Partnerships and knowledge sharing.
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </section>


        <!-- =====================================================
             SUBMISSION
             ====================================================== -->

        <section
            id="submission"
            class="tab-content">

            <div class="card">

                <h2 class="card-title">
                    📤 Submit Research Proposal
                </h2>

                <div class="card-subtitle">
                    Complete all required information before submitting.
                </div>


                <form id="proposalForm">

                    <div class="form-grid">

                        <div class="form-group">

                            <label>
                                Research Project Title *
                            </label>

                            <input
                                type="text"
                                id="projectTitle"
                                placeholder="Enter research project title"
                                required>

                        </div>


                        <div class="form-group">

                            <label>
                                Principal Investigator *
                            </label>

                            <input
                                type="text"
                                id="principalInvestigator"
                                placeholder="Enter researcher name"
                                required>

                        </div>


                        <div class="form-group">

                            <label>
                                Research Category
                            </label>

                            <select>

                                <option>
                                    Select category
                                </option>

                                <option>
                                    Information Technology
                                </option>

                                <option>
                                    Education
                                </option>

                                <option>
                                    Health
                                </option>

                                <option>
                                    Environment
                                </option>

                                <option>
                                    Business
                                </option>

                                <option>
                                    Social Sciences
                                </option>

                            </select>

                        </div>


                        <div class="form-group">

                            <label>
                                Research Team
                            </label>

                            <input
                                type="text"
                                placeholder="Members / collaborators">

                        </div>


                        <div class="form-group full">

                            <label>
                                Abstract / Project Description *
                            </label>

                            <textarea
                                id="abstract"
                                placeholder="Provide a brief description of the research proposal..."
                                required></textarea>

                        </div>


                        <div class="form-group full">

                            <label>
                                Upload Research Proposal PDF *
                            </label>

                            <input
                                type="file"
                                id="proposalFile"
                                accept=".pdf"
                                required>

                        </div>

                    </div>


                    <div class="form-actions">

                        <button
                            type="button"
                            class="btn-secondary"
                            onclick="document.getElementById('proposalForm').reset()">

                            Clear

                        </button>

                        <button
                            type="submit"
                            class="btn-primary">

                            Submit Proposal →

                        </button>

                    </div>

                </form>

            </div>

        </section>


        <!-- =====================================================
             TIMELINE
             ====================================================== -->

        <section
            id="timeline"
            class="tab-content">

            <div class="card">

                <h2 class="card-title">
                    🕒 Proposal Review Timeline
                </h2>

                <div class="card-subtitle">
                    Expected timeline from submission to final decision
                </div>


                <div class="timeline">

                    <div class="timeline-item">

                        <div class="timeline-dot"></div>

                        <div class="timeline-day">
                            Day 0
                        </div>

                        <div class="timeline-title">
                            Proposal Received
                        </div>

                        <div class="timeline-description">
                            Proposal is submitted to CRAD and recorded
                            in the research management system.
                        </div>

                    </div>


                    <div class="timeline-item">

                        <div class="timeline-dot"></div>

                        <div class="timeline-day">
                            Day 1–3
                        </div>

                        <div class="timeline-title">
                            Initial Screening
                        </div>

                        <div class="timeline-description">
                            CRAD verifies proposal completeness,
                            eligibility, and required documentation.
                        </div>

                    </div>


                    <div class="timeline-item">

                        <div class="timeline-dot"></div>

                        <div class="timeline-day">
                            Day 4
                        </div>

                        <div class="timeline-title">
                            Reviewer Assignment
                        </div>

                        <div class="timeline-description">
                            Appropriate reviewers are assigned according
                            to expertise and research area.
                        </div>

                    </div>


                    <div class="timeline-item">

                        <div class="timeline-dot"></div>

                        <div class="timeline-day">
                            Day 5–14
                        </div>

                        <div class="timeline-title">
                            Research Evaluation
                        </div>

                        <div class="timeline-description">
                            Reviewers evaluate the proposal using the
                            approved CRAD criteria and rubric.
                        </div>

                    </div>


                    <div class="timeline-item">

                        <div class="timeline-dot"></div>

                        <div class="timeline-day">
                            Day 15–20
                        </div>

                        <div class="timeline-title">
                            Panel Decision
                        </div>

                        <div class="timeline-description">
                            Final panel recommendation and proposal
                            approval decision are recorded.
                        </div>

                    </div>

                </div>

            </div>

        </section>


        <!-- =====================================================
             REVIEWERS
             ====================================================== -->

        <section
            id="reviewers"
            class="tab-content">

            <div class="card">

                <h2 class="card-title">
                    👥 Research Reviewers
                </h2>

                <div class="card-subtitle">
                    Authorized reviewers and their research expertise
                </div>


                <div class="reviewer-grid">

                    <div class="reviewer-card">

                        <div class="reviewer-avatar">
                            AR
                        </div>

                        <div class="reviewer-name">
                            Dr. Ana Reyes
                        </div>

                        <div class="reviewer-panel">
                            Panel A
                        </div>

                        <div class="reviewer-specialty">
                            Methodology, Statistics, Research Design
                        </div>

                    </div>


                    <div class="reviewer-card">

                        <div class="reviewer-avatar">
                            MS
                        </div>

                        <div class="reviewer-name">
                            Prof. Miguel Santos
                        </div>

                        <div class="reviewer-panel">
                            Panel B
                        </div>

                        <div class="reviewer-specialty">
                            Policy, Ethics, Governance and Social Research
                        </div>

                    </div>


                    <div class="reviewer-card">

                        <div class="reviewer-avatar">
                            LC
                        </div>

                        <div class="reviewer-name">
                            Dr. Liza Cruz
                        </div>

                        <div class="reviewer-panel">
                            Panel A
                        </div>

                        <div class="reviewer-specialty">
                            Fieldwork, Logistics and Community Research
                        </div>

                    </div>

                </div>

            </div>

        </section>


        <!-- =====================================================
             PROPOSALS
             ====================================================== -->

        <section
            id="proposals"
            class="tab-content">

            <div class="card">

                <div class="proposal-header">

                    <div>

                        <h2 class="card-title">
                            📑 Research Proposals
                        </h2>

                        <div class="card-subtitle">
                            View and track submitted research proposals.
                        </div>

                    </div>


                    <div class="proposal-actions">

                        <div class="search-box">

                            <span class="search-icon">
                                🔍
                            </span>

                            <input
                                type="text"
                                id="searchProposal"
                                placeholder="Search proposals...">

                        </div>


                        <select
                            id="statusFilter"
                            class="filter-select">

                            <option value="all">
                                All Status
                            </option>

                            <option value="In Review">
                                In Review
                            </option>

                            <option value="Submitted">
                                Submitted
                            </option>

                            <option value="Approved">
                                Approved
                            </option>

                            <option value="Rejected">
                                Rejected
                            </option>

                        </select>


                        <button
                            class="btn-primary"
                            onclick="openSubmission()">

                            + New

                        </button>

                    </div>

                </div>


                <div class="table-container">

                    <table id="proposalTable">

                        <thead>

                            <tr>

                                <th>
                                    Research Proposal
                                </th>

                                <th>
                                    Principal Investigator
                                </th>

                                <th>
                                    Date Submitted
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr
                                data-status="In Review"
                                data-title="Assessing Urban Air Quality">

                                <td>
                                    <strong>
                                        Assessing Urban Air Quality
                                    </strong>
                                </td>

                                <td>
                                    Dr. Ana Reyes
                                </td>

                                <td>
                                    August 20, 2026
                                </td>

                                <td>
                                    <span class="status status-review">
                                        In Review
                                    </span>
                                </td>

                                <td>

                                    <button
                                        class="action-btn"
                                        onclick="viewProposal(
                                            'Assessing Urban Air Quality',
                                            'Dr. Ana Reyes',
                                            'In Review',
                                            'August 20, 2026',
                                            'This research examines urban air quality and evaluates environmental factors affecting communities.'
                                        )">

                                        View

                                    </button>

                                </td>

                            </tr>


                            <tr
                                data-status="Submitted"
                                data-title="Community Health Interventions">

                                <td>
                                    <strong>
                                        Community Health Interventions
                                    </strong>
                                </td>

                                <td>
                                    Prof. Miguel Santos
                                </td>

                                <td>
                                    August 18, 2026
                                </td>

                                <td>
                                    <span class="status status-submitted">
                                        Submitted
                                    </span>
                                </td>

                                <td>

                                    <button
                                        class="action-btn"
                                        onclick="viewProposal(
                                            'Community Health Interventions',
                                            'Prof. Miguel Santos',
                                            'Submitted',
                                            'August 18, 2026',
                                            'A community-based research project focused on improving access to health services and interventions.'
                                        )">

                                        View

                                    </button>

                                </td>

                            </tr>


                            <tr
                                data-status="Approved"
                                data-title="Sustainable Agriculture Systems">

                                <td>
                                    <strong>
                                        Sustainable Agriculture Systems
                                    </strong>
                                </td>

                                <td>
                                    Dr. Liza Cruz
                                </td>

                                <td>
                                    July 28, 2026
                                </td>

                                <td>
                                    <span class="status status-approved">
                                        Approved
                                    </span>
                                </td>

                                <td>

                                    <button
                                        class="action-btn"
                                        onclick="viewProposal(
                                            'Sustainable Agriculture Systems',
                                            'Dr. Liza Cruz',
                                            'Approved',
                                            'July 28, 2026',
                                            'Research focused on developing sustainable agricultural practices and improving resource efficiency.'
                                        )">

                                        View

                                    </button>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <div
                    class="empty-state"
                    id="emptyState">

                    <div class="empty-icon">
                        🔎
                    </div>

                    <strong>
                        No proposals found
                    </strong>

                    <p>
                        Try changing your search or filter.
                    </p>

                </div>

            </div>

        </section>

    </div>

</main>


<!-- =========================================================
     VIEW PROPOSAL MODAL
     ========================================================= -->

<div
    class="modal"
    id="proposalModal">

    <div class="modal-content">

        <div class="modal-header">

            <h2>
                Proposal Details
            </h2>

            <button
                class="close-modal"
                onclick="closeModal()">

                ×

            </button>

        </div>


        <div class="modal-body">

            <div class="detail-row">

                <div class="detail-label">
                    Research Proposal
                </div>

                <div
                    class="detail-value"
                    id="modalTitle">
                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Principal Investigator
                </div>

                <div
                    class="detail-value"
                    id="modalPI">
                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Date Submitted
                </div>

                <div
                    class="detail-value"
                    id="modalDate">
                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Status
                </div>

                <div
                    class="detail-value"
                    id="modalStatus">
                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Abstract
                </div>

                <div
                    class="detail-value"
                    id="modalAbstract">
                </div>

            </div>


            <div class="form-actions">

                <button
                    class="btn-secondary"
                    onclick="closeModal()">

                    Close

                </button>

            </div>

        </div>

    </div>

</div>


<script>

    /* =========================================================
       TABS
       ========================================================= */

    const tabButtons =
        document.querySelectorAll('.tab-button');

    const tabContents =
        document.querySelectorAll('.tab-content');


    tabButtons.forEach(button => {

        button.addEventListener('click', function () {

            const target =
                this.dataset.tab;

            tabButtons.forEach(btn => {
                btn.classList.remove('active');
            });

            tabContents.forEach(content => {
                content.classList.remove('active');
            });

            this.classList.add('active');

            document
                .getElementById(target)
                .classList.add('active');

        });

    });


    /* =========================================================
       OPEN SUBMISSION
       ========================================================= */

    function openSubmission() {

        tabButtons.forEach(btn => {
            btn.classList.remove('active');
        });

        tabContents.forEach(content => {
            content.classList.remove('active');
        });

        document
            .querySelector('[data-tab="submission"]')
            .classList.add('active');

        document
            .getElementById('submission')
            .classList.add('active');

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

    }


    /* =========================================================
       FORM SUBMISSION
       ========================================================= */

    document
        .getElementById('proposalForm')
        .addEventListener('submit', function (event) {

            event.preventDefault();

            const title =
                document
                .getElementById('projectTitle')
                .value;

            alert(
                'Research proposal "' +
                title +
                '" has been submitted successfully!'
            );

            this.reset();

        });


    /* =========================================================
       SEARCH & FILTER
       ========================================================= */

    const searchInput =
        document.getElementById('searchProposal');

    const statusFilter =
        document.getElementById('statusFilter');

    const tableRows =
        document.querySelectorAll(
            '#proposalTable tbody tr'
        );

    const emptyState =
        document.getElementById('emptyState');


    function filterProposals() {

        const search =
            searchInput.value
            .toLowerCase()
            .trim();

        const status =
            statusFilter.value;

        let visibleCount = 0;


        tableRows.forEach(row => {

            const title =
                row.dataset.title.toLowerCase();

            const rowStatus =
                row.dataset.status;


            const matchesSearch =
                title.includes(search);

            const matchesStatus =
                status === 'all' ||
                rowStatus === status;


            if (
                matchesSearch &&
                matchesStatus
            ) {

                row.style.display = '';

                visibleCount++;

            } else {

                row.style.display = 'none';

            }

        });


        emptyState.style.display =
            visibleCount === 0
            ? 'block'
            : 'none';

    }


    searchInput.addEventListener(
        'input',
        filterProposals
    );

    statusFilter.addEventListener(
        'change',
        filterProposals
    );


    /* =========================================================
       VIEW PROPOSAL
       ========================================================= */

    function viewProposal(
        title,
        pi,
        status,
        date,
        abstract
    ) {

        document
            .getElementById('modalTitle')
            .textContent = title;

        document
            .getElementById('modalPI')
            .textContent = pi;

        document
            .getElementById('modalDate')
            .textContent = date;

        document
            .getElementById('modalStatus')
            .innerHTML = createStatus(status);

        document
            .getElementById('modalAbstract')
            .textContent = abstract;


        document
            .getElementById('proposalModal')
            .classList.add('show');

    }


    function createStatus(status) {

        let className =
            'status-submitted';


        if (status === 'In Review') {
            className = 'status-review';
        }

        if (status === 'Approved') {
            className = 'status-approved';
        }

        if (status === 'Rejected') {
            className = 'status-rejected';
        }


        return `
            <span class="status ${className}">
                ${status}
            </span>
        `;

    }


    /* =========================================================
       CLOSE MODAL
       ========================================================= */

    function closeModal() {

        document
            .getElementById('proposalModal')
            .classList.remove('show');

    }


    document
        .getElementById('proposalModal')
        .addEventListener(
            'click',
            function(event) {

                if (event.target === this) {
                    closeModal();
                }

            }
        );


    /* =========================================================
       ESCAPE KEY
       ========================================================= */

    document.addEventListener(
        'keydown',
        function(event) {

            if (event.key === 'Escape') {
                closeModal();
            }

        }
    );

</script>

</body>
</html>