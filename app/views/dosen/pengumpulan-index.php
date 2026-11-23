<?php ob_start(); ?>

<!-- Back Navigation Button -->
<div class="row mb-3">
    <div class="col-12">
        <a href="/dosen/tugas" class="btn btn-card-header-action shadow-none">
            <i class="ti ti-arrow-left"></i>
            <span>Kembali ke Kelola Tugas</span>
        </a>
    </div>
</div>

<!-- Task Header & Summary Card -->
<div class="row mb-3 mb-md-4">
    <div class="col-12">
        <div class="card shadow-sm border rounded-3 p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center border-bottom pb-3 mb-3 gap-3">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-1.5 mb-2">
                        <span class="badge bg-primary-lt text-primary fw-bold px-2 py-0.5">Kelas <?= e($tugas['kelas']) ?></span>
                        <span class="badge bg-secondary-lt text-secondary fw-semibold px-2 py-0.5">Pertemuan <?= e($tugas['pertemuan_nomor']) ?></span>
                        <?= get_task_status_badge($tugas['status']) ?>
                    </div>
                    <h3 class="fw-bold text-dark mb-1 fs-2"><?= e($tugas['judul']) ?></h3>
                    <div class="text-secondary small d-flex align-items-center gap-1">
                        <i class="ti ti-heading text-primary"></i>
                        <span>Topik: <strong><?= e($tugas['pertemuan_topik']) ?></strong></span>
                    </div>
                </div>
                <div class="text-md-end small d-flex flex-column gap-1.5">
                    <div class="d-flex align-items-center justify-content-md-end gap-1.5 text-danger fw-semibold">
                        <i class="ti ti-calendar-time fs-2"></i>
                        <span>Batas Pengumpulan: <strong><?= format_date((int)$tugas['ditutup_pada']) ?>, <?= format_time((int)$tugas['ditutup_pada']) ?></strong></span>
                    </div>
                    <div class="d-flex align-items-center justify-content-md-end gap-1.5 text-secondary">
                        <i class="ti ti-calendar fs-2"></i>
                        <span>Dibuka: <?= format_date((int)$tugas['dibuka_pada']) ?>, <?= format_time((int)$tugas['dibuka_pada']) ?></span>
                    </div>
                </div>
            </div>

            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-8">
                    <div class="d-flex gap-2 flex-wrap">
                        <div class="p-2 px-3 rounded-pill bg-body-tertiary border small d-inline-flex align-items-center gap-1.5">
                            <i class="ti ti-users text-secondary fs-2"></i>
                            <span class="text-secondary">Total Mhs:</span>
                            <strong class="text-dark ms-0.5"><?= $totalMhs ?></strong>
                        </div>
                        <div class="p-2 px-3 rounded-pill bg-success-lt border border-success-subtle small d-inline-flex align-items-center gap-1.5">
                            <i class="ti ti-circle-check text-success fs-2"></i>
                            <span class="text-success fw-semibold">Sudah Kumpul:</span>
                            <strong class="text-success ms-0.5"><?= $totalKumpul ?></strong>
                        </div>
                        <div class="p-2 px-3 rounded-pill bg-danger-lt border border-danger-subtle small d-inline-flex align-items-center gap-1.5">
                            <i class="ti ti-circle-x text-danger fs-2"></i>
                            <span class="text-danger fw-semibold">Belum Kumpul:</span>
                            <strong class="text-danger ms-0.5"><?= $totalBelum ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
                    <?php if ($totalKumpul > 0): ?>
                        <a href="/dosen/tugas/<?= e($tugas['id']) ?>/download-zip" class="btn btn-table-action-success py-1.5 px-3 fw-semibold shadow-sm btn-mobile-full" title="Unduh semua file submission mahasiswa dalam satu arsip ZIP">
                            <i class="ti ti-file-zip fs-2"></i>
                            <span>Unduh Semua File (ZIP)</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Submissions List Card -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border rounded-3 overflow-hidden">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-2.5 px-3 px-md-4 border-bottom">
                <h4 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2 fs-3">
                    <i class="ti ti-users text-primary"></i>
                    <span>Daftar Pengumpulan Mahasiswa (Semester 5)</span>
                </h4>
                <span class="badge bg-primary text-white fw-bold px-2.5 py-1">
                    <?= $totalKumpul ?> / <?= $totalMhs ?> Kumpul
                </span>
            </div>

            <div class="card-body p-0">
                <!-- Mobile Stacked Card View (d-block d-md-none) -->
                <div class="d-block d-md-none p-3">
                    <div class="mobile-card-list">
                        <?php foreach ($submissions as $sub): 
                            $isGraded = ($sub['nilai'] !== null && $sub['nilai'] !== '');
                        ?>
                            <div class="mobile-card-item">
                                <div class="item-header">
                                    <code class="text-primary fw-bold font-monospace"><?= e($sub['nim']) ?></code>
                                    <?php if (!empty($sub['pengumpulan_id'])): ?>
                                        <?php if ($isGraded): ?>
                                            <span class="badge bg-success-lt text-success fw-bold px-2 py-0.5">
                                                Nilai: <?= number_format((float)$sub['nilai'], 1) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-primary-lt text-primary fw-semibold px-2 py-0.5">Sudah Kumpul</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-danger-lt text-danger fw-semibold px-2 py-0.5">Belum Kumpul</span>
                                    <?php endif; ?>
                                </div>

                                <div class="item-title fw-bold text-dark fs-4"><?= e($sub['nama']) ?></div>

                                <?php if (!empty($sub['pengumpulan_id'])): ?>
                                    <div class="item-meta">
                                        <div class="meta-row">
                                            <span class="text-secondary"><i class="ti ti-clock me-1 text-primary"></i>Waktu:</span>
                                            <span class="small text-dark fw-semibold"><?= format_date((int)$sub['waktu_upload']) ?>, <?= format_time((int)$sub['waktu_upload']) ?></span>
                                        </div>
                                        <div class="meta-row">
                                            <span class="text-secondary"><i class="ti ti-file-text me-1 text-primary"></i>File:</span>
                                            <a href="/dosen/pengumpulan/<?= e($sub['pengumpulan_id']) ?>/download" class="btn btn-table-action-secondary py-1 px-2.5 text-truncate" style="font-size: 0.8rem; max-width: 220px;" title="<?= e($sub['file_asli']) ?>">
                                                <i class="ti ti-download me-1"></i><?= e($sub['file_asli']) ?>
                                            </a>
                                        </div>
                                    </div>

                                    <?php if (!empty($sub['feedback'])): ?>
                                        <div class="feedback-callout mt-2 p-2 rounded-2 bg-body-tertiary border">
                                            <div class="small fw-bold text-primary mb-1 d-flex align-items-center gap-1">
                                                <i class="ti ti-message-dots"></i>
                                                <span>Feedback:</span>
                                            </div>
                                            <div class="fst-italic small text-dark"><?= e($sub['feedback']) ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="item-action mt-2 gap-1.5">
                                        <button type="button" class="btn btn-table-action-primary flex-fill text-center py-1.5" data-bs-toggle="modal" data-bs-target="#gradeModal<?= e($sub['pengumpulan_id']) ?>" title="<?= $isGraded ? 'Edit Nilai & Feedback' : 'Beri Nilai & Feedback' ?>">
                                            <i class="ti ti-edit me-1"></i>
                                            <span><?= $isGraded ? 'Edit Nilai' : 'Beri Nilai' ?></span>
                                        </button>

                                        <?php if ($isGraded): ?>
                                            <button type="button" class="btn btn-table-action-warning py-1.5 px-3 text-center" data-bs-toggle="modal" data-bs-target="#reopenModal<?= e($sub['pengumpulan_id']) ?>" title="Buka kembali pengumpulan tugas">
                                                <i class="ti ti-lock-open me-1"></i>
                                                <span>Buka Kunci</span>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-secondary small fst-italic mt-1">Mahasiswa belum mengunggah berkas tugas.</div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Desktop Table View (d-none d-md-block) -->
                <div class="d-none d-md-block">
                    <style>
                        .table.table-pengumpulan th,
                        .table.table-pengumpulan td {
                            padding-left: 0.6rem !important;
                            padding-right: 0.6rem !important;
                        }
                    </style>
                    <div class="table-responsive" style="overflow-x: hidden;">
                        <table class="table table-pengumpulan table-hover align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center" style="width: 120px;">NIM</th>
                                    <th scope="col" style="width: 230px;">Nama Mahasiswa</th>
                                    <th scope="col" class="text-center" style="width: 90px;">Status</th>
                                    <th scope="col" class="text-center" style="width: 115px;">Waktu Upload</th>
                                    <th scope="col" class="text-center" style="width: 135px;">File Berkas</th>
                                    <th scope="col" class="text-center" style="width: 75px;">Nilai</th>
                                    <th scope="col" style="width: 155px;">Feedback</th>
                                    <th scope="col" class="text-center pe-3" style="width: 95px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($submissions as $sub): 
                                    $isGraded = ($sub['nilai'] !== null && $sub['nilai'] !== '');
                                ?>
                                    <tr>
                                        <td class="text-center">
                                            <code class="text-primary fw-bold font-monospace" style="font-size: 0.8rem;"><?= e($sub['nim']) ?></code>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?= render_avatar($sub, 26) ?>
                                                <div class="fw-bold text-dark text-truncate" style="max-width: 190px;" title="<?= e($sub['nama']) ?>"><?= e($sub['nama']) ?></div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!empty($sub['pengumpulan_id'])): ?>
                                                <span class="badge bg-success-lt text-success fw-bold d-inline-flex align-items-center gap-1.5 px-2.5 py-1">
                                                    <i class="ti ti-circle-check"></i>
                                                    <span>Sudah</span>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-lt text-danger fw-bold d-inline-flex align-items-center gap-1.5 px-2.5 py-1">
                                                    <i class="ti ti-circle-x"></i>
                                                    <span>Belum</span>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!empty($sub['waktu_upload'])): ?>
                                                <div class="small fw-semibold text-dark text-nowrap" style="font-size: 0.78rem;"><?= format_date((int)$sub['waktu_upload']) ?></div>
                                                <div class="text-secondary text-nowrap" style="font-size: 0.72rem;"><?= format_time((int)$sub['waktu_upload']) ?></div>
                                            <?php else: ?>
                                                <span class="text-secondary small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!empty($sub['pengumpulan_id'])): ?>
                                                <a href="/dosen/pengumpulan/<?= e($sub['pengumpulan_id']) ?>/download" 
                                                   class="btn btn-table-action-secondary py-1 px-2 d-inline-flex align-items-center gap-1 text-truncate" 
                                                   style="max-width: 125px; font-size: 0.78rem;" 
                                                   title="Unduh <?= e($sub['file_asli']) ?>">
                                                    <i class="ti ti-file-download text-primary flex-shrink-0"></i>
                                                    <span class="text-truncate"><?= e($sub['file_asli']) ?></span>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-secondary small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($isGraded): ?>
                                                <span class="badge bg-primary text-white fw-bold px-2 py-0.5 fs-4">
                                                    <?= number_format((float)$sub['nilai'], 1) ?>
                                                </span>
                                            <?php elseif (!empty($sub['pengumpulan_id'])): ?>
                                                <span class="badge bg-warning-lt text-warning fw-semibold px-1.5 py-0.5 d-inline-flex flex-column align-items-center text-nowrap" style="font-size: 0.70rem; line-height: 1.15;">
                                                    <span>Belum</span>
                                                    <span>dinilai</span>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-secondary small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($sub['feedback'])): ?>
                                                <div class="d-inline-flex align-items-center gap-1.5 small text-dark text-truncate" style="max-width: 145px;" title="<?= e($sub['feedback']) ?>">
                                                    <i class="ti ti-message-dots text-primary flex-shrink-0"></i>
                                                    <span class="text-truncate"><?= e($sub['feedback']) ?></span>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-secondary small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center pe-3">
                                            <?php if (!empty($sub['pengumpulan_id'])): ?>
                                                <div class="d-inline-flex align-items-center justify-content-center gap-1.5">
                                                    <button type="button" 
                                                            class="btn btn-table-action-primary py-1 px-2" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#gradeModal<?= e($sub['pengumpulan_id']) ?>" 
                                                            title="<?= $isGraded ? 'Edit Nilai & Feedback' : 'Beri Nilai & Feedback' ?>">
                                                        <i class="ti ti-edit"></i>
                                                    </button>

                                                    <?php if ($isGraded): ?>
                                                        <button type="button" 
                                                                class="btn btn-table-action-warning py-1 px-2" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#reopenModal<?= e($sub['pengumpulan_id']) ?>" 
                                                                title="Buka kembali pengumpulan tugas">
                                                            <i class="ti ti-lock-open"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-secondary small">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shared Modals for Grading and Reopen -->
<?php if (!empty($submissions)): ?>
    <?php foreach ($submissions as $sub): 
        if (empty($sub['pengumpulan_id'])) continue;
        $isGraded = ($sub['nilai'] !== null && $sub['nilai'] !== '');
    ?>
        <!-- Grading Modal -->
        <div class="modal fade" id="gradeModal<?= e($sub['pengumpulan_id']) ?>" tabindex="-1" aria-labelledby="gradeModalLabel<?= e($sub['pengumpulan_id']) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered text-start">
                <div class="modal-content shadow-lg border rounded-3 overflow-hidden">
                    <form action="/dosen/pengumpulan/<?= e($sub['pengumpulan_id']) ?>/nilai" method="POST">
                        <?= csrf_field() ?>
                        <div class="modal-header py-3 px-4 border-bottom bg-transparent">
                            <div class="d-flex align-items-center gap-2.5">
                                <span class="avatar bg-warning-lt text-warning rounded-circle shadow-sm flex-shrink-0" style="width: 38px; height: 38px;">
                                    <i class="ti ti-award fs-1"></i>
                                </span>
                                <div>
                                    <h5 class="modal-title fw-bold text-body mb-0 fs-3" id="gradeModalLabel<?= e($sub['pengumpulan_id']) ?>">
                                        <?= $isGraded ? 'Ubah Nilai & Feedback' : 'Beri Nilai & Feedback' ?>
                                    </h5>
                                    <div class="text-secondary small mt-0.5">Evaluasi tugas dan berikan catatan perbaikan untuk mahasiswa.</div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <!-- Student & File Info Card -->
                            <div class="p-3 bg-body-tertiary rounded-3 mb-3 border">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <?= render_avatar($sub, 36) ?>
                                        <div>
                                            <div class="fw-bold text-body fs-3"><?= e($sub['nama']) ?></div>
                                            <code class="text-primary font-monospace small"><?= e($sub['nim']) ?></code>
                                        </div>
                                    </div>
                                    <span class="badge bg-success-lt text-success fw-bold px-2 py-1">
                                        <i class="ti ti-circle-check me-1"></i>Sudah Kumpul
                                    </span>
                                </div>
                                <div class="pt-2 border-top d-flex flex-wrap align-items-center justify-content-between gap-2 small text-secondary">
                                    <div class="d-flex align-items-center gap-1.5 text-truncate" style="max-width: 250px;">
                                        <i class="ti ti-file-text text-primary flex-shrink-0 fs-2"></i>
                                        <a href="/dosen/pengumpulan/<?= e($sub['pengumpulan_id']) ?>/download" class="text-body fw-semibold text-truncate text-decoration-none" title="Unduh <?= e($sub['file_asli']) ?>">
                                            <?= e($sub['file_asli']) ?>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center gap-1 text-secondary">
                                        <i class="ti ti-clock fs-2"></i>
                                        <span><?= format_date((int)$sub['waktu_upload']) ?>, <?= format_time((int)$sub['waktu_upload']) ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="nilai_<?= e($sub['pengumpulan_id']) ?>" class="form-label fw-bold small text-uppercase text-secondary">
                                    Nilai (Skala 0 - 100) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-body-tertiary"><i class="ti ti-award fs-2 text-warning"></i></span>
                                    <input type="number" step="0.1" min="0" max="100" class="form-control" id="nilai_<?= e($sub['pengumpulan_id']) ?>" name="nilai" value="<?= e($sub['nilai'] ?? '') ?>" placeholder="Contoh: 85.5" required>
                                    <span class="input-group-text bg-body-tertiary fw-semibold text-secondary">/ 100</span>
                                </div>
                            </div>

                            <div class="mb-2">
                                <label for="feedback_<?= e($sub['pengumpulan_id']) ?>" class="form-label fw-bold small text-uppercase text-secondary">
                                    Feedback / Catatan untuk Mahasiswa
                                </label>
                                <textarea class="form-control" id="feedback_<?= e($sub['pengumpulan_id']) ?>" name="feedback" rows="3" placeholder="Tuliskan catatan apresiasi, evaluasi, atau perbaikan tugas..."><?= e($sub['feedback'] ?? '') ?></textarea>
                                <div class="form-text small text-secondary mt-1">Catatan ini dapat langsung dibaca oleh mahasiswa di halaman tugas mereka.</div>
                            </div>
                        </div>
                        <div class="modal-footer bg-transparent border-top py-3 px-4 d-flex justify-content-end align-items-center gap-2">
                            <button type="button" class="btn btn-outline-secondary text-body px-3 py-2 fw-semibold rounded-2 d-inline-flex align-items-center gap-1.5 shadow-none" data-bs-dismiss="modal">
                                <i class="ti ti-x"></i>
                                <span>Batal</span>
                            </button>
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm rounded-2 text-white d-inline-flex align-items-center gap-1.5">
                                <i class="ti ti-device-floppy fs-2"></i>
                                <span>Simpan Nilai</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if ($isGraded): ?>
            <!-- Reopen Modal Confirmation -->
            <div class="modal fade" id="reopenModal<?= e($sub['pengumpulan_id']) ?>" tabindex="-1" aria-labelledby="reopenModalLabel<?= e($sub['pengumpulan_id']) ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered text-start">
                    <div class="modal-content shadow-lg border rounded-3 overflow-hidden">
                        <div class="modal-header py-3 px-4 border-bottom bg-transparent">
                            <div class="d-flex align-items-center gap-2.5">
                                <span class="avatar bg-warning-lt text-warning rounded-circle shadow-sm flex-shrink-0" style="width: 38px; height: 38px;">
                                    <i class="ti ti-lock-open fs-1"></i>
                                </span>
                                <div>
                                    <h5 class="modal-title fw-bold text-body mb-0 fs-3" id="reopenModalLabel<?= e($sub['pengumpulan_id']) ?>">
                                        Buka Kembali Pengumpulan Tugas
                                    </h5>
                                    <div class="text-secondary small mt-0.5">Izinkan mahasiswa untuk mengunggah ulang berkas tugas.</div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="p-3 bg-body-tertiary rounded-3 mb-3 border">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <?= render_avatar($sub, 36) ?>
                                    <div>
                                        <div class="fw-bold text-body fs-3"><?= e($sub['nama']) ?></div>
                                        <code class="text-primary font-monospace small"><?= e($sub['nim']) ?></code>
                                    </div>
                                </div>
                                <div class="text-secondary small mt-2 pt-2 border-top d-flex justify-content-between align-items-center">
                                    <span>Nilai Saat Ini:</span>
                                    <span class="badge bg-primary text-white fw-bold px-2.5 py-0.5">
                                        <?= number_format((float)$sub['nilai'], 1) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="alert alert-warning border border-warning-subtle small mb-0 rounded-3 d-flex align-items-start gap-2.5 p-3">
                                <i class="ti ti-alert-triangle text-warning fs-1 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <strong class="d-block mb-1 fs-3 alert-heading">Konfirmasi Pembukaan Akses Tugas</strong>
                                    <div class="alert-desc lh-base">
                                        Membuka kembali tugas akan <span class="fw-bold text-decoration-underline">mereset status nilai saat ini</span> dan mengizinkan mahasiswa untuk mengunggah berkas perbaikan selama batas waktu tugas masih terbuka.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-transparent border-top py-3 px-4 d-flex justify-content-end align-items-center gap-2">
                            <button type="button" class="btn btn-outline-secondary text-body px-3 py-2 fw-semibold rounded-2 d-inline-flex align-items-center gap-1.5 shadow-none" data-bs-dismiss="modal">
                                <i class="ti ti-x"></i>
                                <span>Batal</span>
                            </button>
                            <form action="/dosen/pengumpulan/<?= e($sub['pengumpulan_id']) ?>/buka-kembali" method="POST" class="m-0">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-warning px-4 py-2 fw-bold shadow-sm rounded-2 text-white d-inline-flex align-items-center gap-1.5" style="color: #ffffff !important; background-color: #f76707 !important; border-color: #f76707 !important;">
                                    <i class="ti ti-lock-open fs-2 text-white"></i>
                                    <span class="text-white">Ya, Buka Kembali</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php 
$body = ob_get_clean(); 
Flight::render('layout', ['body' => $body, 'title' => $title ?? 'Daftar Pengumpulan']);
?>
