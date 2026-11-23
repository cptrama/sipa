<?php ob_start(); ?>

<div class="text-center mb-4">
    <a href="/login" class="d-inline-block mb-3 text-decoration-none">
        <img src="/assets/logo-unism.png" alt="Logo Universitas Sari Mulia" class="unism-brand-logo">
    </a>
    <h1 class="h1 text-body fw-bold mb-1 tracking-tight" style="letter-spacing: 0.04em;">SIPA</h1>
    <div class="text-secondary fs-3 fw-medium">(Sistem Informasi Pengumpulan Tugas)</div>
</div>

<div class="card card-md shadow-sm border login-auth-card" style="max-width: 440px; width: 100%; margin: 0 auto;">
    <div class="card-body p-4 p-md-4">
        <h2 class="h3 text-center mb-4 fw-bold">Masuk ke Akun Anda</h2>

        <form action="/login" method="POST" autocomplete="off" id="loginForm">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="nim" class="form-label fw-bold small text-uppercase text-secondary">NIM / NIK</label>
                <div class="input-group">
                    <span class="input-group-text bg-body-tertiary">
                        <i class="ti ti-user fs-2 text-secondary"></i>
                    </span>
                    <input type="text" class="form-control" id="nim" name="nim" required autofocus placeholder="Masukkan NIM atau NIK" autocomplete="username">
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold small text-uppercase text-secondary">Password</label>
                <div class="position-relative">
                    <div class="input-group">
                        <span class="input-group-text bg-body-tertiary">
                            <i class="ti ti-lock fs-2 text-secondary"></i>
                        </span>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Masukkan password" autocomplete="current-password" style="padding-right: 2.85rem;">
                    </div>
                    <button type="button" class="btn btn-link link-secondary p-0 border-0 position-absolute end-0 top-50 translate-middle-y me-3 text-decoration-none shadow-none d-flex align-items-center justify-content-center" id="togglePasswordBtn" title="Tampilkan / Sembunyikan Password" tabindex="-1" style="z-index: 5; background: transparent;">
                        <i class="ti ti-eye fs-2" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>

            <div class="form-footer mt-4">
                <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 fs-3">
                    <i class="ti ti-login fs-2"></i>
                    <span>Masuk Sekarang</span>
                </button>
            </div>
        </form>
    </div>

    <div class="card-footer bg-body-tertiary border-top p-3 text-secondary small">
        <div class="d-flex align-items-center gap-2">
            <i class="ti ti-info-circle text-primary fs-2 flex-shrink-0"></i>
            <div style="line-height: 1.35;">
                Password akun mahasiswa menggunakan format <strong>Nama@123</strong> (Contoh: <code>Rama@123</code>).
            </div>
        </div>
    </div>
</div>

<div class="text-center text-secondary small mt-4">
    <div><?= e(config('app_name')) ?> &copy; <?= date('Y') ?></div>
    <div class="mt-1">Universitas Sari Mulia</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passInput = document.getElementById('password');
        const passIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passInput && passIcon) {
            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const isPassword = passInput.type === 'password';
                passInput.type = isPassword ? 'text' : 'password';
                passIcon.classList.toggle('ti-eye', !isPassword);
                passIcon.classList.toggle('ti-eye-off', isPassword);
            });
        }
    });
</script>

<?php 
$body = ob_get_clean(); 
Flight::render('layout', ['body' => $body, 'title' => $title ?? 'Login', 'hideNavbar' => true]);
?>
