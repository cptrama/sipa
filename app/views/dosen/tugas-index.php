<?php ob_start(); ?>

<!-- Page Title & Header -->
<div class="row mb-3 align-items-center">
    <div class="col-12 col-md-7">
        <h3 class="fw-bold mb-0 d-flex align-items-center gap-2">
            <i class="ti ti-clipboard-list text-primary"></i>
            <span>Kelola Tugas</span>
        </h3>
        <p class="text-secondary small mb-0 mt-1">Atur instruksi tugas, jendela pengumpulan, dan pantau hasil tugas Semester 5.</p>
    </div>
    <div class="col-12 col-md-5 text-md-end mt-3 mt-md-0">
        <a href="/dosen/tugas/tambah" class="btn btn-primary btn-header-action shadow-sm">
            <i class="ti ti-plus"></i>
            <span>Buat Tugas Baru</span>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="glass-card">
            <div class="card-header d-flex justify-content-between align-items-center py-2.5 px-3 px-md-4">
                <h4 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                    <i class="ti ti-list text-primary"></i>
                    <span>Daftar Tugas Perkuliahan</span>
                </h4>
                <span class="badge bg-secondary"><?= count($tugasList) ?> Tugas</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($tugasList)): ?>
                    <div class="text-center py-5 text-secondary px-3">
                        <i class="ti ti-folder-off display-4 mb-2 d-block opacity-50"></i>
                        <h4 class="fw-bold">Belum ada tugas yang dibuat untuk Semester 5.</h4>
                        <p class="small mb-3">Klik tombol di bawah untuk membuat tugas baru.</p>
                        <a href="/dosen/tugas/tambah" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus me-1"></i> Buat Tugas Baru
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Mobile Stacked Card View (d-block d-md-none) -->
                    <div class="d-block d-md-none">
                        <div class="mobile-card-list">
                            <?php foreach ($tugasList as $t): ?>
                                <div class="mobile-card-item">
                                    <div class="item-header">
                                        <span class="badge bg-primary px-2.5 py-1 fw-bold"><?= e($t['pertemuan_nomor']) ?></span>
                                        <?= get_task_status_badge($t['status']) ?>
                                    </div>

                                    <div class="item-title"><?= e($t['judul']) ?></div>
                                    <div class="small text-secondary mb-2"><?= e($t['pertemuan_topik']) ?></div>

                                    <div class="item-meta">
                                        <div class="meta-row align-items-start">
                                            <span class="text-secondary"><i class="ti ti-calendar me-1 text-primary"></i>Buka:</span>
                                            <div class="text-end">
                                                <div class="small text-dark fw-bold"><?= format_date((int)$t['dibuka_pada']) ?></div>
                                                <div class="small text-secondary" style="font-size: 0.76rem;"><?= format_time((int)$t['dibuka_pada']) ?></div>
                                            </div>
                                        </div>
                                        <div class="meta-row align-items-start mt-1">
                                            <span class="text-secondary"><i class="ti ti-calendar-time me-1 text-danger"></i>Tutup:</span>
                                            <div class="text-end">
                                                <div class="small text-danger fw-bold"><?= format_date((int)$t['ditutup_pada']) ?></div>
                                                <div class="small text-danger" style="font-size: 0.76rem;"><?= format_time((int)$t['ditutup_pada']) ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="item-action flex-column gap-1.5 mt-2">
                                        <a href="/dosen/tugas/<?= e($t['id']) ?>/pengumpulan" class="btn btn-table-action-primary w-100 text-center py-1.5">
                                            <i class="ti ti-users"></i>
                                            <span>Periksa (<?= (int)$t['total_kumpul'] ?>)</span>
                                        </a>
                                        <div class="d-flex gap-1.5 w-100">
                                            <a href="/dosen/tugas/<?= e($t['id']) ?>/edit" class="btn btn-table-action-secondary flex-fill text-center py-1.5" title="Edit Tugas">
                                                <i class="ti ti-edit"></i>
                                                <span>Edit</span>
                                            </a>
                                            <button type="button" class="btn btn-table-action-danger flex-fill text-center py-1.5" data-bs-toggle="modal" data-bs-target="#deleteTugasModal<?= e($t['id']) ?>" title="Hapus Tugas">
                                                <i class="ti ti-trash"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Desktop Table View (d-none d-md-block) -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive" style="overflow-x: hidden;">
                            <table class="table table-hover align-middle mb-0 w-100">
                                <thead>
                                    <tr>
                                        <th scope="col" class="ps-3 ps-md-4 text-center" style="width: 70px;">Pertemuan</th>
                                        <th scope="col">Judul Tugas</th>
                                        <th scope="col" class="text-center" style="width: 110px;">Status</th>
                                        <th scope="col" style="width: 180px;">Jendela Pengumpulan</th>
                                        <th scope="col" class="text-center pe-3 pe-md-4" style="width: 195px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tugasList as $t): ?>
                                        <tr>
                                            <td class="ps-3 ps-md-4 text-center">
                                                <span class="badge bg-primary fs-3 px-2.5 py-1 fw-bold"><?= e($t['pertemuan_nomor']) ?></span>
                                            </td>
                                            <td>
                                                <strong class="text-dark"><?= e($t['judul']) ?></strong>
                                                <div class="small text-secondary mt-0.5"><?= e($t['pertemuan_topik']) ?></div>
                                            </td>
                                            <td class="text-center">
                                                <?= get_task_status_badge($t['status']) ?>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1.5 small">
                                                    <div class="d-flex align-items-center gap-1.5">
                                                        <i class="ti ti-calendar text-primary flex-shrink-0"></i>
                                                        <span class="text-secondary">Buka: <strong class="text-dark"><?= format_date((int)$t['dibuka_pada']) ?></strong></span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-1.5">
                                                        <i class="ti ti-calendar-time text-danger flex-shrink-0"></i>
                                                        <span class="text-danger fw-semibold">Tutup: <?= format_date((int)$t['ditutup_pada']) ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center pe-3 pe-md-4">
                                                <div class="d-inline-flex flex-column gap-1.5" style="width: 160px;">
                                                    <a href="/dosen/tugas/<?= e($t['id']) ?>/pengumpulan" class="btn btn-table-action-primary w-100 py-1" title="Lihat daftar pengumpulan">
                                                        <i class="ti ti-users"></i>
                                                        <span>Periksa (<?= (int)$t['total_kumpul'] ?>)</span>
                                                    </a>
                                                    <div class="d-flex gap-1.5 w-100">
                                                        <a href="/dosen/tugas/<?= e($t['id']) ?>/edit" class="btn btn-table-action-secondary flex-fill py-1 px-1.5 text-nowrap" title="Edit Tugas">
                                                            <i class="ti ti-edit"></i>
                                                            <span style="font-size: 0.78rem;">Edit</span>
                                                        </a>
                                                        <button type="button" class="btn btn-table-action-danger flex-fill py-1 px-1.5 text-nowrap" data-bs-toggle="modal" data-bs-target="#deleteTugasModal<?= e($t['id']) ?>" title="Hapus Tugas">
                                                            <i class="ti ti-trash"></i>
                                                            <span style="font-size: 0.78rem;">Hapus</span>
                                                        </button>
                                                    </div>
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

<!-- Shared Delete Confirmation Modals (Placed outside glass card to avoid stacking context issues) -->
<?php if (!empty($tugasList)): ?>
    <?php foreach ($tugasList as $t): ?>
        <div class="modal fade" id="deleteTugasModal<?= e($t['id']) ?>" tabindex="-1" aria-labelledby="deleteTugasModalLabel<?= e($t['id']) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-start shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger fw-bold d-flex align-items-center gap-2" id="deleteTugasModalLabel<?= e($t['id']) ?>">
                            <i class="ti ti-alert-triangle fs-2"></i>
                            <span>Konfirmasi Hapus Tugas</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">Apakah Anda yakin ingin menghapus tugas <strong><?= e($t['judul']) ?></strong>?</p>
                        <div class="alert alert-danger small mb-0 d-flex align-items-start gap-2">
                            <i class="ti ti-shield-alert fs-2 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <strong>Perhatian:</strong> Menghapus tugas ini juga akan menghapus seluruh data nilai dan berkas fisik submission mahasiswa terkait secara permanen!
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                        <form action="/dosen/tugas/<?= e($t['id']) ?>/hapus" method="POST" class="m-0">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger btn-sm px-3 shadow-sm">
                                <i class="ti ti-trash me-1"></i> Ya, Hapus Tugas
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php 
$body = ob_get_clean(); 
Flight::render('layout', ['body' => $body, 'title' => $title ?? 'Kelola Tugas']);
?>
