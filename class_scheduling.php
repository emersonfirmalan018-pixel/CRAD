```php
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'db.php';

/* =========================================================
   TEACHER DATA
   ========================================================= */

$teachers = [
    'Dr. Maria Santos',
    'Prof. John Mendoza',
    'Ms. Carla Lopez',
    'Dr. Michael Reyes',
    'Prof. Ana Garcia',
    'Mr. Rafael Castillo'
];

$teacherInfo = [
    'Dr. Maria Santos' => [
        'photo' => 'Uploads/Prof.jpg',
        'department' => 'Information Technology',
        'position' => 'Associate Professor',
        'specialization' => 'Web Development'
    ],
    'Prof. John Mendoza' => [
        'photo' => 'uploads/john-mendoza.jpg',
        'department' => 'Information Technology',
        'position' => 'Professor',
        'specialization' => 'Data Structures'
    ],
    'Ms. Carla Lopez' => [
        'photo' => 'uploads/carla-lopez.jpg',
        'department' => 'Information Technology',
        'position' => 'Instructor',
        'specialization' => 'Database Systems'
    ],
    'Dr. Michael Reyes' => [
        'photo' => 'uploads/michael-reyes.jpg',
        'department' => 'Information Technology',
        'position' => 'Department Faculty',
        'specialization' => 'Network Security'
    ],
    'Prof. Ana Garcia' => [
        'photo' => 'uploads/ana-garcia.jpg',
        'department' => 'Information Technology',
        'position' => 'Professor',
        'specialization' => 'Programming'
    ],
    'Mr. Rafael Castillo' => [
        'photo' => 'uploads/rafael-castillo.jpg',
        'department' => 'Information Technology',
        'position' => 'Instructor',
        'specialization' => 'Computer Ethics'
    ]
];

$subjects = [
    'IT 101 - Web Fundamentals',
    'IT 102 - Data Structures',
    'IT 103 - Database Systems',
    'IT 104 - Network Security',
    'IT 105 - Programming',
    'IT 106 - Computer Ethics'
];

$sections = ['1A', '2B', '3C', '3D', '4A'];

$buildings = [
    'Main Building',
    'Science Building'
];

$roomTypes = [
    'Regular Classroom',
    'Laboratory',
    'Computer Laboratory'
];

/* =========================================================
   ROOM DATA
   ========================================================= */

$rooms = [
    [
        'number' => '101',
        'building' => 'Main Building',
        'type' => 'Regular Classroom',
        'capacity' => 40,
        'status' => 'Available'
    ],
    [
        'number' => '102',
        'building' => 'Main Building',
        'type' => 'Regular Classroom',
        'capacity' => 40,
        'status' => 'Available'
    ],
    [
        'number' => '201',
        'building' => 'Main Building',
        'type' => 'Laboratory',
        'capacity' => 30,
        'status' => 'Occupied'
    ],
    [
        'number' => '202',
        'building' => 'Main Building',
        'type' => 'Laboratory',
        'capacity' => 30,
        'status' => 'Available'
    ],
    [
        'number' => '301',
        'building' => 'Science Building',
        'type' => 'Computer Laboratory',
        'capacity' => 25,
        'status' => 'Available'
    ],
    [
        'number' => '302',
        'building' => 'Science Building',
        'type' => 'Regular Classroom',
        'capacity' => 50,
        'status' => 'Maintenance'
    ],
    [
        'number' => '401',
        'building' => 'Science Building',
        'type' => 'Regular Classroom',
        'capacity' => 50,
        'status' => 'Available'
    ],
    [
        'number' => '402',
        'building' => 'Science Building',
        'type' => 'Regular Classroom',
        'capacity' => 40,
        'status' => 'Available'
    ],
];

/* =========================================================
   INITIAL CLASS SCHEDULES
   ========================================================= */

if (!isset($_SESSION['class_schedules'])) {
    $_SESSION['class_schedules'] = [
        [
            'teacher' => 'Dr. Maria Santos',
            'subject' => 'IT 101 - Web Fundamentals',
            'section' => '1A',
            'day' => 'Mon',
            'time' => '7:00 AM - 8:00 AM',
            'room' => 'Lab 1'
        ],
        [
            'teacher' => 'Prof. John Mendoza',
            'subject' => 'IT 102 - Data Structures',
            'section' => '2B',
            'day' => 'Tue',
            'time' => '8:00 AM - 9:00 AM',
            'room' => 'Lab 2'
        ],
        [
            'teacher' => 'Ms. Carla Lopez',
            'subject' => 'IT 103 - Database Systems',
            'section' => '3C',
            'day' => 'Wed',
            'time' => '10:00 AM - 11:00 AM',
            'room' => 'Lab 3'
        ],
        [
            'teacher' => 'Dr. Michael Reyes',
            'subject' => 'IT 104 - Network Security',
            'section' => '3C',
            'day' => 'Thu',
            'time' => '1:00 PM - 2:00 PM',
            'room' => 'Lab 2'
        ],
        [
            'teacher' => 'Prof. Ana Garcia',
            'subject' => 'IT 105 - Programming',
            'section' => '2A',
            'day' => 'Fri',
            'time' => '9:00 AM - 10:00 AM',
            'room' => 'Lab 1'
        ],
        [
            'teacher' => 'Mr. Rafael Castillo',
            'subject' => 'IT 106 - Computer Ethics',
            'section' => '4A',
            'day' => 'Tue',
            'time' => '3:00 PM - 4:00 PM',
            'room' => 'Lab 4'
        ],
    ];
}

/* =========================================================
   ADD SCHEDULE
   ========================================================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $_SESSION['class_schedules'][] = [
        'teacher' => $_POST['teacher'] ?? '',
        'subject' => $_POST['subject'] ?? '',
        'section' => $_POST['section'] ?? '',
        'day' => $_POST['day'] ?? '',
        'time' => $_POST['time'] ?? '',
        'room' => $_POST['room'] ?? '',
    ];

    header('Location: class_scheduling.php?assigned=1');
    exit;
}

/* =========================================================
   FILTERS
   ========================================================= */

$term = $_GET['term'] ?? '1st Semester 2025-2026';
$teacherFilter = $_GET['teacher'] ?? 'All Teachers';
$subjectFilter = $_GET['subject'] ?? 'All Subjects';
$buildingFilter = $_GET['building'] ?? 'All Buildings';
$typeFilter = $_GET['room_type'] ?? 'All Room Types';
$date = $_GET['date'] ?? date('Y-m-d');
$time = $_GET['time'] ?? '07:00';

/* =========================================================
   FILTER SCHEDULES
   ========================================================= */

$schedules = array_values(
    array_filter(
        $_SESSION['class_schedules'],
        function ($schedule) use ($teacherFilter, $subjectFilter) {

            return
                ($teacherFilter === 'All Teachers' ||
                    $schedule['teacher'] === $teacherFilter)

                &&

                ($subjectFilter === 'All Subjects' ||
                    $schedule['subject'] === $subjectFilter);
        }
    )
);

/* =========================================================
   FILTER ROOMS
   ========================================================= */

$filteredRooms = array_values(
    array_filter(
        $rooms,
        function ($room) use ($buildingFilter, $typeFilter) {

            return
                ($buildingFilter === 'All Buildings' ||
                    $room['building'] === $buildingFilter)

                &&

                ($typeFilter === 'All Room Types' ||
                    $room['type'] === $typeFilter);
        }
    )
);

$availableCount = count(
    array_filter(
        $filteredRooms,
        fn($room) => $room['status'] === 'Available'
    )
);

$occupiedCount = count(
    array_filter(
        $filteredRooms,
        fn($room) => $room['status'] === 'Occupied'
    )
);

$maintenanceCount = count(
    array_filter(
        $filteredRooms,
        fn($room) => $room['status'] === 'Maintenance'
    )
);

$username = $_SESSION['username'] ?? 'CRAD Staff';

/* =========================================================
   HELPER FUNCTIONS
   ========================================================= */

function teacherInitials($name)
{
    $words = preg_split('/\s+/', trim($name));

    if (count($words) >= 2) {
        return strtoupper(
            substr($words[count($words) - 2], 0, 1) .
            substr($words[count($words) - 1], 0, 1)
        );
    }

    return strtoupper(substr($name, 0, 2));
}

function teacherPhoto($teacher, $teacherInfo)
{
    if (
        isset($teacherInfo[$teacher]['photo']) &&
        file_exists($teacherInfo[$teacher]['photo'])
    ) {
        return $teacherInfo[$teacher]['photo'];
    }

    return '';
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

    <title>Class Scheduling System | CRAD</title>

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

        /* =====================================================
           PAGE
        ===================================================== */

        .schedule-page {
            background:
                linear-gradient(
                    135deg,
                    #f4f8fc 0%,
                    #eef4fb 50%,
                    #f8fafc 100%
                );
            min-height: 100vh;
        }

        .schedule-content {
            padding: 26px 30px 45px;
        }

        /* =====================================================
           HEADER
        ===================================================== */

        .schedule-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 22px;
        }

        .schedule-heading h1 {
            margin: 0;
            color: #172b4d;
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -.3px;
        }

        .schedule-heading p {
            margin: 6px 0 0;
            color: #718096;
            font-size: 12px;
        }

        .heading-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 13px;
            border-radius: 8px;
            background: #fff;
            border: 1px solid #dce7f4;
            color: #49617d;
            font-size: 11px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(28, 56, 99, .05);
        }

        .heading-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #27ae7a;
            box-shadow: 0 0 0 4px #e2f7ee;
        }

        /* =====================================================
           NOTICE
        ===================================================== */

        .notice {
            margin-bottom: 17px;
            padding: 12px 15px;
            border-radius: 8px;
            background: #e9f8f1;
            border: 1px solid #ccefe0;
            color: #16734f;
            font-size: 12px;
            font-weight: 600;
        }

        /* =====================================================
           MAIN LAYOUT
        ===================================================== */

        .schedule-layout {
            display: grid;
            grid-template-columns:
                minmax(0, 1.25fr)
                minmax(360px, .95fr);
            gap: 20px;
            align-items: start;
        }

        .schedule-panel {
            background: rgba(255,255,255,.98);
            border: 1px solid #dfe8f2;
            border-radius: 12px;
            box-shadow:
                0 5px 20px rgba(28, 56, 99, .07);
            overflow: hidden;
        }

        /* =====================================================
           PANEL HEADER
        ===================================================== */

        .panel-head {
            padding: 18px 19px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .panel-head h2 {
            margin: 0;
            font-size: 16px;
            color: #1c3154;
            font-weight: 800;
        }

        .panel-head p {
            margin: 5px 0 0;
            font-size: 11px;
            color: #8491a5;
        }

        .panel-icon {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #edf4ff;
            color: #2468d8;
            font-size: 18px;
            font-weight: 800;
        }

        /* =====================================================
           BUTTONS
        ===================================================== */

        .filter-button,
        .assign-button {
            height: 34px;
            padding: 0 13px;
            border: 0;
            border-radius: 6px;
            background: #2468d8;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
        }

        .filter-button:hover,
        .assign-button:hover {
            background: #1859bd;
            transform: translateY(-1px);
        }

        .assign-button {
            white-space: nowrap;
        }

        /* =====================================================
           FILTER BAR
        ===================================================== */

        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            padding: 0 19px 15px;
            border-bottom: 1px solid #edf1f5;
        }

        .filter-bar label {
            flex: 1 1 115px;
            min-width: 110px;
            color: #718096;
            font-size: 10px;
            font-weight: 700;
        }

        .filter-bar select,
        .filter-bar input {
            display: block;
            width: 100%;
            height: 34px;
            margin-top: 5px;
            padding: 0 9px;
            border: 1px solid #d8e0e9;
            border-radius: 6px;
            background: #fff;
            color: #35445d;
            font: inherit;
            font-size: 11px;
            outline: none;
            transition: .2s;
        }

        .filter-bar select:focus,
        .filter-bar input:focus {
            border-color: #2468d8;
            box-shadow: 0 0 0 3px rgba(36,104,216,.08);
        }

        /* =====================================================
           TEACHER TABLE
        ===================================================== */

        .table-wrap {
            overflow-x: auto;
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .schedule-table th {
            padding: 11px 12px;
            background: #f7f9fc;
            color: #7b8798;
            text-align: left;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;
            white-space: nowrap;
        }

        .schedule-table td {
            padding: 12px;
            border-top: 1px solid #edf1f5;
            color: #40506a;
            white-space: nowrap;
            vertical-align: middle;
        }

        .schedule-table tbody tr {
            transition: .15s ease;
        }

        .schedule-table tbody tr:hover {
            background: #f9fbff;
        }

        /* =====================================================
           TEACHER PROFILE CELL
        ===================================================== */

        .teacher-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 185px;
        }

        .teacher-avatar {
            width: 39px;
            height: 39px;
            flex-shrink: 0;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                linear-gradient(
                    135deg,
                    #2468d8,
                    #6a8fda
                );
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            border: 2px solid #fff;
            box-shadow:
                0 2px 7px rgba(31,64,112,.18);
        }

        .teacher-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .teacher-name {
            color: #203a69;
            font-weight: 800;
            font-size: 11px;
        }

        .teacher-role {
            margin-top: 3px;
            color: #8b98a9;
            font-size: 9px;
        }

        /* =====================================================
           SUBJECT
        ===================================================== */

        .subject-code {
            color: #2468d8;
            font-size: 9px;
            font-weight: 800;
            margin-bottom: 3px;
        }

        .subject-name {
            color: #40506a;
            font-size: 10px;
        }

        /* =====================================================
           SECTION BADGE
        ===================================================== */

        .section-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            padding: 5px 8px;
            border-radius: 5px;
            background: #edf4ff;
            color: #2468d8;
            font-size: 10px;
            font-weight: 800;
        }

        /* =====================================================
           DAY / TIME
        ===================================================== */

        .day-label {
            color: #203a69;
            font-weight: 800;
            margin-bottom: 3px;
        }

        .time-label {
            color: #7b8798;
            font-size: 10px;
        }

        /* =====================================================
           ROOM
        ===================================================== */

        .room-label {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 8px;
            border-radius: 5px;
            background: #f5f7fa;
            color: #52627c;
            font-size: 10px;
            font-weight: 700;
        }

        /* =====================================================
           VIEW BUTTON
        ===================================================== */

        .view-button {
            border: 1px solid #cbdaf0;
            border-radius: 5px;
            background: #f4f8ff;
            color: #2563b8;
            padding: 5px 10px;
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
        }

        .view-button:hover {
            background: #e7f0ff;
        }

        /* =====================================================
           ROOM SUMMARY
        ===================================================== */

        .room-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 9px;
            padding: 15px 19px;
        }

        .summary-box {
            position: relative;
            padding: 13px;
            border-radius: 8px;
            background: #f7fafc;
            border: 1px solid #edf1f5;
            overflow: hidden;
        }

        .summary-box::after {
            content: "";
            position: absolute;
            right: -15px;
            bottom: -15px;
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: rgba(255,255,255,.55);
        }

        .summary-box strong {
            display: block;
            font-size: 21px;
            color: #1c3154;
        }

        .summary-box span {
            display: block;
            margin-top: 3px;
            color: #718096;
            font-size: 9px;
            font-weight: 700;
        }

        .summary-box.available {
            background: #f0fbf7;
            border-color: #d4f2e5;
        }

        .summary-box.available strong {
            color: #15966a;
        }

        .summary-box.occupied {
            background: #fff5f5;
            border-color: #f6dddd;
        }

        .summary-box.occupied strong {
            color: #d14343;
        }

        .summary-box.maintenance {
            background: #f6f8fa;
        }

        /* =====================================================
           STATUS
        ===================================================== */

        .status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 800;
        }

        .status::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .status-available {
            background: #dff7eb;
            color: #13845d;
        }

        .status-occupied {
            background: #ffe5e5;
            color: #c43a3a;
        }

        .status-maintenance {
            background: #edf0f3;
            color: #6b7280;
        }

        /* =====================================================
           FOOTER COUNT
        ===================================================== */

        .table-footer {
            padding: 12px 19px;
            color: #8994a5;
            font-size: 10px;
            background: #fbfcfe;
            border-top: 1px solid #edf1f5;
        }

        /* =====================================================
           MODAL
        ===================================================== */

        .schedule-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1100;
            background: rgba(16, 31, 57, .48);
            align-items: center;
            justify-content: center;
            padding: 20px;
            backdrop-filter: blur(3px);
        }

        .schedule-modal.open {
            display: flex;
        }

        .modal-card {
            width: min(540px, 100%);
            background: #fff;
            border-radius: 13px;
            padding: 22px;
            box-shadow:
                0 20px 60px rgba(16, 31, 57, .25);
            animation: modalIn .18s ease;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: translateY(8px) scale(.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-title {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 18px;
        }

        .modal-title-icon {
            width: 40px;
            height: 40px;
            border-radius: 9px;
            background: #edf4ff;
            color: #2468d8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 800;
        }

        .modal-card h2 {
            margin: 0;
            color: #1c3154;
            font-size: 18px;
        }

        .modal-card-subtitle {
            margin-top: 3px;
            color: #8994a5;
            font-size: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 13px;
        }

        .form-grid label {
            color: #718096;
            font-size: 10px;
            font-weight: 800;
        }

        .form-grid select,
        .form-grid input {
            display: block;
            width: 100%;
            height: 36px;
            margin-top: 5px;
            padding: 0 9px;
            border: 1px solid #d8e0e9;
            border-radius: 6px;
            font: inherit;
            font-size: 11px;
            color: #35445d;
            background: #fff;
            outline: none;
        }

        .form-grid select:focus,
        .form-grid input:focus {
            border-color: #2468d8;
            box-shadow: 0 0 0 3px rgba(36,104,216,.08);
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #edf1f5;
        }

        .cancel-button {
            height: 34px;
            padding: 0 13px;
            border: 1px solid #d8e0e9;
            border-radius: 6px;
            background: #fff;
            color: #52627c;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
        }

        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 1100px) {

            .schedule-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {

            .schedule-content {
                padding: 18px 14px 30px;
            }

            .schedule-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .heading-badge {
                display: none;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .topbar {
                padding: 0 18px;
            }

            .panel-head {
                align-items: flex-start;
                flex-direction: column;
            }

            .assign-button {
                width: 100%;
            }

            .room-summary {
                grid-template-columns: 1fr;
            }
        }

    </style>

</head>

<body class="schedule-page">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main">

        <!-- TOPBAR -->
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

                <div
                    class="notification"
                    aria-label="Notifications"
                >
                    ♧
                </div>

                <div class="user-circle">
                    <?= e(strtoupper(substr($username, 0, 2))) ?>
                </div>

            </div>

        </header>

        <section class="schedule-content">

            <!-- PAGE HEADING -->
            <div class="schedule-heading">

                <div>
                    <h1>Class Scheduling System</h1>

                    <p>
                        Manage teacher schedules and check room availability
                        for efficient class planning.
                    </p>
                </div>

                <div class="heading-badge">
                    <span class="heading-dot"></span>
                    Academic Scheduling
                </div>

            </div>

            <?php if (isset($_GET['assigned'])): ?>

                <div class="notice">
                    ✓ Schedule assigned successfully and added to the teacher schedule mapping.
                </div>

            <?php endif; ?>


            <div class="schedule-layout">

                <!-- =====================================================
                     TEACHER SCHEDULE
                ====================================================== -->

                <section class="schedule-panel">

                    <div class="panel-head">

                        <div style="display:flex;align-items:center;gap:11px;">

                            <div class="panel-icon">
                                👨‍🏫
                            </div>

                            <div>
                                <h2>Teacher Schedule Mapping</h2>

                                <p>
                                    View and manage the teaching schedule of faculty members.
                                </p>
                            </div>

                        </div>

                        <button
                            class="assign-button"
                            type="button"
                            onclick="openScheduleModal()"
                        >
                            ＋ Assign Schedule
                        </button>

                    </div>


                    <!-- FILTERS -->

                    <form
                        class="filter-bar"
                        method="get"
                    >

                        <label>
                            Academic Term

                            <select name="term">

                                <option <?= $term === '1st Semester 2025-2026' ? 'selected' : '' ?>>
                                    1st Semester 2025-2026
                                </option>

                                <option <?= $term === '2nd Semester 2025-2026' ? 'selected' : '' ?>>
                                    2nd Semester 2025-2026
                                </option>

                            </select>

                        </label>


                        <label>
                            Teacher

                            <select name="teacher">

                                <option>All Teachers</option>

                                <?php foreach ($teachers as $teacher): ?>

                                    <option
                                        <?= $teacherFilter === $teacher ? 'selected' : '' ?>
                                    >
                                        <?= e($teacher) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </label>


                        <label>
                            Subject

                            <select name="subject">

                                <option>All Subjects</option>

                                <?php foreach ($subjects as $subject): ?>

                                    <option
                                        <?= $subjectFilter === $subject ? 'selected' : '' ?>
                                    >
                                        <?= e($subject) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </label>


                        <button
                            class="filter-button"
                            type="submit"
                        >
                            Apply
                        </button>

                    </form>


                    <!-- SCHEDULE TABLE -->

                    <div class="table-wrap">

                        <table class="schedule-table">

                            <thead>

                                <tr>
                                    <th>Teacher</th>
                                    <th>Subject</th>
                                    <th>Section</th>
                                    <th>Day &amp; Time</th>
                                    <th>Room</th>
                                    <th></th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php if (empty($schedules)): ?>

                                    <tr>

                                        <td
                                            colspan="6"
                                            style="text-align:center;padding:30px;color:#8994a5;"
                                        >
                                            No schedules found.
                                        </td>

                                    </tr>

                                <?php else: ?>

                                    <?php foreach ($schedules as $schedule): ?>

                                        <?php
                                        $teacher = $schedule['teacher'];
                                        $info = $teacherInfo[$teacher] ?? [];
                                        $photo = teacherPhoto($teacher, $teacherInfo);
                                        ?>

                                        <tr>

                                            <!-- TEACHER -->

                                            <td>

                                                <div class="teacher-cell">

                                                    <div class="teacher-avatar">

                                                        <?php if ($photo): ?>

                                                            <img
                                                                src="<?= e($photo) ?>"
                                                                alt="<?= e($teacher) ?>"
                                                                onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
                                                            >

                                                            <span style="display:none;">
                                                                <?= e(teacherInitials($teacher)) ?>
                                                            </span>

                                                        <?php else: ?>

                                                            <?= e(teacherInitials($teacher)) ?>

                                                        <?php endif; ?>

                                                    </div>


                                                    <div>

                                                        <div class="teacher-name">
                                                            <?= e($teacher) ?>
                                                        </div>

                                                        <div class="teacher-role">
                                                            <?= e($info['position'] ?? 'Faculty Member') ?>
                                                        </div>

                                                    </div>

                                                </div>

                                            </td>


                                            <!-- SUBJECT -->

                                            <td>

                                                <?php
                                                $subjectParts = explode(' - ', $schedule['subject'], 2);
                                                ?>

                                                <div class="subject-code">
                                                    <?= e($subjectParts[0]) ?>
                                                </div>

                                                <div class="subject-name">
                                                    <?= e($subjectParts[1] ?? $schedule['subject']) ?>
                                                </div>

                                            </td>


                                            <!-- SECTION -->

                                            <td>

                                                <span class="section-badge">
                                                    <?= e($schedule['section']) ?>
                                                </span>

                                            </td>


                                            <!-- TIME -->

                                            <td>

                                                <div class="day-label">
                                                    <?= e($schedule['day']) ?>
                                                </div>

                                                <div class="time-label">
                                                    <?= e($schedule['time']) ?>
                                                </div>

                                            </td>


                                            <!-- ROOM -->

                                            <td>

                                                <span class="room-label">
                                                    ▣ <?= e($schedule['room']) ?>
                                                </span>

                                            </td>


                                            <!-- VIEW -->

                                            <td>

                                                <button
                                                    class="view-button"
                                                    type="button"
                                                    onclick="viewTeacher(
                                                        <?= htmlspecialchars(
                                                            json_encode($teacher),
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>,
                                                        <?= htmlspecialchars(
                                                            json_encode($schedule['subject']),
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>,
                                                        <?= htmlspecialchars(
                                                            json_encode($schedule['section']),
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>,
                                                        <?= htmlspecialchars(
                                                            json_encode($schedule['day']),
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>,
                                                        <?= htmlspecialchars(
                                                            json_encode($schedule['time']),
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>,
                                                        <?= htmlspecialchars(
                                                            json_encode($schedule['room']),
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                    )"
                                                >
                                                    View
                                                </button>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>


                    <div class="table-footer">

                        Showing
                        <?= count($schedules) ?>
                        of
                        <?= count($_SESSION['class_schedules']) ?>
                        schedules

                    </div>

                </section>


                <!-- =====================================================
                     ROOM AVAILABILITY
                ====================================================== -->

                <section class="schedule-panel">

                    <div class="panel-head">

                        <div style="display:flex;align-items:center;gap:11px;">

                            <div class="panel-icon">
                                🏫
                            </div>

                            <div>

                                <h2>Room Availability Checker</h2>

                                <p>
                                    Check available rooms for a specific date and time.
                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- ROOM FILTER -->

                    <form
                        class="filter-bar"
                        method="get"
                    >

                        <label>
                            Date

                            <input
                                type="date"
                                name="date"
                                value="<?= e($date) ?>"
                            >

                        </label>


                        <label>
                            Time

                            <input
                                type="time"
                                name="time"
                                value="<?= e($time) ?>"
                            >

                        </label>


                        <label>
                            Building

                            <select name="building">

                                <option>All Buildings</option>

                                <?php foreach ($buildings as $building): ?>

                                    <option
                                        <?= $buildingFilter === $building ? 'selected' : '' ?>
                                    >
                                        <?= e($building) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </label>


                        <label>
                            Room Type

                            <select name="room_type">

                                <option>All Room Types</option>

                                <?php foreach ($roomTypes as $roomType): ?>

                                    <option
                                        <?= $typeFilter === $roomType ? 'selected' : '' ?>
                                    >
                                        <?= e($roomType) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </label>


                        <button
                            class="filter-button"
                            type="submit"
                        >
                            Check Availability
                        </button>

                    </form>


                    <!-- ROOM SUMMARY -->

                    <div class="room-summary">

                        <div class="summary-box available">

                            <strong>
                                <?= $availableCount ?>
                            </strong>

                            <span>
                                Available Rooms
                            </span>

                        </div>


                        <div class="summary-box occupied">

                            <strong>
                                <?= $occupiedCount ?>
                            </strong>

                            <span>
                                Occupied Rooms
                            </span>

                        </div>


                        <div class="summary-box maintenance">

                            <strong>
                                <?= $maintenanceCount ?>
                            </strong>

                            <span>
                                Maintenance
                            </span>

                        </div>

                    </div>


                    <!-- ROOM TABLE -->

                    <div class="table-wrap">

                        <table class="schedule-table">

                            <thead>

                                <tr>
                                    <th>Room #</th>
                                    <th>Building</th>
                                    <th>Room Type</th>
                                    <th>Capacity</th>
                                    <th>Status</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($filteredRooms as $room): ?>

                                    <tr>

                                        <td>
                                            <strong>
                                                <?= e($room['number']) ?>
                                            </strong>
                                        </td>

                                        <td>
                                            <?= e($room['building']) ?>
                                        </td>

                                        <td>
                                            <?= e($room['type']) ?>
                                        </td>

                                        <td>
                                            <?= e($room['capacity']) ?>
                                            seats
                                        </td>

                                        <td>

                                            <span
                                                class="status status-<?= strtolower($room['status']) ?>"
                                            >
                                                <?= e($room['status']) ?>
                                            </span>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>


                    <div class="table-footer">

                        Showing
                        <?= count($filteredRooms) ?>
                        of
                        <?= count($rooms) ?>
                        rooms for
                        <?= e($date) ?>
                        at
                        <?= e(date('g:i A', strtotime($time))) ?>

                    </div>

                </section>

            </div>

        </section>

    </main>


    <!-- =========================================================
         ASSIGN SCHEDULE MODAL
    ========================================================== -->

    <div
        class="schedule-modal"
        id="scheduleModal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="assignTitle"
    >

        <form
            class="modal-card"
            method="post"
        >

            <div class="modal-title">

                <div class="modal-title-icon">
                    ＋
                </div>

                <div>

                    <h2 id="assignTitle">
                        Assign Teacher Schedule
                    </h2>

                    <div class="modal-card-subtitle">
                        Create a new class schedule mapping
                    </div>

                </div>

            </div>


            <div class="form-grid">

                <label>
                    Teacher

                    <select
                        name="teacher"
                        required
                    >

                        <?php foreach ($teachers as $teacher): ?>

                            <option>
                                <?= e($teacher) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </label>


                <label>
                    Subject

                    <select
                        name="subject"
                        required
                    >

                        <?php foreach ($subjects as $subject): ?>

                            <option>
                                <?= e($subject) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </label>


                <label>
                    Section

                    <select
                        name="section"
                        required
                    >

                        <?php foreach ($sections as $section): ?>

                            <option>
                                <?= e($section) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </label>


                <label>
                    Day

                    <select
                        name="day"
                        required
                    >

                        <?php foreach (
                            ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
                            as $day
                        ): ?>

                            <option>
                                <?= e($day) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </label>


                <label>
                    Time

                    <input
                        name="time"
                        required
                        placeholder="7:00 AM - 8:00 AM"
                    >

                </label>


                <label>
                    Assigned Room

                    <input
                        name="room"
                        required
                        placeholder="Lab 1"
                    >

                </label>

            </div>


            <div class="modal-actions">

                <button
                    class="cancel-button"
                    type="button"
                    onclick="closeScheduleModal()"
                >
                    Cancel
                </button>

                <button
                    class="assign-button"
                    type="submit"
                >
                    Save Schedule
                </button>

            </div>

        </form>

    </div>


    <!-- =========================================================
         JAVASCRIPT
    ========================================================== -->

    <script>

        function toggleSidebar() {
            document.body.classList.toggle('sidebar-hidden');
        }


        function openScheduleModal() {
            document
                .getElementById('scheduleModal')
                .classList.add('open');
        }


        function closeScheduleModal() {
            document
                .getElementById('scheduleModal')
                .classList.remove('open');
        }


        document
            .getElementById('scheduleModal')
            .addEventListener('click', function(event) {

                if (event.target === this) {
                    closeScheduleModal();
                }

            });


        /* =====================================================
           TEACHER SCHEDULE DETAILS
        ===================================================== */

        function viewTeacher(
            teacher,
            subject,
            section,
            day,
            time,
            room
        ) {

            alert(
                'TEACHER SCHEDULE\n\n' +
                'Teacher: ' + teacher + '\n' +
                'Subject: ' + subject + '\n' +
                'Section: ' + section + '\n' +
                'Schedule: ' + day + ' | ' + time + '\n' +
                'Room: ' + room
            );

        }


        /* =====================================================
           ESC KEY CLOSE MODAL
        ===================================================== */

        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {
                closeScheduleModal();
            }

        });

    </script>

</body>

</html>

