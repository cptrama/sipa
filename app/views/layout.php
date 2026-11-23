<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Primary SEO & Title -->
    <title><?= e(!empty($title) ? (str_contains($title, config('app_name')) ? $title : $title . ' - ' . config('app_name')) : config('app_name')) ?></title>
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="description" content="<?= e(config('app_name')) ?> & Materi Kuliah <?= e(config('mata_kuliah', 'Komputasi Awan')) ?>, Program Studi Teknologi Informasi, Semester <?= e(config('semester')) ?> <?= e(config('tahun_ajar')) ?>.">
    <meta name="author" content="Program Studi <?= e(config('prodi')) ?>">
    <meta name="application-name" content="<?= e(config('app_name')) ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/apple-touch-icon.png">
    
    <!-- Tabler Core CSS -->
    <link rel="stylesheet" href="/assets/css/tabler.min.css">
    
    <!-- Tabler & Bootstrap Icons -->
    <link rel="stylesheet" href="/assets/css/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-icons.min.css">
    
    <!-- Custom Tabler Bridge -->
    <link rel="stylesheet" href="/assets/css/tabler-custom.css?v=<?= @filemtime(dirname(__DIR__, 2) . '/public/assets/css/tabler-custom.css') ?: '2.1' ?>">

    <!-- Early Theme Initializer (Prevent FOUC) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('tabler-theme') || localStorage.getItem('theme');
            const theme = savedTheme ? savedTheme : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', theme);
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>
<body>
    <?php 
    $flashes = get_flashes();
    $welcomeFlash = null;
    $regularFlashes = [];
    foreach ($flashes as $fl) {
        if (stripos($fl['message'], 'Selamat datang') !== false) {
            $welcomeFlash = $fl;
        } else {
            $regularFlashes[] = $fl;
        }
    }

    $showWelcomeModal = false;
    if (($welcomeFlash || !empty($_SESSION['just_logged_in']) || !empty($_GET['welcome'])) && is_logged_in()) {
        $showWelcomeModal = true;
        unset($_SESSION['just_logged_in']);
    }
    ?>

    <?php if (!empty($hideNavbar)): ?>
        <!-- Floating Theme Toggle for Login / Auth page -->
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <a href="javascript:void(0)" class="btn btn-icon btn-outline-secondary rounded-circle shadow-sm" id="tablerThemeToggleLogin" title="Ganti Mode Gelap / Terang">
                <i class="ti ti-moon fs-2 theme-icon-dark text-primary"></i>
                <i class="ti ti-sun fs-2 theme-icon-light text-warning d-none"></i>
            </a>
        </div>

        <!-- Tabler Page Center for Authentication -->
        <div class="page page-center">
            <div class="container-tight py-4" style="max-width: 440px; width: 100%; margin: 0 auto;">
                <?= $body ?? '' ?>
            </div>
        </div>

    <?php else: ?>
        <div class="page">

            <!-- Sticky Dual-Header Container (Always follows screen on scroll, never cut off) -->
            <div class="sticky-top shadow-sm" style="z-index: 1020;">
                <!-- Tabler Header Bar (Main Branding & User Controls) -->
                <header class="navbar navbar-expand-md d-print-none border-bottom py-3" style="min-height: 76px; background-color: var(--tblr-card-bg) !important;">
                <div class="container-xl d-flex align-items-center justify-content-between">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Brand -->
                    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3 m-0 p-0">
                        <a href="<?= is_dosen() ? '/dosen' : (is_mahasiswa() ? '/dashboard' : '/login') ?>" class="d-flex align-items-center text-decoration-none text-reset" style="gap: 14px;">
                            <img src="/assets/logo-unism.png" alt="Logo Universitas Sari Mulia" class="flex-shrink-0" style="height: 42px; width: auto; object-fit: contain;">
                            <div class="d-flex flex-column text-start justify-content-center" style="gap: 4px;">
                                <span class="fw-bold fs-3 text-body" style="line-height: 1.25;"><?= e(config('app_name')) ?></span>
                                <span class="text-secondary small" style="font-size: 0.78rem; line-height: 1.25;"><?= e(config('mata_kuliah', 'Komputasi Awan')) ?> &bull; Semester <?= e(config('semester')) ?> (<?= e(config('tahun_ajar')) ?>)</span>
                            </div>
                        </a>
                    </h1>

                    <!-- Right controls: Theme switcher & User menu -->
                    <div class="navbar-nav flex-row order-md-last align-items-center" style="gap: 16px;">
                        
                        <!-- Dark / Light Theme Toggle -->
                        <div>
                            <a href="javascript:void(0)" class="nav-link rounded-circle d-flex align-items-center justify-content-center shadow-none" id="tablerThemeToggle" title="Ganti Mode Gelap / Terang" style="width: 38px; height: 38px; padding: 0;">
                                <i class="ti ti-moon fs-2 theme-icon-dark text-primary"></i>
                                <i class="ti ti-sun fs-2 theme-icon-light text-warning d-none"></i>
                            </a>
                        </div>

                        <?php if (is_logged_in()): ?>
                            <?php $user = auth_user(); ?>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link d-flex align-items-center text-reset p-1 rounded text-decoration-none shadow-none" data-bs-toggle="dropdown" data-bs-display="dynamic" data-bs-offset="0,6" aria-expanded="false" aria-label="Open user menu" style="gap: 12px;">
                                    <?= render_avatar($user, 40) ?>
                                    <div class="d-none d-xl-flex flex-column text-start justify-content-center" style="gap: 3px;">
                                        <span class="fw-semibold text-dark fs-4" style="line-height: 1.25;"><?= e($user['nama']) ?></span>
                                        <span class="text-secondary small" style="font-size: 0.78rem; line-height: 1.25;">
                                            <?= is_dosen() ? 'Dosen Pengampu' : 'Mahasiswa (Kelas ' . e($user['kelas'] ?? 'S5') . ')' ?>
                                        </span>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <a href="<?= is_dosen() ? '/dosen/profil' : '/profil' ?>" class="dropdown-item d-flex align-items-center gap-2">
                                        <i class="ti ti-user text-primary fs-2"></i>
                                        <span>Profil Saya</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <button type="button" class="dropdown-item text-danger d-flex align-items-center gap-2 w-100" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">
                                        <i class="ti ti-logout fs-2"></i>
                                        <span>Logout</span>
                                    </button>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="nav-item">
                                <a href="/login" class="btn btn-primary btn-sm">
                                    <i class="ti ti-login me-1"></i> Login
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                </header>

                <!-- Tabler Navigation Menu Bar -->
                <header class="navbar-expand-md border-bottom bg-body-tertiary" style="background-color: var(--tblr-card-bg) !important;">
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div class="navbar">
                        <div class="container-xl">
                            <ul class="navbar-nav">
                                <?php if (is_dosen()): ?>
                                    <li class="nav-item <?= (strpos($_SERVER['REQUEST_URI'] ?? '', '/dosen') === 0 && strpos($_SERVER['REQUEST_URI'] ?? '', '/dosen/') === false) ? 'active' : '' ?>">
                                        <a class="nav-link" href="/dosen">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-home fs-2"></i></span>
                                            <span class="nav-link-title">Dashboard</span>
                                        </a>
                                    </li>
                                    <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/dosen/pertemuan') === 0 ? 'active' : '' ?>">
                                        <a class="nav-link" href="/dosen/pertemuan">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-calendar-event fs-2"></i></span>
                                            <span class="nav-link-title">Pertemuan</span>
                                        </a>
                                    </li>
                                    <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/dosen/tugas') === 0 ? 'active' : '' ?>">
                                        <a class="nav-link" href="/dosen/tugas">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-clipboard-list fs-2"></i></span>
                                            <span class="nav-link-title">Tugas</span>
                                        </a>
                                    </li>
                                    <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/dosen/rekap') === 0 ? 'active' : '' ?>">
                                        <a class="nav-link" href="/dosen/rekap">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-table fs-2"></i></span>
                                            <span class="nav-link-title">Rekap Nilai</span>
                                        </a>
                                    </li>
                                    <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/dosen/mahasiswa') === 0 ? 'active' : '' ?>">
                                        <a class="nav-link" href="/dosen/mahasiswa">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-users fs-2"></i></span>
                                            <span class="nav-link-title">Mahasiswa</span>
                                        </a>
                                    </li>
                                <?php elseif (is_mahasiswa()): ?>
                                    <li class="nav-item <?= (strpos($_SERVER['REQUEST_URI'] ?? '', '/dashboard') === 0) ? 'active' : '' ?>">
                                        <a class="nav-link" href="/dashboard">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-home fs-2"></i></span>
                                            <span class="nav-link-title">Dashboard</span>
                                        </a>
                                    </li>
                                    <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/pertemuan') === 0 ? 'active' : '' ?>">
                                        <a class="nav-link" href="/pertemuan">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-book fs-2"></i></span>
                                            <span class="nav-link-title">Pertemuan & Materi</span>
                                        </a>
                                    </li>
                                    <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/nilai') === 0 ? 'active' : '' ?>">
                                        <a class="nav-link" href="/nilai">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-certificate fs-2"></i></span>
                                            <span class="nav-link-title">Nilai Saya</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                </header>
            </div>

            <!-- Page Wrapper -->
            <div class="page-wrapper">
            <div class="page-body">
                <div class="container-xl">
                    <!-- Page Content Body -->
                    <?= $body ?? '' ?>
                </div>
            </div>

            <!-- Footer -->
            <footer class="footer footer-transparent d-print-none border-top mt-auto py-3">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <ul class="list-inline list-inline-dots mb-0 text-secondary small">
                                <li class="list-inline-item">Semester <?= e(config('semester')) ?> <?= e(config('tahun_ajar')) ?></li>
                                <li class="list-inline-item">Program Studi <?= e(config('prodi')) ?></li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0 text-secondary small">
                                <li class="list-inline-item">
                                    <?= e(config('app_name')) ?> &copy; <?= date('Y') ?> &bull; Universitas Sari Mulia
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

    </div>
    <?php endif; ?>

    <!-- Welcome Center Pop-up Modal -->
    <?php if ($showWelcomeModal && is_logged_in()): ?>
        <?php $authUser = auth_user(); ?>
        <div class="modal modal-blur fade" id="welcomePopupModal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="true">
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 440px;">
                <div class="modal-content shadow-lg border-0 text-center p-4 rounded-4 position-relative">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    <div class="modal-body p-0 mt-2">
                        <div class="avatar avatar-xl bg-primary-lt text-primary rounded-circle shadow-sm mx-auto mb-3" style="width: 68px; height: 68px;">
                            <i class="ti ti-sparkles" style="font-size: 2.2rem;"></i>
                        </div>
                        <h3 class="fw-bold mb-1 fs-2 text-dark">Selamat Datang!</h3>
                        <h4 class="text-primary fw-semibold mb-2"><?= e($authUser['nama'] ?? 'Pengguna') ?></h4>
                        <div class="d-inline-flex align-items-center gap-1 badge bg-secondary-lt text-secondary px-3 py-1 mb-3">
                            <span><?= is_dosen() ? 'Dosen Pengampu' : 'Mahasiswa (NIM: ' . e($authUser['nim'] ?? '') . ')' ?></span>
                        </div>
                        <p class="text-secondary small mb-4 lh-base" style="font-size: 0.95rem;">
                            <?= !empty($welcomeFlash['message']) ? e($welcomeFlash['message']) . '<br>' : 'Anda berhasil masuk ke sistem.<br>' ?>
                            Mata Kuliah <strong><?= e(config('mata_kuliah', 'Komputasi Awan')) ?></strong> &bull; Semester <?= e(config('semester')) ?> (<?= e(config('tahun_ajar')) ?>)
                        </p>
                        <button type="button" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 fs-3 rounded-3" data-bs-dismiss="modal">
                            <span>Masuk ke Dashboard</span>
                            <i class="ti ti-arrow-right fs-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Flash Notification Pop-up Modal (Validation, Danger, Warning, Success, Info) -->
    <?php if (!empty($regularFlashes)): 
        $hasDanger = false;
        $hasWarning = false;
        $hasSuccess = false;
        foreach ($regularFlashes as $rf) {
            if ($rf['type'] === 'danger') $hasDanger = true;
            elseif ($rf['type'] === 'warning') $hasWarning = true;
            elseif ($rf['type'] === 'success') $hasSuccess = true;
        }

        if ($hasDanger) {
            $modalColor = 'danger';
            $modalIcon = 'ti-alert-triangle';
            $modalBtnClass = 'btn-danger text-white';
            $modalBtnText = 'Mengerti';
            $modalTitle = 'Pemberitahuan';
            foreach ($regularFlashes as $rf) {
                if (stripos($rf['message'], 'blokir') !== false) {
                    $modalTitle = 'Diblokir Sementara';
                    break;
                } elseif (stripos($rf['message'], 'akses ditolak') !== false) {
                    $modalTitle = 'Akses Ditolak';
                    break;
                } elseif (stripos($rf['message'], 'login') !== false || stripos($rf['message'], 'nim') !== false || stripos($rf['message'], 'password') !== false || stripos($rf['message'], 'salah') !== false) {
                    $modalTitle = 'Login Gagal';
                    break;
                } elseif (stripos($rf['message'], 'gagal') !== false || stripos($rf['message'], 'ditolak') !== false) {
                    $modalTitle = 'Login Gagal';
                    break;
                }
            }
        } elseif ($hasWarning) {
            $modalColor = 'warning';
            $modalIcon = 'ti-alert-circle';
            $modalBtnClass = 'btn-warning text-dark';
            $modalBtnText = 'Mengerti';
            $modalTitle = 'Peringatan';
        } elseif ($hasSuccess) {
            $modalColor = 'success';
            $modalIcon = 'ti-circle-check';
            $modalBtnClass = 'btn-success text-white';
            $modalBtnText = 'Selesai';
            $modalTitle = 'Berhasil';
        } else {
            $modalColor = 'primary';
            $modalIcon = 'ti-info-circle';
            $modalBtnClass = 'btn-primary text-white';
            $modalBtnText = 'Tutup';
            $modalTitle = 'Informasi';
        }
    ?>
    <div class="modal modal-blur fade" id="flashNotificationModal" tabindex="-1" role="dialog" aria-labelledby="flashModalTitle" aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 440px;">
            <div class="modal-content shadow-lg border-0 text-center p-4 rounded-4 position-relative overflow-hidden">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Tutup"></button>
                <div class="modal-body p-0 mt-2">
                    <div class="avatar avatar-xl bg-<?= $modalColor ?>-lt text-<?= $modalColor ?> rounded-circle shadow-sm mx-auto mb-3" style="width: 64px; height: 64px;">
                        <i class="ti <?= $modalIcon ?>" style="font-size: 2.2rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-2 fs-2 text-dark" id="flashModalTitle"><?= e($modalTitle) ?></h3>
                    
                    <div class="text-secondary mb-3 lh-base" style="font-size: 0.95rem;">
                        <?php if (count($regularFlashes) === 1): ?>
                            <p class="mb-0"><?= e($regularFlashes[0]['message']) ?></p>
                        <?php else: ?>
                            <ul class="text-start mb-0 ps-3">
                                <?php foreach ($regularFlashes as $rf): ?>
                                    <li class="mb-1"><?= e($rf['message']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                    <div class="small text-secondary mb-3 d-flex align-items-center justify-content-center gap-1.5" style="font-size: 0.84rem;">
                        <i class="ti ti-clock-hour-4"></i>
                        <span>Menutup otomatis dalam <strong id="flashTimerSecond" class="text-<?= $modalColor ?> fw-bold">5</strong> detik</span>
                    </div>
                    
                    <button type="button" class="btn <?= $modalBtnClass ?> w-100 py-2.5 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 fs-3 rounded-3" data-bs-dismiss="modal">
                        <span><?= e($modalBtnText) ?></span>
                        <span id="flashTimerText" class="opacity-75 small fw-normal ms-1">(5s)</span>
                    </button>
                </div>

                <!-- 5-Second Countdown Progress Bar Animation at Bottom Edge -->
                <div class="modal-countdown-track">
                    <div id="flashTimerBar" class="modal-countdown-bar bg-<?= $modalColor ?>"></div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Logout Confirmation Pop-up Modal (Centered) -->
    <?php if (is_logged_in()): ?>
    <div class="modal modal-blur fade" id="logoutConfirmModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalTitle" aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 420px;">
            <div class="modal-content shadow-lg border-0 text-center p-4 rounded-4 position-relative overflow-hidden">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Tutup"></button>
                <div class="modal-body p-0 mt-2">
                    <div class="avatar avatar-xl bg-danger-lt text-danger rounded-circle shadow-sm mx-auto mb-3" style="width: 64px; height: 64px;">
                        <i class="ti ti-logout" style="font-size: 2.2rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-2 fs-2 text-body" id="logoutModalTitle">Konfirmasi Logout</h3>
                    <p class="text-secondary small mb-4 lh-base" style="font-size: 0.95rem;">
                        Apakah Anda yakin ingin keluar dari sistem? Anda harus memasukkan kredensial login kembali untuk mengakses akun Anda.
                    </p>
                    <div class="row g-2">
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-secondary w-100 py-2.5 fw-semibold rounded-3 shadow-none d-inline-flex align-items-center justify-content-center gap-1.5" data-bs-dismiss="modal">
                                <i class="ti ti-x"></i>
                                <span>Batal</span>
                            </button>
                        </div>
                        <div class="col-6">
                            <form action="/logout" method="POST" class="m-0 p-0" id="formLogoutModal">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger w-100 py-2.5 fw-semibold rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center gap-1.5 text-white btn-logout-confirm">
                                    <i class="ti ti-logout"></i>
                                    <span>Ya, Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Tabler Core JS -->
    <script src="/assets/js/tabler.min.js"></script>

    <!-- Theme Switcher & Countdown Timer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Countdown timer logic
            const countdownElements = document.querySelectorAll('[data-countdown]');
            if (countdownElements.length > 0) {
                function updateCountdowns() {
                    const now = Math.floor(Date.now() / 1000);
                    countdownElements.forEach(el => {
                        const target = parseInt(el.getAttribute('data-countdown'), 10);
                        const diff = target - now;

                        if (diff <= 0) {
                            el.innerHTML = '<span class="badge bg-danger text-white"><i class="ti ti-clock-stop me-1"></i>Waktu habis</span>';
                            return;
                        }

                        const days = Math.floor(diff / 86400);
                        const hours = Math.floor((diff % 86400) / 3600);
                        const minutes = Math.floor((diff % 3600) / 60);
                        const seconds = diff % 60;

                        const fullCountdown = (days > 0 ? days + 'h ' : '') +
                            String(hours).padStart(2, '0') + 'j ' +
                            String(minutes).padStart(2, '0') + 'm ' +
                            String(seconds).padStart(2, '0') + 'd';

                        el.innerHTML = '<span class="badge bg-primary text-white text-nowrap px-2.5 py-1.5 d-inline-flex align-items-center shadow-sm font-monospace" style="font-size: 0.82rem; font-weight: 600; gap: 7px;">' +
                            '<i class="ti ti-clock"></i><span style="position: relative; top: 0.5px;">' + fullCountdown + '</span>' +
                        '</span>';
                    });
                }
                updateCountdowns();
                setInterval(updateCountdowns, 1000);
            }

            // 2. Tabler Theme Toggle logic
            function getCurrentTheme() {
                return document.documentElement.getAttribute('data-bs-theme') || 'light';
            }

            function syncThemeIcons(theme) {
                document.querySelectorAll('.theme-icon-dark').forEach(el => {
                    el.classList.toggle('d-none', theme === 'dark');
                });
                document.querySelectorAll('.theme-icon-light').forEach(el => {
                    el.classList.toggle('d-none', theme === 'light');
                });
            }

            function applyTheme(theme) {
                document.documentElement.setAttribute('data-bs-theme', theme);
                document.documentElement.setAttribute('data-theme', theme);
                if (document.body) {
                    document.body.setAttribute('data-bs-theme', theme);
                    document.body.setAttribute('data-theme', theme);
                }
                syncThemeIcons(theme);
            }

            applyTheme(getCurrentTheme());

            document.addEventListener('click', function (e) {
                const btn = e.target.closest('#tablerThemeToggle, #tablerThemeToggleLogin');
                if (btn) {
                    e.preventDefault();
                    const current = getCurrentTheme();
                    const nextTheme = current === 'dark' ? 'light' : 'dark';
                    applyTheme(nextTheme);
                    localStorage.setItem('tabler-theme', nextTheme);
                    localStorage.setItem('theme', nextTheme);
                }
            });

            // 3. Flash Notification Pop-up Modal Auto-Display & 5s Countdown Auto-Dismiss
            const flashModalEl = document.getElementById('flashNotificationModal');
            let flashModalInstance = null;
            if (flashModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                try {
                    flashModalInstance = new bootstrap.Modal(flashModalEl);
                    flashModalInstance.show();

                    const timerText = document.getElementById('flashTimerText');
                    const timerSecond = document.getElementById('flashTimerSecond');
                    let timeLeft = 5;
                    let countdownInterval = null;
                    let autoDismissTimeout = null;

                    // Second-by-second countdown (5s -> 4s -> 3s -> 2s -> 1s)
                    countdownInterval = setInterval(function () {
                        timeLeft--;
                        if (timerText && timeLeft >= 0) {
                            timerText.textContent = '(' + timeLeft + 's)';
                        }
                        if (timerSecond && timeLeft >= 0) {
                            timerSecond.textContent = timeLeft;
                        }
                        if (timeLeft <= 0) {
                            clearInterval(countdownInterval);
                        }
                    }, 1000);

                    // Automatically dismiss modal after exactly 5 seconds (5000ms)
                    autoDismissTimeout = setTimeout(function () {
                        if (flashModalInstance && flashModalEl.classList.contains('show')) {
                            flashModalInstance.hide();
                        }
                    }, 5000);

                    // Clean up timers immediately when closed (via Mengerti button, X, ESC, or backdrop)
                    flashModalEl.addEventListener('hidden.bs.modal', function () {
                        clearInterval(countdownInterval);
                        clearTimeout(autoDismissTimeout);
                    }, { once: true });

                } catch (err) {
                    console.error('Flash modal error:', err);
                }
            }

            // 4. Welcome Center Pop-up Modal Auto-Display
            const welcomeModalEl = document.getElementById('welcomePopupModal');
            if (welcomeModalEl && !flashModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                try {
                    const welcomeModalInstance = new bootstrap.Modal(welcomeModalEl);
                    welcomeModalInstance.show();
                } catch (err) {
                    console.error('Welcome modal error:', err);
                }
            }
        });
    </script>
</body>
</html>
