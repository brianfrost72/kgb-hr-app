<?php
require_once __DIR__ . "/../../koneksi.php";
// =========================
// USER PROFILE SIDEBAR
// =========================

$user_id = $_SESSION['user_id'] ?? 0;

$user_sidebar = null;

if ($user_id > 0) {

    $queryUserSidebar = mysqli_query($conn, "
        SELECT 
            users.id,
            users.email,

            user_profile.full_name,
            user_profile.photo_profile,

            roles.role_name

        FROM users

        LEFT JOIN user_profile 
            ON user_profile.user_id = users.id

        LEFT JOIN roles 
            ON roles.id = users.role_id

        WHERE users.id = '$user_id'

        LIMIT 1
    ");

    if ($queryUserSidebar && mysqli_num_rows($queryUserSidebar) > 0) {
        $user_sidebar = mysqli_fetch_assoc($queryUserSidebar);
    }
}

// =========================
// PHOTO PROFILE
// =========================

$defaultPhoto = "../assets/images/avatar/demi.png";

$userPhoto = $defaultPhoto;

if (
    !empty($user_sidebar['photo_profile']) &&
    file_exists(
        __DIR__ . "/../../assets/images/uploads/user_photos/" . $user_sidebar['photo_profile']
    )
) {
    $userPhoto = "../assets/images/uploads/user_photos/" . $user_sidebar['photo_profile'];
}

// =========================
// USER NAME & ROLE
// =========================

$userName = $user_sidebar['full_name'] ?? 'Unknown User';
$userRole = $user_sidebar['role_name'] ?? 'No Role';
$userEmail = $user_sidebar['email'] ?? '-';

// =========================
// ROLE ACCESS & ACTIVE MENU
// =========================

// role login sekarang
$currentRole = strtolower(trim($userRole));

// halaman aktif sekarang
$currentPage = basename($_SERVER['PHP_SELF']);

// =========================
// ROLE ACCESS
// =========================

function roleAccess($roles = [])
{
    global $currentRole;

    // =========================================
    // ROLE YANG SELALU BISA AKSES SEMUA MENU
    // TAMBAH ROLE DISINI
    // =========================================

    $fullAccessRoles = [
        'Super Admin',
        'Admin'
    ];

    if (in_array($currentRole, $fullAccessRoles)) {
        return true;
    }

    // lowercase semua role
    $roles = array_map('strtolower', $roles);

    return in_array($currentRole, $roles);
}

// =========================
// ACTIVE MENU
// =========================

function activeMenu($pages = [])
{
    global $currentPage;

    return in_array($currentPage, $pages)
        ? 'active open'
        : '';
}

// =========================
// SHOW SUBMENU
// =========================

function showMenu($pages = [])
{
    global $currentPage;

    return in_array($currentPage, $pages)
        ? 'show'
        : '';
}
?>



<div class="mdk-drawer js-mdk-drawer" id="default-drawer" data-align="end">
    <div class="mdk-drawer__content">
        <div
            class="sidebar sidebar-light sidebar-left sidebar-p-t"
            data-perfect-scrollbar>
            <!-- *********************************FIRST MENU********************************* -->
            <div class="sidebar-heading">Menu</div>
            <ul class="sidebar-menu">
                <!-- DASHBOARD_MENU -->
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fluid-ui-buttons.html">
                        <i
                            class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dvr</i>
                        <span class="sidebar-menu-text">Dashboard</span>
                    </a>
                </li>
                <!-- DASHBOARD_MENU END -->

                <!-- ROLE AKSES MENU -->
                <?php if (roleAccess(['Super Admin', 'Moderator'])): ?>
                    <li class="sidebar-menu-item <?= activeMenu([
                                                        'add_roles.php',
                                                        'manage_roles.php',
                                                        'add_regions.php'
                                                    ]); ?>">
                        <a
                            class="sidebar-menu-button"
                            data-toggle="collapse"
                            href="#role_menu">
                            <i
                                class="sidebar-menu-icon sidebar-menu-icon--left material-icons">accessibility</i>
                            <span class="sidebar-menu-text">Master Role</span>
                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                        </a>
                        <ul class="sidebar-submenu collapse <?= showMenu([
                                                                'add_roles.php',
                                                                'manage_roles.php',
                                                                'add_regions.php'
                                                            ]); ?>" id="role_menu">
                            <li class="sidebar-menu-item <?= activeMenu(['add_roles.php']); ?>">
                                <a
                                    class="sidebar-menu-button"
                                    href="add_roles.php">
                                    <span class="sidebar-menu-text">Tambah Role</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item <?= activeMenu(['manage_roles.php']); ?>">
                                <a class="sidebar-menu-button" href="manage_roles.php">
                                    <span class="sidebar-menu-text">Manage Role</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item <?= activeMenu(['add_regions.php']); ?>">
                                <a class="sidebar-menu-button" href="add_regions.php">
                                    <span class="sidebar-menu-text">Tambah Cabang Lokasi</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <!-- ROLE AKSES MENU END -->

                <!-- STRUKTUR ORGANISASI MENU -->
                <?php if (roleAccess(['Super Admin', 'Moderator'])): ?>
                    <li class="sidebar-menu-item <?= activeMenu([
                                                        'manage_departments.php',
                                                        'manage_positions.php'
                                                    ]); ?>">
                        <a
                            class="sidebar-menu-button"
                            data-toggle="collapse"
                            href="#structure_menu">
                            <i
                                class="sidebar-menu-icon sidebar-menu-icon--left material-icons">work</i>
                            <span class="sidebar-menu-text">Struktur Organisasi</span>
                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                        </a>
                        <ul class="sidebar-submenu collapse" id="structure_menu">
                            <li class="sidebar-menu-item <?= activeMenu(['manage_departments.php']); ?>">
                                <a class="sidebar-menu-button" href="manage_departments.php">
                                    <span class="sidebar-menu-text">Manage Departemen</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item <?= activeMenu(['manage_positions.php']); ?>">
                                <a class="sidebar-menu-button" href="manage_positions.php">
                                    <span class="sidebar-menu-text">Manage Jabatan</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <!-- STRUKTUR ORGANISASI MENU END -->

                <!-- PERSONEL MENU -->
                <?php if (roleAccess(['Super Admin', 'Moderator', 'Admin'])): ?>
                    <li class="sidebar-menu-item <?= activeMenu(['manage_employees.php']); ?>">
                        <a class="sidebar-menu-button" href="manage_employees.php">
                            <i
                                class="sidebar-menu-icon sidebar-menu-icon--left material-icons">group</i>
                            <span class="sidebar-menu-text">Master Personel</span>
                        </a>
                    </li>
                <?php endif; ?>
                <!-- PERSONEL MENU END -->

                <!-- LAYANAN MENU -->
                <?php if (roleAccess(['Super Admin', 'Moderator'])): ?>
                    <li class="sidebar-menu-item <?= activeMenu(['manage_services.php']); ?>">
                        <a
                            class="sidebar-menu-button"
                            data-toggle="collapse"
                            href="#services_menu">
                            <i
                                class="sidebar-menu-icon sidebar-menu-icon--left material-icons">work</i>
                            <span class="sidebar-menu-text">Layanan</span>
                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                        </a>
                        <ul class="sidebar-submenu collapse" id="services_menu">
                            <li class="sidebar-menu-item <?= activeMenu(['manage_services.php']); ?>">
                                <a class="sidebar-menu-button" href="manage_services.php">
                                    <span class="sidebar-menu-text">Manage Layanan</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <!-- LAYANAN MENU END -->
            </ul>
            <!-- *********************************FIRST MENU END********************************* -->

            <!-- *********************************FINANCIAL MENU********************************* -->
            <div class="sidebar-heading">Financial Menu</div>
            <ul class="sidebar-menu">
                <!-- LAPORAN FINANSIAL MENU -->
                <?php if (roleAccess(['Super Admin', 'Moderator', 'Staff'])): ?>
                    <li class="sidebar-menu-item <?= activeMenu([
                                                        'manage_income_reports.php',
                                                        'manage_expense_reports.php',
                                                        'manage_employee_payment.php',
                                                        'manage_deposite_reports.php'
                                                    ]); ?>">
                        <a
                            class="sidebar-menu-button"
                            data-toggle="collapse"
                            href="#financial_reports_menu">
                            <i
                                class="sidebar-menu-icon sidebar-menu-icon--left material-icons">work</i>
                            <span class="sidebar-menu-text">Laporan Finansial</span>
                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                        </a>
                        <ul class="sidebar-submenu collapse" id="financial_reports_menu">
                            <li class="sidebar-menu-item <?= activeMenu(['manage_income_reports.php']); ?>">
                                <a class="sidebar-menu-button" href="manage_income_reports.php">
                                    <span class="sidebar-menu-text">Manage Laporan Pemasukan</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item <?= activeMenu(['manage_expense_reports.php']); ?>">
                                <a class="sidebar-menu-button" href="manage_expense_reports.php">
                                    <span class="sidebar-menu-text">Manage Laporan Pengeluaran</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item <?= activeMenu(['manage_employee_payment.php']); ?>">
                                <a class="sidebar-menu-button" href="manage_employee_payment.php">
                                    <span class="sidebar-menu-text">Manage Penggajian</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="manage_deposite_reports.php">
                                    <span class="sidebar-menu-text">Deposit Keuangan</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <!-- LAPORAN PEMASUKAN MENU END -->
            </ul>
            <!-- *********************************FINANCIAL MENU END********************************* -->

            <!-- *********************************CLIENT MENU********************************* -->
            <!-- <div class="sidebar-heading">CLIENT MENU</div>
            <ul class="sidebar-menu" id="components_menu"> -->

            <!-- MASTER CLIENT MENU -->
            <!-- <li class="sidebar-menu-item">
                    <a
                        class="sidebar-menu-button"
                        data-toggle="collapse"
                        href="#client_menu">
                        <i
                            class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_circle</i>
                        <span class="sidebar-menu-text">Master Klien</span>
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse" id="client_menu">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="manage_client-private.php">
                                <span class="sidebar-menu-text">Personal / Private</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="manage_client-corporate.php">
                                <span class="sidebar-menu-text">Perusahaan</span>
                            </a>
                        </li>
                    </ul>
                </li> -->
            <!-- MASTER CLIENT MENU END -->

            <!-- ORDER MENU -->
            <!-- <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="manage_orders.php">
                        <i
                            class="sidebar-menu-icon sidebar-menu-icon--left material-icons">local_grocery_store</i>
                        <span class="sidebar-menu-text">Manage Order</span>
                        <span class="badge badge-primary badge-pill ml-1">3</span>
                    </a>
                </li> -->
            <!-- ORDER MENU END -->

            <!-- MEMBER MENU -->
            <!-- <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="our_members.php">
                        <i
                            class="sidebar-menu-icon sidebar-menu-icon--left material-icons">card_membership</i>
                        <span class="sidebar-menu-text">Member Konig</span>
                    </a>
                </li> -->
            <!-- MEMBER MENU END -->

            <!-- MEMBER MENU -->
            <!-- <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="fluid-ui-alerts.html">
                        <i
                            class="sidebar-menu-icon sidebar-menu-icon--left material-icons">person</i>
                        <span class="sidebar-menu-text">Member</span>
                    </a>
                </li> -->
            <!-- MEMBER MENU END -->

            <!-- MASTER MEMBER FEATURE MENU -->
            <!-- <li class="sidebar-menu-item">
                    <a
                        class="sidebar-menu-button"
                        data-toggle="collapse"
                        href="#feature_menu">
                        <i
                            class="sidebar-menu-icon sidebar-menu-icon--left material-icons">card_membership</i>
                        <span class="sidebar-menu-text">Master Fitur Layanan</span>
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse" id="feature_menu">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fluid-companies.html">
                                <span class="sidebar-menu-text">Layanan Kontrak</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fluid-stories.html">
                                <span class="sidebar-menu-text">Layanan Professional</span>
                            </a>
                        </li>
                    </ul>
                </li> -->
            <!-- MASTER MEMBER FEATURE MENU END -->

            <!-- MASTER TRANSACTION MENU -->
            <!-- <li class="sidebar-menu-item">
                    <a
                        class="sidebar-menu-button"
                        data-toggle="collapse"
                        href="#transaction_menu">
                        <i
                            class="sidebar-menu-icon sidebar-menu-icon--left material-icons">access_time</i>
                        <span class="sidebar-menu-text">Riwayat Transaksi</span>
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse" id="transaction_menu">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fluid-companies.html">
                                <span class="sidebar-menu-text">Riwayat Pembayaran</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fluid-stories.html">
                                <span class="sidebar-menu-text">Riwayat Invoice</span>
                            </a>
                        </li>
                    </ul>
                </li> -->
            <!-- MASTER TRANSACTION MENU END -->

            <!-- MASTER REPORT MENU -->
            <!-- <li class="sidebar-menu-item">
                    <a
                        class="sidebar-menu-button"
                        data-toggle="collapse"
                        href="#transaction_menu">
                        <i
                            class="sidebar-menu-icon sidebar-menu-icon--left material-icons">access_time</i>
                        <span class="sidebar-menu-text">Riwayat Membership</span>
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse" id="transaction_menu">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fluid-companies.html">
                                <span class="sidebar-menu-text">Riwayat Member</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fluid-stories.html">
                                <span class="sidebar-menu-text">Riwayat Kontrak</span>
                            </a>
                        </li>
                    </ul>
                </li> -->
            <!-- MASTER REPORT MENU END -->

            <!-- MASTER TESTIMONY -->
            <!-- <li class="sidebar-menu-item">
                    <a
                        class="sidebar-menu-button"
                        data-toggle="collapse"
                        href="#testimony_menu">
                        <i
                            class="sidebar-menu-icon sidebar-menu-icon--left material-icons">access_time</i>
                        <span class="sidebar-menu-text">Riwayat Testimoni</span>
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse" id="testimony_menu">
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fluid-companies.html">
                                <span class="sidebar-menu-text">Kirim Testimoni</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a class="sidebar-menu-button" href="fluid-stories.html">
                                <span class="sidebar-menu-text">Riwayat Testimoni</span>
                            </a>
                        </li>
                    </ul>
                </li> -->
            <!-- MASTER TESTIMONY END -->
            </ul>

            <!-- *********************************CONTENT MENU********************************* -->
            <?php if (roleAccess(['Super Admin', 'Moderator', 'Admin'])): ?>
                <div class="sidebar-heading">CONTENT MENU</div>
                <div class="sidebar-block p-0 mb-0">
                    <ul class="sidebar-menu">

                        <!-- JOB APPLICATION MENU -->
                        <li class="sidebar-menu-item <?= activeMenu([
                                                            'add_job_information.php',
                                                            'manage_job_information.php'
                                                        ]); ?>">
                            <a
                                class="sidebar-menu-button"
                                data-toggle="collapse"
                                href="#job_menu">
                                <i
                                    class="sidebar-menu-icon sidebar-menu-icon--left material-icons">work</i>
                                <span class="sidebar-menu-text">INFO LOKER</span>
                                <span class="ml-auto sidebar-menu-toggle-icon"></span>
                            </a>
                            <ul class="sidebar-submenu collapse" id="job_menu">
                                <li class="sidebar-menu-item <?= activeMenu(['add_job_information.php']); ?>">
                                    <a
                                        class="sidebar-menu-button"
                                        href="add_job_information.php">
                                        <span class="sidebar-menu-text">Tambah Info Loker</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item <?= activeMenu(['manage_job_information.php']); ?>">
                                    <a class="sidebar-menu-button" href="manage_job_information.php">
                                        <span class="sidebar-menu-text">Manage Info Loker</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- JOB APPLICATION MENU END -->

                        <!-- STRUKTUR PERUSAHAAN MENU -->
                        <li class="sidebar-menu-item <?= activeMenu([
                                                            'manage_company_structure.php'
                                                        ]); ?>">
                            <a
                                class="sidebar-menu-button"
                                data-toggle="collapse"
                                href="#company_structure_menu">
                                <i
                                    class="sidebar-menu-icon sidebar-menu-icon--left material-icons">work</i>
                                <span class="sidebar-menu-text">Struktur Perusahaan</span>
                                <span class="ml-auto sidebar-menu-toggle-icon"></span>
                            </a>
                            <ul class="sidebar-submenu collapse" id="company_structure_menu">
                                <li class="sidebar-menu-item <?= activeMenu(['manage_company_structure.php']); ?>">
                                    <a class="sidebar-menu-button" href="manage_company_structure.php">
                                        <span class="sidebar-menu-text">Manage Struktur Perusahaan</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- STRUKTUR PERUSAHAAN MENU END -->

                        <!-- GALLERY MENU -->
                        <li class="sidebar-menu-item <?= activeMenu(['manage_gallery.php']); ?>">
                            <a
                                class="sidebar-menu-button"
                                data-toggle="collapse"
                                href="#gallery_menu">
                                <i
                                    class="sidebar-menu-icon sidebar-menu-icon--left material-icons">photo_library</i>
                                <span class="sidebar-menu-text">Galeri</span>
                                <span class="ml-auto sidebar-menu-toggle-icon"></span>
                            </a>
                            <ul class="sidebar-submenu collapse" id="gallery_menu">
                                <li class="sidebar-menu-item <?= activeMenu(['manage_gallery.php']); ?>">
                                    <a class="sidebar-menu-button" href="manage_gallery.php">
                                        <span class="sidebar-menu-text">Manage Galeri</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- GALLERY MENU END -->

                        <!-- OUR CLIENT MENU -->
                        <li class="sidebar-menu-item <?= activeMenu(['manage_our_clients.php']); ?>">
                            <a
                                class="sidebar-menu-button"
                                data-toggle="collapse"
                                href="#our_client_menu">
                                <i
                                    class="sidebar-menu-icon sidebar-menu-icon--left material-icons">assignment_turned_in</i>
                                <span class="sidebar-menu-text">Klien Kami</span>
                                <span class="ml-auto sidebar-menu-toggle-icon"></span>
                            </a>
                            <ul class="sidebar-submenu collapse" id="our_client_menu">
                                <li class="sidebar-menu-item <?= activeMenu(['manage_our_clients.php']); ?>">
                                    <a class="sidebar-menu-button" href="manage_our_clients.php">
                                        <span class="sidebar-menu-text">Manage Klien Kami</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- KLIEN KAMI MENU END -->

                        <!-- MITRA MENU -->
                        <li class="sidebar-menu-item <?= activeMenu(['manage_partners.php']); ?>">
                            <a class="sidebar-menu-button" href="manage_partners.php">
                                <i
                                    class="sidebar-menu-icon sidebar-menu-icon--left material-icons">layers</i>
                                <span class="sidebar-menu-text">Mitra Kami</span>
                            </a>
                        </li>
                        <!-- MITRA MENU END -->

                        <!-- BANNER MENU -->
                        <li class="sidebar-menu-item <?= activeMenu(['manage_banner.php']); ?>">
                            <a class="sidebar-menu-button" href="manage_banner.php">
                                <i
                                    class="sidebar-menu-icon sidebar-menu-icon--left material-icons">layers</i>
                                <span class="sidebar-menu-text">Manage Hero Banner</span>
                            </a>
                        </li>
                        <!-- BANNER MENU END -->

                        <!-- SOCIAL MEDIA MENU -->
                        <li class="sidebar-menu-item <?= activeMenu(['manage_socmed.php']); ?>">
                            <a class="sidebar-menu-button" href="manage_socmed.php">
                                <i
                                    class="sidebar-menu-icon sidebar-menu-icon--left material-icons">layers</i>
                                <span class="sidebar-menu-text">Manage Social Media</span>
                            </a>
                        </li>
                        <!-- SOCIAL MEDIA MENU END -->

                        <!-- IKLAN MENU -->
                        <li class="sidebar-menu-item <?= activeMenu(['manage_ads.php']); ?>">
                            <a class="sidebar-menu-button" href="manage_ads.php">
                                <i
                                    class="sidebar-menu-icon sidebar-menu-icon--left material-icons">layers</i>
                                <span class="sidebar-menu-text">Manage Iklan</span>
                            </a>
                        </li>
                        <!-- IKLAN MENU END -->

                        <!-- LEGALITAS MENU -->
                        <li class="sidebar-menu-item <?= activeMenu(['manage_legality.php']); ?>">
                            <a class="sidebar-menu-button" href="manage_legality.php">
                                <i
                                    class="sidebar-menu-icon sidebar-menu-icon--left material-icons">assignment_turned_in</i>
                                <span class="sidebar-menu-text">Legalitas</span>
                            </a>
                        </li>
                        <!-- LEGALITAS MENU END -->

                        <!-- BERITA / ARTIKEL PERUSAHAAN MENU -->
                        <li class="sidebar-menu-item <?= activeMenu([
                                                            'manage_post_category.php',
                                                            'manage_post_subcategory.php',
                                                            'add_post.php',
                                                            'manage_post.php',
                                                            'manage_comments.php'
                                                        ]); ?>">
                            <a
                                class="sidebar-menu-button"
                                data-toggle="collapse"
                                href="#article_menu">
                                <i
                                    class="sidebar-menu-icon sidebar-menu-icon--left material-icons">layers</i>
                                <span class="sidebar-menu-text">Berita / Artikel</span>
                                <!-- <span class="badge badge-primary badge-pill ml-1">3</span> -->
                                <span class="ml-auto sidebar-menu-toggle-icon"></span>
                            </a>
                            <ul class="sidebar-submenu collapse" id="article_menu">
                                <li class="sidebar-menu-item <?= activeMenu(['manage_post_category.php']); ?>">
                                    <a class="sidebar-menu-button" href="manage_post_category.php">
                                        <span class="sidebar-menu-text">Manage Kategori</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item <?= activeMenu(['manage_post_subcategory.php']); ?>">
                                    <a class="sidebar-menu-button" href="manage_post_subcategory.php">
                                        <span class="sidebar-menu-text">Manage Sub-Kategori</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item <?= activeMenu(['add_post.php']); ?>">
                                    <a class="sidebar-menu-button" href="add_post.php">
                                        <span class="sidebar-menu-text">Tambah Postingan</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item <?= activeMenu(['manage_post.php']); ?>">
                                    <a class="sidebar-menu-button" href="manage_post.php">
                                        <span class="sidebar-menu-text">Manage Postingan</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item <?= activeMenu(['manage_comments.php']); ?>">
                                    <a class="sidebar-menu-button" href="manage_comments.php">
                                        <span class="sidebar-menu-text">Manage Komentar</span>
                                        <!-- <span class="badge badge-primary badge-pill ml-1">3</span> -->
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- BERITA / ARTIKEL MENU END -->

                        <!-- KOTAK MASUK MENU -->
                        <li class="sidebar-menu-item <?= activeMenu(['manage_inbox.php']); ?>">
                            <a class="sidebar-menu-button" href="manage_inbox.php">
                                <i
                                    class="sidebar-menu-icon sidebar-menu-icon--left material-icons">inbox</i>
                                <span class="sidebar-menu-text">Kotak Masuk</span>
                                <!-- <span class="badge badge-primary badge-pill ml-1">3</span> -->
                            </a>
                        </li>
                        <!-- KOTAK MASUK MENU END -->
                    </ul>
                    <!-- *********************************FIRST MENU END********************************* -->

                </div>
            <?php endif; ?>

            <div class="d-flex align-items-center sidebar-p-a border-bottom sidebar-account">

                <a href="edit_profile.php"
                    class="flex d-flex align-items-center text-underline-0 text-body">

                    <span class="avatar avatar-sm mr-2">

                        <img src="<?= $userPhoto; ?>"
                            alt="<?= htmlspecialchars($userName); ?>"
                            class="avatar-img rounded-circle"
                            style="object-fit: cover;">

                    </span>

                    <span class="flex d-flex flex-column">

                        <strong>
                            <?= htmlspecialchars($userName); ?>
                        </strong>

                        <small class="text-muted text-uppercase">
                            <?= htmlspecialchars($userRole); ?>
                        </small>

                    </span>

                </a>

                <div class="dropdown ml-auto">

                    <a href="#"
                        data-toggle="dropdown"
                        data-caret="false"
                        class="text-muted">

                        <i class="material-icons">more_vert</i>

                    </a>

                    <div class="dropdown-menu dropdown-menu-right">

                        <div class="dropdown-item-text dropdown-item-text--lh">

                            <div>
                                <strong><?= htmlspecialchars($userName); ?></strong>
                            </div>

                            <div>
                                <?= htmlspecialchars($userEmail); ?>
                            </div>

                        </div>

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item"
                            href="index.php">
                            Dashboard
                        </a>

                        <a class="dropdown-item"
                            href="edit_profile.php">
                            Edit Profile
                        </a>

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item"
                            href="logout.php">
                            Logout
                        </a>

                    </div>

                </div>

            </div>
        </div>
        <!-- *********************************CLIENT MENU END********************************* -->
    </div>
</div>
</div>