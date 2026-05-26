<?php
// =========================
// USER TOP HEADER PROFILE
// =========================

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . "/../../koneksi.php";

$user_id = $_SESSION['user_id'] ?? 0;

$user_header = null;

if ($user_id > 0) {

  $queryHeaderUser = mysqli_query($conn, "
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

  if ($queryHeaderUser && mysqli_num_rows($queryHeaderUser) > 0) {
    $user_header = mysqli_fetch_assoc($queryHeaderUser);
  }
}

// =========================
// DEFAULT VALUE
// =========================

$defaultPhoto = "../assets/images/avatar/demi.png";

$userNameHeader  = $user_header['full_name'] ?? 'Unknown User';
$userRoleHeader  = $user_header['role_name'] ?? 'No Role';
$userEmailHeader = $user_header['email'] ?? '-';

$userPhotoHeader = $defaultPhoto;

// =========================
// CHECK PHOTO
// =========================

if (
  !empty($user_header['photo_profile']) &&
  file_exists(
    __DIR__ . "/../../assets/images/uploads/user_photos/" . $user_header['photo_profile']
  )
) {

  $userPhotoHeader = "../assets/images/uploads/user_photos/" . $user_header['photo_profile'];
}
?>
<div id="header" class="mdk-header bg-dark js-mdk-header m-0" data-fixed>
  <div class="mdk-header__content">
    <div
      class="navbar navbar-expand-sm navbar-main navbar-dark bg-dark pr-0 pr-0"
      id="navbar"
      data-primary>
      <div class="container-fluid p-0">
        <!-- Navbar toggler -->
        <button
          class="navbar-toggler navbar-toggler-custom navbar-toggler-right d-block"
          type="button"
          data-toggle="sidebar">
          <span class="material-icons">apps</span>
        </button>

        <!-- Navbar Brand -->
        <a href="/" class="my-navbar-brand">
          <img
            src="/my-dashboard/fixed-v2/dashboard/assets/images/logos/logo.png"
            alt="Logo"
            class="my-navbar-logo">
        </a>

        <!-- <form
          class="search-form d-none d-sm-flex flex"
          action="fluid-index.html">
          <button class="btn" type="submit">
            <i class="material-icons">search</i>
          </button>
          <input type="text" class="form-control" placeholder="Search" />
        </form> -->

        <ul class="nav navbar-nav ml-auto d-none d-md-flex">
          <li class="nav-item dropdown">
            <a
              href="#notifications_menu"
              class="nav-link dropdown-toggle"
              data-toggle="dropdown"
              data-caret="false">
              <i
                class="material-icons nav-icon navbar-notifications-indicator">notifications</i>
            </a>
            <div
              id="notifications_menu"
              class="dropdown-menu dropdown-menu-right navbar-notifications-menu">
              <div class="dropdown-item d-flex align-items-center py-2">
                <span class="flex navbar-notifications-menu__title m-0">Notifikasi</span>
                <a href="javascript:void(0)" class="text-muted"><small>Hapus Semua</small></a>
              </div>
              <div
                class="navbar-notifications-menu__content">
                <div class="py-2">
                  <div class="dropdown-item d-flex">
                    <div class="mr-3">
                      <div
                        class="avatar avatar-sm"
                        style="width: 32px; height: 32px">
                        <img
                          src="assets/images/256_daniel-gaffey-1060698-unsplash.jpg"
                          alt="Avatar"
                          class="avatar-img rounded-circle" />
                      </div>
                    </div>
                    <div class="flex">
                      <a href="">A.Demian</a> menulis pesan di
                      <a href="">Kontak Kami</a><br />
                      <small class="text-muted">1 minute ago</small>
                    </div>
                  </div>
                  <div class="dropdown-item d-flex">
                    <div class="mr-3">
                      <a href="#">
                        <div
                          class="avatar avatar-xs"
                          style="width: 32px; height: 32px">
                          <span
                            class="avatar-title bg-purple rounded-circle"><i class="material-icons icon-16pt">person_add</i></span>
                        </div>
                      </a>
                    </div>
                    <div class="flex">
                      Selamat datang <a href="#">Peter Parker</a> telah mendaftar di Konig Guard<br />
                      <small class="text-muted">1 hour ago</small>
                    </div>
                  </div>

                  <div class="dropdown-item d-flex">
                    <div class="mr-3">
                      <div
                        class="avatar avatar-sm"
                        style="width: 32px; height: 32px">
                        <img
                          src="assets/images/256_daniel-gaffey-1060698-unsplash.jpg"
                          alt="Avatar"
                          class="avatar-img rounded-circle" />
                      </div>
                    </div>
                    <div class="flex">
                      Seseorang telah memesan jasa #ORD1702271
                      <a href="">Cek Disini</a><br />
                      <small class="text-muted">1 minute ago</small>
                    </div>
                  </div>
                </div>
              </div>
              <a
                href="javascript:void(0);"
                class="dropdown-item text-center navbar-notifications-menu__footer mt-4">Lihat Semua</a>
            </div>
          </li>
        </ul>

        <ul
          class="nav navbar-nav d-none d-sm-flex border-left navbar-height align-items-center">

          <li class="nav-item dropdown">

            <a
              href="#account_menu"
              class="nav-link dropdown-toggle"
              data-toggle="dropdown"
              data-caret="false">

              <span class="mr-1 d-flex-inline">

                <span class="text-light">
                  <?= htmlspecialchars($userNameHeader); ?>
                </span>

              </span>

              <img
                src="<?= $userPhotoHeader; ?>"
                class="rounded-circle"
                width="32"
                height="32"
                style="object-fit: cover;"
                alt="<?= htmlspecialchars($userNameHeader); ?>" />

            </a>

            <div
              id="account_menu"
              class="dropdown-menu dropdown-menu-right">

              <div class="dropdown-item-text dropdown-item-text--lh">

                <div>
                  <strong>
                    <?= htmlspecialchars($userNameHeader); ?>
                  </strong>
                </div>

                <div class="text-muted">
                  <?= htmlspecialchars($userRoleHeader); ?>
                </div>

                <div class="text-muted small">
                  <?= htmlspecialchars($userEmailHeader); ?>
                </div>

              </div>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item active" href="index.php">
                <i class="material-icons">dvr</i>
                Dashboard
              </a>

              <a class="dropdown-item" href="edit_profile.php">
                <i class="material-icons">account_circle</i>
                Edit Akun
              </a>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="logout.php">
                <i class="material-icons">exit_to_app</i>
                Logout
              </a>

            </div>

          </li>

        </ul>
      </div>
    </div>
  </div>
</div>