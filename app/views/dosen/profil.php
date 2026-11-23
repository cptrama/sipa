<?php ob_start(); ?>

<div class="row">
    <div class="col-12">

        <!-- Back Navigation Button -->
        <div class="mb-3">
            <a href="/dosen" class="btn btn-card-header-action shadow-none">
                <i class="ti ti-arrow-left"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

        <!-- Main Profile Form Card Container -->
        <div class="card shadow-sm border rounded-3 overflow-hidden mb-4">
            
            <!-- Card Header -->
            <div class="card-header bg-transparent py-3 px-4 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <span class="avatar bg-primary-lt text-primary rounded-circle shadow-sm flex-shrink-0" style="width: 44px; height: 44px;">
                        <i class="ti ti-user-cog fs-1"></i>
                    </span>
                    <div>
                        <h3 class="card-title fw-bold text-body mb-0 fs-3">Pengaturan Profil Dosen</h3>
                        <div class="text-secondary small mt-0.5">Ubah nama lengkap dan perbarui password akun dosen Anda.</div>
                    </div>
                </div>
            </div>

            <form action="/dosen/profil" method="POST" enctype="multipart/form-data" autocomplete="off" id="formProfilDosen">
                <?= csrf_field() ?>

                <div class="card-body p-4">

                    <!-- Profile Overview Card Banner with Photo Upload -->
                    <div class="card border rounded-3 p-3 p-md-4 mb-4 bg-body-tertiary shadow-none">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="position-relative flex-shrink-0" id="avatarPreviewContainer">
                                    <?= render_avatar($user, 72, 'border border-2 border-primary-subtle shadow-sm') ?>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                        <h3 class="fw-bold text-body mb-0 fs-3"><?= e($user['nama']) ?></h3>
                                        <span class="badge bg-warning-lt text-warning border border-warning-subtle fw-bold px-2 py-0.5">Dosen Pengampu</span>
                                    </div>
                                    <div class="text-secondary small d-flex align-items-center gap-2 flex-wrap">
                                        <span>NIK / Username: <code class="px-1.5 py-0.5 rounded bg-body border text-body font-monospace"><?= e($user['nim']) ?></code></span>
                                        <span>&bull;</span>
                                        <span>Maksimal <strong>2 MB</strong> &bull; Format: JPG, PNG, atau WebP</span>
                                    </div>
                                    <div id="fileStatusContainer" style="display: none;" class="mt-2"></div>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <label for="foto" class="btn btn-primary px-3 py-2 shadow-sm d-inline-flex align-items-center gap-1.5 mb-0" id="btnUploadLabel" style="cursor: pointer;">
                                    <i class="ti ti-camera fs-2"></i>
                                    <span id="btnUploadText"><?= !empty($user['foto']) ? 'Ganti Foto' : 'Unggah Foto' ?></span>
                                </label>
                                <input type="file" id="foto" name="foto" class="d-none" accept="image/jpeg,image/png,image/webp">
                                
                                <label class="btn btn-outline-danger px-3 py-2 d-inline-flex align-items-center gap-1.5 mb-0 <?= empty($user['foto']) ? 'd-none' : '' ?>" id="btnHapusFotoLabel" style="cursor: pointer;" title="Hapus Foto Profil">
                                    <input type="checkbox" name="hapus_foto" value="1" id="hapus_foto" class="d-none">
                                    <i class="ti ti-trash fs-2"></i>
                                    <span id="textHapusFoto">Hapus Foto</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Identitas Pengguna -->
                    <div class="mb-3">
                        <label for="nim" class="form-label fw-bold small text-uppercase text-secondary">
                            NIK / Username Login
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="ti ti-id fs-2 text-secondary"></i></span>
                            <input type="text" class="form-control bg-body-tertiary font-monospace" id="nim" value="<?= e($user['nim']) ?>" readonly disabled style="cursor: not-allowed;">
                        </div>
                        <div class="form-text small text-secondary mt-1">NIK digunakan sebagai ID login akun resmi dan tidak dapat diubah.</div>
                    </div>

                    <div class="mb-4">
                        <label for="nama" class="form-label fw-bold small text-uppercase text-secondary">
                            Nama Lengkap &amp; Gelar <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary"><i class="ti ti-user fs-2 text-primary"></i></span>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?= e($user['nama']) ?>" required placeholder="Contoh: Dr. Fajar Ramadhan, M.Kom">
                        </div>
                        <div class="form-text small text-secondary mt-1">Nama ini ditampilkan pada kop lembar tugas perkuliahan, rekap nilai, dan akun mahasiswa.</div>
                    </div>

                    <!-- Bagian Ubah Password Akun -->
                    <div class="card shadow-none border rounded-3 mb-2 overflow-hidden" style="background: var(--tblr-bg-surface-secondary, rgba(0, 0, 0, 0.02));">
                        <div class="card-header bg-transparent py-2.5 px-3 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ti ti-lock-cog text-primary fs-2"></i>
                                <h4 class="card-title fw-bold text-body mb-0 fs-4">Ubah Password Akun</h4>
                            </div>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            <div class="alert alert-info border border-info-subtle small mb-3 rounded-3 d-flex align-items-center gap-2.5 py-2.5 px-3">
                                <i class="ti ti-info-circle text-info fs-2 flex-shrink-0"></i>
                                <div class="lh-sm">
                                    Kosongkan seluruh kolom password di bawah jika Anda hanya ingin memperbarui nama profil.
                                </div>
                            </div>

                            <!-- Password Saat Ini -->
                            <div class="mb-3">
                                <label for="password_lama" class="form-label fw-bold small text-uppercase text-secondary">
                                    Password Saat Ini
                                </label>
                                <div class="position-relative">
                                    <div class="input-group">
                                        <span class="input-group-text bg-body-tertiary"><i class="ti ti-key fs-2 text-secondary"></i></span>
                                        <input type="password" class="form-control" id="password_lama" name="password_lama" 
                                               placeholder="Masukkan password saat ini untuk konfirmasi" autocomplete="current-password" style="padding-right: 2.85rem;">
                                    </div>
                                    <button type="button" class="btn btn-link link-secondary p-0 border-0 position-absolute end-0 top-50 translate-middle-y me-3 text-decoration-none shadow-none d-flex align-items-center justify-content-center" id="btn-toggle-old" title="Tampilkan / Sembunyikan Password" tabindex="-1" style="z-index: 5; background: transparent;">
                                        <i class="ti ti-eye fs-2" id="icon-toggle-old"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="password_baru" class="form-label fw-bold small text-uppercase text-secondary">
                                        Password Baru
                                    </label>
                                    <div class="position-relative">
                                        <div class="input-group">
                                            <span class="input-group-text bg-body-tertiary"><i class="ti ti-lock fs-2 text-secondary"></i></span>
                                            <input type="password" class="form-control" id="password_baru" name="password_baru" 
                                                   placeholder="Minimal 6 karakter" autocomplete="new-password" minlength="6" style="padding-right: 2.85rem;">
                                        </div>
                                        <button type="button" class="btn btn-link link-secondary p-0 border-0 position-absolute end-0 top-50 translate-middle-y me-3 text-decoration-none shadow-none d-flex align-items-center justify-content-center" id="btn-toggle-new" title="Tampilkan / Sembunyikan Password" tabindex="-1" style="z-index: 5; background: transparent;">
                                            <i class="ti ti-eye fs-2" id="icon-toggle-new"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="konfirmasi_password" class="form-label fw-bold small text-uppercase text-secondary">
                                        Ulangi Password Baru
                                    </label>
                                    <div class="position-relative">
                                        <div class="input-group">
                                            <span class="input-group-text bg-body-tertiary"><i class="ti ti-shield-check fs-2 text-secondary"></i></span>
                                            <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" 
                                                   placeholder="Ulangi password baru" autocomplete="new-password" minlength="6" style="padding-right: 2.85rem;">
                                        </div>
                                        <button type="button" class="btn btn-link link-secondary p-0 border-0 position-absolute end-0 top-50 translate-middle-y me-3 text-decoration-none shadow-none d-flex align-items-center justify-content-center" id="btn-toggle-confirm" title="Tampilkan / Sembunyikan Password" tabindex="-1" style="z-index: 5; background: transparent;">
                                            <i class="ti ti-eye fs-2" id="icon-toggle-confirm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Card Footer Actions -->
                <div class="card-footer bg-transparent border-top py-3 px-4 d-flex justify-content-end align-items-center gap-2">
                    <a href="/dosen" class="btn btn-outline-secondary px-4 py-2 fw-semibold rounded-2 d-inline-flex align-items-center gap-1.5 shadow-none">
                        <i class="ti ti-x"></i>
                        <span>Batal</span>
                    </a>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm rounded-2 text-white d-inline-flex align-items-center gap-1.5">
                        <i class="ti ti-device-floppy fs-2"></i>
                        <span>Simpan Perubahan Profil</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Live photo preview & button text handler
    const fotoInput = document.getElementById('foto');
    const previewContainer = document.getElementById('avatarPreviewContainer');
    const statusContainer = document.getElementById('fileStatusContainer');
    const btnUploadText = document.getElementById('btnUploadText');
    const hapusCheckbox = document.getElementById('hapus_foto');
    const textHapus = document.getElementById('textHapusFoto');
    const labelHapus = document.getElementById('btnHapusFotoLabel');

    const originalAvatarHtml = previewContainer ? previewContainer.innerHTML : '';
    const hasInitialPhoto = <?= !empty($user['foto']) ? 'true' : 'false' ?>;

    if (fotoInput && previewContainer) {
        fotoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file foto maksimal 2 MB.');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewContainer.innerHTML = '<span class="avatar rounded-circle shadow-sm border border-2 border-primary-subtle flex-shrink-0" style="width: 72px; height: 72px; background-image: url(\'' + e.target.result + '\'); background-size: cover; background-position: center;"></span>';
                    previewContainer.style.opacity = '1';
                };
                reader.readAsDataURL(file);

                if (statusContainer) {
                    statusContainer.innerHTML = '<span class="badge bg-success-lt text-success border border-success-subtle px-2.5 py-1 text-truncate d-inline-flex align-items-center gap-1" style="max-width: 360px;"><i class="ti ti-check"></i> Foto baru dipilih: <strong>' + file.name + '</strong></span>';
                    statusContainer.style.display = 'block';
                }

                if (btnUploadText) {
                    btnUploadText.textContent = 'Ganti Foto';
                }

                if (labelHapus) {
                    labelHapus.classList.remove('d-none');
                    labelHapus.classList.replace('btn-danger', 'btn-outline-danger');
                }
                if (hapusCheckbox) {
                    hapusCheckbox.checked = false;
                }
                if (textHapus) {
                    textHapus.textContent = hasInitialPhoto ? 'Hapus Foto' : 'Batalkan Foto';
                }
            }
        });
    }

    // Toggle delete photo or cancel selection
    if (hapusCheckbox && textHapus && labelHapus) {
        labelHapus.addEventListener('click', function(e) {
            // If user previously chose a new file and wants to cancel before saving
            if (fotoInput && fotoInput.files && fotoInput.files.length > 0 && !hasInitialPhoto) {
                e.preventDefault();
                fotoInput.value = '';
                if (previewContainer) {
                    previewContainer.innerHTML = originalAvatarHtml;
                    previewContainer.style.opacity = '1';
                }
                if (statusContainer) {
                    statusContainer.innerHTML = '';
                    statusContainer.style.display = 'none';
                }
                labelHapus.classList.add('d-none');
                if (btnUploadText) {
                    btnUploadText.textContent = 'Unggah Foto';
                }
                return;
            }
        });

        hapusCheckbox.addEventListener('change', function() {
            if (this.checked) {
                labelHapus.classList.replace('btn-outline-danger', 'btn-danger');
                textHapus.textContent = 'Batal Hapus Foto';
                if (btnUploadText) {
                    btnUploadText.textContent = 'Unggah Foto';
                }
                if (statusContainer) {
                    statusContainer.style.display = 'none';
                }
                if (fotoInput) {
                    fotoInput.value = '';
                }
                if (previewContainer) {
                    previewContainer.style.opacity = '0.35';
                }
            } else {
                labelHapus.classList.replace('btn-danger', 'btn-outline-danger');
                textHapus.textContent = 'Hapus Foto';
                if (btnUploadText) {
                    btnUploadText.textContent = hasInitialPhoto ? 'Ganti Foto' : 'Unggah Foto';
                }
                if (previewContainer) {
                    previewContainer.style.opacity = '1';
                    previewContainer.innerHTML = originalAvatarHtml;
                }
            }
        });
    }

    // Password visibility toggle helper
    function setupToggle(btnId, iconId, inputId) {
        const btn   = document.getElementById(btnId);
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (!btn || !input || !icon) return;

        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const isPassword = (input.type === 'password');
            input.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('ti-eye', !isPassword);
            icon.classList.toggle('ti-eye-off', isPassword);
        });
    }

    setupToggle('btn-toggle-old',     'icon-toggle-old',     'password_lama');
    setupToggle('btn-toggle-new',     'icon-toggle-new',     'password_baru');
    setupToggle('btn-toggle-confirm', 'icon-toggle-confirm', 'konfirmasi_password');

    // Client-side confirmation check
    const form = document.getElementById('formProfilDosen');
    if (form) {
        form.addEventListener('submit', function (e) {
            const passBaru = document.getElementById('password_baru').value;
            const passKonf = document.getElementById('konfirmasi_password').value;
            const passLama = document.getElementById('password_lama').value;

            if (passBaru.length > 0 || passKonf.length > 0) {
                if (!passLama) {
                    e.preventDefault();
                    alert('Silakan masukkan password saat ini untuk mengonfirmasi penggantian password.');
                    document.getElementById('password_lama').focus();
                    return;
                }
                if (passBaru.length < 6) {
                    e.preventDefault();
                    alert('Password baru minimal harus 6 karakter.');
                    document.getElementById('password_baru').focus();
                    return;
                }
                if (passBaru !== passKonf) {
                    e.preventDefault();
                    alert('Konfirmasi password baru tidak cocok. Silakan periksa kembali.');
                    document.getElementById('konfirmasi_password').focus();
                    return;
                }
            }
        });
    }
});
</script>

<?php 
$body = ob_get_clean(); 
Flight::render('layout', ['body' => $body, 'title' => $title ?? 'Pengaturan Profil Dosen']);
?>
