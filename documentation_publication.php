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

    <title>Documentation & Publication Management</title>

    <link rel="icon" type="image/png" href="logo.png">

    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="stylesheet" href="assets/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>

        /* =========================================================
           DOCUMENTATION & PUBLICATION MANAGEMENT
           ENHANCED CRAD DESIGN
           ========================================================= */

        :root {
            --primary: #1d4ed8;
            --primary-dark: #173b8f;
            --primary-light: #dbeafe;

            --secondary: #4f46e5;
            --secondary-light: #e0e7ff;

            --cyan: #0891b2;
            --cyan-light: #cffafe;

            --green: #059669;
            --green-light: #d1fae5;

            --orange: #d97706;
            --orange-light: #fef3c7;

            --red: #dc2626;
            --red-light: #fee2e2;

            --purple: #7c3aed;
            --purple-light: #ede9fe;

            --text-dark: #0f172a;
            --text: #1e3a5f;
            --muted: #64748b;

            --background: #eef5ff;
            --card-bg: #ffffff;
            --soft-bg: #f8fbff;

            --border: #dbe7f7;

            --shadow:
                0 10px 30px rgba(30, 64, 175, .08);

            --shadow-hover:
                0 18px 45px rgba(30, 64, 175, .16);

            --radius: 16px;
        }


        /* =========================================================
           GLOBAL
           ========================================================= */

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;

            font-family: 'Inter', sans-serif;

            background:
                radial-gradient(
                    circle at 90% 0%,
                    rgba(37, 99, 235, .12),
                    transparent 30%
                ),
                radial-gradient(
                    circle at 10% 90%,
                    rgba(6, 182, 212, .08),
                    transparent 28%
                ),
                linear-gradient(
                    135deg,
                    #f8fbff,
                    #eef5ff
                );

            color: var(--text);

            min-height: 100vh;
        }


        /* =========================================================
           MAIN CONTENT
           ========================================================= */

        .main {
            min-height: 100vh;
        }

        .content {
            padding: 30px;
            max-width: 1550px;
            margin: auto;
        }


        /* =========================================================
           BREADCRUMB
           ========================================================= */

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 7px;

            color: #64748b;

            font-size: 12px;
            font-weight: 600;

            margin-bottom: 15px;

            padding-left: 2px;
        }

        .breadcrumb::before {
            content: "⌂";

            display: inline-flex;

            width: 25px;
            height: 25px;

            align-items: center;
            justify-content: center;

            border-radius: 7px;

            background: var(--primary-light);

            color: var(--primary);

            font-size: 13px;
        }


        /* =========================================================
           HERO / DOCUMENTATION CARD
           ========================================================= */

        .doc-card {
            position: relative;

            overflow: hidden;

            background:
                linear-gradient(
                    135deg,
                    #142b63 0%,
                    #1d4ed8 55%,
                    #0891b2 100%
                );

            color: white;

            padding: 30px;

            border-radius: 22px;

            margin-bottom: 22px;

            border: 1px solid rgba(255,255,255,.15);

            box-shadow:
                0 18px 45px rgba(29,78,216,.22);
        }

        .doc-card::before {
            content: "";

            position: absolute;

            width: 280px;
            height: 280px;

            border-radius: 50%;

            right: -80px;
            top: -140px;

            background: rgba(255,255,255,.08);
        }

        .doc-card::after {
            content: "";

            position: absolute;

            width: 180px;
            height: 180px;

            border-radius: 50%;

            right: 100px;
            bottom: -130px;

            background: rgba(34,211,238,.10);
        }

        .doc-card > * {
            position: relative;
            z-index: 2;
        }

        .doc-card h3 {
            margin: 0 0 9px;

            font-size: 26px;

            font-weight: 800;

            letter-spacing: -.4px;
        }

        .doc-card p {
            max-width: 850px;

            line-height: 1.7;

            color: rgba(255,255,255,.85);

            font-size: 13px;

            margin: 0 0 15px;
        }

        .doc-card ul {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(200px, 1fr));

            gap: 8px 25px;

            padding: 0;

            margin: 15px 0 20px;

            list-style: none;

            max-width: 800px;
        }

        .doc-card li {
            color: rgba(255,255,255,.90);

            font-size: 12px;

            display: flex;

            align-items: center;

            gap: 8px;
        }

        .doc-card li::before {
            content: "✓";

            width: 21px;
            height: 21px;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: rgba(255,255,255,.16);

            color: #fff;

            font-weight: 800;
        }

        .doc-cta {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 7px;

            margin-top: 5px;

            padding: 11px 17px;

            border-radius: 10px;

            background: white;

            color: var(--primary-dark);

            text-decoration: none;

            font-size: 12px;

            font-weight: 800;

            border: 0;

            cursor: pointer;

            transition: .25s;

            box-shadow: 0 7px 18px rgba(0,0,0,.12);
        }

        .doc-cta:hover {
            transform: translateY(-3px);

            box-shadow:
                0 12px 25px rgba(0,0,0,.18);
        }


        /* =========================================================
           STATISTICS
           ========================================================= */

        .stats {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 16px;

            margin-bottom: 22px;
        }

        .stat-card {
            position: relative;

            overflow: hidden;

            background: rgba(255,255,255,.95);

            padding: 19px;

            border-radius: 16px;

            border: 1px solid var(--border);

            box-shadow: var(--shadow);

            min-width: 160px;

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
            background: var(--purple);
        }

        .stat-card h4 {
            margin: 0;

            font-size: 28px;

            font-weight: 800;

            color: var(--primary-dark);
        }

        .stat-card:nth-child(2) h4 {
            color: var(--orange);
        }

        .stat-card:nth-child(3) h4 {
            color: var(--green);
        }

        .stat-card:nth-child(4) h4 {
            color: var(--purple);
        }

        .stat-card p {
            margin: 6px 0 0;

            color: var(--muted);

            font-size: 12px;

            font-weight: 700;
        }


        /* =========================================================
           TWO COLUMN LAYOUT
           ========================================================= */

        .two-col {
            display: grid;

            grid-template-columns:
                minmax(0, 1fr)
                330px;

            gap: 20px;

            align-items: start;
        }


        /* =========================================================
           TABS
           ========================================================= */

        .tabs {
            display: flex;

            align-items: center;

            gap: 7px;

            flex-wrap: wrap;

            background: rgba(255,255,255,.9);

            border: 1px solid var(--border);

            border-radius: 15px;

            padding: 7px;

            margin-bottom: 14px;

            box-shadow: var(--shadow);
        }

        .tab {
            position: relative;

            background: transparent;

            border: 0;

            padding: 11px 16px;

            border-radius: 10px;

            color: #64748b;

            cursor: pointer;

            font-family: inherit;

            font-size: 12px;

            font-weight: 800;

            transition: .25s;

            white-space: nowrap;
        }

        .tab:hover {
            color: var(--primary);

            background: var(--primary-light);
        }

        .tab.active {
            background:
                linear-gradient(
                    135deg,
                    var(--primary-dark),
                    var(--primary)
                );

            color: white;

            box-shadow:
                0 6px 15px rgba(37,99,235,.25);
        }


        /* =========================================================
           TAB CONTENT
           ========================================================= */

        .tab-content {
            display: none;

            background: rgba(255,255,255,.96);

            padding: 25px;

            border-radius: var(--radius);

            border: 1px solid var(--border);

            margin-bottom: 20px;

            box-shadow: var(--shadow);

            animation: fadeTab .3s ease;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeTab {

            from {
                opacity: 0;
                transform: translateY(7px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }

        .tab-content h2 {
            margin: 0 0 7px;

            color: var(--text-dark);

            font-size: 21px;

            font-weight: 800;
        }

        .tab-content > p {
            color: var(--muted);

            line-height: 1.7;

            font-size: 13px;
        }


        /* =========================================================
           RIGHT WORKSPACE
           ========================================================= */

        .right-panel {
            position: sticky;

            top: 20px;

            background:
                linear-gradient(
                    145deg,
                    #ffffff,
                    #f7fbff
                );

            border: 1px solid var(--border);

            padding: 18px;

            border-radius: 17px;

            box-shadow: var(--shadow);
        }

        .right-panel h4 {
            color: var(--text-dark);

            font-size: 13px;

            font-weight: 800;
        }

        .chip {
            display: inline-flex;

            align-items: center;

            padding: 6px 10px;

            border-radius: 999px;

            background:
                linear-gradient(
                    135deg,
                    #dbeafe,
                    #e0e7ff
                );

            border: 1px solid #c7d7fb;

            color: var(--primary-dark);

            font-size: 11px;

            font-weight: 800;
        }

        .muted {
            color: var(--muted);
        }


        /* =========================================================
           RIGHT PANEL MINI STATS
           ========================================================= */

        .right-panel .stats {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 8px;
        }

        .right-panel .stat-card {
            min-width: 0;

            padding: 12px;

            border-radius: 12px;
        }

        .right-panel .stat-card h4 {
            font-size: 20px;

            margin-bottom: 4px;
        }

        .right-panel .stat-card p {
            margin: 0;

            font-size: 10px;
        }


        /* =========================================================
           AUTHORS
           ========================================================= */

        .author-list {
            display: flex;

            flex-direction: column;

            gap: 8px;

            margin-top: 8px;
        }

        .author-item {
            display: flex;

            gap: 10px;

            align-items: center;

            padding: 9px;

            background: white;

            border: 1px solid #edf2fa;

            border-radius: 10px;

            transition: .2s;
        }

        .author-item:hover {
            border-color: #cbdcf7;

            background: #f8fbff;

            transform: translateX(2px);
        }

        .avatar {
            width: 40px;
            height: 40px;

            flex-shrink: 0;

            border-radius: 12px;

            background:
                linear-gradient(
                    135deg,
                    var(--primary-dark),
                    var(--primary)
                );

            display: inline-grid;

            place-items: center;

            color: white;

            font-weight: 800;

            font-size: 12px;
        }

        .author-item:nth-child(2) .avatar {
            background:
                linear-gradient(
                    135deg,
                    #6d28d9,
                    #8b5cf6
                );
        }

        .author-item:nth-child(3) .avatar {
            background:
                linear-gradient(
                    135deg,
                    #0891b2,
                    #06b6d4
                );
        }


        /* =========================================================
           QUICK FILTERS
           ========================================================= */

        .quick-filter {
            transition: .2s;
        }

        .quick-filter:hover {
            background: var(--primary);

            color: white;

            border-color: var(--primary);
        }


        /* =========================================================
           TAG CLOUD
           ========================================================= */

        .tag-cloud {
            display: flex;

            flex-wrap: wrap;

            gap: 7px;

            margin-top: 8px;
        }

        .tag-cloud .tag {
            background: var(--primary-light);

            padding: 7px 10px;

            border-radius: 999px;

            border: 1px solid #cfe0fb;

            color: var(--primary-dark);

            font-weight: 700;

            font-size: 10px;

            transition: .2s;
        }

        .tag-cloud .tag:hover {
            background: var(--primary);

            color: white;

            transform: translateY(-2px);
        }


        /* =========================================================
           CALENDAR
           ========================================================= */

        .calendar {
            border: 1px solid var(--border);

            padding: 12px;

            border-radius: 12px;

            margin-top: 8px;

            background:
                linear-gradient(
                    135deg,
                    #fbfdff,
                    #f1f7ff
                );
        }


        /* =========================================================
           RECENT UPLOADS
           ========================================================= */

        .recent-list {
            margin-top: 10px;

            display: flex;

            flex-direction: column;

            gap: 8px;
        }

        .recent-item {
            display: flex;

            gap: 10px;

            align-items: center;

            padding: 9px;

            border-radius: 10px;

            border: 1px solid #edf2fa;

            background: white;

            transition: .2s;
        }

        .recent-item:hover {
            transform: translateX(3px);

            border-color: #cbdcf7;
        }

        .recent-item .thumb {
            width: 43px;
            height: 50px;

            flex-shrink: 0;

            background:
                linear-gradient(
                    145deg,
                    #dbeafe,
                    #cffafe
                );

            border-radius: 8px;

            display: inline-grid;

            place-items: center;

            color: var(--primary-dark);

            font-weight: 800;

            font-size: 11px;
        }


        /* =========================================================
           BUTTONS
           ========================================================= */

        .btn {
            padding: 8px 11px;

            border-radius: 9px;

            border: 1px solid #d8e5f8;

            background: white;

            color: var(--primary-dark);

            cursor: pointer;

            font-family: inherit;

            font-size: 11px;

            font-weight: 800;

            transition: .2s;
        }

        .btn:hover {
            background: var(--primary);

            border-color: var(--primary);

            color: white;

            transform: translateY(-1px);
        }

        .collapse-btn {
            display: inline-block;

            border: 1px solid #dbe7f7;

            padding: 7px 10px;

            border-radius: 9px;

            background: white;

            cursor: pointer;

            color: var(--primary);

            font-weight: 800;

            font-size: 10px;

            transition: .2s;
        }

        .collapse-btn:hover {
            background: var(--primary);

            color: white;
        }


        /* =========================================================
           TOOLBAR
           ========================================================= */

        .toolbar {
            display: flex;

            gap: 10px;

            align-items: center;

            margin-bottom: 14px;

            flex-wrap: wrap;

            padding: 12px;

            background: #f8fbff;

            border: 1px solid var(--border);

            border-radius: 12px;
        }

        .toolbar > div:last-child {
            margin-left: auto;
        }

        .filters {
            display: flex;

            gap: 10px;

            align-items: center;

            margin: 6px 0 18px;

            flex-wrap: wrap;
        }

        .filters input,
        .filters select,
        .toolbar select,
        .submission-grid select,
        .submission-grid input,
        .submission-grid textarea {
            font-family: inherit;
        }

        #pubSearch {
            min-height: 40px;

            border: 1px solid #d6e3f6 !important;

            background: white;

            box-shadow: 0 3px 10px rgba(30,64,175,.03);

            outline: none;

            transition: .2s;
        }

        #pubSearch:focus {
            border-color: var(--primary) !important;

            box-shadow:
                0 0 0 4px rgba(37,99,235,.08);
        }

        #pubStatus,
        #sortBy {
            min-height: 40px;

            border: 1px solid #d6e3f6 !important;

            background: white;

            outline: none;
        }


        /* =========================================================
           PUBLICATION TABLE
           ========================================================= */

        .table-wrap {
            background: white;

            border-radius: 14px;

            overflow: hidden;

            border: 1px solid var(--border);

            box-shadow: 0 5px 20px rgba(30,64,175,.04);
        }

        .publications-table {
            width: 100%;

            border-collapse: collapse;
        }

        .publications-table thead th {
            background:
                linear-gradient(
                    135deg,
                    #eff6ff,
                    #e0f2fe
                );

            color: var(--primary-dark);

            font-size: 10px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: .5px;

            padding: 14px;

            border-bottom: 2px solid #dbe7f7;

            white-space: nowrap;
        }

        .publications-table tbody tr {
            transition: .2s;
        }

        .publications-table tbody tr:hover {
            background: #f8fbff !important;

            box-shadow:
                inset 3px 0 0 var(--primary);
        }

        .publications-table tbody tr:nth-child(odd) {
            background: rgba(66,98,172,.025);
        }

        .publications-table td {
            padding: 14px;

            border-bottom: 1px solid #edf2f7;

            color: #475569;

            font-size: 12px;

            vertical-align: middle;
        }

        .publications-table td:nth-child(2) {
            color: var(--text-dark);

            font-weight: 800;
        }

        .publications-table td.tags {
            color: #5273ad;

            font-size: 11px;
        }


        /* =========================================================
           BADGES
           ========================================================= */

        .badge {
            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 6px 10px;

            border-radius: 999px;

            font-weight: 800;

            font-size: 10px;

            text-transform: capitalize;
        }

        .badge::before {
            content: "";

            width: 6px;
            height: 6px;

            border-radius: 50%;

            background: currentColor;
        }

        .badge-draft {
            background: var(--orange-light);

            color: #b45309;

            border: 1px solid #f3d99b;
        }

        .badge-published {
            background: var(--green-light);

            color: #047857;

            border: 1px solid #b8ead0;
        }

        .badge-archived {
            background: var(--red-light);

            color: #b91c1c;

            border: 1px solid #f5c7cc;
        }


        /* =========================================================
           MODAL
           ========================================================= */

        .modal {
            position: fixed;

            inset: 0;

            background:
                rgba(8,23,64,.58);

            backdrop-filter: blur(6px);

            display: grid;

            place-items: center;

            z-index: 1400;

            padding: 20px;
        }

        .modal-content {
            background: white;

            padding: 26px;

            border-radius: 20px;

            max-width: 760px;

            width: 92%;

            max-height: 90vh;

            overflow-y: auto;

            box-shadow:
                0 25px 70px rgba(8,23,64,.25);

            position: relative;

            animation: modalIn .25s ease;
        }

        @keyframes modalIn {

            from {
                opacity: 0;
                transform: scale(.96) translateY(12px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }

        }

        .modal-content h3 {
            margin: 0 0 18px;

            color: var(--text-dark);

            font-size: 22px;

            padding-bottom: 14px;

            border-bottom: 1px solid var(--border);
        }

        .modal-close {
            position: absolute;

            right: 16px;
            top: 16px;

            width: 35px;
            height: 35px;

            border: 0;

            background: #f1f5f9;

            color: #64748b;

            border-radius: 50%;

            font-size: 15px;

            cursor: pointer;

            transition: .2s;
        }

        .modal-close:hover {
            background: var(--red-light);

            color: var(--red);
        }

        .meta-row {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 10px;

            margin-top: 8px;
        }

        .meta-item {
            background:
                linear-gradient(
                    135deg,
                    #f8fbff,
                    #eef5ff
                );

            padding: 12px;

            border-radius: 10px;

            border: 1px solid var(--border);

            color: var(--text-dark);

            font-size: 11px;
        }

        .meta-item strong {
            display: block;

            color: var(--primary);

            font-size: 9px;

            text-transform: uppercase;

            margin-bottom: 5px;
        }

        #pubAbstract {
            color: #52647d;

            line-height: 1.7;

            font-size: 13px;

            background: #f8fbff;

            border: 1px solid #e7eef8;

            padding: 13px;

            border-radius: 10px;
        }

        #pubHistory {
            line-height: 1.8;

            padding-left: 20px;

            font-size: 12px;
        }

        #pubFile {
            display: inline-flex;

            padding: 10px 14px;

            border-radius: 9px;

            background: var(--primary-light);

            color: var(--primary);

            text-decoration: none;

            font-weight: 800;

            font-size: 11px;
        }

        #pubFile:hover {
            background: var(--primary);

            color: white;
        }


        /* =========================================================
           PAGINATION
           ========================================================= */

        .pagination {
            display: flex;

            gap: 8px;

            align-items: center;

            justify-content: flex-end;

            margin-top: 13px;

            padding: 5px;
        }

        .pagination button {
            padding: 8px 12px;

            border-radius: 8px;

            border: 1px solid #dce7f7;

            background: white;

            cursor: pointer;

            color: var(--primary);

            font-size: 11px;

            font-weight: 800;

            transition: .2s;
        }

        .pagination button:hover {
            background: var(--primary);

            color: white;
        }


        /* =========================================================
           SUBMISSION
           ========================================================= */

        .submission-grid {
            display: grid;

            grid-template-columns:
                minmax(0, 1fr)
                320px;

            gap: 22px;

            align-items: start;
        }

        .proposal-form {
            background: #fbfdff;

            border: 1px solid var(--border);

            padding: 20px;

            border-radius: 15px;
        }

        .field-group {
            display: flex;

            flex-direction: column;

            gap: 8px;
        }

        .field-group label {
            color: var(--text-dark);

            font-size: 11px;

            font-weight: 800;

            margin-top: 7px;
        }

        .field-group input,
        .field-group textarea,
        .field-group select {
            width: 100%;

            border: 1px solid #d7e4f5;

            padding: 12px;

            border-radius: 9px;

            background: white;

            color: var(--text-dark);

            outline: none;

            font-size: 12px;

            transition: .2s;
        }

        .field-group input:focus,
        .field-group textarea:focus,
        .field-group select:focus {
            border-color: var(--primary);

            box-shadow:
                0 0 0 4px rgba(37,99,235,.08);
        }

        .field-group textarea {
            resize: vertical;
        }


        /* =========================================================
           FILE DROP
           ========================================================= */

        .file-drop {
            border: 2px dashed #cbdcf4;

            padding: 35px 20px;

            border-radius: 15px;

            text-align: center;

            background:
                linear-gradient(
                    145deg,
                    #fbfdff,
                    #f1f7ff
                );

            cursor: pointer;

            color: var(--primary-dark);

            font-size: 13px;

            font-weight: 800;

            transition: .25s;
        }

        .file-drop::before {
            content: "📄";

            display: block;

            font-size: 35px;

            margin-bottom: 10px;
        }

        .file-drop:hover,
        .file-drop.dragover {
            background: #eef6ff;

            border-color: var(--primary);

            transform: translateY(-2px);

            box-shadow:
                0 10px 25px rgba(37,99,235,.08);
        }

        .file-drop small {
            display: block;

            margin-top: 7px;

            font-weight: 500;
        }

        #docFileName {
            padding: 9px;

            border-radius: 8px;

            background: var(--green-light);

            color: #047857;

            font-weight: 700;
        }


        /* =========================================================
           PROGRESS
           ========================================================= */

        .progress-wrap {
            margin-top: 12px;
        }

        .progress {
            width: 100%;

            height: 10px;

            background: #e8eef8;

            border-radius: 999px;

            overflow: hidden;
        }

        .progress > span {
            display: block;

            height: 100%;

            width: 0;

            background:
                linear-gradient(
                    90deg,
                    var(--primary),
                    var(--cyan)
                );

            transition: width .2s;
        }


        /* =========================================================
           TAG INPUT
           ========================================================= */

        .tags-input {
            display: flex;

            gap: 6px;

            flex-wrap: wrap;

            padding: 7px;

            border: 1px solid #d7e4f5;

            border-radius: 9px;

            background: white;

            min-height: 44px;
        }

        .tags-input:focus-within {
            border-color: var(--primary);

            box-shadow:
                0 0 0 4px rgba(37,99,235,.08);
        }

        .tags-input input {
            flex: 1;

            min-width: 150px;

            border: 0 !important;

            box-shadow: none !important;

            padding: 5px !important;
        }

        .tag-pill {
            background: var(--primary-light);

            padding: 6px 9px;

            border-radius: 999px;

            color: var(--primary-dark);

            font-weight: 800;

            font-size: 10px;

            cursor: pointer;
        }

        .tag-pill:hover {
            background: var(--red-light);

            color: var(--red);
        }


        /* =========================================================
           PREVIEW
           ========================================================= */

        #previewMeta {
            padding: 12px;

            background: #f8fbff;

            border: 1px solid var(--border);

            border-radius: 9px;

            font-size: 11px;
        }


        /* =========================================================
           SUBMISSION BUTTONS
           ========================================================= */

        .submission-actions {
            display: flex;

            gap: 8px;

            align-items: center;

            justify-content: flex-end;

            margin-top: 14px;
        }

        #submitDoc {
            background:
                linear-gradient(
                    135deg,
                    var(--primary-dark),
                    var(--primary)
                );

            color: white;

            border: none;

            padding: 11px 17px;

            box-shadow:
                0 7px 18px rgba(37,99,235,.18);
        }

        #submitDoc:hover {
            background:
                linear-gradient(
                    135deg,
                    #1e40af,
                    #0891b2
                );
        }


        /* =========================================================
           GUIDELINES
           ========================================================= */

        #dp-guidelines ul {
            list-style: none;

            padding: 0;

            display: grid;

            gap: 10px;
        }

        #dp-guidelines li {
            padding: 15px;

            border: 1px solid var(--border);

            border-radius: 11px;

            background: #f8fbff;

            transition: .2s;
        }

        #dp-guidelines li:hover {
            background: var(--primary-light);

            transform: translateX(4px);
        }

        #dp-guidelines a {
            color: var(--primary);

            font-weight: 800;

            font-size: 12px;

            text-decoration: none;
        }

        #dp-guidelines a::before {
            content: "📄";

            margin-right: 8px;
        }


        /* =========================================================
           RESPONSIVE
           ========================================================= */

        @media (max-width: 1200px) {

            .stats {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .two-col {
                grid-template-columns: 1fr;
            }

            .right-panel {
                position: static;
            }

        }


        @media (max-width: 950px) {

            .submission-grid {
                grid-template-columns: 1fr;
            }

            .meta-row {
                grid-template-columns:
                    repeat(2, 1fr);
            }

        }


        @media (max-width: 750px) {

            .content {
                padding: 18px;
            }

            .doc-card {
                padding: 23px;
            }

            .doc-card h3 {
                font-size: 21px;
            }

            .doc-card ul {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .tabs {
                overflow-x: auto;

                flex-wrap: nowrap;
            }

            .tab {
                flex-shrink: 0;
            }

            .tab-content {
                padding: 18px;
            }

            .toolbar {
                align-items: stretch;
            }

            .toolbar > div:last-child {
                margin-left: 0;

                width: 100%;

                display: flex;

                flex-wrap: wrap;
            }

            .meta-row {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 600px) {

            .content {
                padding: 12px;
            }

            .doc-card {
                border-radius: 16px;

                padding: 20px;
            }

            .right-panel {
                padding: 14px;
            }

            .publications-table {
                min-width: 850px;
            }

            .table-wrap {
                overflow-x: auto;
            }

            .submission-actions {
                flex-direction: column;

                align-items: stretch;
            }

            .submission-actions button {
                width: 100%;
            }

        }

    </style>

</head>


<body>

<?php include 'includes/sidebar.php'; ?>


<main class="main">

    <section class="content">


        <!-- =====================================================
             BREADCRUMB
             ====================================================== -->

        <div class="breadcrumb">
            Home / Research / Documentation & Publication
        </div>


        <!-- =====================================================
             HERO
             ====================================================== -->

        <div class="doc-card">

            <h3>
                Documentation & Publication Management
            </h3>

            <p>
                Manage institutional documents, journals, research
                publications, manuscripts, guidelines, and archived
                materials through the centralized CRAD publication
                management workspace.
            </p>

            <ul>

                <li>
                    Central publication repository and versioning
                </li>

                <li>
                    Submission and editorial workflow
                </li>

                <li>
                    Publication publishing and archival
                </li>

                <li>
                    Guidelines, templates, and DOI management
                </li>

                <li>
                    Research document tracking
                </li>

                <li>
                    Institutional publication records
                </li>

            </ul>

            <a
                class="doc-cta"
                href="documentation_publication.php">

                Manage Publications →

            </a>

        </div>


        <!-- =====================================================
             STATISTICS
             ====================================================== -->

        <div class="stats">

            <div class="stat-card">

                <h4>
                    128
                </h4>

                <p>
                    Total Documents
                </p>

            </div>


            <div class="stat-card">

                <h4>
                    24
                </h4>

                <p>
                    Pending Review
                </p>

            </div>


            <div class="stat-card">

                <h4>
                    92
                </h4>

                <p>
                    Published
                </p>

            </div>


            <div class="stat-card">

                <h4>
                    12
                </h4>

                <p>
                    Archived
                </p>

            </div>

        </div>


        <!-- =====================================================
             TWO COLUMN
             ====================================================== -->

        <div class="two-col">


            <div>


                <!-- =================================================
                     TABS
                     ================================================== -->

                <div class="tabs">

                    <button
                        class="tab active"
                        data-target="dp-overview">

                        📊 Overview

                    </button>


                    <button
                        class="tab"
                        data-target="dp-publications">

                        📚 Publications

                    </button>


                    <button
                        class="tab"
                        data-target="dp-submission">

                        📤 Submission

                    </button>


                    <button
                        class="tab"
                        data-target="dp-guidelines">

                        📋 Guidelines

                    </button>

                </div>


                <!-- =================================================
                     OVERVIEW
                     ================================================== -->

                <section
                    id="dp-overview"
                    class="tab-content active">

                    <h2>
                        Documentation & Publication Overview
                    </h2>

                    <p>
                        This workspace centralizes institutional
                        documentation and publication workflows,
                        including submission tracking, editorial
                        review, publication management, version
                        control, and archival.
                    </p>

                    <div
                        class="meta-row"
                        style="margin-top:20px">

                        <div class="meta-item">

                            <strong>
                                Repository
                            </strong>

                            128 Documents

                        </div>

                        <div class="meta-item">

                            <strong>
                                Published
                            </strong>

                            92 Records

                        </div>

                        <div class="meta-item">

                            <strong>
                                Review Queue
                            </strong>

                            24 Documents

                        </div>

                        <div class="meta-item">

                            <strong>
                                Archived
                            </strong>

                            12 Records

                        </div>

                    </div>

                    <div
                        style="
                            margin-top:20px;
                            padding:18px;
                            border-radius:13px;
                            background:linear-gradient(135deg,#eff6ff,#ecfeff);
                            border:1px solid #dbeafe;
                        ">

                        <h4
                            style="
                                margin:0 0 7px;
                                color:#173b8f;
                            ">

                            📌 CRAD Publication Workflow

                        </h4>

                        <p
                            style="
                                margin:0;
                                color:#64748b;
                                font-size:12px;
                                line-height:1.7;
                            ">

                            Documents are submitted, screened,
                            reviewed, approved, published, and
                            archived according to institutional
                            documentation and publication procedures.

                        </p>

                    </div>

                </section>


                <!-- =================================================
                     PUBLICATIONS
                     ================================================== -->

                <section
                    id="dp-publications"
                    class="tab-content">

                    <h2>
                        Publications
                    </h2>

                    <p class="small-muted">
                        Search, review, organize, publish, and archive
                        institutional documents.
                    </p>


                    <div class="toolbar">

                        <div class="chip">
                            📚 Publications
                        </div>

                        <div class="muted">
                            Manage repository, track versions,
                            and publish outputs.
                        </div>


                        <div
                            style="
                                display:flex;
                                gap:8px;
                                align-items:center;
                            ">

                            <select id="sortBy">

                                <option value="date_desc">
                                    Sort: Newest
                                </option>

                                <option value="date_asc">
                                    Sort: Oldest
                                </option>

                                <option value="title_asc">
                                    Title A–Z
                                </option>

                                <option value="title_desc">
                                    Title Z–A
                                </option>

                            </select>


                            <button
                                id="bulkPublish"
                                class="btn">

                                ✓ Publish

                            </button>


                            <button
                                id="bulkArchive"
                                class="btn">

                                Archive

                            </button>


                            <button
                                id="btnNewDoc"
                                class="doc-cta">

                                + New Document

                            </button>

                        </div>

                    </div>


                    <div class="filters">

                        <input
                            id="pubSearch"
                            type="search"
                            placeholder="🔍 Search title, author, keyword..."
                            style="
                                flex:1;
                                min-width:220px;
                                padding:10px 13px;
                                border-radius:9px;
                            ">


                        <select
                            id="pubStatus"
                            style="
                                padding:10px 13px;
                                border-radius:9px;
                            ">

                            <option value="all">
                                All Status
                            </option>

                            <option value="draft">
                                Draft
                            </option>

                            <option value="published">
                                Published
                            </option>

                            <option value="archived">
                                Archived
                            </option>

                        </select>

                    </div>


                    <div class="table-wrap">

                        <div
                            style="
                                overflow:auto;
                                background:#fff;
                            ">

                            <table class="publications-table">

                                <thead>

                                    <tr>

                                        <th
                                            style="
                                                width:48px;
                                                text-align:center;
                                            ">

                                            <input
                                                id="selectAll"
                                                type="checkbox">

                                        </th>

                                        <th>
                                            Title
                                        </th>

                                        <th>
                                            Author
                                        </th>

                                        <th>
                                            Date
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                        <th>
                                            Tags
                                        </th>

                                        <th>
                                            Actions
                                        </th>

                                    </tr>

                                </thead>


                                <tbody id="pubsBody">


                                    <tr
                                        data-title="CRAD Review and Approach"
                                        data-author="Office of CRAD"
                                        data-abstract="The official Criteria Review & Approach Document."
                                        data-file="crad_review.pdf"
                                        data-status="published"
                                        data-tags="CRAD,criteria,policy">

                                        <td style="text-align:center">

                                            <input
                                                class="rowCheck"
                                                type="checkbox">

                                        </td>

                                        <td>
                                            CRAD Review and Approach
                                        </td>

                                        <td>
                                            Office of CRAD
                                        </td>

                                        <td>
                                            2026-01-15
                                        </td>

                                        <td>

                                            <span class="badge badge-published">
                                                Published
                                            </span>

                                        </td>

                                        <td class="tags">
                                            CRAD, criteria, policy
                                        </td>

                                        <td>

                                            <button
                                                class="btn pub-view">

                                                View

                                            </button>

                                            <a
                                                href="#"
                                                class="btn">

                                                Download

                                            </a>

                                        </td>

                                    </tr>


                                    <tr
                                        data-title="Submission Guidelines v2"
                                        data-author="Editorial Team"
                                        data-abstract="Updated submission templates and DOI instructions."
                                        data-file="guidelines_v2.pdf"
                                        data-status="draft"
                                        data-tags="templates,doi">

                                        <td style="text-align:center">

                                            <input
                                                class="rowCheck"
                                                type="checkbox">

                                        </td>

                                        <td>
                                            Submission Guidelines v2
                                        </td>

                                        <td>
                                            Editorial Team
                                        </td>

                                        <td>
                                            2026-06-02
                                        </td>

                                        <td>

                                            <span class="badge badge-draft">
                                                Draft
                                            </span>

                                        </td>

                                        <td class="tags">
                                            templates, doi
                                        </td>

                                        <td>

                                            <button
                                                class="btn pub-view">

                                                View

                                            </button>

                                            <a
                                                href="#"
                                                class="btn">

                                                Download

                                            </a>

                                        </td>

                                    </tr>


                                    <tr
                                        data-title="Archived Proceedings 2024"
                                        data-author="Conference Org"
                                        data-abstract="Proceedings from the 2024 symposium."
                                        data-file="proceedings_2024.pdf"
                                        data-status="archived"
                                        data-tags="conference,proceedings">

                                        <td style="text-align:center">

                                            <input
                                                class="rowCheck"
                                                type="checkbox">

                                        </td>

                                        <td>
                                            Archived Proceedings 2024
                                        </td>

                                        <td>
                                            Conference Org
                                        </td>

                                        <td>
                                            2024-11-10
                                        </td>

                                        <td>

                                            <span class="badge badge-archived">
                                                Archived
                                            </span>

                                        </td>

                                        <td class="tags">
                                            conference, proceedings
                                        </td>

                                        <td>

                                            <button
                                                class="btn pub-view">

                                                View

                                            </button>

                                            <a
                                                href="#"
                                                class="btn">

                                                Download

                                            </a>

                                        </td>

                                    </tr>


                                </tbody>

                            </table>

                        </div>


                        <div
                            class="pagination"
                            id="pubPagination">

                            <div class="muted">

                                Showing
                                <span id="pubShowing">
                                    1–3
                                </span>

                            </div>

                            <div style="flex:1"></div>

                            <button id="prevPage">
                                ← Prev
                            </button>

                            <button id="nextPage">
                                Next →
                            </button>

                        </div>

                    </div>


                    <!-- PUBLICATION MODAL -->

                    <div
                        id="pubModal"
                        class="modal"
                        style="display:none">

                        <div class="modal-content">

                            <button
                                class="modal-close"
                                aria-label="Close">

                                ✕

                            </button>


                            <h3 id="pubTitle">
                                Document Title
                            </h3>


                            <div class="meta-row">

                                <div class="meta-item">

                                    <strong>
                                        Author
                                    </strong>

                                    <div id="pubAuthor">
                                        —
                                    </div>

                                </div>


                                <div class="meta-item">

                                    <strong>
                                        Status
                                    </strong>

                                    <div id="pubStatusLbl">
                                        —
                                    </div>

                                </div>


                                <div class="meta-item">

                                    <strong>
                                        Date
                                    </strong>

                                    <div id="pubDate">
                                        —
                                    </div>

                                </div>


                                <div class="meta-item">

                                    <strong>
                                        Tags
                                    </strong>

                                    <div id="pubTags">
                                        —
                                    </div>

                                </div>

                            </div>


                            <h4 style="margin-top:22px">
                                Summary
                            </h4>

                            <p id="pubAbstract"></p>


                            <h4>
                                Version History
                            </h4>

                            <ul
                                id="pubHistory"
                                class="muted">

                                <li>
                                    v1.0 — Initial release — 2026-01-15
                                </li>

                                <li>
                                    v1.1 — Minor edits — 2026-02-01
                                </li>

                            </ul>


                            <p>

                                <a
                                    id="pubFile"
                                    href="#"
                                    target="_blank">

                                    📥 Download File

                                </a>

                            </p>

                        </div>

                    </div>

                </section>


                <!-- =================================================
                     SUBMISSION
                     ================================================== -->

                <section
                    id="dp-submission"
                    class="tab-content">

                    <h2>
                        Submit New Document
                    </h2>

                    <p>
                        Upload new documents, assign authors,
                        provide metadata, and configure publication
                        status.
                    </p>


                    <div class="submission-grid">


                        <form
                            id="docForm"
                            method="post"
                            enctype="multipart/form-data"
                            class="proposal-form">

                            <div class="field-group">

                                <label>
                                    Document Title *
                                </label>

                                <input
                                    id="titleInput"
                                    type="text"
                                    name="title"
                                    placeholder="Enter document title"
                                    required>


                                <label>
                                    Author / Unit *
                                </label>

                                <input
                                    id="authorInput"
                                    type="text"
                                    name="author"
                                    placeholder="Author, department, or unit"
                                    required>


                                <label>
                                    Abstract / Summary *
                                </label>

                                <textarea
                                    id="abstractInput"
                                    name="abstract"
                                    rows="6"
                                    placeholder="Enter a short summary..."
                                    required></textarea>


                                <label>
                                    Publication Status
                                </label>

                                <select
                                    id="statusInput"
                                    name="status">

                                    <option value="draft">
                                        Draft
                                    </option>

                                    <option value="published">
                                        Published
                                    </option>

                                </select>


                                <label>
                                    DOI
                                    <span class="muted">
                                        (optional)
                                    </span>
                                </label>

                                <input
                                    id="doiInput"
                                    type="text"
                                    name="doi"
                                    placeholder="10.12345/example.doi">


                                <label>
                                    Keywords
                                </label>

                                <div
                                    class="tags-input"
                                    id="tagsInput"
                                    tabindex="0">

                                    <input
                                        id="tagText"
                                        type="text"
                                        placeholder="Type keyword + Enter"
                                        style="
                                            border:0;
                                            outline:none;
                                            min-width:120px;
                                        ">

                                </div>


                                <label>
                                    Embargo Release Date
                                    <span class="muted">
                                        (optional)
                                    </span>
                                </label>

                                <input
                                    id="embargoInput"
                                    type="date"
                                    name="embargo">

                            </div>

                        </form>


                        <div>

                            <div
                                id="fileDrop"
                                class="file-drop">

                                Drop PDF here or click to choose

                                <small class="muted">
                                    Accepted: .pdf • Maximum 10MB
                                </small>

                                <input
                                    id="docFileInput"
                                    type="file"
                                    name="doc_file"
                                    accept="application/pdf"
                                    style="display:none">

                            </div>


                            <div
                                id="docFileName"
                                class="muted"
                                style="
                                    margin-top:8px;
                                    font-size:12px;
                                ">
                            </div>


                            <div
                                class="progress-wrap"
                                style="display:none"
                                id="uploadProgress">

                                <div class="progress">

                                    <span
                                        id="progressBar">
                                    </span>

                                </div>

                                <div
                                    class="muted"
                                    style="
                                        margin-top:6px;
                                        font-size:11px;
                                    "
                                    id="progressText">

                                    Uploading…

                                </div>

                            </div>


                            <div
                                style="
                                    margin-top:14px;
                                    border:1px solid var(--card-border);
                                    padding:14px;
                                    border-radius:11px;
                                    background:#f8fbff;
                                ">

                                <h4
                                    style="
                                        margin:0 0 7px;
                                        color:#173b8f;
                                    ">

                                    📄 Document Preview

                                </h4>

                                <div
                                    class="muted"
                                    id="previewMeta">

                                    No file selected.

                                </div>

                            </div>


                            <div class="submission-actions">

                                <button
                                    id="cancelUpload"
                                    class="btn"
                                    style="display:none">

                                    Cancel

                                </button>

                                <button
                                    id="submitDoc"
                                    class="doc-cta">

                                    Upload Document

                                </button>

                            </div>

                        </div>

                    </div>

                </section>


                <!-- =================================================
                     GUIDELINES
                     ================================================== -->

                <section
                    id="dp-guidelines"
                    class="tab-content">

                    <h2>
                        Guidelines & Templates
                    </h2>

                    <p>
                        Access submission templates, DOI policies,
                        editorial checklists, formatting standards,
                        and other publication resources.
                    </p>


                    <ul>

                        <li>
                            <a href="#">
                                Submission Template (DOCX)
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Reference and Citation Format
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Editorial Review Checklist
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                DOI Assignment Policy
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Publication Formatting Standards
                            </a>
                        </li>

                    </ul>

                </section>

            </div>


            <!-- =====================================================
                 RIGHT SIDEBAR
                 ====================================================== -->

            <aside class="right-panel">


                <div
                    style="
                        display:flex;
                        align-items:center;
                        justify-content:space-between;
                    ">

                    <h4 style="margin:0">
                        Workspace
                    </h4>

                    <button
                        id="toggleRight"
                        class="collapse-btn">

                        Collapse

                    </button>

                </div>


                <div
                    style="
                        margin-top:10px;
                        display:flex;
                        gap:8px;
                        align-items:center;
                    ">

                    <div class="chip">
                        📁 Docs
                    </div>

                    <div
                        class="muted"
                        style="font-size:11px">

                        Repository overview

                    </div>

                </div>


                <div
                    class="stats"
                    style="margin-top:12px">

                    <div class="stat-card">

                        <h4>
                            24
                        </h4>

                        <p>
                            Pending
                        </p>

                    </div>

                    <div class="stat-card">

                        <h4>
                            92
                        </h4>

                        <p>
                            Published
                        </p>

                    </div>

                </div>


                <h4 style="margin-top:20px">
                    👥 Top Authors
                </h4>


                <div class="author-list">

                    <div class="author-item">

                        <div class="avatar">
                            AR
                        </div>

                        <div>

                            <strong>
                                Dr. Ana Reyes
                            </strong>

                            <div
                                class="muted"
                                style="font-size:10px">

                                12 documents

                            </div>

                        </div>

                    </div>


                    <div class="author-item">

                        <div class="avatar">
                            MS
                        </div>

                        <div>

                            <strong>
                                Prof. Miguel Santos
                            </strong>

                            <div
                                class="muted"
                                style="font-size:10px">

                                8 documents

                            </div>

                        </div>

                    </div>


                    <div class="author-item">

                        <div class="avatar">
                            LC
                        </div>

                        <div>

                            <strong>
                                Dr. Liza Cruz
                            </strong>

                            <div
                                class="muted"
                                style="font-size:10px">

                                6 documents

                            </div>

                        </div>

                    </div>

                </div>


                <h4 style="margin-top:20px">
                    🔎 Quick Filters
                </h4>


                <div
                    style="
                        display:flex;
                        gap:7px;
                        flex-wrap:wrap;
                    ">

                    <button
                        class="btn quick-filter"
                        data-q="CRAD">

                        CRAD

                    </button>

                    <button
                        class="btn quick-filter"
                        data-q="templates">

                        Templates

                    </button>

                    <button
                        class="btn quick-filter"
                        data-q="conference">

                        Conference

                    </button>

                    <button
                        class="btn quick-filter"
                        data-q="policy">

                        Policy

                    </button>

                </div>


                <h4 style="margin-top:20px">
                    🏷 Tags
                </h4>


                <div class="tag-cloud">

                    <div class="tag">
                        policy
                    </div>

                    <div class="tag">
                        templates
                    </div>

                    <div class="tag">
                        DOI
                    </div>

                    <div class="tag">
                        conference
                    </div>

                    <div class="tag">
                        CRAD
                    </div>

                </div>


                <h4 style="margin-top:20px">
                    📅 Calendar
                </h4>


                <div class="calendar">

                    <div
                        style="
                            font-weight:800;
                            margin-bottom:9px;
                            color:#173b8f;
                            font-size:12px;
                        ">

                        September 2026

                    </div>

                    <div
                        style="
                            display:grid;
                            grid-template-columns:repeat(7,1fr);
                            gap:5px;
                            font-size:9px;
                            color:#6b7a99;
                            text-align:center;
                        ">

                        <div>Sun</div>
                        <div>Mon</div>
                        <div>Tue</div>
                        <div>Wed</div>
                        <div>Thu</div>
                        <div>Fri</div>
                        <div>Sat</div>

                        <div
                            style="
                                grid-column:span 7;
                                height:35px;
                                background:#eef5ff;
                                border-radius:7px;
                            ">
                        </div>

                    </div>

                </div>


                <h4 style="margin-top:20px">
                    📤 Recent Uploads
                </h4>


                <div class="recent-list">

                    <div class="recent-item">

                        <div class="thumb">
                            CR
                        </div>

                        <div>

                            <strong
                                style="
                                    font-size:11px;
                                    color:#172554;
                                ">

                                CRAD Review and Approach

                            </strong>

                            <div
                                class="muted"
                                style="font-size:9px">

                                2026-01-15 • Published

                            </div>

                        </div>

                    </div>


                    <div class="recent-item">

                        <div class="thumb">
                            GV
                        </div>

                        <div>

                            <strong
                                style="
                                    font-size:11px;
                                    color:#172554;
                                ">

                                Guidelines v2

                            </strong>

                            <div
                                class="muted"
                                style="font-size:9px">

                                2026-06-02 • Draft

                            </div>

                        </div>

                    </div>

                </div>

            </aside>

        </div>

    </section>

</main>


<script>

    /* =========================================================
       SIDEBAR
       ========================================================= */

    function toggleSidebar() {

        document.body.classList.toggle(
            'sidebar-hidden'
        );

    }


    /* =========================================================
       TABS
       ========================================================= */

    document
        .querySelectorAll('.tab')
        .forEach(function(btn) {

            btn.addEventListener(
                'click',
                function() {

                    document
                        .querySelectorAll('.tab')
                        .forEach(t =>
                            t.classList.remove('active')
                        );

                    document
                        .querySelectorAll('.tab-content')
                        .forEach(c =>
                            c.classList.remove('active')
                        );


                    btn.classList.add('active');


                    const id =
                        btn.getAttribute(
                            'data-target'
                        );


                    document
                        .getElementById(id)
                        .classList.add('active');

                }
            );

        });


    /* =========================================================
       PUBLICATIONS
       ========================================================= */

    (function() {

        const search =
            document.getElementById(
                'pubSearch'
            );

        const status =
            document.getElementById(
                'pubStatus'
            );

        const tbody =
            document.getElementById(
                'pubsBody'
            );

        const modal =
            document.getElementById(
                'pubModal'
            );

        const pubTitle =
            document.getElementById(
                'pubTitle'
            );

        const pubAuthor =
            document.getElementById(
                'pubAuthor'
            );

        const pubStatusLbl =
            document.getElementById(
                'pubStatusLbl'
            );

        const pubAbstract =
            document.getElementById(
                'pubAbstract'
            );

        const pubFile =
            document.getElementById(
                'pubFile'
            );


        function filterRows() {

            const q =
                (
                    search &&
                    search.value ||
                    ''
                )
                .toLowerCase()
                .trim();


            const s =
                (
                    status &&
                    status.value
                ) || 'all';


            Array
                .from(
                    tbody.querySelectorAll('tr')
                )
                .forEach(tr => {

                    const title =
                        (
                            tr.dataset.title ||
                            ''
                        ).toLowerCase();


                    const author =
                        (
                            tr.dataset.author ||
                            ''
                        ).toLowerCase();


                    const abs =
                        (
                            tr.dataset.abstract ||
                            ''
                        ).toLowerCase();


                    const tags =
                        (
                            tr.dataset.tags ||
                            ''
                        ).toLowerCase();


                    const st =
                        (
                            tr.dataset.status ||
                            ''
                        ).toLowerCase();


                    const matchQ =
                        !q ||
                        title.includes(q) ||
                        author.includes(q) ||
                        abs.includes(q) ||
                        tags.includes(q);


                    const matchS =
                        s === 'all' ||
                        s === st;


                    tr.style.display =
                        (
                            matchQ &&
                            matchS
                        )
                        ? ''
                        : 'none';

                });

        }


        if (search)
            search.addEventListener(
                'input',
                filterRows
            );


        if (status)
            status.addEventListener(
                'change',
                filterRows
            );


        function attachView() {

            document
                .querySelectorAll('.pub-view')
                .forEach(b => {

                    b.addEventListener(
                        'click',
                        function() {

                            const tr =
                                b.closest('tr');

                            if (!tr) return;


                            pubTitle.textContent =
                                tr.dataset.title ||
                                '—';


                            pubAuthor.textContent =
                                tr.dataset.author ||
                                '—';


                            pubStatusLbl.textContent =
                                (
                                    tr.dataset.status ||
                                    '—'
                                );


                            pubAbstract.textContent =
                                tr.dataset.abstract ||
                                'No summary provided.';


                            document
                                .getElementById(
                                    'pubTags'
                                )
                                .textContent =
                                tr.dataset.tags ||
                                '—';


                            document
                                .getElementById(
                                    'pubDate'
                                )
                                .textContent =
                                tr.children[3]
                                    .textContent;


                            pubFile.href =
                                tr.dataset.file ||
                                '#';


                            modal.style.display =
                                'grid';

                        }
                    );

                });

        }


        attachView();


        modal
            .querySelectorAll('.modal-close')
            .forEach(b =>
                b.addEventListener(
                    'click',
                    () =>
                        modal.style.display =
                            'none'
                )
            );


        modal.addEventListener(
            'click',
            e => {

                if (
                    e.target === modal
                ) {

                    modal.style.display =
                        'none';

                }

            }
        );


        const btnNew =
            document.getElementById(
                'btnNewDoc'
            );


        if (btnNew) {

            btnNew.addEventListener(
                'click',
                function() {

                    document
                        .querySelectorAll('.tab')
                        .forEach(t =>
                            t.classList.remove(
                                'active'
                            )
                        );


                    document
                        .querySelectorAll(
                            '.tab-content'
                        )
                        .forEach(c =>
                            c.classList.remove(
                                'active'
                            )
                        );


                    const tab =
                        document.querySelector(
                            '.tab[data-target="dp-submission"]'
                        );


                    if (tab) {

                        tab.classList.add(
                            'active'
                        );

                        document
                            .getElementById(
                                'dp-submission'
                            )
                            .classList.add(
                                'active'
                            );

                    }


                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });

                }
            );

        }

    })();


    /* =========================================================
       SUBMISSION
       ========================================================= */

    (function() {

        const docForm =
            document.getElementById(
                'docForm'
            );

        const fileInput =
            document.getElementById(
                'docFileInput'
            );

        const fileDrop =
            document.getElementById(
                'fileDrop'
            );

        const fileName =
            document.getElementById(
                'docFileName'
            );

        const previewMeta =
            document.getElementById(
                'previewMeta'
            );

        const progressWrap =
            document.getElementById(
                'uploadProgress'
            );

        const progressBar =
            document.getElementById(
                'progressBar'
            );

        const progressText =
            document.getElementById(
                'progressText'
            );

        const submitBtn =
            document.getElementById(
                'submitDoc'
            );

        const cancelBtn =
            document.getElementById(
                'cancelUpload'
            );


        /* TAGS */

        const tags = [];

        const tagsInput =
            document.getElementById(
                'tagsInput'
            );

        const tagText =
            document.getElementById(
                'tagText'
            );


        function renderTags() {

            tagsInput
                .querySelectorAll(
                    '.tag-pill'
                )
                .forEach(n =>
                    n.remove()
                );


            tags.forEach(t => {

                const el =
                    document.createElement(
                        'div'
                    );


                el.className =
                    'tag-pill';


                el.textContent =
                    t;


                el.title =
                    'Click to remove';


                el.addEventListener(
                    'click',
                    () => {

                        tags.splice(
                            tags.indexOf(t),
                            1
                        );

                        renderTags();

                    }
                );


                tagsInput.insertBefore(
                    el,
                    tagText
                );

            });

        }


        tagText.addEventListener(
            'keydown',
            function(e) {

                if (
                    e.key === 'Enter'
                ) {

                    e.preventDefault();


                    const v =
                        tagText.value
                            .trim();


                    if (
                        v &&
                        !tags.includes(v)
                    ) {

                        tags.push(v);

                        tagText.value =
                            '';

                        renderTags();

                    }

                }

            }
        );


        /* FILE */

        fileDrop.addEventListener(
            'click',
            () =>
                fileInput.click()
        );


        fileInput.addEventListener(
            'change',
            updateFile
        );


        [
            'dragenter',
            'dragover'
        ].forEach(ev =>
            fileDrop.addEventListener(
                ev,
                e => {

                    e.preventDefault();

                    fileDrop.classList.add(
                        'dragover'
                    );

                }
            )
        );


        [
            'dragleave',
            'drop'
        ].forEach(ev =>
            fileDrop.addEventListener(
                ev,
                e => {

                    e.preventDefault();

                    fileDrop.classList.remove(
                        'dragover'
                    );

                }
            )
        );


        fileDrop.addEventListener(
            'drop',
            function(e) {

                const f =
                    e.dataTransfer.files[0];


                if (f) {

                    fileInput.files =
                        e.dataTransfer.files;

                    updateFile();

                }

            }
        );


        function updateFile() {

            const f =
                fileInput.files[0];


            if (f) {

                const size =
                    f.size /
                    (1024 * 1024);


                fileName.textContent =
                    `${f.name} • ${size.toFixed(2)} MB`;


                previewMeta.textContent =
                    `${f.name} — Ready to upload`;

            } else {

                fileName.textContent =
                    '';

                previewMeta.textContent =
                    'No file selected.';

            }

        }


        /* DOI */

        function validDOI(str) {

            return /^10\.\d{4,9}\/[-._;()/:A-Z0-9]+$/i
                .test(str);

        }


        /* UPLOAD */

        let uploadTimer = null;


        submitBtn.addEventListener(
            'click',
            function(e) {

                e.preventDefault();


                const title =
                    document
                        .getElementById(
                            'titleInput'
                        )
                        .value
                        .trim();


                const author =
                    document
                        .getElementById(
                            'authorInput'
                        )
                        .value
                        .trim();


                const doi =
                    document
                        .getElementById(
                            'doiInput'
                        )
                        .value
                        .trim();


                if (
                    !title ||
                    !author
                ) {

                    alert(
                        'Title and Author are required.'
                    );

                    return;

                }


                if (
                    doi &&
                    !validDOI(doi)
                ) {

                    alert(
                        'DOI format looks invalid.'
                    );

                    return;

                }


                const f =
                    fileInput.files[0];


                if (!f) {

                    alert(
                        'Please select a PDF to upload.'
                    );

                    return;

                }


                if (
                    f.type !==
                    'application/pdf'
                ) {

                    alert(
                        'Only PDF files are allowed.'
                    );

                    return;

                }


                if (
                    f.size >
                    10 * 1024 * 1024
                ) {

                    alert(
                        'File size must not exceed 10MB.'
                    );

                    return;

                }


                progressWrap.style.display =
                    '';


                progressBar.style.width =
                    '4%';


                progressText.textContent =
                    'Starting upload...';


                cancelBtn.style.display =
                    'inline-block';


                let p = 4;


                uploadTimer =
                    setInterval(
                        () => {

                            p +=
                                Math.floor(
                                    Math.random() * 12
                                ) + 4;


                            if (p > 100)
                                p = 100;


                            progressBar.style.width =
                                p + '%';


                            progressText.textContent =
                                `Uploading ${p}%`;


                            if (p >= 100) {

                                clearInterval(
                                    uploadTimer
                                );


                                progressText.textContent =
                                    'Processing document...';


                                setTimeout(
                                    () => {

                                        progressWrap.style.display =
                                            'none';


                                        cancelBtn.style.display =
                                            'none';


                                        alert(
                                            'Upload complete (UI only). Implement server-side handler to save the file.'
                                        );


                                        docForm.reset();


                                        fileInput.value =
                                            '';


                                        fileName.textContent =
                                            '';


                                        previewMeta.textContent =
                                            'No file selected.';


                                        renderNewRow(
                                            title,
                                            author,
                                            f.name,
                                            document
                                                .getElementById(
                                                    'statusInput'
                                                )
                                                .value,
                                            tags.join(', ')
                                        );


                                        tags.length =
                                            0;


                                        renderTags();

                                    },
                                    800
                                );

                            }

                        },
                        300
                    );

            }
        );


        cancelBtn.addEventListener(
            'click',
            function() {

                if (uploadTimer) {

                    clearInterval(
                        uploadTimer
                    );

                    uploadTimer =
                        null;


                    progressWrap.style.display =
                        'none';


                    cancelBtn.style.display =
                        'none';


                    progressBar.style.width =
                        '0%';


                    alert(
                        'Upload canceled.'
                    );

                }

            }
        );


        function renderNewRow(
            title,
            author,
            filename,
            status,
            tagsStr
        ) {

            const tbody =
                document.getElementById(
                    'pubsBody'
                );


            const tr =
                document.createElement(
                    'tr'
                );


            tr.dataset.title =
                title;


            tr.dataset.author =
                author;


            tr.dataset.abstract =
                '(Uploaded via UI)';


            tr.dataset.file =
                filename;


            tr.dataset.status =
                status;


            tr.dataset.tags =
                tagsStr;


            const formattedStatus =
                status.charAt(0).toUpperCase() +
                status.slice(1);


            const badgeClass =
                status === 'published'
                    ? 'badge-published'
                    : 'badge-draft';


            tr.innerHTML = `

                <td style="text-align:center">

                    <input
                        class="rowCheck"
                        type="checkbox">

                </td>

                <td>
                    ${title}
                </td>

                <td>
                    ${author}
                </td>

                <td>
                    ${new Date()
                        .toISOString()
                        .slice(0,10)}
                </td>

                <td>

                    <span
                        class="badge ${badgeClass}">

                        ${formattedStatus}

                    </span>

                </td>

                <td class="tags">
                    ${tagsStr}
                </td>

                <td>

                    <button
                        class="btn pub-view">

                        View

                    </button>

                    <a
                        href="#"
                        class="btn">

                        Download

                    </a>

                </td>

            `;


            tbody.insertBefore(
                tr,
                tbody.firstChild
            );


            attachNewViewButton(tr);

        }


        function attachNewViewButton(tr) {

            const button =
                tr.querySelector(
                    '.pub-view'
                );


            button.addEventListener(
                'click',
                function() {

                    document
                        .getElementById(
                            'pubTitle'
                        )
                        .textContent =
                        tr.dataset.title;


                    document
                        .getElementById(
                            'pubAuthor'
                        )
                        .textContent =
                        tr.dataset.author;


                    document
                        .getElementById(
                            'pubStatusLbl'
                        )
                        .textContent =
                        tr.dataset.status;


                    document
                        .getElementById(
                            'pubAbstract'
                        )
                        .textContent =
                        tr.dataset.abstract;


                    document
                        .getElementById(
                            'pubFile'
                        )
                        .href =
                        tr.dataset.file;


                    document
                        .getElementById(
                            'pubTags'
                        )
                        .textContent =
                        tr.dataset.tags;


                    document
                        .getElementById(
                            'pubDate'
                        )
                        .textContent =
                        new Date()
                            .toISOString()
                            .slice(0,10);


                    document
                        .getElementById(
                            'pubModal'
                        )
                        .style.display =
                        'grid';

                }
            );

        }

    })();


    /* =========================================================
       SELECTION / BULK / SORT / PAGINATION
       ========================================================= */

    (function() {

        const selectAll =
            document.getElementById(
                'selectAll'
            );


        let rows =
            Array.from(
                document.querySelectorAll(
                    '#pubsBody tr'
                )
            );


        const pageSize = 5;

        let page = 0;


        function collectRows() {

            rows =
                Array.from(
                    document.querySelectorAll(
                        '#pubsBody tr'
                    )
                );

        }


        function updateShowing() {

            collectRows();


            const visibleRows =
                rows.filter(
                    r =>
                        r.dataset.filtered !==
                        'true'
                );


            const totalPages =
                Math.max(
                    1,
                    Math.ceil(
                        visibleRows.length /
                        pageSize
                    )
                );


            if (page >= totalPages)
                page = totalPages - 1;


            const start =
                page * pageSize;


            const end =
                Math.min(
                    start + pageSize,
                    visibleRows.length
                );


            rows.forEach(
                r =>
                    r.style.display =
                        'none'
            );


            for (
                let i = start;
                i < end;
                i++
            ) {

                visibleRows[i]
                    .style.display =
                    '';

            }


            document
                .getElementById(
                    'pubShowing'
                )
                .textContent =
                visibleRows.length
                    ? `${start + 1}–${end}`
                    : '0';

        }


        document
            .getElementById(
                'prevPage'
            )
            .addEventListener(
                'click',
                function() {

                    if (page > 0) {

                        page--;

                        updateShowing();

                    }

                }
            );


        document
            .getElementById(
                'nextPage'
            )
            .addEventListener(
                'click',
                function() {

                    collectRows();


                    const visibleRows =
                        rows.filter(
                            r =>
                                r.dataset.filtered !==
                                'true'
                        );


                    if (
                        (page + 1) * pageSize <
                        visibleRows.length
                    ) {

                        page++;

                        updateShowing();

                    }

                }
            );


        if (selectAll) {

            selectAll.addEventListener(
                'change',
                function() {

                    document
                        .querySelectorAll(
                            '.rowCheck'
                        )
                        .forEach(
                            c =>
                                c.checked =
                                selectAll.checked
                        );

                }
            );

        }


        document
            .getElementById(
                'bulkPublish'
            )
            .addEventListener(
                'click',
                function() {

                    const checked =
                        Array.from(
                            document.querySelectorAll(
                                '.rowCheck:checked'
                            )
                        );


                    if (!checked.length) {

                        alert(
                            'Select rows to Publish.'
                        );

                        return;

                    }


                    checked.forEach(
                        ch => {

                            const tr =
                                ch.closest('tr');


                            tr.dataset.status =
                                'published';


                            const badge =
                                tr.querySelector(
                                    '.badge'
                                );


                            badge.className =
                                'badge badge-published';


                            badge.textContent =
                                'Published';

                        }
                    );


                    alert(
                        'Selected documents marked as Published (UI only).'
                    );

                }
            );


        document
            .getElementById(
                'bulkArchive'
            )
            .addEventListener(
                'click',
                function() {

                    const checked =
                        Array.from(
                            document.querySelectorAll(
                                '.rowCheck:checked'
                            )
                        );


                    if (!checked.length) {

                        alert(
                            'Select rows to Archive.'
                        );

                        return;

                    }


                    checked.forEach(
                        ch => {

                            const tr =
                                ch.closest('tr');


                            tr.dataset.status =
                                'archived';


                            const badge =
                                tr.querySelector(
                                    '.badge'
                                );


                            badge.className =
                                'badge badge-archived';


                            badge.textContent =
                                'Archived';

                        }
                    );


                    alert(
                        'Selected documents marked as Archived (UI only).'
                    );

                }
            );


        /* SORTING */

        document
            .getElementById(
                'sortBy'
            )
            .addEventListener(
                'change',
                function() {

                    const val =
                        this.value;


                    const tbody =
                        document.getElementById(
                            'pubsBody'
                        );


                    const arr =
                        Array.from(
                            tbody.querySelectorAll(
                                'tr'
                            )
                        );


                    arr.sort(
                        (a,b) => {

                            if (
                                val ===
                                'date_desc'
                            ) {

                                return new Date(
                                    b.children[3]
                                        .textContent
                                ) -
                                new Date(
                                    a.children[3]
                                        .textContent
                                );

                            }


                            if (
                                val ===
                                'date_asc'
                            ) {

                                return new Date(
                                    a.children[3]
                                        .textContent
                                ) -
                                new Date(
                                    b.children[3]
                                        .textContent
                                );

                            }


                            if (
                                val ===
                                'title_asc'
                            ) {

                                return a.children[1]
                                    .textContent
                                    .localeCompare(
                                        b.children[1]
                                            .textContent
                                    );

                            }


                            if (
                                val ===
                                'title_desc'
                            ) {

                                return b.children[1]
                                    .textContent
                                    .localeCompare(
                                        a.children[1]
                                            .textContent
                                    );

                            }


                            return 0;

                        }
                    );


                    arr.forEach(
                        r =>
                            tbody.appendChild(r)
                    );


                    page = 0;

                    filterPubRows();

                    updateShowing();

                }
            );


        function filterPubRows() {

            const q =
                (
                    document
                        .getElementById(
                            'pubSearch'
                        )
                        .value ||
                    ''
                )
                .toLowerCase()
                .trim();


            const s =
                document
                    .getElementById(
                        'pubStatus'
                    )
                    .value;


            collectRows();


            rows.forEach(
                tr => {

                    const title =
                        (
                            tr.dataset.title ||
                            ''
                        ).toLowerCase();


                    const author =
                        (
                            tr.dataset.author ||
                            ''
                        ).toLowerCase();


                    const abs =
                        (
                            tr.dataset.abstract ||
                            ''
                        ).toLowerCase();


                    const tags =
                        (
                            tr.dataset.tags ||
                            ''
                        ).toLowerCase();


                    const st =
                        (
                            tr.dataset.status ||
                            ''
                        ).toLowerCase();


                    const matchQ =
                        !q ||
                        title.includes(q) ||
                        author.includes(q) ||
                        abs.includes(q) ||
                        tags.includes(q);


                    const matchS =
                        s === 'all' ||
                        s === st;


                    tr.dataset.filtered =
                        (
                            matchQ &&
                            matchS
                        )
                        ? 'false'
                        : 'true';

                }
            );

        }


        document
            .getElementById(
                'pubSearch'
            )
            .addEventListener(
                'input',
                function() {

                    page = 0;

                    filterPubRows();

                    updateShowing();

                }
            );


        document
            .getElementById(
                'pubStatus'
            )
            .addEventListener(
                'change',
                function() {

                    page = 0;

                    filterPubRows();

                    updateShowing();

                }
            );


        filterPubRows();

        updateShowing();

    })();


    /* =========================================================
       RIGHT PANEL
       ========================================================= */

    (function() {

        document
            .querySelectorAll(
                '.quick-filter'
            )
            .forEach(
                b =>
                    b.addEventListener(
                        'click',
                        function() {

                            const q =
                                b.dataset.q ||
                                '';


                            const search =
                                document
                                    .getElementById(
                                        'pubSearch'
                                    );


                            search.value =
                                q;


                            search.dispatchEvent(
                                new Event(
                                    'input'
                                )
                            );


                            document
                                .querySelectorAll(
                                    '.tab'
                                )
                                .forEach(
                                    t =>
                                        t.classList
                                            .remove(
                                                'active'
                                            )
                                );


                            document
                                .querySelectorAll(
                                    '.tab-content'
                                )
                                .forEach(
                                    c =>
                                        c.classList
                                            .remove(
                                                'active'
                                            )
                                );


                            const tab =
                                document.querySelector(
                                    '.tab[data-target="dp-publications"]'
                                );


                            if (tab) {

                                tab.classList.add(
                                    'active'
                                );


                                document
                                    .getElementById(
                                        'dp-publications'
                                    )
                                    .classList.add(
                                        'active'
                                    );

                            }

                        }
                    )
            );


        const toggle =
            document.getElementById(
                'toggleRight'
            );


        const right =
            document.querySelector(
                '.right-panel'
            );


        if (
            toggle &&
            right
        ) {

            toggle.addEventListener(
                'click',
                function() {

                    if (
                        right.style.display ===
                        'none'
                    ) {

                        right.style.display =
                            '';

                        toggle.textContent =
                            'Collapse';

                    } else {

                        right.style.display =
                            'none';

                        toggle.textContent =
                            'Expand';

                    }

                }
            );

        }

    })();


    /* =========================================================
       ESCAPE TO CLOSE MODAL
       ========================================================= */

    document.addEventListener(
        'keydown',
        function(e) {

            if (
                e.key === 'Escape'
            ) {

                const modal =
                    document.getElementById(
                        'pubModal'
                    );


                if (modal)
                    modal.style.display =
                        'none';

            }

        }
    );

</script>


</body>

</html>