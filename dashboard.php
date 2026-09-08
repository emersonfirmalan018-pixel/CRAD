<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

/*
|--------------------------------------------------------------------------
| USER INFORMATION
|--------------------------------------------------------------------------
*/

$username = $_SESSION['username'] ?? 'CRAD Staff';

/*
|--------------------------------------------------------------------------
| DASHBOARD DATA
|--------------------------------------------------------------------------
| You can replace these sample values with database queries later.
|--------------------------------------------------------------------------
*/

$totalStudents = 1250;
$newApplicants = 186;
$enrollmentRequests = 342;
$registrarRequests = 128;

$pendingRequests = 76;
$approvedRequests = 214;
$completedRequests = 138;


/*
|--------------------------------------------------------------------------
| STACKED AREA CHART DATA
|--------------------------------------------------------------------------
*/

$months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August"
];

$enrollmentData = [
    85, 110, 125, 150, 180, 210, 250, 290
];

$registrarData = [
    35, 45, 50, 65, 70, 90, 110, 125
];

$admissionData = [
    25, 30, 40, 45, 60, 75, 85, 100
];

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>CRAD Office Dashboard</title>

<link rel="icon" type="image/png" href="logo.png">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Shared stylesheet -->
<link rel="stylesheet" href="assets/style.css">

<style>

/* =====================================================

    display: none;
}

.has-submenu .submenu a {
    display: block;
    padding: 10px 18px;
    color: #cbd6f2;
    text-decoration: none;
    border-radius: 6px;
    font-size: 15px;
    font-weight: 600;
}

.has-submenu .submenu a:hover { background: #3b5ba6; color: white; }


.menu-icon {

    width: 25px;

    text-align: center;

    font-size: 21px;
}


/* =====================================================
   MAIN CONTENT
   ===================================================== */

.main {

    margin-left: 220px;

    min-height: 100vh;

    transition: 0.3s;
}


.sidebar-hidden .main {

    margin-left: 0;
}


/* =====================================================
   TOP BAR
   ===================================================== */

.topbar {

    height: 85px;

    background: white;

    border-bottom:
        1px solid #e2e8f0;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 35px;

    position: sticky;

    top: 0;

    z-index: 500;
}


.menu-button {

    width: 45px;
    height: 45px;

    border: none;

    background: transparent;

    font-size: 29px;

    color: #526684;

    cursor: pointer;

    border-radius: 8px;
}


.menu-button:hover {

    background: #f1f5f9;
}


.topbar-right {

    display: flex;

    align-items: center;

    gap: 25px;
}


.clock {

    color: #17263d;

    font-size: 16px;

    font-weight: 500;
}


.notification {

    font-size: 23px;

    color: #61728e;
}


.user-circle {

    width: 42px;
    height: 42px;

    border-radius: 50%;

    background: #344d91;

    display: flex;

    align-items: center;

    justify-content: center;

    color: white;

    font-weight: 700;
}


/* =====================================================
   CONTENT
   ===================================================== */

.content {

    padding: 35px 45px;
}


.breadcrumb {

    color: #5147e8;

    font-size: 16px;

    font-weight: 600;

    margin-bottom: 12px;
}


.page-title {

    font-size: 38px;

    font-weight: 800;

    color: #132544;

    margin-bottom: 8px;
}


.page-description {

    color: #718096;

    font-size: 16px;

    margin-bottom: 30px;
}


/* =====================================================
   CARDS
   ===================================================== */

.stats-grid {

    display: grid;

    grid-template-columns: repeat(6, 1fr);

    gap: 18px;

    margin-bottom: 22px;
}


.stat-card {

    background: white;

    border-radius: 12px;

    padding: 14px 16px;

    box-shadow: 0 6px 18px rgba(7,24,58,0.06);

    border: 1px solid rgba(13,30,63,0.06);
}


.stat-header {

    display: flex;

    align-items: center;

    gap: 12px;
}


.stat-icon {

    width: 48px;
    height: 48px;

    border-radius: 12px;

    background: #e9edff;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 23px;

    color: #344f9d;
}


.stat-title {

    margin-top: 20px;

    color: #718096;

    font-size: 15px;

    font-weight: 600;
}


.stat-value {

    margin-top: 6px;

    color: #142746;

    font-size: 31px;

    font-weight: 800;
}


/* =====================================================
   CHART CARD
   ===================================================== */

.chart-card {

    background: white;

    border-radius: 16px;

    padding: 28px;

    box-shadow:
        0 2px 8px
        rgba(15, 23, 42, 0.06);

    border: 1px solid #e5eaf2;

    margin-bottom: 30px;

    animation: chartFadeIn 1.1s ease-out;
}

@keyframes chartFadeIn {
    from {
        opacity: 0;
        transform: translateY(16px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}


.chart-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 25px;
}


.chart-title {

    font-size: 23px;

    font-weight: 800;

    color: #142746;
}


.chart-subtitle {

    margin-top: 5px;

    color: #7a879b;

    font-size: 14px;
}


.chart-container {

    position: relative;

    height: 390px;

    width: 100%;
}


/* =====================================================
   BOTTOM CARDS
   ===================================================== */

.bottom-grid {

    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 25px;
}


.activity-card {

    background: white;

    border-radius: 16px;

    padding: 28px;

    border: 1px solid #e5eaf2;

    box-shadow:
        0 2px 8px
        rgba(15, 23, 42, 0.06);
}


.card-title {

    font-size: 21px;

    font-weight: 800;

    margin-bottom: 22px;

    color: #142746;
}


.request-row {

    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 16px 0;

    border-bottom:
        1px solid #edf0f5;
}


.request-row:last-child {

    border-bottom: none;
}


.request-name {

    font-weight: 600;

    color: #263752;
}


.request-count {

    background: #edf1ff;

    color: #344f9d;

    padding: 6px 13px;

    border-radius: 20px;

    font-size: 13px;

    font-weight: 700;
}


/* =====================================================
   STATUS
   ===================================================== */

.stat-icon {

    width: 46px;

    height: 46px;

    border-radius: 10px;

    background: #eef5ff;

    display: grid;

    place-items: center;

    font-size: 20px;

    color: #25438f;
}

.stat-meta {
    display:flex;
    flex-direction:column;
}

.stat-sub {
    font-size:12px;
    color:#6b7b95;
}

.quick-actions {
    margin-top:18px;
    margin-bottom:22px;
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}

.quick-actions .btn {
    padding:10px 14px;
    border-radius:8px;
    font-weight:700;
    color:white;
    border:none;
    cursor:pointer;
}

.btn-primary { background:#2563eb }
.btn-green { background:#10b981 }
.btn-purple { background:#8b5cf6 }
.btn-orange { background:#fb923c }
.btn-cyan { background:#06b6d4 }
.btn-red { background:#ef4444 }



.status-number {

    display: block;

    font-size: 28px;

    font-weight: 800;

    color: #203b78;
}


.status-label {

    display: block;

    margin-top: 5px;

    font-size: 13px;

    color: #718096;
}


/* =====================================================
   RESPONSIVE
   ===================================================== */

@media (max-width: 1200px) {

    .stats-grid {

        grid-template-columns:
            repeat(2, 1fr);
    }
}


@media (max-width: 900px) {

    .sidebar {

        left: -360px;
    }

    .sidebar {

        left: -320px;
    }

    .sidebar-hidden .sidebar {

        left: -320px;
    }

    .main {

        margin-left: 0;
    }

    .stats-grid {

        grid-template-columns: 1fr;
    }

    .bottom-grid {

        grid-template-columns: 1fr;
    }

    .content {

        padding: 25px;
    }
}


@media (max-width: 600px) {

    .topbar {

        padding: 0 18px;
    }

    .clock {

        display: none;
    }

    .page-title {

        font-size: 30px;
    }

    .status-grid {

        grid-template-columns: 1fr;
    }
}

</style>

</head>


<body>


<!-- Sidebar include (centralized) -->
<?php include 'includes/sidebar.php'; ?>


<!-- =====================================================
     MAIN
     ===================================================== -->

<main class="main">


    <!-- TOP BAR -->

    <header class="topbar">

        <button
            class="menu-button"
            onclick="toggleSidebar()"
        >
            ☰
        </button>


        <div class="topbar-right">

            <div
                class="clock"
                id="clock"
            >
                08:12:43 AM
            </div>

            <div class="notification">
                ♧
            </div>

            <div class="user-circle">
                <?= strtoupper(substr($username, 0, 2)) ?>
            </div>

        </div>

    </header>


    <!-- =================================================
         CONTENT
         ================================================= -->

    <section class="content">


        <div class="breadcrumb">
            CRAD Office
        </div>


        <h1 class="page-title">
            CRAD Dashboard
        </h1>


        <p class="page-description">
            College Registrar and Admissions Office
            Management Dashboard
        </p>


        <!-- =================================================
             STATISTICS
             ================================================= -->

        <div class="stats-grid">

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">📊</div>
                    <div class="stat-meta">
                        <div class="stat-title">Total Research</div>
                        <div class="stat-value">214</div>
                        <div class="stat-sub">+12% this month</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-meta">
                        <div class="stat-title">Pending Requests</div>
                        <div class="stat-value">76</div>
                        <div class="stat-sub">Needs review</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">👥</div>
                    <div class="stat-meta">
                        <div class="stat-title">Assigned Advisers</div>
                        <div class="stat-value">48</div>
                        <div class="stat-sub">Active projects</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">💰</div>
                    <div class="stat-meta">
                        <div class="stat-title">Approved Funding</div>
                        <div class="stat-value">₱125,000</div>
                        <div class="stat-sub">This month</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">📅</div>
                    <div class="stat-meta">
                        <div class="stat-title">Upcoming Defenses</div>
                        <div class="stat-value">8</div>
                        <div class="stat-sub">This week</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">✅</div>
                    <div class="stat-meta">
                        <div class="stat-title">Completed Research</div>
                        <div class="stat-value">90</div>
                        <div class="stat-sub">+8% this month</div>
                    </div>
                </div>
            </div>

        </div>

        <div class="quick-actions">
            <button class="btn btn-primary">+ Add Research</button>
            <button class="btn btn-green">Assign Adviser</button>
            <button class="btn btn-purple">Schedule Defense</button>
            <button class="btn btn-orange">Review Funding</button>
            <button class="btn btn-cyan">Review Document</button>
            <button class="btn btn-red">Generate Report</button>
        </div>


        <!-- =================================================
             CHARTS GRID (left: area, right: donut + pending)
             ================================================= -->

        <div class="charts-grid">

            <div class="left-chart">

                <div class="chart-card">

                    <div class="chart-header">

                        <div>

                            <div class="chart-title">
                                Monthly Research Performance
                            </div>

                            <div class="chart-subtitle">
                                Submitted, approved and completed by month
                            </div>

                        </div>

                    </div>

                    <div class="chart-container">
                        <canvas id="stackedAreaChart"></canvas>
                    </div>

                </div>

                <div class="chart-card" style="margin-top:18px;">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">Monthly Performance Trend</div>
                            <div class="chart-subtitle">Total office requests by month</div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="lineTrendChart"></canvas>
                    </div>
                </div>

            </div>

            <div class="right-column">

                <div class="chart-card donut-card">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">Research Status Overview</div>
                            <div class="chart-subtitle">Status breakdown</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:18px;">
                        <div style="flex:1;max-width:220px;">
                            <canvas id="donutChart"></canvas>
                        </div>
                        <div style="flex:1;padding-left:8px;">
                            <div class="status-list">
                                <div class="status-row"><span class="status-dot" style="background:#ffb020"></span> Pending <span class="status-count">35 (16%)</span></div>
                                <div class="status-row"><span class="status-dot" style="background:#06b6d4"></span> Ongoing <span class="status-count">82 (38%)</span></div>
                                <div class="status-row"><span class="status-dot" style="background:#8b5cf6"></span> For Review <span class="status-count">41 (19%)</span></div>
                                <div class="status-row"><span class="status-dot" style="background:#34d399"></span> Completed <span class="status-count">56 (27%)</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="chart-card" style="margin-top:18px;">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">Pending Actions</div>
                            <div class="chart-subtitle">Items that need attention</div>
                        </div>
                    </div>
                    <div class="pending-actions">
                        <div class="pending-row"><span class="pending-bullet">📄</span> Research proposals for review <span class="pending-count">12</span> <a class="review-link">Review</a></div>
                        <div class="pending-row"><span class="pending-bullet">👥</span> Adviser assignments pending <span class="pending-count">7</span> <a class="review-link">Review</a></div>
                        <div class="pending-row"><span class="pending-bullet">💰</span> Funding applications for review <span class="pending-count">15</span> <a class="review-link">Review</a></div>
                        <div class="pending-row"><span class="pending-bullet">📆</span> Defense schedules for confirmation <span class="pending-count">5</span> <a class="review-link">Review</a></div>
                        <div class="pending-row"><span class="pending-bullet">🔎</span> Documents for verification <span class="pending-count">9</span> <a class="review-link">Review</a></div>
                    </div>
                </div>

            </div>

        </div>


        <!-- =================================================
             BOTTOM
             ================================================= -->

        <div class="bottom-grid">


            <!-- REQUESTS -->

            <div class="activity-card">

                <div class="card-title">
                    Recent CRAD Requests
                </div>


                <div class="request-row">

                    <span class="request-name">
                        Admission Requests
                    </span>

                    <span class="request-count">
                        186
                    </span>

                </div>


                <div class="request-row">

                    <span class="request-name">
                        Enrollment Requests
                    </span>

                    <span class="request-count">
                        342
                    </span>

                </div>


                <div class="request-row">

                    <span class="request-name">
                        Registrar Documents
                    </span>

                    <span class="request-count">
                        128
                    </span>

                </div>


                <div class="request-row">

                    <span class="request-name">
                        Verification Requests
                    </span>

                    <span class="request-count">
                        94
                    </span>

                </div>

            </div>


            <!-- STATUS -->

            <div class="activity-card">

                <div class="card-title">
                    Request Status
                </div>


                <div class="status-grid">


                    <div class="status-box">

                        <span class="status-number">
                            <?= $pendingRequests ?>
                        </span>

                        <span class="status-label">
                            Pending
                        </span>

                    </div>


                    <div class="status-box">

                        <span class="status-number">
                            <?= $approvedRequests ?>
                        </span>

                        <span class="status-label">
                            Approved
                        </span>

                    </div>


                    <div class="status-box">

                        <span class="status-number">
                            <?= $completedRequests ?>
                        </span>

                        <span class="status-label">
                            Completed
                        </span>

                    </div>


                </div>

            </div>


        </div>


    </section>

</main>


<script>

/* =====================================================
   SIDEBAR
   ===================================================== */

function toggleSidebar() {

    document.body.classList.toggle(
        "sidebar-hidden"
    );

}


/* =====================================================
   CLOCK
   ===================================================== */

function updateClock() {

    const clock =
        document.getElementById("clock");

    if (!clock) return;

    const now = new Date();

    clock.innerText =
        now.toLocaleTimeString(
            "en-US",
            {
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit"
            }
        );
}

updateClock();

setInterval(
    updateClock,
    1000
);


/* =====================================================
   STACKED AREA CHART
   ===================================================== */

const ctx =
    document
        .getElementById("stackedAreaChart")
        .getContext("2d");


new Chart(ctx, {

    type: "line",

    data: {

        labels:
            <?= json_encode($months) ?>,

        datasets: [

            {
                label: "Enrollment",

                data:
                    <?= json_encode($enrollmentData) ?>,

                fill: true,

                tension: 0.45,

                borderWidth: 3,

                backgroundColor:
                    "rgba(37, 67, 143, 0.45)",

                borderColor:
                    "#25438f",

                pointRadius: 4,

                pointHoverRadius: 6,

                pointBackgroundColor: "#25438f",

                pointBorderColor: "#ffffff"
            },

            {
                label: "Registrar",

                data:
                    <?= json_encode($registrarData) ?>,

                fill: true,

                tension: 0.45,

                borderWidth: 3,

                backgroundColor:
                    "rgba(81, 71, 232, 0.40)",

                borderColor:
                    "#5147e8",

                pointRadius: 4,

                pointHoverRadius: 6,

                pointBackgroundColor: "#5147e8",

                pointBorderColor: "#ffffff"
            },

            {
                label: "Admissions",

                data:
                    <?= json_encode($admissionData) ?>,

                fill: true,

                tension: 0.45,

                borderWidth: 3,

                backgroundColor:
                    "rgba(0, 170, 220, 0.35)",

                borderColor:
                    "#009dcc",

                pointRadius: 4,

                pointHoverRadius: 6,

                pointBackgroundColor: "#009dcc",

                pointBorderColor: "#ffffff"
            }

        ]

    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        animation: {
            duration: 1500,
            easing: "easeOutCubic",
            delay: (context) => context.dataIndex * 80
        },

        interaction: {

            mode: "index",

            intersect: false
        },

        plugins: {

            legend: {

                position: "top",

                labels: {

                    usePointStyle: true,

                    padding: 20

                }

            },

            tooltip: {

                mode: "index",

                intersect: false

            }

        },

        scales: {

            x: {

                stacked: true,

                grid: {

                    display: false

                }

            },

            y: {

                stacked: true,

                beginAtZero: true,

                grid: {

                    color:
                        "rgba(148, 163, 184, 0.15)"

                },

                ticks: {

                    precision: 0

                }

            }

        }

    }

});

const lineCtx = document.getElementById("lineTrendChart").getContext("2d");

const totalTrend = <?= json_encode(array_map(function($a, $b, $c) {
    return $a + $b + $c;
}, $enrollmentData, $registrarData, $admissionData)) ?>;

new Chart(lineCtx, {

    type: "line",

    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [{
            label: "Total Requests",
            data: totalTrend,
            borderColor: "#3b82f6",
            backgroundColor: "rgba(59, 130, 246, 0.12)",
            borderWidth: 3,
            fill: true,
            tension: 0.45,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: "#3b82f6",
            pointBorderColor: "#ffffff"
        }]
    },

    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            duration: 1600,
            easing: "easeOutCubic",
            delay: (context) => context.dataIndex * 90
        },
        plugins: {
            legend: {
                position: "top",
                labels: {
                    usePointStyle: true,
                    padding: 18
                }
            },
            tooltip: {
                mode: "nearest",
                intersect: false
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: "rgba(148, 163, 184, 0.15)"
                },
                ticks: {
                    precision: 0
                }
            }
        }
    }

});

// -----------------------------
// DONUT / RESEARCH STATUS
// -----------------------------
const donutCtx = document.getElementById('donutChart').getContext('2d');
const donutData = {
    labels: ['Pending','Ongoing','For Review','Completed'],
    datasets: [{
        data: [35,82,41,56],
        backgroundColor: ['#ffb020','#06b6d4','#8b5cf6','#34d399'],
        hoverOffset: 6
    }]
};

const centerTextPlugin = {
    id: 'centerText',
    beforeDraw(chart) {
        const {ctx, chartArea: {width, height}} = chart;
        ctx.save();
        const total = donutData.datasets[0].data.reduce((a,b)=>a+b,0);
        ctx.font = '700 20px Arial';
        ctx.fillStyle = '#142744';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(total, width/2 + chart.chartArea.left, height/2 + chart.chartArea.top);
        ctx.restore();
    }
};

new Chart(donutCtx, {
    type: 'doughnut',
    data: donutData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%'
    },
    plugins: [centerTextPlugin]
});

</script>

<script>
// Toggle a submenu by id (used by submenu buttons)
function toggleSubmenu(id) {
    const el = document.getElementById(id);
    const btn = document.querySelector(`[data-target="${id}"]`);
    if (!el || !btn) return;
    const expanded = btn.getAttribute('aria-expanded') === 'true';
    if (expanded) {
        el.style.display = 'none';
        btn.setAttribute('aria-expanded', 'false');
        const arrow = btn.querySelector('.submenu-arrow');
        if (arrow) arrow.style.transform = '';
    } else {
        el.style.display = 'block';
        btn.setAttribute('aria-expanded', 'true');
        const arrow = btn.querySelector('.submenu-arrow');
        if (arrow) arrow.style.transform = 'rotate(180deg)';
    }
}

// Enhance buttons to allow keyboard interaction (Enter/Space)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.submenu-toggle').forEach(function(btn) {
        btn.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const target = btn.getAttribute('data-target');
                if (target) toggleSubmenu(target);
            }
        });
    });
});
</script>


</body>

</html>