<?php ob_start(); ?>

<!-- Page Title & Header -->
<div class="row mb-3 align-items-center">
    <div class="col-12 col-md-7">
        <h3 class="fw-bold mb-0 d-flex align-items-center gap-2">
            <i class="ti ti-gauge text-primary"></i>
            <span>Panel Dosen</span>
        </h3>
        <p class="text-secondary small mb-0 mt-1">Program Studi <?= e(config('prodi')) ?> &bull; Semester <?= e(config('semester')) ?> <?= e(config('tahun_ajar')) ?></p>
    </div>
    <div class="col-12 col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 flex-wrap justify-content-md-end">
        <a href="/dosen/tugas/tambah" class="btn btn-primary btn-header-action flex-fill flex-md-grow-0 shadow-sm">
            <i class="ti ti-plus"></i>
            <span>Buat Tugas</span>
        </a>
        <a href="/dosen/pertemuan/tambah" class="btn btn-header-action btn-action-primary-soft flex-fill flex-md-grow-0">
            <i class="ti ti-folder-plus"></i>
            <span>Buat Pertemuan</span>
        </a>
        <a href="/dosen/profil" class="btn btn-header-action btn-action-neutral-soft flex-fill flex-md-grow-0" title="Ubah Nama & Password">
            <i class="ti ti-user-cog"></i>
            <span>Edit Profil</span>
        </a>
    </div>
</div>

<!-- Stat Cards - Tabler Metric Style -->
<div class="row g-3 mb-3">
    <div class="col-12 col-sm-4">
        <div class="stat-pill-card d-flex flex-row justify-content-between align-items-center">
            <div>
                <div class="text-muted">Pertemuan</div>
                <h4><?= $totalPertemuan ?></h4>
            </div>
            <div class="rounded-circle text-primary" style="background: rgba(32, 107, 196, 0.12);">
                <i class="ti ti-calendar-event fs-2"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="stat-pill-card d-flex flex-row justify-content-between align-items-center">
            <div>
                <div class="text-muted">Total Tugas</div>
                <h4><?= $totalTugas ?></h4>
            </div>
            <div class="rounded-circle text-success" style="background: rgba(47, 179, 68, 0.12);">
                <i class="ti ti-clipboard-list fs-2"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="stat-pill-card d-flex flex-row justify-content-between align-items-center">
            <div>
                <div class="text-muted">Mahasiswa</div>
                <h4><?= $totalMahasiswa ?></h4>
            </div>
            <div class="rounded-circle text-warning" style="background: rgba(245, 159, 0, 0.12);">
                <i class="ti ti-users fs-2"></i>
            </div>
        </div>
    </div>
</div>

<!-- Task Overview & Submission Tracking -->
<div class="row">
    <div class="col-12">
        <div class="glass-card">
            <div class="card-header d-flex justify-content-between align-items-center py-2 px-3 px-md-4">
                <h4 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                    <i class="ti ti-clipboard-check text-primary"></i>
                    <span>Pemantauan & Pengumpulan Tugas</span>
                </h4>
                <a href="/dosen/tugas" class="btn btn-card-header-action shadow-none">
                    <i class="ti ti-list"></i>
                    <span>Semua Tugas</span>
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($tasks)): ?>
                    <div class="text-center py-5 text-secondary px-3">
                        <i class="ti ti-folder-off display-4 mb-2 d-block opacity-50"></i>
                        <h4 class="fw-bold">Belum ada tugas yang dibuat.</h4>
                        <p class="small mb-3">Mulai buat pertemuan dan tugas baru untuk mahasiswa.</p>
                        <a href="/dosen/tugas/tambah" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus me-1"></i> Buat Tugas Pertama
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Mobile Stacked Cards (d-block d-md-none) -->
                    <div class="d-block d-md-none">
                        <div class="mobile-card-list">
                            <?php foreach ($tasks as $task): ?>
                                <div class="mobile-card-item">
                                    <div class="item-header">
                                        <span class="badge bg-primary px-2.5 py-1 fw-bold"><?= e($task['pertemuan_nomor']) ?></span>
                                        <?= get_task_status_badge($task['status']) ?>
                                    </div>

                                    <div class="item-title"><?= e($task['judul']) ?></div>
                                    <div class="small text-secondary mb-2"><?= e($task['pertemuan_topik']) ?></div>

                                    <div class="item-meta">
                                        <div class="meta-row">
                                            <span class="text-secondary"><i class="ti ti-calendar-time me-1 text-danger"></i>Batas Waktu:</span>
                                            <span class="small text-danger fw-bold"><?= format_datetime((int)$task['ditutup_pada']) ?></span>
                                        </div>
                                        <div class="d-flex gap-2 mt-2">
                                            <span class="badge bg-success flex-fill py-1">Kumpul: <?= $task['total_kumpul'] ?> / <?= $task['total_mhs'] ?></span>
                                            <span class="badge <?= $task['total_belum'] > 0 ? 'bg-danger' : 'bg-secondary' ?> flex-fill py-1">Belum: <?= $task['total_belum'] ?></span>
                                        </div>
                                    </div>

                                    <div class="item-action flex-column gap-1.5 mt-2">
                                        <a href="/dosen/tugas/<?= e($task['id']) ?>/pengumpulan" class="btn btn-table-action-primary w-100 text-center py-1.5">
                                            <i class="ti ti-users"></i>
                                            <span>Periksa (<?= $task['total_kumpul'] ?>)</span>
                                        </a>
                                        <?php if ($task['total_kumpul'] > 0): ?>
                                            <a href="/dosen/tugas/<?= e($task['id']) ?>/download-zip" class="btn btn-table-action-success w-100 text-center py-1.5" title="Unduh ZIP">
                                                <i class="ti ti-file-zip"></i>
                                                <span>Unduh ZIP</span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Desktop Table View (d-none d-md-block) -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col" class="ps-3 text-center" style="width: 70px;">Pertemuan</th>
                                        <th scope="col">Judul Tugas</th>
                                        <th scope="col" class="text-center" style="width: 120px;">Status</th>
                                        <th scope="col" style="width: 180px;">Batas Waktu</th>
                                        <th scope="col" class="text-center" style="width: 90px;">Mhs</th>
                                        <th scope="col" class="text-center" style="width: 90px;">Kumpul</th>
                                        <th scope="col" class="text-center" style="width: 90px;">Belum</th>
                                        <th scope="col" class="text-center pe-3" style="width: 160px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tasks as $task): ?>
                                        <tr>
                                            <td class="ps-3 text-center">
                                                <span class="badge bg-primary fs-3 px-2.5 py-1 fw-bold"><?= e($task['pertemuan_nomor']) ?></span>
                                            </td>
                                            <td>
                                                <div class="fw-bold"><?= e($task['judul']) ?></div>
                                                <div class="text-secondary small mt-0.5"><?= e($task['pertemuan_topik']) ?></div>
                                            </td>
                                            <td class="text-center">
                                                <?= get_task_status_badge($task['status']) ?>
                                            </td>
                                            <td>
                                                <div class="small fw-semibold text-danger"><?= format_datetime((int)$task['ditutup_pada']) ?></div>
                                                <div class="text-secondary small" style="font-size: 0.78rem;">Buka: <?= format_datetime((int)$task['dibuka_pada']) ?></div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary"><?= $task['total_mhs'] ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success"><?= $task['total_kumpul'] ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge <?= $task['total_belum'] > 0 ? 'bg-danger' : 'bg-secondary' ?>">
                                                    <?= $task['total_belum'] ?>
                                                </span>
                                            </td>
                                            <td class="text-center pe-3 py-2">
                                                <div class="d-inline-flex flex-column gap-1 align-items-center" style="width: 125px;">
                                                    <a href="/dosen/tugas/<?= e($task['id']) ?>/pengumpulan" class="btn btn-table-action-primary w-100 py-1" title="Lihat daftar pengumpulan & nilai">
                                                        <i class="ti ti-users"></i>
                                                        <span>Periksa (<?= $task['total_kumpul'] ?>)</span>
                                                    </a>
                                                    <?php if ($task['total_kumpul'] > 0): ?>
                                                        <a href="/dosen/tugas/<?= e($task['id']) ?>/download-zip" class="btn btn-table-action-success w-100 py-1" title="Unduh semua berkas sebagai ZIP">
                                                            <i class="ti ti-file-zip"></i>
                                                            <span>Unduh ZIP</span>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php 
$body = ob_get_clean(); 
Flight::render('layout', ['body' => $body, 'title' => $title ?? 'Panel Dosen']);
?>
