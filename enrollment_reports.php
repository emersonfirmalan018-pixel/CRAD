<?php

/* =========================================================
   SESSION
========================================================= */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* =========================================================
   AUTHENTICATION
========================================================= */

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


/* =========================================================
   DATABASE
========================================================= */

require_once 'db.php';


/* =========================================================
   USER
========================================================= */

$username = $_SESSION['username'] ?? 'Registrar';


/* =========================================================
   FILTERS
========================================================= */

$search = trim($_GET['search'] ?? '');

$studentType = trim($_GET['student_type'] ?? '');

$studentStatus = trim($_GET['student_status'] ?? '');

$program = trim($_GET['program'] ?? '');


/* =========================================================
   ALLOWED OPTIONS
========================================================= */

$studentTypes = [
    'Regular',
    'Irregular',
    'Transferee',
    'Returning Student'
];

$studentStatuses = [
    'Active',
    'Inactive',
    'Graduated',
    'Dropped',
    'Withdrawn'
];


/* =========================================================
   VARIABLES
========================================================= */

$students = [];

$programs = [];

$error = '';

$savedStudent = null;


/* =========================================================
   GET PROGRAM LIST
========================================================= */

try {

    $programQuery = $pdo->query(
        "SELECT DISTINCT academic_program
         FROM student_profiles
         WHERE academic_program IS NOT NULL
         AND academic_program <> ''
         ORDER BY academic_program ASC"
    );

    $programs = $programQuery->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $exception) {

    $programs = [];
}


/* =========================================================
   BUILD STUDENT QUERY
========================================================= */

try {

    $where = [];

    $params = [];


    /* -----------------------------------------------------
       SEARCH
    ----------------------------------------------------- */

    if ($search !== '') {

        $where[] = "
            (
                student_id LIKE ?
                OR first_name LIKE ?
                OR middle_name LIKE ?
                OR last_name LIKE ?
                OR mobile_number LIKE ?
                OR email LIKE ?
                OR academic_program LIKE ?
            )
        ";

        $searchValue = '%' . $search . '%';

        $params[] = $searchValue;
        $params[] = $searchValue;
        $params[] = $searchValue;
        $params[] = $searchValue;
        $params[] = $searchValue;
        $params[] = $searchValue;
        $params[] = $searchValue;
    }


    /* -----------------------------------------------------
       STUDENT TYPE
    ----------------------------------------------------- */

    if (
        $studentType !== '' &&
        in_array($studentType, $studentTypes, true)
    ) {

        $where[] = 'student_type = ?';

        $params[] = $studentType;
    }


    /* -----------------------------------------------------
       STUDENT STATUS
    ----------------------------------------------------- */

    if (
        $studentStatus !== '' &&
        in_array($studentStatus, $studentStatuses, true)
    ) {

        $where[] = 'student_status = ?';

        $params[] = $studentStatus;
    }


    /* -----------------------------------------------------
       ACADEMIC PROGRAM
    ----------------------------------------------------- */

    if ($program !== '') {

        $where[] = 'academic_program = ?';

        $params[] = $program;
    }


    /* -----------------------------------------------------
       FINAL QUERY
    ----------------------------------------------------- */

    $sql = "
        SELECT
            id,
            user_id,
            student_id,
            first_name,
            middle_name,
            last_name,
            suffix,
            date_of_birth,
            place_of_birth,
            sex,
            civil_status,
            nationality,
            religion,
            student_type,
            student_status,
            photo_path,
            mobile_number,
            email,
            current_address,
            permanent_address,
            city_municipality,
            province,
            academic_program
        FROM student_profiles
    ";


    if (!empty($where)) {

        $sql .= ' WHERE ' . implode(' AND ', $where);
    }


    $sql .= "
        ORDER BY
            CASE
                WHEN student_status = 'Active' THEN 1
                WHEN student_status = 'Inactive' THEN 2
                WHEN student_status = 'Returning Student' THEN 3
                WHEN student_status = 'Graduated' THEN 4
                WHEN student_status = 'Dropped' THEN 5
                WHEN student_status = 'Withdrawn' THEN 6
                ELSE 7
            END,
            last_name ASC,
            first_name ASC
    ";


    $stmt = $pdo->prepare($sql);

    $stmt->execute($params);

    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $exception) {

    if ($exception->getCode() === '42S02') {

        $error =
            'The student_profiles table is not installed yet. Import the updated database.sql file first.';

    } else {

        $error =
            'Unable to load enrollment records. Please check your database connection.';
    }
}


/* =========================================================
   REPORT STATISTICS
========================================================= */

$totalStudents = count($students);

$activeStudents = 0;

$inactiveStudents = 0;

$graduatedStudents = 0;

$droppedStudents = 0;

$withdrawnStudents = 0;

$regularStudents = 0;

$irregularStudents = 0;

$transfereeStudents = 0;

$returningStudents = 0;


foreach ($students as $student) {

    switch ($student['student_status'] ?? '') {

        case 'Active':
            $activeStudents++;
            break;

        case 'Inactive':
            $inactiveStudents++;
            break;

        case 'Graduated':
            $graduatedStudents++;
            break;

        case 'Dropped':
            $droppedStudents++;
            break;

        case 'Withdrawn':
            $withdrawnStudents++;
            break;
    }


    switch ($student['student_type'] ?? '') {

        case 'Regular':
            $regularStudents++;
            break;

        case 'Irregular':
            $irregularStudents++;
            break;

        case 'Transferee':
            $transfereeStudents++;
            break;

        case 'Returning Student':
            $returningStudents++;
            break;
    }
}


/* =========================================================
   SAVED STUDENT
========================================================= */

if (
    isset($_GET['saved']) &&
    $_GET['saved'] === '1' &&
    !empty($_GET['student_id'])
) {

    $savedStudentId = trim($_GET['student_id']);

    foreach ($students as $student) {

        if (
            (string) $student['student_id']
            === (string) $savedStudentId
        ) {

            $savedStudent = $student;

            break;
        }
    }
}


/* =========================================================
   HELPER FUNCTIONS
========================================================= */

function fullStudentName(array $student): string
{
    return trim(
        ($student['first_name'] ?? '') . ' ' .
        ($student['middle_name'] ?? '') . ' ' .
        ($student['last_name'] ?? '') . ' ' .
        ($student['suffix'] ?? '')
    );
}


function studentInitials(array $student): string
{
    $initials = '';

    if (!empty($student['first_name'])) {

        $initials .= strtoupper(
            substr($student['first_name'], 0, 1)
        );
    }

    if (!empty($student['last_name'])) {

        $initials .= strtoupper(
            substr($student['last_name'], 0, 1)
        );
    }

    return $initials ?: 'ST';
}


function statusClass(string $status): string
{
    return match ($status) {

        'Active' => 'active',

        'Inactive' => 'inactive',

        'Graduated' => 'graduated',

        'Dropped' => 'dropped',

        'Withdrawn' => 'withdrawn',

        default => 'inactive'
    };
}


function typeClass(string $type): string
{
    return match ($type) {

        'Regular' => 'regular',

        'Irregular' => 'irregular',

        'Transferee' => 'transferee',

        'Returning Student' => 'returning',

        default => 'regular'
    };
}

?>

<!doctype html>

<html lang="en">

<head>

<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1"
>

<title>
    Enrollment Reports | CRAD
</title>

<link
    rel="icon"
    type="image/png"
    href="logo.png"
>

<link
    rel="stylesheet"
    href="assets/style.css"
>


<style>

/* =========================================================
   RESET
========================================================= */

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    background: #eef3f7;

    color: #172033;

    font-family:
        Arial,
        Helvetica,
        sans-serif;
}


/* =========================================================
   MAIN
========================================================= */

.main {

    min-height: 100vh;

    transition: .25s ease;
}

.reports-page {

    background:
        linear-gradient(
            180deg,
            #edf3f7 0%,
            #f5f7fa 100%
        );

    min-height: 100vh;
}


/* =========================================================
   TOP BAR
========================================================= */

.topbar {

    height: 54px;

    background: #ffffff;

    border-bottom: 1px solid #dce4eb;

    display: flex;

    align-items: center;

    padding: 0 22px;

    position: sticky;

    top: 0;

    z-index: 50;

    box-shadow:
        0 1px 5px rgba(20,45,70,.04);
}

.menu-button {

    width: 34px;

    height: 34px;

    border: 0;

    background: transparent;

    color: #344054;

    font-size: 21px;

    cursor: pointer;
}

.menu-button:hover {

    background: #f1f5f9;

    border-radius: 6px;
}

.topbar-right {

    margin-left: auto;

    display: flex;

    align-items: center;

    gap: 17px;
}

.notification {

    position: relative;

    color: #344054;

    font-size: 19px;
}

.notification:after {

    content: "3";

    position: absolute;

    top: -7px;

    right: -7px;

    width: 15px;

    height: 15px;

    display: grid;

    place-items: center;

    border-radius: 50%;

    background: #c8414b;

    color: #fff;

    font-size: 8px;

    font-weight: 800;
}

.user-circle {

    width: 32px;

    height: 32px;

    border-radius: 50%;

    display: grid;

    place-items: center;

    background:
        linear-gradient(
            135deg,
            #173c68,
            #267e8a
        );

    color: #fff;

    font-size: 10px;

    font-weight: 800;

    box-shadow:
        0 2px 5px rgba(22,58,93,.18);
}


/* =========================================================
   CONTENT
========================================================= */

.report-content {

    padding: 22px 28px 40px;

    max-width: 1500px;

    margin: auto;
}


/* =========================================================
   PAGE HEADER
========================================================= */

.report-heading {

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 20px;

    margin-bottom: 15px;
}

.heading-left {

    display: flex;

    align-items: center;

    gap: 12px;
}

.heading-icon {

    width: 42px;

    height: 42px;

    display: grid;

    place-items: center;

    border-radius: 9px;

    background:
        linear-gradient(
            135deg,
            #173b64,
            #237d88
        );

    color: white;

    font-size: 19px;

    box-shadow:
        0 5px 12px rgba(20,63,92,.16);
}

.report-heading h1 {

    margin: 0;

    color: #122d60;

    font-size: 23px;

    font-weight: 800;

    letter-spacing: -.4px;
}

.report-heading p {

    margin: 4px 0 0;

    color: #778496;

    font-size: 10px;
}

.heading-actions {

    display: flex;

    align-items: center;

    gap: 7px;
}


/* =========================================================
   BUTTONS
========================================================= */

.report-button {

    height: 34px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding: 0 14px;

    border: 0;

    border-radius: 5px;

    background:
        linear-gradient(
            135deg,
            #1665ca,
            #1f79d8
        );

    color: white;

    text-decoration: none;

    font-size: 10px;

    font-weight: 800;

    cursor: pointer;

    box-shadow:
        0 3px 7px rgba(25,100,190,.15);

    transition: .15s ease;
}

.report-button:hover {

    transform: translateY(-1px);
}

.report-button.secondary {

    background: #fff;

    color: #52627a;

    border: 1px solid #d6dfe8;

    box-shadow: none;
}

.report-button.green {

    background:
        linear-gradient(
            135deg,
            #16845d,
            #22a06f
        );
}


/* =========================================================
   NOTICE
========================================================= */

.notice {

    display: flex;

    align-items: center;

    gap: 9px;

    margin-bottom: 13px;

    padding: 10px 13px;

    border-radius: 6px;

    background: #e8f8ef;

    color: #14794f;

    border: 1px solid #c9ecd9;

    font-size: 11px;

    font-weight: 600;
}

.notice:before {

    content: "✓";

    width: 20px;

    height: 20px;

    display: grid;

    place-items: center;

    border-radius: 50%;

    background: #c8edd8;

    font-weight: 900;
}

.notice.error {

    background: #fff1f1;

    color: #b43e3e;

    border-color: #f1d0d0;
}

.notice.error:before {

    content: "!";
}


/* =========================================================
   SUMMARY CARDS
========================================================= */

.summary-grid {

    display: grid;

    grid-template-columns:
        repeat(5, 1fr);

    gap: 9px;

    margin-bottom: 14px;
}

.summary-card {

    min-height: 88px;

    position: relative;

    overflow: hidden;

    background: #fff;

    border: 1px solid #dfe6ed;

    border-radius: 8px;

    padding: 12px;

    box-shadow:
        0 3px 10px rgba(28,56,99,.045);
}

.summary-card:after {

    content: "";

    position: absolute;

    width: 58px;

    height: 58px;

    right: -20px;

    bottom: -23px;

    border-radius: 50%;

    background: rgba(30,107,139,.06);
}

.summary-icon {

    width: 28px;

    height: 28px;

    display: grid;

    place-items: center;

    border-radius: 6px;

    background: #edf5f9;

    color: #247188;

    font-size: 12px;

    margin-bottom: 7px;
}

.summary-label {

    color: #8a95a3;

    font-size: 7px;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .25px;
}

.summary-value {

    margin-top: 3px;

    color: #24385b;

    font-size: 17px;

    font-weight: 800;
}


/* =========================================================
   REPORT CARD
========================================================= */

.report-card {

    background: #fff;

    border: 1px solid #dce4ec;

    box-shadow:
        0 5px 18px rgba(28,56,99,.055);

    border-radius: 9px;

    overflow: hidden;
}


/* =========================================================
   REPORT HEADER
========================================================= */

.report-card-header {

    min-height: 58px;

    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 11px 17px;

    border-bottom: 1px solid #e7edf3;

    background:
        linear-gradient(
            90deg,
            #ffffff,
            #fbfcfd
        );
}

.report-header-left {

    display: flex;

    align-items: center;

    gap: 9px;
}

.card-header-icon {

    width: 30px;

    height: 30px;

    display: grid;

    place-items: center;

    border-radius: 6px;

    background: #edf5fa;

    color: #237188;

    font-size: 12px;
}

.report-card-header h2 {

    margin: 0;

    color: #17336a;

    font-size: 13px;

    font-weight: 800;
}

.report-card-header span {

    display: block;

    margin-top: 3px;

    color: #8792a1;

    font-size: 8px;
}


/* =========================================================
   FILTER AREA
========================================================= */

.filters {

    padding: 13px 17px;

    border-bottom: 1px solid #e7edf3;

    background: #f8fafc;
}

.filter-grid {

    display: grid;

    grid-template-columns:
        2fr
        1fr
        1fr
        1fr
        auto;

    gap: 9px;

    align-items: end;
}

.filter-field label {

    display: block;

    margin-bottom: 5px;

    color: #64748b;

    font-size: 8px;

    font-weight: 800;
}

.filter-field input,
.filter-field select {

    width: 100%;

    height: 34px;

    padding: 0 9px;

    border: 1px solid #d7e0e9;

    border-radius: 5px;

    background: #fff;

    color: #263b60;

    font-family: inherit;

    font-size: 10px;

    outline: none;
}

.filter-field input:focus,
.filter-field select:focus {

    border-color: #3d86cf;

    box-shadow:
        0 0 0 3px rgba(45,124,205,.09);
}

.search-wrapper {

    position: relative;
}

.search-wrapper input {

    padding-left: 31px;
}

.search-icon {

    position: absolute;

    left: 10px;

    top: 50%;

    transform: translateY(-50%);

    color: #8794a5;

    font-size: 12px;

    pointer-events: none;
}

.filter-buttons {

    display: flex;

    gap: 5px;
}


/* =========================================================
   REPORT META
========================================================= */

.report-meta {

    min-height: 42px;

    padding: 0 17px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 10px;

    border-bottom: 1px solid #e7edf3;

    background: #fff;
}

.result-count {

    color: #748196;

    font-size: 9px;
}

.result-count strong {

    color: #263b60;

    font-weight: 800;
}

.report-date {

    color: #8a95a3;

    font-size: 8px;
}


/* =========================================================
   TABLE
========================================================= */

.table-wrapper {

    width: 100%;

    overflow-x: auto;
}

.students-table {

    width: 100%;

    min-width: 950px;

    border-collapse: collapse;
}

.students-table thead {

    background: #f5f8fb;
}

.students-table th {

    padding: 10px 11px;

    text-align: left;

    color: #65748a;

    font-size: 8px;

    font-weight: 800;

    text-transform: uppercase;

    letter-spacing: .35px;

    border-bottom: 1px solid #dfe7ee;

    white-space: nowrap;
}

.students-table td {

    padding: 9px 11px;

    color: #40516d;

    font-size: 9px;

    border-bottom: 1px solid #edf1f5;

    vertical-align: middle;
}

.students-table tbody tr {

    transition: background .12s ease;
}

.students-table tbody tr:hover {

    background: #f8fbfd;
}

.students-table tbody tr:last-child td {

    border-bottom: 0;
}


/* =========================================================
   STUDENT CELL
========================================================= */

.student-cell {

    display: flex;

    align-items: center;

    gap: 9px;

    min-width: 210px;
}

.table-avatar {

    width: 34px;

    height: 34px;

    flex-shrink: 0;

    border-radius: 6px;

    display: grid;

    place-items: center;

    overflow: hidden;

    background:
        linear-gradient(
            135deg,
            #173c68,
            #348f93
        );

    color: #fff;

    font-size: 9px;

    font-weight: 800;
}

.table-avatar img {

    width: 100%;

    height: 100%;

    object-fit: cover;
}

.student-name {

    color: #1c3564;

    font-size: 9px;

    font-weight: 800;
}

.student-number {

    margin-top: 3px;

    color: #8793a2;

    font-size: 7px;
}


/* =========================================================
   PROGRAM
========================================================= */

.program-name {

    color: #30466b;

    font-weight: 700;

    max-width: 230px;

    line-height: 1.35;
}


/* =========================================================
   BADGES
========================================================= */

.status-badge,
.type-badge {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-height: 21px;

    padding: 3px 7px;

    border-radius: 20px;

    font-size: 7px;

    font-weight: 800;

    white-space: nowrap;
}


/* STATUS */

.status-badge.active {

    background: #e6f7ee;

    color: #158257;
}

.status-badge.inactive {

    background: #eef1f4;

    color: #697586;
}

.status-badge.graduated {

    background: #e7f1fc;

    color: #2874bd;
}

.status-badge.dropped {

    background: #fff4df;

    color: #b47b20;
}

.status-badge.withdrawn {

    background: #fff0f1;

    color: #bd4b56;
}


/* TYPE */

.type-badge.regular {

    background: #edf5fc;

    color: #2a70b3;
}

.type-badge.irregular {

    background: #f4effd;

    color: #7653aa;
}

.type-badge.transferee {

    background: #fff3e9;

    color: #b76729;
}

.type-badge.returning {

    background: #eaf8f5;

    color: #25816e;
}


/* =========================================================
   ACTION
========================================================= */

.view-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-height: 27px;

    padding: 0 9px;

    border-radius: 4px;

    border: 1px solid #d2deea;

    background: #fff;

    color: #2469aa;

    text-decoration: none;

    font-size: 8px;

    font-weight: 800;

    transition: .15s ease;

    white-space: nowrap;
}

.view-button:hover {

    background: #eef6fd;

    border-color: #b8cee3;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state {

    padding: 55px 20px;

    text-align: center;
}

.empty-icon {

    width: 52px;

    height: 52px;

    margin: 0 auto 12px;

    display: grid;

    place-items: center;

    border-radius: 10px;

    background: #edf5fa;

    color: #237188;

    font-size: 20px;
}

.empty-state h3 {

    margin: 0;

    color: #24385b;

    font-size: 13px;
}

.empty-state p {

    margin: 6px auto 15px;

    max-width: 400px;

    color: #8793a2;

    font-size: 9px;

    line-height: 1.5;
}


/* =========================================================
   FOOTER
========================================================= */

.report-footer {

    min-height: 48px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    padding: 10px 17px;

    background: #f8fafc;

    border-top: 1px solid #e5ebf0;
}

.footer-note {

    color: #7d8997;

    font-size: 8px;
}

.footer-note strong {

    color: #52627a;
}


/* =========================================================
   PRINT
========================================================= */

@media print {

    body {

        background: #fff;
    }

    .topbar,
    .sidebar,
    .heading-actions,
    .filters,
    .report-footer,
    .view-button {

        display: none !important;
    }

    .report-content {

        padding: 0;

        max-width: none;
    }

    .report-heading {

        margin-bottom: 15px;
    }

    .report-card {

        border: 0;

        box-shadow: none;
    }

    .summary-grid {

        grid-template-columns:
            repeat(5, 1fr);
    }

    .students-table {

        min-width: 0;
    }
}


/* =========================================================
   SIDEBAR
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

@media(max-width:1200px) {

    .summary-grid {

        grid-template-columns:
            repeat(3, 1fr);
    }

    .filter-grid {

        grid-template-columns:
            2fr
            1fr
            1fr;
    }

    .filter-buttons {

        grid-column: 1 / -1;
    }
}


@media(max-width:850px) {

    .report-content {

        padding: 18px;
    }

    .summary-grid {

        grid-template-columns:
            repeat(2, 1fr);
    }

    .filter-grid {

        grid-template-columns:
            repeat(2, 1fr);
    }

    .filter-field:first-child {

        grid-column: 1 / -1;
    }

    .filter-buttons {

        grid-column: auto;
    }
}


@media(max-width:650px) {

    .report-content {

        padding: 14px 12px 28px;
    }

    .report-heading {

        align-items: flex-start;

        flex-direction: column;
    }

    .heading-actions {

        width: 100%;
    }

    .heading-actions .report-button {

        flex: 1;
    }

    .report-heading h1 {

        font-size: 19px;
    }

    .summary-grid {

        grid-template-columns:
            1fr 1fr;
    }

    .filter-grid {

        grid-template-columns: 1fr;
    }

    .filter-field:first-child {

        grid-column: auto;
    }

    .filter-buttons {

        width: 100%;
    }

    .filter-buttons .report-button {

        flex: 1;
    }

    .report-meta {

        align-items: flex-start;

        flex-direction: column;

        justify-content: center;

        padding-top: 8px;

        padding-bottom: 8px;
    }
}


@media(max-width:430px) {

    .summary-grid {

        grid-template-columns: 1fr;
    }
}

</style>

</head>


<body class="reports-page">


<?php include 'includes/sidebar.php'; ?>


<main class="main">


<!-- =====================================================
     TOP BAR
===================================================== -->

<header class="topbar">

    <button
        class="menu-button"
        type="button"
        onclick="toggleSidebar()"
        aria-label="Toggle sidebar"
    >
        ☰
    </button>


    <div class="topbar-right">

        <div class="notification">
            ♧
        </div>

        <div class="user-circle">

            <?= e(
                strtoupper(
                    substr($username, 0, 2)
                )
            ) ?>

        </div>

    </div>

</header>


<section class="report-content">


<!-- =====================================================
     PAGE HEADER
===================================================== -->

<div class="report-heading">

    <div class="heading-left">

        <div class="heading-icon">
            ▥
        </div>

        <div>

            <h1>
                Enrollment Reports
            </h1>

            <p>
                Registrar Department
                &nbsp; • &nbsp;
                Student Enrollment Management
            </p>

        </div>

    </div>


    <div class="heading-actions">

        <a
            href="profile.php?new=1"
            class="report-button"
        >
            + New Student
        </a>

        <button
            type="button"
            class="report-button secondary"
            onclick="window.print()"
        >
            ▣ Print Report
        </button>

    </div>

</div>


<!-- =====================================================
     SUCCESS MESSAGE
===================================================== -->

<?php if ($savedStudent): ?>

<div class="notice">

    Student
    <strong>
        <?= e(fullStudentName($savedStudent)) ?>
    </strong>
    was saved successfully and added to the enrollment report.

</div>

<?php endif; ?>


<!-- =====================================================
     ERROR
===================================================== -->

<?php if ($error): ?>

<div class="notice error">

    <?= e($error) ?>

</div>

<?php endif; ?>


<!-- =====================================================
     SUMMARY
===================================================== -->

<div class="summary-grid">


    <!-- TOTAL -->

    <div class="summary-card">

        <div class="summary-icon">
            ▦
        </div>

        <div class="summary-label">
            Total Students
        </div>

        <div class="summary-value">
            <?= number_format($totalStudents) ?>
        </div>

    </div>


    <!-- ACTIVE -->

    <div class="summary-card">

        <div class="summary-icon">
            ●
        </div>

        <div class="summary-label">
            Active Students
        </div>

        <div class="summary-value">
            <?= number_format($activeStudents) ?>
        </div>

    </div>


    <!-- INACTIVE -->

    <div class="summary-card">

        <div class="summary-icon">
            ◌
        </div>

        <div class="summary-label">
            Inactive Students
        </div>

        <div class="summary-value">
            <?= number_format($inactiveStudents) ?>
        </div>

    </div>


    <!-- GRADUATED -->

    <div class="summary-card">

        <div class="summary-icon">
            ✓
        </div>

        <div class="summary-label">
            Graduated
        </div>

        <div class="summary-value">
            <?= number_format($graduatedStudents) ?>
        </div>

    </div>


    <!-- OTHER -->

    <div class="summary-card">

        <div class="summary-icon">
            ◇
        </div>

        <div class="summary-label">
            Other Status
        </div>

        <div class="summary-value">

            <?= number_format(
                $droppedStudents + $withdrawnStudents
            ) ?>

        </div>

    </div>

</div>


<!-- =====================================================
     MAIN REPORT
===================================================== -->

<div class="report-card">


<!-- =====================================================
     REPORT HEADER
===================================================== -->

<div class="report-card-header">

    <div class="report-header-left">

        <div class="card-header-icon">
            ▣
        </div>

        <div>

            <h2>
                Student Enrollment Master List
            </h2>

            <span>
                Official student enrollment and classification records
            </span>

        </div>

    </div>

</div>


<!-- =====================================================
     FILTERS
===================================================== -->

<form
    method="get"
    class="filters"
>

    <div class="filter-grid">


        <!-- SEARCH -->

        <div class="filter-field">

            <label for="search">
                Search Student
            </label>

            <div class="search-wrapper">

                <span class="search-icon">
                    ⌕
                </span>

                <input
                    id="search"
                    name="search"
                    type="text"
                    placeholder="Student ID, name, email, program..."
                    value="<?= e($search) ?>"
                >

            </div>

        </div>


        <!-- TYPE -->

        <div class="filter-field">

            <label for="student_type">
                Student Type
            </label>

            <select
                id="student_type"
                name="student_type"
            >

                <option value="">
                    All Types
                </option>

                <?php foreach ($studentTypes as $type): ?>

                    <option
                        value="<?= e($type) ?>"
                        <?= $studentType === $type
                            ? 'selected'
                            : '' ?>
                    >
                        <?= e($type) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>


        <!-- STATUS -->

        <div class="filter-field">

            <label for="student_status">
                Enrollment Status
            </label>

            <select
                id="student_status"
                name="student_status"
            >

                <option value="">
                    All Statuses
                </option>

                <?php foreach ($studentStatuses as $status): ?>

                    <option
                        value="<?= e($status) ?>"
                        <?= $studentStatus === $status
                            ? 'selected'
                            : '' ?>
                    >
                        <?= e($status) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>


        <!-- PROGRAM -->

        <div class="filter-field">

            <label for="program">
                Academic Program
            </label>

            <select
                id="program"
                name="program"
            >

                <option value="">
                    All Programs
                </option>

                <?php foreach ($programs as $programOption): ?>

                    <option
                        value="<?= e($programOption) ?>"
                        <?= $program === $programOption
                            ? 'selected'
                            : '' ?>
                    >
                        <?= e($programOption) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>


        <!-- BUTTONS -->

        <div class="filter-buttons">

            <button
                type="submit"
                class="report-button"
            >
                Filter
            </button>

            <a
                href="enrollment_report.php"
                class="report-button secondary"
            >
                Reset
            </a>

        </div>


    </div>

</form>


<!-- =====================================================
     REPORT META
===================================================== -->

<div class="report-meta">

    <div class="result-count">

        Showing

        <strong>
            <?= number_format($totalStudents) ?>
        </strong>

        student record<?= $totalStudents === 1 ? '' : 's' ?>

        <?php if ($search !== ''): ?>

            matching
            <strong>
                "<?= e($search) ?>"
            </strong>

        <?php endif; ?>

    </div>


    <div class="report-date">

        Generated:
        <?= date('M d, Y h:i A') ?>

    </div>

</div>


<!-- =====================================================
     TABLE
===================================================== -->

<?php if (!empty($students)): ?>

<div class="table-wrapper">

<table class="students-table">

<thead>

<tr>

    <th>
        Student
    </th>

    <th>
        Academic Program
    </th>

    <th>
        Type
    </th>

    <th>
        Status
    </th>

    <th>
        Sex
    </th>

    <th>
        Contact
    </th>

    <th>
        Location
    </th>

    <th>
        Action
    </th>

</tr>

</thead>


<tbody>

<?php foreach ($students as $student): ?>

<tr>


<!-- =====================================================
     STUDENT
===================================================== -->

<td>

    <div class="student-cell">

        <div class="table-avatar">

            <?php if (!empty($student['photo_path'])): ?>

                <img
                    src="<?= e($student['photo_path']) ?>"
                    alt="Student photo"
                >

            <?php else: ?>

                <?= e(studentInitials($student)) ?>

            <?php endif; ?>

        </div>


        <div>

            <div class="student-name">

                <?= e(
                    fullStudentName($student)
                    ?: 'Unnamed Student'
                ) ?>

            </div>

            <div class="student-number">

                ID:
                <?= e(
                    $student['student_id']
                    ?: 'Not assigned'
                ) ?>

            </div>

        </div>

    </div>

</td>


<!-- =====================================================
     PROGRAM
===================================================== -->

<td>

    <div class="program-name">

        <?= e(
            $student['academic_program']
            ?: 'Not specified'
        ) ?>

    </div>

</td>


<!-- =====================================================
     TYPE
===================================================== -->

<td>

    <span
        class="type-badge <?= e(
            typeClass(
                $student['student_type'] ?? ''
            )
        ) ?>"
    >

        <?= e(
            $student['student_type']
            ?: 'Not specified'
        ) ?>

    </span>

</td>


<!-- =====================================================
     STATUS
===================================================== -->

<td>

    <span
        class="status-badge <?= e(
            statusClass(
                $student['student_status'] ?? ''
            )
        ) ?>"
    >

        <?= e(
            $student['student_status']
            ?: 'Not specified'
        ) ?>

    </span>

</td>


<!-- =====================================================
     SEX
===================================================== -->

<td>

    <?= e(
        $student['sex']
        ?: '—'
    ) ?>

</td>


<!-- =====================================================
     CONTACT
===================================================== -->

<td>

    <?php if (!empty($student['mobile_number'])): ?>

        <?= e($student['mobile_number']) ?>

    <?php elseif (!empty($student['email'])): ?>

        <?= e($student['email']) ?>

    <?php else: ?>

        —

    <?php endif; ?>

</td>


<!-- =====================================================
     LOCATION
===================================================== -->

<td>

    <?php

    $locationParts = array_filter([
        $student['city_municipality'] ?? '',
        $student['province'] ?? ''
    ]);

    ?>

    <?= e(
        !empty($locationParts)
            ? implode(', ', $locationParts)
            : '—'
    ) ?>

</td>


<!-- =====================================================
     ACTION
===================================================== -->

<td>

    <a
        href="profile.php?student_id=<?= urlencode(
            $student['student_id']
        ) ?>"
        class="view-button"
    >
        View Profile
    </a>

</td>


</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>


<?php else: ?>


<!-- =====================================================
     EMPTY STATE
===================================================== -->

<div class="empty-state">

    <div class="empty-icon">
        ▣
    </div>

    <h3>
        No student records found
    </h3>

    <p>
        There are no enrollment records matching the current
        search and filter criteria.
    </p>

    <?php if (
        $search !== '' ||
        $studentType !== '' ||
        $studentStatus !== '' ||
        $program !== ''
    ): ?>

        <a
            href="enrollment_report.php"
            class="report-button secondary"
        >
            Clear Filters
        </a>

    <?php else: ?>

        <a
            href="profile.php?new=1"
            class="report-button"
        >
            + Add Student
        </a>

    <?php endif; ?>

</div>


<?php endif; ?>


<!-- =====================================================
     REPORT FOOTER
===================================================== -->

<div class="report-footer">

    <div class="footer-note">

        <strong>
            Registrar Database
        </strong>

        &nbsp; • &nbsp;

        Student enrollment records are based on the
        Student Master Record.

    </div>


    <div class="footer-note">

        Total:
        <strong>
            <?= number_format($totalStudents) ?>
        </strong>

    </div>

</div>


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
   AUTO SUBMIT FILTERS
========================================================= */

const typeFilter =
    document.getElementById(
        'student_type'
    );

const statusFilter =
    document.getElementById(
        'student_status'
    );

const programFilter =
    document.getElementById(
        'program'
    );


if (typeFilter) {

    typeFilter.addEventListener(
        'change',
        function () {

            this.form.submit();

        }
    );
}


if (statusFilter) {

    statusFilter.addEventListener(
        'change',
        function () {

            this.form.submit();

        }
    );
}


if (programFilter) {

    programFilter.addEventListener(
        'change',
        function () {

            this.form.submit();

        }
    );
}


/* =========================================================
   SEARCH ENTER
========================================================= */

const searchInput =
    document.getElementById(
        'search'
    );


if (searchInput) {

    searchInput.addEventListener(
        'keydown',
        function (event) {

            if (event.key === 'Enter') {

                event.preventDefault();

                this.form.submit();
            }

        }
    );
}


/* =========================================================
   PRINT
========================================================= */

function printReport() {

    window.print();

}

</script>


</body>

</html>