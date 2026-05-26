<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("Location: ../../index.php");
  exit;
}
require_once __DIR__ . '/../koneksi.php';

date_default_timezone_set('Asia/Jakarta');

// ================================
// GET ROLE USER LOGIN
// ================================

$userRole = '';

// DETEKSI SEMUA KEMUNGKINAN SESSION ID
$user_id =
  $_SESSION['id']
  ?? $_SESSION['user_id']
  ?? $_SESSION['users_id']
  ?? $_SESSION['admin_id']
  ?? 0;

if (!empty($user_id)) {

  $queryUserRole = mysqli_query($conn, "
        SELECT
            users.id,
            users.role_id,
            roles.role_name
        FROM users
        LEFT JOIN roles
            ON roles.id = users.role_id
        WHERE users.id = '$user_id'
        LIMIT 1
    ");

  if ($queryUserRole && mysqli_num_rows($queryUserRole) > 0) {

    $dataUserRole = mysqli_fetch_assoc($queryUserRole);

    $userRole = strtolower(trim($dataUserRole['role_name']));
  }
}

// ================================
// FUNCTION CHECK ROLE
// ================================
function hasRole($roles = [])
{
  global $userRole;

  $roles = array_map(function ($role) {
    return strtolower(trim($role));
  }, $roles);

  return in_array($userRole, $roles);
}

/* =========================================================
   VISITOR ANALYTICS QUERY
========================================================= */

$chart_labels  = [];
$chart_data    = [];
$queryPages    = null;
$queryDevices  = null;

if (hasRole(['Super Admin', 'moderator', 'admin'])) {

  $filter_page   = $_GET['page_url'] ?? 'all';
  $filter_device = $_GET['device'] ?? 'all';
  $filter_type   = $_GET['type'] ?? 'daily';


  /* =========================
       PAGE FILTER
    ========================= */

  $queryPages = mysqli_query($conn, "
        SELECT DISTINCT page_url
        FROM visitors
        ORDER BY page_url ASC
    ");


  /* =========================
       DEVICE FILTER
    ========================= */

  $queryDevices = mysqli_query($conn, "
        SELECT DISTINCT device
        FROM visitors
        ORDER BY device ASC
    ");


  /* =========================
       WHERE
    ========================= */

  $where = "WHERE 1=1";

  if ($filter_page != 'all') {

    $filter_page_safe = mysqli_real_escape_string($conn, $filter_page);

    $where .= " AND page_url = '$filter_page_safe'";
  }

  if ($filter_device != 'all') {

    $filter_device_safe = mysqli_real_escape_string($conn, $filter_device);

    $where .= " AND device = '$filter_device_safe'";
  }


  /* =========================
       GROUPING
    ========================= */

  if ($filter_type == 'monthly') {

    $group_select = "DATE_FORMAT(visit_date, '%Y-%m')";
    $group_order  = "DATE_FORMAT(visit_date, '%Y-%m')";
  } elseif ($filter_type == 'yearly') {

    $group_select = "YEAR(visit_date)";
    $group_order  = "YEAR(visit_date)";
  } else {

    $group_select = "DATE(visit_date)";
    $group_order  = "DATE(visit_date)";
  }


  /* =========================
       CHART QUERY
    ========================= */

  $queryChart = mysqli_query($conn, "
        SELECT
            $group_select as label_chart,
            COUNT(*) as total_visitor
        FROM visitors
        $where
        GROUP BY label_chart
        ORDER BY $group_order ASC
    ");


  while ($row = mysqli_fetch_assoc($queryChart)) {

    $chart_labels[] = $row['label_chart'];
    $chart_data[]   = (int)$row['total_visitor'];
  }
}

/* =========================================================
   TOTAL PENDAPATAN CHART
========================================================= */

$revenue_labels = [];
$revenue_data   = [];
$total_revenue_all = 0;

// FILTER TANGGAL
$date_from = $_GET['date_from'] ?? '';
$date_to   = $_GET['date_to'] ?? '';

// WHERE
$where_income = "WHERE 1=1";

if (!empty($date_from)) {

  $date_from_safe = mysqli_real_escape_string($conn, $date_from);

  $where_income .= " AND DATE(date_income) >= '$date_from_safe'";
}

if (!empty($date_to)) {

  $date_to_safe = mysqli_real_escape_string($conn, $date_to);

  $where_income .= " AND DATE(date_income) <= '$date_to_safe'";
}

// QUERY CHART
$queryRevenue = mysqli_query($conn, "
    SELECT
        DATE_FORMAT(date_income, '%Y-%m') as label_chart,
        SUM(amount_income) as total_income
    FROM report_income
    $where_income
    GROUP BY DATE_FORMAT(date_income, '%Y-%m')
    ORDER BY DATE_FORMAT(date_income, '%Y-%m') ASC
");

while ($rowRevenue = mysqli_fetch_assoc($queryRevenue)) {

  $revenue_labels[] = $rowRevenue['label_chart'];

  $revenue_data[] = (float)$rowRevenue['total_income'];
}

// TOTAL SEMUA PENDAPATAN
$queryTotalRevenue = mysqli_query($conn, "
    SELECT
        SUM(amount_income) as total_all_income
    FROM report_income
    $where_income
");

$dataTotalRevenue = mysqli_fetch_assoc($queryTotalRevenue);

$total_revenue_all = $dataTotalRevenue['total_all_income'] ?? 0;


// LAPORAN BULANAN
/* =========================================================
   LAPORAN KEUANGAN BULANAN
========================================================= */

$finance_data = [];

// QUERY INCOME
$queryFinanceIncome = mysqli_query($conn, "
    SELECT
        DATE_FORMAT(date_income, '%Y-%m') as month_key,
        SUM(amount_income) as total_income
    FROM report_income
    GROUP BY DATE_FORMAT(date_income, '%Y-%m')
");

// SIMPAN INCOME
while ($income = mysqli_fetch_assoc($queryFinanceIncome)) {

  $month = $income['month_key'];

  $finance_data[$month]['income'] = (float)$income['total_income'];
}

// QUERY EXPENSE
$queryFinanceExpense = mysqli_query($conn, "
    SELECT
        DATE_FORMAT(date_expense, '%Y-%m') as month_key,
        type_expense,
        SUM(amount_expense) as total_expense
    FROM report_expense
    GROUP BY
        DATE_FORMAT(date_expense, '%Y-%m'),
        type_expense
");

// SIMPAN EXPENSE
while ($expense = mysqli_fetch_assoc($queryFinanceExpense)) {

  $month = $expense['month_key'];

  if (!isset($finance_data[$month]['salary'])) {
    $finance_data[$month]['salary'] = 0;
  }

  if (!isset($finance_data[$month]['office'])) {
    $finance_data[$month]['office'] = 0;
  }

  $type = strtolower(trim($expense['type_expense']));

  // PENGELUARAN GAJI
  if (
    strpos($type, 'gaji') !== false ||
    strpos($type, 'salary') !== false
  ) {

    $finance_data[$month]['salary'] +=
      (float)$expense['total_expense'];
  }

  // PENGELUARAN KANTOR
  else {

    $finance_data[$month]['office'] +=
      (float)$expense['total_expense'];
  }
}

// NORMALISASI DATA
foreach ($finance_data as $month => $data) {

  $income = $data['income'] ?? 0;
  $salary = $data['salary'] ?? 0;
  $office = $data['office'] ?? 0;

  $finance_data[$month] = [
    'income' => $income,
    'salary' => $salary,
    'office' => $office
  ];
}
?>

<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Dashboard | Konig Guard Bureau</title>

  <!-- Perfect Scrollbar -->
  <link
    type="text/css"
    href="../assets/vendor/perfect-scrollbar.css"
    rel="stylesheet" />

  <!-- App CSS -->
  <link type="text/css" href="../assets/css/app.css" rel="stylesheet" />

  <!-- Material Design Icons -->
  <link
    type="text/css"
    href="../assets/css/vendor-material-icons.css"
    rel="stylesheet" />

  <!-- Font Awesome FREE Icons -->
  <link
    type="text/css"
    href="../assets/css/vendor-fontawesome-free.css"
    rel="stylesheet" />

  <!-- Flatpickr -->
  <link
    type="text/css"
    href="../assets/css/vendor-flatpickr.css"
    rel="stylesheet" />
  <link
    type="text/css"
    href="../assets/css/vendor-flatpickr-airbnb.css"
    rel="stylesheet" />

  <!-- Vector Maps -->
  <link
    type="text/css"
    href="../assets/vendor/jqvmap/jqvmap.min.css"
    rel="stylesheet" />
</head>

<body class="layout-fluid layout-sticky-subnav">
  <div class="preloader"></div>

  <!-- Header Layout -->
  <div class="mdk-header-layout js-mdk-header-layout">
    <!-- **********************************Top Header********************************** -->
    <?php include 'includes/topheader.php'; ?>
    <!-- **********************************// END Top Header //********************************** -->

    <!-- Header Layout Content -->
    <div class="mdk-header-layout__content page">
      <div class="page__header">
        <div class="container-fluid page__heading-container">
          <div class="page__heading d-flex align-items-end">
            <div class="flex">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                  <li class="breadcrumb-item active" aria-current="page">
                    Dashboard
                  </li>
                </ol>
              </nav>
              <h1 class="m-0">Dashboard</h1>
            </div>
          </div>
        </div>
      </div>
      <!-- // END page__header -->

      <!-- ********************************// START page__content //******************************* -->
      <div class="container-fluid page__container">
        <?php if (hasRole(['Super Admin', 'moderator', 'admin'])): ?>
          <div class="row mt-3 mb-3">
            <h3>Quick Access</h3>
          </div>
        <?php endif; ?>
        <div class="row card-group-row">
          <!-- BUAT ACCESS MENU INI -->
          <?php if (hasRole(['Super Admin', 'moderator', 'admin'])): ?>
            <div class="col-lg-3 col-md-4 card-group-row__col">
              <div class="card card-group-row__card">
                <div class="p-2 d-flex flex-row align-items-center">
                  <div class="avatar avatar-xs mr-2">
                    <span
                      class="avatar-title rounded-circle text-center bg-primary">
                      <i class="material-icons text-white icon-18pt">accessibility</i>
                    </span>
                  </div>
                  <a href="manage_roles.php" class="text-dark">
                    <strong>Manage Role</strong>
                  </a>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <?php if (hasRole(['super admin', 'moderator', 'admin'])): ?>
            <div class="col-lg-3 col-md-4 card-group-row__col">
              <div class="card card-group-row__card">
                <div class="p-2 d-flex flex-row align-items-center">
                  <div class="avatar avatar-xs mr-2">
                    <span
                      class="avatar-title rounded-circle text-center bg-success">
                      <i class="material-icons text-white icon-18pt">person_add</i>
                    </span>
                  </div>
                  <a href="manage_employees.php" class="text-dark">
                    <strong>Tambah Personel</strong>
                  </a>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <?php if (hasRole(['super admin', 'moderator', 'admin'])): ?>
            <div class="col-lg-3 col-md-4 card-group-row__col">
              <div class="card card-group-row__card">
                <div class="p-2 d-flex flex-row align-items-center">
                  <div class="avatar avatar-xs mr-2">
                    <span
                      class="avatar-title rounded-circle text-center bg-info">
                      <i class="material-icons text-white icon-18pt">photo_library</i>
                    </span>
                  </div>
                  <a href="manage_gallery.php" class="text-dark">
                    <strong>Manage Galeri</strong>
                  </a>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <?php if (hasRole(['super admin', 'moderator', 'admin'])): ?>
            <div class="col-lg-3 col-md-4 card-group-row__col">
              <div class="card card-group-row__card">
                <div class="p-2 d-flex flex-row align-items-center">
                  <div class="avatar avatar-xs mr-2">
                    <span
                      class="avatar-title rounded-circle text-center bg-blue">
                      <i class="material-icons text-white icon-18pt">work</i>
                    </span>
                  </div>
                  <a href="manage_job_information.php" class="text-dark">
                    <strong>Manage Lowongan Kerja</strong>
                  </a>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <?php if (hasRole(['super admin', 'moderator', 'admin'])): ?>
            <div class="col-lg-3 col-md-4 card-group-row__col">
              <div class="card card-group-row__card">
                <div class="p-2 d-flex flex-row align-items-center">
                  <div class="avatar avatar-xs mr-2">
                    <span
                      class="avatar-title rounded-circle text-center bg-warning">
                      <i class="material-icons text-white icon-18pt">work</i>
                    </span>
                  </div>
                  <a href="manage_company_structure.php" class="text-dark">
                    <strong>Manage Struktur Organisasi</strong>
                  </a>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <?php if (hasRole(['super admin', 'moderator', 'admin'])): ?>
            <div class="col-lg-3 col-md-4 card-group-row__col">
              <div class="card card-group-row__card">
                <div class="p-2 d-flex flex-row align-items-center">
                  <div class="avatar avatar-xs mr-2">
                    <span
                      class="avatar-title rounded-circle text-center bg-primary">
                      <i class="material-icons text-white icon-18pt">inbox</i>
                    </span>
                  </div>
                  <a href="manage_inbox.php" class="text-dark">
                    <strong>Kotak Masuk</strong>
                  </a>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <?php if (hasRole(['super admin', 'moderator', 'admin'])): ?>
            <div class="col-lg-3 col-md-4 card-group-row__col">
              <div class="card card-group-row__card">
                <div class="p-2 d-flex flex-row align-items-center">
                  <div class="avatar avatar-xs mr-2">
                    <span
                      class="avatar-title rounded-circle text-center bg-primary">
                      <i class="material-icons text-white icon-18pt">layers</i>
                    </span>
                  </div>
                  <a href="manage_post.php" class="text-dark">
                    <strong>Manage Postingan</strong>
                  </a>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <?php if (hasRole(['super admin', 'moderator', 'admin'])): ?>
            <div class="col-lg-3 col-md-4 card-group-row__col">
              <div class="card card-group-row__card">
                <div class="p-2 d-flex flex-row align-items-center">
                  <div class="avatar avatar-xs mr-2">
                    <span
                      class="avatar-title rounded-circle text-center bg-primary">
                      <i class="material-icons text-white icon-18pt">layers</i>
                    </span>
                  </div>
                  <a href="manage_our_clients.php" class="text-dark">
                    <strong>Manage Klien Kami</strong>
                  </a>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <?php if (hasRole(['super admin', 'moderator', 'admin'])): ?>
            <div class="col-lg-3 col-md-4 card-group-row__col">
              <div class="card card-group-row__card">
                <div class="p-2 d-flex flex-row align-items-center">
                  <div class="avatar avatar-xs mr-2">
                    <span
                      class="avatar-title rounded-circle text-center bg-primary">
                      <i class="material-icons text-white icon-18pt">layers</i>
                    </span>
                  </div>
                  <a href="manage_partners.php" class="text-dark">
                    <strong>Manage Mitra Kami</strong>
                  </a>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <!-- <div class="col-lg-3 col-md-4 card-group-row__col">
              <div class="card card-group-row__card">
                <div class="p-2 d-flex flex-row align-items-center">
                  <div class="avatar avatar-xs mr-2">
                    <span
                      class="avatar-title rounded-circle text-center bg-primary"
                    >
                      <i class="material-icons text-white icon-18pt">insert_emoticon</i>
                    </span>
                  </div>
                  <a href="#" class="text-dark">
                    <strong>Manage Testimoni</strong>
                  </a>
                </div>
              </div>
            </div> -->
          <?php if (hasRole(['super admin', 'moderator', 'admin'])): ?>
            <div class="col-lg-3 col-md-4 card-group-row__col">
              <div class="card card-group-row__card">
                <div class="p-2 d-flex flex-row align-items-center">
                  <div class="avatar avatar-xs mr-2">
                    <span
                      class="avatar-title rounded-circle text-center bg-primary">
                      <i class="material-icons text-white icon-18pt">layers</i>
                    </span>
                  </div>
                  <a href="manage_legality.php" class="text-dark">
                    <strong>Manage Legalitas Perusahaan</strong>
                  </a>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <!-- **************************** ANALITIK DAN LAPORAN **************************** -->

        <?php if (hasRole(['Super Admin', 'moderator', 'admin'])): ?>

          <div class="row mt-4 mb-3">

            <div class="col-lg-12">

              <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                  <h3 class="mb-1">
                    Analitik & Laporan
                  </h3>

                  <p class="text-muted mb-0">
                    Statistik visitor website berdasarkan halaman dan device.
                  </p>

                </div>

              </div>

            </div>

          </div>



          <div class="row card-group-row">

            <div class="col-lg-12">

              <div class="card shadow-sm border-0">

                <!-- HEADER -->
                <div class="card-header bg-white border-bottom">

                  <div class="d-flex justify-content-between align-items-center flex-wrap">

                    <h4 class="m-0 font-weight-bold mb-3">
                      Visitor Analytics
                    </h4>


                    <!-- FILTER -->
                    <form method="GET" class="d-flex flex-wrap align-items-center">

                      <!-- PAGE -->
                      <div class="mr-2 mb-2">

                        <select
                          name="page_url"
                          class="form-control"
                          onchange="this.form.submit()">

                          <option value="all">
                            Semua Halaman
                          </option>

                          <?php while ($page = mysqli_fetch_assoc($queryPages)): ?>

                            <option
                              value="<?= htmlspecialchars($page['page_url']) ?>"
                              <?= ($filter_page == $page['page_url']) ? 'selected' : '' ?>>

                              <?= htmlspecialchars($page['page_url']) ?>

                            </option>

                          <?php endwhile; ?>

                        </select>

                      </div>



                      <!-- DEVICE -->
                      <div class="mr-2 mb-2">

                        <select
                          name="device"
                          class="form-control"
                          onchange="this.form.submit()">

                          <option value="all">
                            Semua Device
                          </option>

                          <?php while ($device = mysqli_fetch_assoc($queryDevices)): ?>

                            <option
                              value="<?= htmlspecialchars($device['device']) ?>"
                              <?= ($filter_device == $device['device']) ? 'selected' : '' ?>>

                              <?= htmlspecialchars($device['device']) ?>

                            </option>

                          <?php endwhile; ?>

                        </select>

                      </div>



                      <!-- TYPE -->
                      <div class="mb-2">

                        <select
                          name="type"
                          class="form-control"
                          onchange="this.form.submit()">

                          <option value="daily" <?= ($filter_type == 'daily') ? 'selected' : '' ?>>
                            Harian
                          </option>

                          <option value="monthly" <?= ($filter_type == 'monthly') ? 'selected' : '' ?>>
                            Bulanan
                          </option>

                          <option value="yearly" <?= ($filter_type == 'yearly') ? 'selected' : '' ?>>
                            Tahunan
                          </option>

                        </select>

                      </div>

                    </form>

                  </div>

                </div>



                <!-- BODY -->
                <div class="card-body">

                  <div style="height: 380px;">

                    <canvas id="visitorChart"></canvas>

                  </div>

                </div>

              </div>

            </div>

          </div>

        <?php endif; ?>

        <!-- ****************************PENDAPATAN**************************** -->
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <!-- HEADER -->
              <div
                class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="m-0">Total Pendapatan</h4>
                <a href="manage_income_reports.php" class="btn btn-sm btn-primary">Lihat</a>
              </div>

              <!-- FILTER -->
              <div class="card-body border-bottom">
                <div class="row">
                  <div class="col-md-4">
                    <label>Dari</label>
                    <input type="date" id="dateFrom" class="form-control" />
                  </div>
                  <div class="col-md-4">
                    <label>Sampai</label>
                    <input type="date" id="dateTo" class="form-control" />
                  </div>
                  <div class="col-md-4 d-flex align-items-end">
                    <button id="filterBtn" class="btn btn-primary w-100">
                      Filter
                    </button>
                  </div>
                </div>
              </div>

              <!-- TOTAL SUMMARY -->
              <div class="card-body text-center border-bottom">
                <h6 class="text-muted">Total Pendapatan (1 Tahun)</h6>
                <h2 id="totalRevenueAll" style="font-weight: 600">Rp 0</h2>
              </div>

              <!-- CHART -->
              <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <!-- HEADER -->
              <div
                class="card-header d-flex justify-content-between align-items-center">
                <h4 class="m-0">Laporan Keuangan Bulanan</h4>
                <a href="manage_deposite_reports.php" class="btn btn-sm btn-primary">Lihat</a>
              </div>

              <!-- FILTER -->
              <div class="card-body border-bottom">
                <div class="row align-items-end">
                  <div class="col-md-4">
                    <label>Pilih Bulan</label>
                    <input
                      type="month"
                      id="monthPicker"
                      class="form-control" />
                  </div>

                  <div class="col-md-2">
                    <button id="loadFinance" class="btn btn-success w-100">
                      Tampilkan
                    </button>
                  </div>
                </div>
              </div>

              <!-- KPI -->
              <div class="card-body">
                <div class="row text-center">
                  <div class="col-md-6">
                    <h6>Total Pendapatan</h6>
                    <h2 id="totalIncome">Rp 0</h2>
                  </div>

                  <div class="col-md-6">
                    <h6>Sisa Deposit</h6>
                    <h2 id="totalBalance">Rp 0</h2>
                  </div>
                </div>
              </div>

              <!-- CHART -->
              <div class="card-body">
                <canvas id="financeChart" height="120"></canvas>
              </div>

              <!-- NOTE -->
              <div class="card-body border-top">
                <p class="mb-1"><strong>Catatan:</strong></p>
                <p class="mb-1">
                  • Total Pengeluaran Gaji: <span id="salaryCost">Rp 0</span>
                </p>
                <p class="mb-0">
                  • Pengeluaran Kantor: <span id="officeCost">Rp 0</span>
                </p>
              </div>
            </div>
          </div>
        </div>
        <!-- ********************************** //END page-content ********************************** -->
      </div>
      <!-- ********************************** //END page-content ********************************** -->
    </div>
  </div>
  <!-- // END header-layout -->

  <!-- App Settings FAB -->
  <div id="app-settings" style="display: none">
    <app-settings
      layout-active="fluid"
      :layout-location="{
      'default': 'index.html',
      'fixed': 'fixed-dashboard.html',
      'fluid': 'fluid-dashboard.html',
      'mini': 'mini-dashboard.html'
    }"></app-settings>
  </div>

  <!-- ********************************** // MENU-Drawer ********************************** -->
  <?php include 'includes/drawer_menu.php'; ?>
  <!-- ********************************** //END MENU-drawer ********************************** -->

  <footer class="dashboard-footer mt-4">
    <div class="container-fluid">
      <div class="row align-items-center">
        <!-- LEFT -->
        <div class="col-md-6 text-md-left text-center mb-2 mb-md-0">
          <span class="footer-text">
            © 2026 Konig Guard Bureau. All rights reserved.
          </span>
        </div>
      </div>
    </div>
  </footer>

  <!-- jQuery -->
  <script src="../assets/vendor/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Bootstrap -->
  <script src="../assets/vendor/popper.min.js"></script>
  <script src="../assets/vendor/bootstrap.min.js"></script>

  <!-- Perfect Scrollbar -->
  <script src="../assets/vendor/perfect-scrollbar.min.js"></script>

  <!-- DOM Factory -->
  <script src="../assets/vendor/dom-factory.js"></script>

  <!-- MDK -->
  <script src="../assets/vendor/material-design-kit.js"></script>

  <!-- App -->
  <script src="../assets/js/toggle-check-all.js"></script>
  <script src="../assets/js/check-selected-row.js"></script>
  <script src="../assets/js/dropdown.js"></script>
  <script src="../assets/js/sidebar-mini.js"></script>
  <script src="../assets/js/app.js"></script>

  <!-- App Settings (safe to remove) -->
  <script src="../assets/js/app-settings.js"></script>

  <!-- Flatpickr -->
  <script src="../assets/vendor/flatpickr/flatpickr.min.js"></script>
  <script src="../assets/js/flatpickr.js"></script>

  <!-- Global Settings -->
  <script src="../assets/js/settings.js"></script>

  <!-- Moment.js -->
  <script src="../assets/vendor/moment.min.js"></script>
  <script src="../assets/vendor/moment-range.js"></script>

  <!-- Chart.js -->
  <script src="../assets/vendor/Chart.min.js"></script>

  <!-- CHART SCRIPT GLOBAL -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {

      const visitorCanvas = document.getElementById('visitorChart');

      if (!visitorCanvas) {
        return;
      }

      const visitorCtx = visitorCanvas.getContext('2d');

      new Chart(visitorCtx, {

        type: 'line',

        data: {

          labels: <?= json_encode($chart_labels) ?>,

          datasets: [{
            label: 'Total Visitor',
            data: <?= json_encode($chart_data) ?>,
            borderWidth: 3,
            tension: 0.35,
            pointRadius: 4,
            fill: false
          }]
        },

        options: {

          responsive: true,

          maintainAspectRatio: false,

          interaction: {
            mode: 'index',
            intersect: false
          },

          plugins: {

            legend: {
              display: true
            }
          },

          scales: {

            yAxes: [{

              ticks: {

                beginAtZero: true,

                min: 0,

                stepSize: 1,

                callback: function(value) {

                  if (Number.isInteger(value)) {
                    return value;
                  }

                  return null;
                }
              }
            }]
          }
        }
      });

    });
  </script>

  <script>
    // FORMAT RUPIAH
    function formatRupiah(number) {

      return 'Rp ' + Number(number).toLocaleString('id-ID');
    }

    // TOTAL ALL
    document.getElementById('totalRevenueAll').innerText =
      formatRupiah(<?= $total_revenue_all ?>);

    // CHART
    const revenueCtx = document
      .getElementById('revenueChart')
      .getContext('2d');

    const revenueChart = new Chart(revenueCtx, {

      type: 'line',

      data: {

        labels: <?= json_encode($revenue_labels) ?>,

        datasets: [{

          label: 'Pendapatan',

          data: <?= json_encode($revenue_data) ?>,

          borderWidth: 3,

          tension: 0.35,

          fill: true,

          pointRadius: 4,

          backgroundColor: 'rgba(103,116,223,0.15)',

          borderColor: '#6774DF'
        }]
      },

      options: {

        responsive: true,

        plugins: {

          legend: {
            display: true
          }
        },

        scales: {

          yAxes: [{

            ticks: {

              beginAtZero: true,

              min: 0,

              max: Math.ceil(
                Math.max(...<?= json_encode($revenue_data) ?>) / 1000000
              ) * 1000000 + 1000000,

              stepSize: 1000000,

              callback: function(value) {

                return 'Rp. ' + Number(value).toLocaleString('id-ID');
              }
            }
          }]
        }
      }
    });


    // FILTER BUTTON
    document
      .getElementById('filterBtn')
      .addEventListener('click', function() {

        const from = document.getElementById('dateFrom').value;
        const to = document.getElementById('dateTo').value;

        const url = new URL(window.location.href);

        if (from) {
          url.searchParams.set('date_from', from);
        } else {
          url.searchParams.delete('date_from');
        }

        if (to) {
          url.searchParams.set('date_to', to);
        } else {
          url.searchParams.delete('date_to');
        }

        window.location.href = url.toString();
      });


    // SET VALUE FILTER SAAT RELOAD
    document.getElementById('dateFrom').value =
      "<?= htmlspecialchars($date_from) ?>";

    document.getElementById('dateTo').value =
      "<?= htmlspecialchars($date_to) ?>";
  </script>

  <script>
    // UNTUK PENGELUARAN
    const financeData = <?= json_encode($finance_data) ?>;

    // -----------------------------
    function formatRupiah(num) {
      return "Rp " + num.toLocaleString("id-ID");
    }

    // ANIMASI ANGKA
    function animateValue(id, start, end, duration) {
      let range = end - start;
      let current = start;
      let increment = range / (duration / 16);

      const obj = document.getElementById(id);

      const timer = setInterval(() => {
        current += increment;

        if (
          (increment > 0 && current >= end) ||
          (increment < 0 && current <= end)
        ) {
          current = end;
          clearInterval(timer);
        }

        obj.innerText = formatRupiah(Math.floor(current));
      }, 16);
    }

    // -------------------------------------
    const ctxFinance = document
      .getElementById("financeChart")
      .getContext("2d");

    let financeChart;

    function loadFinanceChart(income, expense) {
      if (financeChart) financeChart.destroy();

      financeChart = new Chart(ctxFinance, {
        type: "bar",
        data: {
          labels: ["Pendapatan", "Pengeluaran"],
          datasets: [{
            data: [income, expense],
            backgroundColor: ["#7DC668", "#ff7076"],
          }, ],
        },
        options: {
          indexAxis: "y", // horizontal
          animation: {
            duration: 1200,
            easing: "easeOutQuart",
          },
          plugins: {
            legend: {
              display: false
            },
          },
          scales: {
            x: {
              beginAtZero: true,
            },
          },
        },
      });
    }

    // ------------------------------------
    function loadFinance(month) {
      const data = financeData[month];
      if (!data) return;

      const income = data.income;
      const salary = data.salary;
      const office = data.office;

      const totalExpense = salary + office;
      const balance = income - totalExpense;

      // ANIMASI KPI
      animateValue("totalIncome", 0, income, 800);
      animateValue("totalBalance", 0, balance, 800);

      // NOTE
      document.getElementById("salaryCost").innerText = formatRupiah(salary);
      document.getElementById("officeCost").innerText = formatRupiah(office);

      // CHART
      loadFinanceChart(income, totalExpense);
    }

    // ----------------------------
    document
      .getElementById("loadFinance")
      .addEventListener("click", function() {
        const month = document.getElementById("monthPicker").value;

        if (!month) {
          alert("Pilih bulan dulu");
          return;
        }

        loadFinance(month);
      });
  </script>
</body>

</html>