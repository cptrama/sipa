<?php ob_start(); ?>

<div class="row mb-3">
    <div class="col-12">
        <a href="/pertemuan" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1.5 shadow-sm">
            <i class="ti ti-arrow-left fs-2"></i>
            <span>Kembali ke Daftar Pertemuan</span>
        </a>
    </div>
</div>

<!-- Meeting Header & Material Card -->
<div class="row mb-3 mb-md-4">
    <div class="col-12">
        <div class="card shadow-sm border">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center border-bottom pb-3 mb-3 gap-2">
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <span class="badge bg-primary text-white px-2.5 py-1">Pertemuan <?= e($pertemuan['nomor']) ?></span>
                            <span class="badge bg-secondary-lt text-secondary px-2.5 py-1">Semester 5</span>
                            <?php if (!empty($pertemuan['mata_kuliah'])): ?>
                                <span class="badge bg-info-lt text-info px-2.5 py-1"><?= e($pertemuan['mata_kuliah']) ?></span>
                            <?php endif; ?>
                        </div>
                        <h2 class="fw-bold text-dark mb-0 fs-2"><?= e($pertemuan['topik']) ?></h2>
                    </div>
                    <div class="text-secondary small d-inline-flex align-items-center gap-1.5">
                        <i class="ti ti-calendar text-primary fs-2"></i>
                        <span>Tanggal: <strong class="text-dark"><?= !empty($pertemuan['tanggal']) ? format_date(strtotime($pertemuan['tanggal'])) : '-' ?></strong></span>
                    </div>
                </div>

                <!-- Material Download Box -->
                <?php $materiFiles = get_materi_files($pertemuan['file_materi'] ?? null); ?>
                <div class="p-3 rounded-3 bg-body-tertiary border">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-md bg-primary-lt text-primary rounded-circle shadow-sm me-3 flex-shrink-0" style="width: 44px; height: 44px;">
                            <i class="ti ti-file-text fs-2"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark fs-3">Materi Perkuliahan</div>
                            <div class="text-secondary small">
                                <?= !empty($materiFiles) ? count($materiFiles) . ' Berkas Lampiran Materi' : 'Tidak ada lampiran materi untuk pertemuan ini.' ?>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($materiFiles)): ?>
                        <div class="d-flex flex-column gap-3 mt-3 pt-3 border-top">
                            <?php foreach ($materiFiles as $mf): 
                                $ext = strtolower(pathinfo($mf, PATHINFO_EXTENSION));
                                $iconClass = 'ti-file-text text-primary';
                                $extColor = 'primary';
                                if ($ext === 'pdf') {
                                    $iconClass = 'ti-file-type-pdf text-danger';
                                    $extColor = 'danger';
                                } elseif (in_array($ext, ['ppt', 'pptx'])) {
                                    $iconClass = 'ti-file-type-ppt text-warning';
                                    $extColor = 'warning';
                                } elseif (in_array($ext, ['doc', 'docx'])) {
                                    $iconClass = 'ti-file-type-doc text-primary';
                                    $extColor = 'primary';
                                }
                            ?>
                                <div class="materi-file-item d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                                    <div class="d-flex align-items-center gap-3 text-truncate me-sm-2" style="min-width: 0;">
                                        <div class="avatar avatar-md rounded-2 bg-<?= $extColor ?>-lt text-<?= $extColor ?> flex-shrink-0" style="width: 40px; height: 40px;">
                                            <i class="ti <?= $iconClass ?>" style="font-size: 1.45rem;"></i>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center text-truncate" style="min-width: 0;">
                                            <div class="fw-bold text-dark text-truncate small mb-1" title="<?= e($mf) ?>" style="font-size: 0.88rem; line-height: 1.35;">
                                                <?= e($mf) ?>
                                            </div>
                                            <div class="text-secondary small" style="font-size: 0.78rem; line-height: 1.25;">
                                                Berkas Materi Perkuliahan &bull; Format <strong class="text-uppercase text-secondary"><?= e($ext) ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="/materi/<?= e($pertemuan['id']) ?>/download?file=<?= urlencode($mf) ?>"
                                        class="btn btn-materi-download btn-sm flex-shrink-0 d-inline-flex align-items-center gap-1.5 shadow-none ms-sm-3">
                                        <i class="ti ti-download fs-2"></i>
                                        <span>Unduh</span>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assignments in this meeting -->
<div class="row">
    <div class="col-12">
        <h3 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
            <i class="ti ti-checklist text-primary fs-1"></i>
            <span>Tugas Pertemuan Ini</span>
        </h3>

        <?php if (empty($tugasList)): ?>
            <div class="card shadow-sm border py-5 text-center text-secondary px-3">
                <i class="ti ti-clipboard-off fs-1 text-secondary opacity-50 mb-2 d-block"></i>
                <h4 class="fw-bold mb-1">Tidak ada tugas pada pertemuan ini.</h4>
                <p class="small text-secondary mb-0">Tugas akan ditampilkan di sini jika dosen telah menambahkannya.</p>
            </div>
        <?php else: ?>
            <?php foreach ($tugasList as $tugas):
                $isGraded = ($tugas['nilai'] !== null && $tugas['nilai'] !== '');
                $isSubmitted = !empty($tugas['pengumpulan_id']);
                ?>
                <div class="card shadow-sm border mb-4">
                    <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center py-2.5 px-3 px-md-4 gap-2">
                        <h4 class="card-title fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="ti ti-file-description text-primary fs-2"></i>
                            <span><?= e($tugas['judul']) ?></span>
                        </h4>
                        <div>
                            <?= get_task_status_badge($tugas['status']) ?>
                        </div>
                    </div>

                    <div class="card-body p-3 p-md-4">
                        <!-- Instruksi Tugas -->
                        <div class="mb-3">
                            <div class="fw-bold text-secondary small text-uppercase mb-2 d-flex align-items-center gap-1.5" style="letter-spacing: 0.03em;">
                                <i class="ti ti-notes text-primary fs-2"></i>
                                <span>Instruksi Tugas:</span>
                            </div>
                            <div class="rounded-3 bg-body-tertiary border text-dark" style="padding: 0.85rem 1.15rem; border-left: 3.5px solid #206bc4 !important; font-size: 0.93rem; line-height: 1.6;"><?= nl2br(e(trim($tugas['deskripsi']))) ?></div>
                        </div>

                        <!-- Date & Countdown Information Cards (3 Equal Balanced Columns) -->
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-3 bg-body-tertiary border h-100 d-flex flex-column align-items-center justify-content-center text-center">
                                    <div class="text-secondary small d-flex align-items-center justify-content-center mb-2" style="gap: 8px;">
                                        <i class="ti ti-calendar-event text-primary fs-2"></i>
                                        <span class="fw-semibold" style="position: relative; top: 1.5px;">Dibuka Pada</span>
                                    </div>
                                    <div class="fw-bold text-dark fs-3">
                                        <?= format_datetime((int)$tugas['dibuka_pada']) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-3 bg-body-tertiary border h-100 d-flex flex-column align-items-center justify-content-center text-center">
                                    <div class="text-secondary small d-flex align-items-center justify-content-center mb-2" style="gap: 8px;">
                                        <i class="ti ti-calendar-due text-danger fs-2"></i>
                                        <span class="fw-semibold" style="position: relative; top: 1.5px;">Batas Pengumpulan</span>
                                    </div>
                                    <div class="fw-bold text-danger fs-3">
                                        <?= format_datetime((int)$tugas['ditutup_pada']) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-3 bg-body-tertiary border h-100 d-flex flex-column align-items-center justify-content-center text-center">
                                    <div class="text-secondary small d-flex align-items-center justify-content-center mb-2" style="gap: 8px;">
                                        <i class="ti ti-clock text-warning fs-2"></i>
                                        <span class="fw-semibold" style="position: relative; top: 1.5px;">Sisa Waktu</span>
                                    </div>
                                    <div class="countdown-timer" data-countdown="<?= (int)$tugas['ditutup_pada'] ?>">
                                        <?= $tugas['status'] === 'terbuka' ? '<span class="badge bg-primary text-white px-2.5 py-1.5"><i class="ti ti-clock me-1"></i>Menghitung...</span>' : '-' ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Unified Submission Status & Grade Card -->
                        <?php if ($isSubmitted): 
                            $submittedExt = strtolower(pathinfo($tugas['file_asli'] ?? '', PATHINFO_EXTENSION));
                            $subIcon = match ($submittedExt) {
                                'pdf' => 'ti-file-type-pdf text-danger',
                                'doc', 'docx' => 'ti-file-type-docx text-primary',
                                'ppt', 'pptx' => 'ti-file-type-ppt text-warning',
                                'xls', 'xlsx' => 'ti-file-type-xls text-success',
                                'zip', 'rar', '7z' => 'ti-file-type-zip text-secondary',
                                default => 'ti-file-text text-primary'
                            };
                            $subBg = match ($submittedExt) {
                                'pdf' => 'bg-danger-lt',
                                'doc', 'docx' => 'bg-primary-lt',
                                'ppt', 'pptx' => 'bg-warning-lt',
                                'xls', 'xlsx' => 'bg-success-lt',
                                'zip', 'rar', '7z' => 'bg-secondary-lt',
                                default => 'bg-primary-lt'
                            };
                        ?>
                            <div class="card shadow-sm border border-success border-opacity-35 rounded-3 mb-4 overflow-hidden">
                                <!-- Top Status Header Strip -->
                                <div class="px-3 px-md-4 py-2.5 bg-success-lt border-bottom border-success border-opacity-20 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="badge bg-success text-white px-2.5 py-1 d-inline-flex align-items-center">
                                            <i class="ti ti-circle-check me-1"></i>Tugas Sudah Dikumpulkan
                                        </span>
                                        <?php if ($isGraded): ?>
                                            <span class="badge bg-primary text-white fw-bold px-2.5 py-1 d-inline-flex align-items-center">
                                                <i class="ti ti-award me-1"></i>Nilai: <?= number_format((float)$tugas['nilai'], 1) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-lt text-secondary px-2.5 py-1 d-inline-flex align-items-center">
                                                <i class="ti ti-clock me-1"></i>Menunggu Penilaian Dosen
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ($isGraded): ?>
                                        <span class="badge bg-secondary-lt text-secondary px-2.5 py-1 d-inline-flex align-items-center gap-1" style="font-size: 0.78rem;">
                                            <i class="ti ti-lock text-warning"></i>
                                            <span>Terkunci (Read-Only)</span>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Card Body: File Info & Download Button -->
                                <div class="card-body p-3 p-md-4">
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                        <div class="d-flex align-items-center gap-3 text-truncate" style="min-width: 0;">
                                            <span class="avatar avatar-md rounded-2 <?= $subBg ?> flex-shrink-0" style="width: 46px; height: 46px; font-size: 1.5rem;">
                                                <i class="ti <?= $subIcon ?>"></i>
                                            </span>
                                            <div class="text-truncate">
                                                <div class="fw-bold text-dark text-truncate small mb-1" style="font-size: 0.95rem;">
                                                    <?= e($tugas['file_asli']) ?>
                                                </div>
                                                <div class="text-secondary small d-flex flex-wrap align-items-center gap-2" style="font-size: 0.78rem;">
                                                    <span class="d-inline-flex align-items-center gap-1 text-success">
                                                        <i class="ti ti-calendar-check"></i>
                                                        Dikumpulkan: <?= format_datetime((int)$tugas['waktu_upload']) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Download Button -->
                                        <div class="flex-shrink-0">
                                            <a href="/pengumpulan/<?= e($tugas['pengumpulan_id']) ?>/download"
                                                class="btn btn-materi-download btn-sm px-3 py-2 d-inline-flex align-items-center gap-1.5 shadow-none">
                                                <i class="ti ti-download fs-2"></i>
                                                <span>Unduh Berkas Anda</span>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Feedback Dosen Section -->
                                    <?php if ($isGraded && !empty($tugas['feedback'])): ?>
                                        <div class="mt-3 p-3 rounded-3 bg-body-tertiary border text-body" style="border-left: 3.5px solid #206bc4 !important;">
                                            <div class="d-flex align-items-center gap-2 text-primary fw-bold small mb-1.5">
                                                <i class="ti ti-message-dots fs-2"></i>
                                                <span style="position: relative; top: 0.5px;">Feedback Dosen</span>
                                            </div>
                                            <div class="text-secondary" style="font-size: 0.9rem; line-height: 1.6;">
                                                <?= nl2br(e(trim($tugas['feedback']))) ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Form Upload / Re-Upload Section -->
                        <?php if ($isGraded): ?>
                            <!-- Graded: locked from upload -->
                        <?php elseif ($tugas['status'] === 'terbuka'): ?>
                            <!-- Upload Form (Active when task status is terbuka and not graded) -->
                            <div class="p-3 p-md-4 rounded-3 border bg-body shadow-sm">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h5 class="fw-bold text-primary mb-0 d-flex align-items-center gap-2">
                                        <i class="ti <?= $isSubmitted ? 'ti-arrow-back-up' : 'ti-cloud-upload' ?> fs-2"></i>
                                        <span><?= $isSubmitted ? 'Unggah Ulang Berkas Tugas' : 'Form Pengumpulan Tugas' ?></span>
                                    </h5>
                                    <?php if ($isSubmitted): ?>
                                        <span class="badge bg-warning-lt text-warning small px-2 py-0.5">
                                            <i class="ti ti-alert-triangle me-1"></i>Menimpa berkas lama
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php if ($isSubmitted): ?>
                                    <div class="small text-secondary mb-3">
                                        Anda sudah mengumpulkan tugas ini sebelumnya. Jika Anda mengunggah berkas baru, berkas lama Anda di server akan digantikan secara otomatis.
                                    </div>
                                <?php endif; ?>

                                <form action="/tugas/<?= e($tugas['id']) ?>/upload" method="POST" enctype="multipart/form-data">
                                    <?= csrf_field() ?>

                                    <div class="mb-3">
                                        <label for="file_tugas_<?= e($tugas['id']) ?>"
                                            class="form-label fw-bold small text-uppercase text-secondary">
                                            Pilih Berkas Tugas
                                        </label>
                                        <input class="form-control" type="file" id="file_tugas_<?= e($tugas['id']) ?>"
                                            name="file_tugas" required
                                            accept=".pdf,.doc,.docx,.ppt,.pptx">
                                        <div class="form-text small text-secondary mt-1">
                                            Maksimal ukuran: <strong>20 MB</strong>. Ekstensi yang diizinkan:
                                            <span class="badge bg-secondary-lt text-secondary">pdf</span>
                                            <span class="badge bg-secondary-lt text-secondary">doc</span>
                                            <span class="badge bg-secondary-lt text-secondary">docx</span>
                                            <span class="badge bg-secondary-lt text-secondary">ppt</span>
                                            <span class="badge bg-secondary-lt text-secondary">pptx</span>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-sm px-3 fw-bold shadow-sm btn-mobile-full d-inline-flex align-items-center gap-1.5"
                                        onclick="return confirm('Apakah Anda yakin ingin mengunggah file ini?')">
                                        <i class="ti <?= $isSubmitted ? 'ti-arrow-back-up' : 'ti-upload' ?> fs-2"></i>
                                        <span><?= $isSubmitted ? 'Unggah Ulang Tugas' : 'Kirim Tugas Sekarang' ?></span>
                                    </button>
                                </form>
                            </div>
                        <?php elseif ($tugas['status'] === 'belum_dibuka'): ?>
                            <div class="p-3 rounded-3 bg-body-tertiary border text-secondary small d-flex align-items-center gap-2">
                                <i class="ti ti-clock-history fs-2 text-primary flex-shrink-0"></i>
                                <div>
                                    Pengumpulan untuk tugas ini <strong>belum dibuka</strong>. Dibuka pada
                                    <strong><?= format_datetime((int)$tugas['dibuka_pada']) ?></strong>.
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="p-3 rounded-3 bg-danger-lt border border-danger border-opacity-25 text-danger small d-flex align-items-center gap-2">
                                <i class="ti ti-lock fs-2 flex-shrink-0"></i>
                                <div>
                                    Masa pengumpulan tugas ini <strong>telah ditutup</strong> pada
                                    <strong><?= format_datetime((int)$tugas['ditutup_pada']) ?></strong>. Tidak ada penerimaan keterlambatan.
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php
$body = ob_get_clean();
Flight::render('layout', ['body' => $body, 'title' => $title ?? 'Detail Pertemuan']);
?>
