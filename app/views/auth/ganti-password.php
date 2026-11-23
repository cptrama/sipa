<?php ob_start(); ?>

<div class="text-center mb-4">
    <div class="avatar avatar-xl bg-primary-lt text-primary rounded-circle shadow-sm mb-3">
        <i class="ti ti-key fs-1"></i>
    </div>
    <h1 class="h2 text-dark fw-bold mb-1">Ganti Password</h1>
    <p class="text-secondary small mb-0 mt-2">
        <?php if (!empty($user['wajib_ganti_password'])): ?>
            Ini adalah login pertama Anda. Anda <strong class="text-danger">wajib</strong> mengganti password awal untuk melanjutkan.
        <?php else: ?>
            Perbarui kata sandi akun Anda di bawah ini.
        <?php endif; ?>
    </p>
</div>

<div class="card card-md shadow-sm border login-auth-card" style="max-width: 440px; width: 100%; margin: 0 auto;">
    <div class="card-body p-4">
        <form action="/ganti-password" method="POST" autocomplete="off">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="password_baru" class="form-label fw-bold small text-uppercase text-secondary">Password Baru</label>
                <div class="position-relative">
                    <div class="input-group">
                        <span class="input-group-text bg-body-tertiary"><i class="ti ti-lock fs-2 text-secondary"></i></span>
                        <input type="password" class="form-control" id="password_baru" name="password_baru" required minlength="6" placeholder="Minimal 6 karakter" autofocus style="padding-right: 2.85rem;">
                    </div>
                    <button type="button" class="btn btn-link link-secondary p-0 border-0 position-absolute end-0 top-50 translate-middle-y me-3 text-decoration-none shadow-none d-flex align-items-center justify-content-center" id="togglePasswordBtn" title="Tampilkan / Sembunyikan Password" tabindex="-1" style="z-index: 5; background: transparent;">
                        <i class="ti ti-eye fs-2" id="togglePasswordIcon"></i>
                    </button>
                </div>
                <div class="form-text small text-secondary">Panjang minimal 6 karakter.</div>
            </div>

            <div class="mb-3">
                <label for="konfirmasi_password" class="form-label fw-bold small text-uppercase text-secondary">Konfirmasi Password Baru</label>
                <div class="position-relative">
                    <div class="input-group">
                        <span class="input-group-text bg-body-tertiary"><i class="ti ti-shield-check fs-2 text-secondary"></i></span>
                        <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" required minlength="6" placeholder="Ketik ulang password baru" style="padding-right: 2.85rem;">
                    </div>
                    <button type="button" class="btn btn-link link-secondary p-0 border-0 position-absolute end-0 top-50 translate-middle-y me-3 text-decoration-none shadow-none d-flex align-items-center justify-content-center" id="toggleConfirmPasswordBtn" title="Tampilkan / Sembunyikan Password" tabindex="-1" style="z-index: 5; background: transparent;">
                        <i class="ti ti-eye fs-2" id="toggleConfirmPasswordIcon"></i>
                    </button>
                </div>
            </div>

            <div class="form-footer mt-4">
                <button type="submit" class="btn btn-primary text-white w-100 py-2.5 fw-bold shadow-sm d-flex align-items-center justify-content-center fs-3">
                    <span>Simpan Password Baru</span>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="text-center text-secondary small mt-4">
    <div><?= e(config('app_name')) ?> &copy; <?= date('Y') ?></div>
    <div class="mt-1">Universitas Sari Mulia</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function setupPasswordToggle(btnId, inputId, iconId) {
            const toggleBtn = document.getElementById(btnId);
            const passInput = document.getElementById(inputId);
            const passIcon = document.getElementById(iconId);

            if (toggleBtn && passInput && passIcon) {
                toggleBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const isPassword = passInput.type === 'password';
                    passInput.type = isPassword ? 'text' : 'password';
                    passIcon.classList.toggle('ti-eye', !isPassword);
                    passIcon.classList.toggle('ti-eye-off', isPassword);
                });
            }
        }

        setupPasswordToggle('togglePasswordBtn', 'password_baru', 'togglePasswordIcon');
        setupPasswordToggle('toggleConfirmPasswordBtn', 'konfirmasi_password', 'toggleConfirmPasswordIcon');
    });
</script>

<?php 
$body = ob_get_clean(); 
Flight::render('layout', ['body' => $body, 'title' => $title ?? 'Ganti Password', 'hideNavbar' => true]);
?>
