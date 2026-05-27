<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

/*
|--------------------------------------------------------------------------
| SHOW ENTRIES
|--------------------------------------------------------------------------
*/
$show = isset($_GET['show']) ? (int)$_GET['show'] : 10;

if ($show <= 0) {
    $show = 10;
}

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page <= 0) {
    $page = 1;
}

$start = ($page - 1) * $show;

/*
|--------------------------------------------------------------------------
| QUERY TOTAL
|--------------------------------------------------------------------------
*/
$where = "";

if (!empty($search)) {

    $searchEsc = mysqli_real_escape_string($conn, $search);

    $where .= " WHERE
        ci.name_inbox LIKE '%$searchEsc%'
        OR ci.email_inbox LIKE '%$searchEsc%'
        OR ci.inbox_title LIKE '%$searchEsc%'
        OR s.service_name LIKE '%$searchEsc%'
    ";
}

$totalQuery = mysqli_query($conn, "
    SELECT COUNT(*) as total
    FROM contact_inbox ci
    LEFT JOIN services s
        ON ci.id_services = s.id
    $where
");

$totalData = mysqli_fetch_assoc($totalQuery)['total'];

$totalPages = ceil($totalData / $show);

/*
|--------------------------------------------------------------------------
| QUERY MAIN TABLE
|--------------------------------------------------------------------------
*/
$queryInbox = mysqli_query($conn, "
    SELECT
        ci.*,
        s.service_name
    FROM contact_inbox ci
    LEFT JOIN services s
        ON ci.id_services = s.id
    $where
    ORDER BY ci.id DESC
    LIMIT $start, $show
");


?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Pesan Masuk - Dashboard | Konig Guard Bureau</title>
    <link href="../assets/images/favicon.png" rel="icon" />


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
                                        Pesan Masuk
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Pesan Masuk</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-center mt-3 mb-4 flex-wrap">
                    <div>
                        <h3 class="mb-1 font-weight-bold">Pesan Masuk</h3>
                        <p class="text-muted mb-0">
                            Daftar pesan masuk dari form contact us website
                        </p>
                    </div>

                    <div class="d-flex align-items-center mt-3 mt-md-0">
                        <div class="input-group mr-2" style="max-width: 300px;">
                            <div class="input-group-prepend">
                                <button class="btn btn-primary d-flex align-items-center">
                                    <span class="material-icons" style="font-size:20px;">search</span>
                                </button>
                            </div>

                            <input type="text"
                                class="form-control border-left-0"
                                placeholder="Cari pesan...">
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-3">

                    <span class="mr-2 small text-muted">
                        Show
                    </span>

                    <select id="showEntries"
                        class="form-control form-control-sm"
                        style="width:80px;">

                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>

                    </select>

                    <span class="ml-2 small text-muted">
                        entries
                    </span>

                </div>

                <!-- CARD LIST -->
                <div class="card" style="border-radius:14px;">
                    <div class="card-body p-0">

                        <div class="table-responsive">
                            <table class="table table-hover mb-0">

                                <thead class="bg-light">
                                    <tr>
                                        <th width="60">#</th>
                                        <th>ID Tiket</th>
                                        <th>Pengirim</th>
                                        <th>Jasa Layanan</th>
                                        <th>Pesan</th>
                                        <th>Attachment</th>
                                        <th>Tanggal</th>
                                        <th width="150">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody id="tablePesanMasuk">
                                    <?php if (mysqli_num_rows($queryInbox) > 0): ?>

                                        <?php
                                        $no = $start + 1;

                                        while ($row = mysqli_fetch_assoc($queryInbox)):

                                            $idInbox = $row['id'];

                                            $firstLetter = strtoupper(substr($row['name_inbox'], 0, 1));

                                            $tanggal = date('d M Y', strtotime($row['created_at']));
                                            $jam      = date('H:i', strtotime($row['created_at']));
                                        ?>

                                            <tr>

                                                <!-- NO -->
                                                <td><?= $no++; ?></td>

                                                <!-- ID -->
                                                <td>
                                                    #INBOX<?= str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?>
                                                </td>

                                                <!-- PENGIRIM -->
                                                <td>

                                                    <div class="d-flex align-items-start">

                                                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                                                            style="
                        width:45px;
                        height:45px;
                        background:#eef2ff;
                        color:var(--primary);
                        font-weight:700;
                    ">

                                                            <?= $firstLetter; ?>

                                                        </div>

                                                        <div>

                                                            <div class="font-weight-bold">
                                                                <?= htmlspecialchars($row['name_inbox']); ?>
                                                            </div>

                                                            <div class="small text-muted">
                                                                <?= htmlspecialchars($row['email_inbox']); ?>
                                                            </div>

                                                            <div class="small text-muted">
                                                                <?= htmlspecialchars($row['no_telp_inbox']); ?>
                                                            </div>

                                                        </div>

                                                    </div>

                                                </td>

                                                <!-- LAYANAN -->
                                                <td>

                                                    <span class="badge badge-primary px-3 py-2">

                                                        <?= htmlspecialchars($row['service_name']); ?>

                                                    </span>

                                                </td>

                                                <!-- PESAN -->
                                                <td style="max-width:350px;">

                                                    <div class="font-weight-bold mb-1 text-truncate">
                                                        <?= htmlspecialchars($row['inbox_title']); ?>
                                                    </div>

                                                    <div class="small text-muted text-truncate">

                                                        <?= htmlspecialchars($row['inbox_desc']); ?>

                                                    </div>

                                                </td>

                                                <!-- ATTACHMENT -->
                                                <td>

                                                    <?php if (!empty($row['inbox_attach'])): ?>

                                                        <a href="../uploads/inbox/<?= $row['inbox_attach']; ?>"
                                                            target="_blank"
                                                            class="btn btn-light border btn-sm d-inline-flex align-items-center">

                                                            <span class="material-icons mr-1"
                                                                style="font-size:16px;">
                                                                attach_file
                                                            </span>

                                                            <?= $row['inbox_attach']; ?>

                                                        </a>

                                                    <?php else: ?>

                                                        <span class="text-muted small">
                                                            Tidak ada file
                                                        </span>

                                                    <?php endif; ?>

                                                </td>

                                                <!-- TANGGAL -->
                                                <td>

                                                    <div class="small">
                                                        <?= $tanggal; ?>
                                                    </div>

                                                    <div class="small text-muted">
                                                        <?= $jam; ?> WIB
                                                    </div>

                                                </td>

                                                <!-- AKSI -->
                                                <td>

                                                    <div class="d-flex">

                                                        <!-- VIEW -->
                                                        <button
                                                            class="btn btn-sm btn-light border mr-2 btn-view-message"

                                                            data-id="<?= $row['id']; ?>"
                                                            data-name="<?= htmlspecialchars($row['name_inbox']); ?>"
                                                            data-email="<?= htmlspecialchars($row['email_inbox']); ?>"
                                                            data-phone="<?= htmlspecialchars($row['no_telp_inbox']); ?>"
                                                            data-service="<?= htmlspecialchars($row['service_name']); ?>"
                                                            data-title="<?= htmlspecialchars($row['inbox_title']); ?>"
                                                            data-desc="<?= htmlspecialchars($row['inbox_desc']); ?>"
                                                            data-attach="<?= htmlspecialchars($row['inbox_attach']); ?>"
                                                            data-date="<?= date('d M Y H:i', strtotime($row['created_at'])); ?>">

                                                            <span class="material-icons"
                                                                style="font-size:18px;">
                                                                visibility
                                                            </span>

                                                        </button>

                                                        <!-- REPLY -->
                                                        <button
                                                            class="btn btn-sm btn-success btn-open-reply"

                                                            data-id="<?= $row['id']; ?>"
                                                            data-name="<?= htmlspecialchars($row['name_inbox']); ?>"
                                                            data-email="<?= htmlspecialchars($row['email_inbox']); ?>"
                                                            data-phone="<?= htmlspecialchars($row['no_telp_inbox']); ?>"
                                                            data-service="<?= htmlspecialchars($row['service_name']); ?>">

                                                            <span class="material-icons"
                                                                style="font-size:18px;">
                                                                reply
                                                            </span>

                                                        </button>

                                                    </div>

                                                </td>

                                            </tr>

                                        <?php endwhile; ?>

                                    <?php endif; ?>

                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>

                <!-- ========================================================= -->
                <!-- PAGINATION -->
                <!-- ========================================================= -->
                <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">

                    <div class="small text-muted">

                        Menampilkan
                        <?= $start + 1; ?>
                        -
                        <?= min($start + $show, $totalData); ?>

                        dari
                        <?= $totalData; ?>
                        data

                    </div>

                    <div class="d-flex align-items-center">

                        <?php if ($page > 1): ?>

                            <a href="?page=<?= $page - 1; ?>&show=<?= $show; ?>&search=<?= urlencode($search); ?>"
                                class="page-btn mr-2">

                                <span class="material-icons">
                                    chevron_left
                                </span>

                            </a>

                        <?php endif; ?>

                        <?php
                        for ($i = 1; $i <= $totalPages; $i++):

                            if (
                                $i == 1 ||
                                $i == $totalPages ||
                                ($i >= $page - 1 && $i <= $page + 1)
                            ):
                        ?>

                                <a href="?page=<?= $i; ?>&show=<?= $show; ?>&search=<?= urlencode($search); ?>"
                                    class="page-btn mr-2 <?= ($i == $page) ? 'active' : ''; ?>">

                                    <?= $i; ?>

                                </a>

                            <?php elseif (
                                $i == 2 && $page > 4 ||
                                $i == $totalPages - 1 && $page < $totalPages - 3
                            ): ?>

                                <span class="mr-2">...</span>

                            <?php endif; ?>

                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>

                            <a href="?page=<?= $page + 1; ?>&show=<?= $show; ?>&search=<?= urlencode($search); ?>"
                                class="page-btn">

                                <span class="material-icons">
                                    chevron_right
                                </span>

                            </a>

                        <?php endif; ?>

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
        <app-settings layout-active="fluid"></app-settings>
    </div>

    <!-- ********************************** // MENU-Drawer ********************************** -->
    <?php include 'includes/drawer_menu.php'; ?>
    <!-- ********************************** //END MENU-drawer ********************************** -->

    <!-- ========================= MODAL DETAIL PESAN ========================= -->
    <div class="modal fade"
        id="detailPesanModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="detailPesanModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-scrollable"
            role="document">

            <div class="modal-content border-0 shadow-lg"
                style="border-radius:18px; overflow:hidden;">

                <!-- HEADER -->
                <div class="modal-header border-0"
                    style="background:#f9fafc;">

                    <div class="d-flex align-items-center">

                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                            style="
                            width:60px;
                            height:60px;
                            background:#eef2ff;
                            color:var(--primary);
                            font-size:22px;
                            font-weight:700;
                        ">
                            A
                        </div>

                        <div>
                            <h4 class="mb-1 font-weight-bold" id="viewName"></h4>

                            <div class="text-muted small">
                                Pesan masuk dari Contact Us
                            </div>
                        </div>

                    </div>

                    <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">

                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>

                <!-- BODY -->
                <div class="modal-body p-0">

                    <div class="container-fluid">

                        <div class="row">

                            <!-- LEFT SIDE -->
                            <div class="col-lg-4 border-right"
                                style="background:#fbfcfd;">

                                <div class="p-4">

                                    <h6 class="font-weight-bold mb-4">
                                        Informasi Pengirim
                                    </h6>

                                    <!-- EMAIL -->
                                    <div class="d-flex mb-4">

                                        <div class="mr-3">
                                            <span class="material-icons text-primary">
                                                mail
                                            </span>
                                        </div>

                                        <div>
                                            <div class="small text-muted">
                                                Email
                                            </div>

                                            <div class="font-weight-500"
                                                id="viewEmail">
                                            </div>
                                        </div>

                                    </div>

                                    <!-- PHONE -->
                                    <div class="d-flex mb-4">

                                        <div class="mr-3">
                                            <span class="material-icons text-success">
                                                call
                                            </span>
                                        </div>

                                        <div>
                                            <div class="small text-muted">
                                                Nomor Telepon
                                            </div>

                                            <div class="font-weight-500"
                                                id="viewPhone">
                                            </div>
                                        </div>

                                    </div>

                                    <!-- SERVICE -->
                                    <div class="d-flex mb-4">

                                        <div class="mr-3">
                                            <span class="material-icons text-warning">
                                                business_center
                                            </span>
                                        </div>

                                        <div>
                                            <div class="small text-muted">
                                                Jasa Layanan
                                            </div>

                                            <div>
                                                <span class="badge badge-primary px-3 py-2"
                                                    id="viewService">
                                                </span>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- DATE -->
                                    <div class="d-flex mb-4">

                                        <div class="mr-3">
                                            <span class="material-icons text-primary">
                                                assignment_ind
                                            </span>
                                        </div>

                                        <div>
                                            <div class="small text-muted">
                                                Tiket Keluhan
                                            </div>

                                            <div class="font-weight-500">
                                                170226#1
                                            </div>
                                        </div>

                                    </div>

                                    <!-- DATE -->
                                    <div class="d-flex mb-4">

                                        <div class="mr-3">
                                            <span class="material-icons text-danger">
                                                schedule
                                            </span>
                                        </div>

                                        <div>
                                            <div class="small text-muted">
                                                Tanggal Pesan
                                            </div>

                                            <div class="font-weight-500"
                                                id="viewDate">
                                            </div>
                                        </div>

                                    </div>

                                    <!-- ATTACHMENT -->
                                    <div class="card border-0 shadow-sm" style="border-radius:12px;">

                                        <a href="uploads/proposal.pdf"
                                            target="_blank"
                                            class="btn btn-light border d-flex align-items-center justify-content-between w-100 p-3 shadow-sm text-decoration-none btn-attachment"
                                            style="
            border-radius:14px;
            transition:all .25s ease;
        ">

                                            <div class="d-flex align-items-center">

                                                <div class="mr-3">

                                                    <div class="rounded d-flex align-items-center justify-content-center"
                                                        style="
                        width:50px;
                        height:50px;
                        background:#fff3e0;
                    ">

                                                        <span class="material-icons text-warning">
                                                            description
                                                        </span>

                                                    </div>

                                                </div>

                                                <div class="text-left">

                                                    <div class="font-weight-bold text-dark"
                                                        id="viewAttachmentWrapper">
                                                    </div>

                                                    <div class="small text-muted">
                                                        2.4 MB Document
                                                    </div>

                                                </div>

                                            </div>

                                            <span class="material-icons text-muted">
                                                open_in_new
                                            </span>

                                        </a>

                                    </div>

                                </div>

                            </div>

                            <!-- RIGHT SIDE -->
                            <div class="col-lg-8">

                                <div class="p-4">

                                    <!-- SUBJECT -->
                                    <div class="mb-4">

                                        <div class="small text-muted mb-2">
                                            Subject Pesan
                                        </div>

                                        <h4 class="font-weight-bold mb-0"
                                            id="viewTitle">
                                        </h4>

                                    </div>

                                    <!-- MESSAGE -->
                                    <div class="card border-0"
                                        style="
                                        background:#f8fafc;
                                        border-radius:16px;
                                    ">

                                        <div class="card-body p-4">

                                            <div class="d-flex align-items-center mb-4">

                                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                                                    style="
                                                    width:45px;
                                                    height:45px;
                                                    background:#eef2ff;
                                                    color:var(--primary);
                                                    font-weight:600;
                                                ">
                                                    A
                                                </div>

                                                <div>
                                                    <div class="font-weight-bold">
                                                        Andi Saputra
                                                    </div>

                                                    <div class="small text-muted">
                                                        12 Mei 2026 • 08:15 WIB
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="text-muted"
                                                style="
                                                line-height:1.9;
                                                font-size:15px;"
                                                id="viewDesc"></div>

                                        </div>

                                    </div>

                                </div>

                            </div>
                            <!-- END RIGHT -->

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- ========================= END MODAL DETAIL PESAN ========================= -->

    <!-- ========================= MODAL BALAS PESAN ========================= -->
    <div class="modal fade"
        id="replyPesanModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="replyPesanModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-scrollable"
            role="document">

            <div class="modal-content border-0 shadow-lg"
                style="border-radius:18px; overflow:hidden;">

                <!-- HEADER -->
                <div class="modal-header border-0"
                    style="background:#f8fafc;">

                    <div class="d-flex align-items-center">

                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                            style="
                            width:55px;
                            height:55px;
                            background:#e8f5e9;
                            color:#28a745;
                        ">

                            <span class="material-icons">
                                reply
                            </span>

                        </div>

                        <div>
                            <h4 class="mb-1 font-weight-bold">
                                Balas Pesan
                            </h4>

                            <div class="small text-muted">
                                Kirim balasan email kepada customer
                            </div>
                        </div>

                    </div>

                    <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">

                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>

                <!-- BODY -->
                <div class="modal-body p-4">

                    <div class="row">

                        <!-- LEFT -->
                        <div class="col-lg-4">

                            <input type="hidden"
                                id="replyInboxId"
                                name="id_contact_inbox">

                            <div class="card border-0 shadow-sm mb-4"
                                style="border-radius:16px;">

                                <div class="card-body">

                                    <h6 class="font-weight-bold mb-4">
                                        Informasi Pengirim
                                    </h6>

                                    <!-- NAMA -->
                                    <div class="mb-3">

                                        <label class="small text-muted mb-2">
                                            Nama
                                        </label>

                                        <div class="input-group">

                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-0">
                                                    <span class="material-icons"
                                                        style="font-size:18px;">
                                                        person
                                                    </span>
                                                </span>
                                            </div>

                                            <input type="text"
                                                id="replyName"
                                                class="form-control border-0 bg-light"
                                                readonly>

                                        </div>

                                    </div>

                                    <!-- EMAIL -->
                                    <div class="mb-3">

                                        <label class="small text-muted mb-2">
                                            Email
                                        </label>

                                        <div class="input-group">

                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-0">
                                                    <span class="material-icons"
                                                        style="font-size:18px;">
                                                        mail
                                                    </span>
                                                </span>
                                            </div>

                                            <input type="email"
                                                id="replyEmail"
                                                name="email_contact_inbox"
                                                class="form-control border-0 bg-light"
                                                readonly>
                                        </div>

                                    </div>

                                    <!-- PHONE -->
                                    <div class="mb-3">

                                        <label class="small text-muted mb-2">
                                            Nomor Telepon
                                        </label>

                                        <div class="input-group">

                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-0">
                                                    <span class="material-icons"
                                                        style="font-size:18px;">
                                                        call
                                                    </span>
                                                </span>
                                            </div>

                                            <input type="text"
                                                id="replyPhone"
                                                class="form-control border-0 bg-light"
                                                readonly>

                                        </div>

                                    </div>

                                    <!-- LAYANAN -->
                                    <div class="mb-0">

                                        <label class="small text-muted mb-2">
                                            Layanan Yang Ditanyakan
                                        </label>

                                        <div class="input-group">

                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-0">
                                                    <span class="material-icons"
                                                        style="font-size:18px;">
                                                        business_center
                                                    </span>
                                                </span>
                                            </div>

                                            <input type="text"
                                                id="replyService"
                                                class="form-control border-0 bg-light"
                                                readonly>

                                        </div>

                                    </div>

                                    <!-- ID TIKET -->
                                    <div class="mb-3">

                                        <label class="small text-muted mb-2">
                                            Tiket Keluhan
                                        </label>

                                        <div class="input-group">

                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-0">
                                                    <span class="material-icons"
                                                        style="font-size:18px;">
                                                        assignment_ind
                                                    </span>
                                                </span>
                                            </div>

                                            <input type="text"
                                                class="form-control border-0 bg-light"
                                                value="170226#1"
                                                readonly>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="col-lg-8">

                            <div class="card border-0 shadow-sm"
                                style="border-radius:16px;">

                                <div class="card-body">

                                    <h6 class="font-weight-bold mb-4">
                                        Form Balasan
                                    </h6>

                                    <!-- JUDUL -->
                                    <div class="form-group">

                                        <label class="font-weight-500">
                                            Judul Pesan
                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            placeholder="Masukkan judul pesan">

                                    </div>

                                    <!-- KONFIRMASI EMAIL -->
                                    <div class="form-group">

                                        <label class="font-weight-500">
                                            Konfirmasi Email Tujuan
                                        </label>

                                        <div class="input-group">

                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-0">
                                                    <span class="material-icons"
                                                        style="font-size:18px;">
                                                        email
                                                    </span>
                                                </span>
                                            </div>

                                            <input type="email"
                                                class="form-control"
                                                id="confirmReplyEmail"
                                                placeholder="Ketik ulang email tujuan">

                                        </div>

                                        <small class="text-danger d-none"
                                            id="confirmEmailError">

                                            Email konfirmasi tidak sama

                                        </small>

                                    </div>

                                    <!-- ISI -->
                                    <div class="form-group">

                                        <label class="font-weight-500">
                                            Isi Pesan
                                        </label>

                                        <textarea class="form-control"
                                            rows="9"
                                            placeholder="Tulis balasan pesan..."></textarea>

                                    </div>

                                    <!-- ATTACHMENT -->
                                    <div class="form-group mb-4">

                                        <label class="font-weight-500 d-block mb-3">
                                            Attachment File
                                        </label>

                                        <!-- UPLOAD BOX -->
                                        <label for="replyAttachmentInput"
                                            class="upload-reply-box w-100 text-center p-4">

                                            <input type="file"
                                                id="replyAttachmentInput"
                                                hidden>

                                            <span class="material-icons upload-icon mb-2">
                                                upload_file
                                            </span>

                                            <div class="font-weight-bold text-dark mb-1">
                                                Upload Attachment
                                            </div>

                                            <div class="small text-muted">
                                                PDF, DOCX, JPG, PNG, ZIP
                                            </div>

                                        </label>

                                        <!-- PREVIEW FILE -->
                                        <div id="replyAttachmentPreview"
                                            class="mt-3 d-none">

                                            <div class="card border-0 shadow-sm"
                                                style="
                border-radius:16px;
                overflow:hidden;
            ">

                                                <div class="card-body p-3">

                                                    <div class="d-flex align-items-center">

                                                        <!-- ICON -->
                                                        <div id="replyPreviewIcon"
                                                            class="rounded d-flex align-items-center justify-content-center mr-3"
                                                            style="
                            width:60px;
                            height:60px;
                            background:#eef2ff;
                            color:var(--primary);
                        ">

                                                            <span class="material-icons">
                                                                description
                                                            </span>

                                                        </div>

                                                        <!-- FILE INFO -->
                                                        <div class="flex-grow-1">

                                                            <div class="font-weight-bold text-dark"
                                                                id="replyFileName">

                                                                nama-file.pdf

                                                            </div>

                                                            <div class="small text-muted"
                                                                id="replyFileSize">

                                                                0 KB

                                                            </div>

                                                        </div>

                                                        <!-- REMOVE -->
                                                        <button type="button"
                                                            class="btn btn-light border rounded-circle ml-2"
                                                            id="removeReplyAttachment"
                                                            style="
                            width:42px;
                            height:42px;
                        ">

                                                            <span class="material-icons text-danger"
                                                                style="font-size:20px;">
                                                                close
                                                            </span>

                                                        </button>

                                                    </div>

                                                    <!-- IMAGE PREVIEW -->
                                                    <div id="replyImagePreviewWrapper"
                                                        class="mt-3 d-none">

                                                        <img id="replyImagePreview"
                                                            class="img-fluid rounded shadow-sm"
                                                            style="
                            max-height:260px;
                            object-fit:cover;
                            width:100%;
                        ">
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <!-- ACTION -->
                                    <div class="d-flex justify-content-end">

                                        <button type="submit"
                                            class="btn btn-success"
                                            id="btnSendReply">

                                            <span class="material-icons mr-1">
                                                send
                                            </span>

                                            Kirim Balasan

                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- ========================= END MODAL BALAS PESAN ========================= -->

    <!-- ========================= MODAL HAPUS PESAN ========================= -->
    <div class="modal fade"
        id="deleteModal"
        tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow"
                style="border-radius:20px;">

                <div class="modal-body text-center p-5">

                    <div class="mb-4">

                        <div class="rounded-circle bg-danger-light d-inline-flex align-items-center justify-content-center"
                            style="
                            width:80px;
                            height:80px;
                            background:#fee2e2;
                        ">

                            <span class="material-icons text-danger"
                                style="font-size:40px;">
                                delete
                            </span>

                        </div>

                    </div>

                    <h4 class="font-weight-bold mb-3">
                        Hapus Pesan?
                    </h4>

                    <p class="text-muted mb-4">
                        Pesan yang dihapus tidak dapat dikembalikan.
                    </p>

                    <div class="d-flex justify-content-center">

                        <button class="btn btn-light border mr-2"
                            data-dismiss="modal">
                            Batal
                        </button>

                        <button class="btn btn-danger"
                            id="confirmDeleteBtn">

                            Ya, Hapus

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- ========================= END MODAL HAPUS PESAN ========================= -->

    <!-- ===================================== -->
    <!-- LOADING MODAL -->
    <!-- ===================================== -->
    <div class="modal fade"
        id="loadingModal"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow"
                style="border-radius:20px;">

                <div class="modal-body text-center py-5">

                    <div class="mb-4">

                        <div class="spinner-border text-primary"
                            style="width:4rem;height:4rem;"
                            role="status">
                        </div>

                    </div>

                    <h5 class="font-weight-bold mb-2">
                        Memuat Data...
                    </h5>

                    <p class="text-muted mb-0">
                        Mohon tunggu sebentar
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- ===================================== -->
    <!-- LOADING KIRIM EMAIL -->
    <!-- ===================================== -->
    <div class="modal fade"
        id="sendingReplyModal"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow"
                style="border-radius:22px; overflow:hidden;">

                <div class="modal-body text-center py-5">

                    <div class="mb-4">

                        <div class="position-relative d-inline-flex align-items-center justify-content-center">

                            <div class="spinner-border text-success"
                                style="width:5rem;height:5rem;">
                            </div>

                            <span class="material-icons position-absolute text-success"
                                style="font-size:34px;">
                                send
                            </span>

                        </div>

                    </div>

                    <h4 class="font-weight-bold mb-2">
                        Pesan Sedang Dikirim...
                    </h4>

                    <p class="text-muted mb-0">
                        Mohon tunggu sebentar
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- ===================================== -->
    <!-- MODAL SUKSES BALAS PESAN -->
    <!-- ===================================== -->
    <div class="modal fade"
        id="successReplyModal"
        tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow"
                style="border-radius:22px; overflow:hidden;">

                <div class="modal-body text-center p-5">

                    <div class="mb-4">

                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="
                        width:95px;
                        height:95px;
                        background:#ecfdf3;
                    ">

                            <span class="material-icons text-success"
                                style="font-size:52px;">
                                check_circle
                            </span>

                        </div>

                    </div>

                    <h3 class="font-weight-bold mb-3">
                        Pesan Berhasil Dikirim
                    </h3>

                    <p class="text-muted mb-4"
                        id="successReplyText"
                        style="
                    line-height:1.8;
                    font-size:15px;
                ">
                        Pesan berhasil dikirim
                    </p>

                    <button class="btn btn-success px-4"
                        data-dismiss="modal">

                        Oke

                    </button>

                </div>

            </div>

        </div>

    </div>

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

    <script>
        // =====================================
        // OPEN MODAL WITH LOADING
        // =====================================
        function openModalWithLoading(targetModal, callback = null) {

            // tampilkan loading
            $("#loadingModal").modal("show");

            // simulasi loading
            setTimeout(() => {

                // tutup loading
                $("#loadingModal").modal("hide");

                // buka modal tujuan
                $(targetModal).modal("show");

                // callback optional
                if (callback) {
                    callback();
                }

            }, 800);

        }
    </script>

    <script>
        // =====================================
        // KIRIM BALASAN EMAIL
        // =====================================
        $("#btnSendReply").on("click", function() {

            // ambil data
            const targetEmail =
                $("#replyPesanModal input[type='email']").first().val();

            const confirmEmail =
                $("#confirmReplyEmail").val();

            const judulPesan =
                $("#replyPesanModal input[type='text'][placeholder='Masukkan judul pesan']").val();

            const isiPesan =
                $("#replyPesanModal textarea").val();

            // reset error
            $("#confirmEmailError").addClass("d-none");

            // validasi kosong
            if (
                judulPesan.trim() === "" ||
                isiPesan.trim() === "" ||
                confirmEmail.trim() === ""
            ) {

                alert("Lengkapi form balasan terlebih dahulu");
                return;
            }

            // validasi email sama
            if (targetEmail !== confirmEmail) {

                $("#confirmEmailError").removeClass("d-none");

                $("#confirmReplyEmail").focus();

                return;
            }

            // tutup modal balasan
            $("#replyPesanModal").modal("hide");

            // tampilkan loading
            $("#sendingReplyModal").modal("show");

            // simulasi proses kirim
            setTimeout(() => {

                // tutup loading
                $("#sendingReplyModal").modal("hide");

                // isi text sukses
                $("#successReplyText").html(`
            Pesan balasan
            <b>"${judulPesan}"</b>
            telah dikirim ke email
            <b>${targetEmail}</b>
        `);

                // tampilkan modal sukses
                $("#successReplyModal").modal("show");

                // reset form
                $("#confirmReplyEmail").val("");

                $("#replyPesanModal input[type='text'][placeholder='Masukkan judul pesan']").val("");

                $("#replyPesanModal textarea").val("");

            }, 2200);

        });
    </script>

    <script>
        // =====================================
        // PREVIEW ATTACHMENT BALASAN
        // =====================================
        const replyAttachmentInput =
            document.getElementById("replyAttachmentInput");

        const replyAttachmentPreview =
            document.getElementById("replyAttachmentPreview");

        const replyFileName =
            document.getElementById("replyFileName");

        const replyFileSize =
            document.getElementById("replyFileSize");

        const replyPreviewIcon =
            document.getElementById("replyPreviewIcon");

        const removeReplyAttachment =
            document.getElementById("removeReplyAttachment");

        const replyImagePreviewWrapper =
            document.getElementById("replyImagePreviewWrapper");

        const replyImagePreview =
            document.getElementById("replyImagePreview");

        // =====================================
        // FILE CHANGE
        // =====================================
        replyAttachmentInput.addEventListener("change", function(e) {

            const file = e.target.files[0];

            if (!file) return;

            // tampilkan preview
            replyAttachmentPreview.classList.remove("d-none");

            // nama file
            replyFileName.textContent = file.name;

            // ukuran file
            const fileSize =
                (file.size / 1024).toFixed(1);

            replyFileSize.textContent =
                `${fileSize} KB`;

            // reset preview image
            replyImagePreviewWrapper.classList.add("d-none");

            // extension
            const extension =
                file.name.split('.').pop().toLowerCase();

            // default icon
            let icon = "description";
            let bg = "#eef2ff";
            let color = "#4f46e5";

            // PDF
            if (extension === "pdf") {
                icon = "picture_as_pdf";
                bg = "#fee2e2";
                color = "#dc2626";
            }

            // IMAGE
            else if (
                ["jpg", "jpeg", "png", "webp"].includes(extension)
            ) {

                icon = "image";
                bg = "#ecfdf3";
                color = "#16a34a";

                // preview image
                const reader = new FileReader();

                reader.onload = function(event) {

                    replyImagePreview.src =
                        event.target.result;

                    replyImagePreviewWrapper.classList.remove("d-none");

                };

                reader.readAsDataURL(file);

            }

            // DOC / DOCX
            else if (
                ["doc", "docx"].includes(extension)
            ) {

                icon = "description";
                bg = "#eff6ff";
                color = "#2563eb";

            }

            // ZIP
            else if (
                ["zip", "rar"].includes(extension)
            ) {

                icon = "folder_zip";
                bg = "#fff7ed";
                color = "#ea580c";

            }

            // update icon
            replyPreviewIcon.innerHTML = `
        <span class="material-icons"
            style="font-size:30px;color:${color};">
            ${icon}
        </span>
    `;

            replyPreviewIcon.style.background = bg;

        });

        // =====================================
        // REMOVE FILE
        // =====================================
        removeReplyAttachment.addEventListener("click", function() {

            // reset input
            replyAttachmentInput.value = "";

            // hide preview
            replyAttachmentPreview.classList.add("d-none");

            // reset image
            replyImagePreview.src = "";

        });
    </script>

    <script>
        $(document).on("click", ".btn-view-message", function() {

            const name = $(this).data("name");
            const email = $(this).data("email");
            const phone = $(this).data("phone");
            const service = $(this).data("service");
            const title = $(this).data("title");
            const desc = $(this).data("desc");
            const attach = $(this).data("attach");
            const date = $(this).data("date");

            // first letter
            $("#viewName").text(name);

            $("#viewEmail").text(email);

            $("#viewPhone").text(phone);

            $("#viewService").text(service);

            $("#viewDate").text(date);

            $("#viewTitle").text(title);

            $("#viewDesc").html(desc.replace(/\n/g, "<br>"));

            // attachment
            if (attach !== "") {

                $("#viewAttachmentWrapper").html(`
            <a href="../uploads/inbox/${attach}"
                target="_blank"
                class="btn btn-light border">

                ${attach}

            </a>
        `);

            } else {

                $("#viewAttachmentWrapper").html(`
            <span class="text-muted">
                Tidak ada file
            </span>
        `);

            }

            $("#detailPesanModal").modal("show");

        });
    </script>

    <script>
        $(document).on("click", ".btn-open-reply", function() {

            const id = $(this).data("id");
            const name = $(this).data("name");
            const email = $(this).data("email");
            const phone = $(this).data("phone");
            const service = $(this).data("service");

            $("#replyInboxId").val(id);

            $("#replyName").val(name);

            $("#replyEmail").val(email);

            $("#replyPhone").val(phone);

            $("#replyService").val(service);

            $("#replyPesanModal").modal("show");

        });
    </script>

    <script>
        $("#detailPesanModal").appendTo("body");
        $("#replyPesanModal").appendTo("body");
    </script>

</body>

</html>