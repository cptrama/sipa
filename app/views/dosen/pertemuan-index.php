<?php ob_start(); ?>

<!-- Page Title & Header -->
<div class="row mb-3 align-items-center">
    <div class="col-12 col-md-7">
        <h3 class="fw-bold mb-0 d-flex align-items-center gap-2">
            <i class="ti ti-calendar-event text-primary"></i>
            <span>Kelola Pertemuan</span>
        </h3>
        <p class="text-secondary small mb-0 mt-1">Kelola jadwal pertemuan, topik materi, dan lampiran perkuliahan Semester 5.</p>
    </div>
    <div class="col-12 col-md-5 text-md-end mt-3 mt-md-0">
        <a href="/dosen/pertemuan/tambah" class="btn btn-primary btn-header-action shadow-sm">
            <i class="ti ti-plus"></i>
            <span>Tambah Pertemuan</span>
        </a>
    </div>
</div>

<!-- Meetings List -->
<div class="row">
    <div class="col-12">
        <div class="glass-card">
            <div class="card-header d-flex justify-content-between align-items-center py-2.5 px-3 px-md-4">
                <h4 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                    <i class="ti ti-list text-primary"></i>
                    <span>Daftar Pertemuan Perkuliahan</span>
                </h4>
                <span class="badge bg-secondary"><?= count($pertemuanList) ?> Pertemuan</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($pertemuanList)): ?>
                    <div class="text-center py-5 text-secondary px-3">
                        <i class="ti ti-calendar-off display-4 mb-2 d-block opacity-50"></i>
                        <h4 class="fw-bold">Belum ada data pertemuan Semester 5.</h4>
                        <p class="small mb-3">Klik tombol di bawah untuk menambahkan pertemuan baru.</p>
                        <a href="/dosen/pertemuan/tambah" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus me-1"></i> Tambah Pertemuan
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Mobile Stacked Card View (d-block d-md-none) -->
                    <div class="d-block d-md-none">
                        <div class="mobile-card-list">
                            <?php foreach ($pertemuanList as $p): ?>
                                <div class="mobile-card-item">
                                    <div class="item-header">
                                        <span class="badge bg-primary px-2.5 py-1 fw-bold"><?= e($p['nomor']) ?></span>
                                        <?php 
                                            $tglTime = strtotime($p['tanggal']);
                                            $tglFmt = $tglTime ? date('d M Y', $tglTime) : $p['tanggal'];
                                        ?>
                                        <small class="text-secondary"><i class="ti ti-calendar me-1"></i><?= e($tglFmt) ?></small>
                                    </div>

                                    <div class="item-title"><?= e($p['topik']) ?></div>
                                    <?php if (!empty($p['mata_kuliah'])): ?>
                                        <div class="small text-secondary mb-2"><?= e($p['mata_kuliah']) ?></div>
                                    <?php endif; ?>

                                    <div class="item-meta">
                                        <div class="meta-row">
                                            <span class="text-secondary"><i class="ti ti-clipboard-list me-1 text-primary"></i>Tugas:</span>
                                            <span class="badge bg-secondary"><?= (int)$p['total_tugas'] ?> Tugas</span>
                                        </div>
                                        <div class="meta-row">
                                            <span class="text-secondary"><i class="ti ti-files me-1 text-success"></i>Materi:</span>
                                            <?php 
                                                $files = get_materi_files($p['file_materi'] ?? null);
                                                if (!empty($files)):
                                            ?>
                                                <div class="d-flex flex-column gap-1">
                                                    <?php foreach ($files as $f): ?>
                                                        <a href="/materi/<?= e($p['id']) ?>/download?file=<?= urlencode($f) ?>" class="btn btn-table-action-success py-1 px-2 text-truncate" style="font-size: 0.78rem; max-width: 200px;">
                                                            <i class="ti ti-file-download me-1"></i><?= e($f) ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-secondary small">Tidak ada</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="item-action flex-column gap-1.5 mt-2">
                                        <a href="/dosen/tugas/tambah?pertemuan_id=<?= e($p['id']) ?>" class="btn btn-table-action-primary w-100 text-center py-1.5">
                                            <i class="ti ti-plus"></i>
                                            <span>+ Tugas Baru</span>
                                        </a>
                                        <div class="d-flex gap-1.5 w-100">
                                            <a href="/dosen/pertemuan/<?= e($p['id']) ?>/edit" class="btn btn-table-action-secondary flex-fill text-center py-1.5" title="Edit Pertemuan">
                                                <i class="ti ti-edit"></i>
                                                <span>Edit</span>
                                            </a>
                                            <button type="button" class="btn btn-table-action-danger flex-fill text-center py-1.5" data-bs-toggle="modal" data-bs-target="#deleteModal<?= e($p['id']) ?>" title="Hapus Pertemuan">
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
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col" class="ps-3 ps-md-4 text-center" style="width: 70px;">Pertemuan</th>
                                        <th scope="col" style="width: 150px;">Mata Kuliah</th>
                                        <th scope="col">Topik Pertemuan</th>
                                        <th scope="col" class="text-center" style="width: 140px;">Tanggal</th>
                                        <th scope="col" class="text-center" style="width: 220px;">File Materi</th>
                                        <th scope="col" class="text-center" style="width: 110px;">Tugas</th>
                                        <th scope="col" class="text-center pe-3 pe-md-4" style="width: 160px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pertemuanList as $p): ?>
                                        <tr>
                                            <td class="ps-3 ps-md-4 text-center">
                                                <span class="badge bg-primary fs-3 px-2.5 py-1 fw-bold"><?= e($p['nomor']) ?></span>
                                            </td>
                                            <td>
                                                <?= !empty($p['mata_kuliah']) ? '<span class="text-dark">' . e($p['mata_kuliah']) . '</span>' : '<span class="text-secondary">-</span>' ?>
                                            </td>
                                            <td>
                                                <strong class="text-dark"><?= e($p['topik']) ?></strong>
                                            </td>
                                            <td class="text-center">
                                                <?php 
                                                    $tglTime = strtotime($p['tanggal']);
                                                    $tglFmt = $tglTime ? date('d M Y', $tglTime) : $p['tanggal'];
                                                ?>
                                                <div class="small text-secondary text-nowrap d-inline-flex align-items-center justify-content-center gap-1.5">
                                                    <i class="ti ti-calendar text-primary"></i>
                                                    <span><?= e($tglFmt) ?></span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <?php 
                                                    $files = get_materi_files($p['file_materi'] ?? null);
                                                    if (!empty($files)):
                                                ?>
                                                    <div class="d-inline-flex flex-column gap-1 align-items-center">
                                                        <?php foreach ($files as $f): ?>
                                                            <a href="/materi/<?= e($p['id']) ?>/download?file=<?= urlencode($f) ?>" class="btn btn-table-action-success py-1 px-2 d-inline-flex align-items-center text-truncate" style="font-size: 0.78rem; max-width: 200px;" title="Unduh <?= e($f) ?>">
                                                                <i class="ti ti-file-download flex-shrink-0 me-1"></i>
                                                                <span class="text-truncate"><?= e($f) ?></span>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-secondary small">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge <?= (int)$p['total_tugas'] > 0 ? 'bg-primary-lt text-primary' : 'bg-secondary' ?> px-2.5 py-1">
                                                    <i class="ti ti-clipboard-list me-1"></i><?= (int)$p['total_tugas'] ?> Tugas
                                                </span>
                                            </td>
                                            <td class="text-center pe-3 pe-md-4 py-2">
                                                <div class="d-inline-flex flex-column gap-1 align-items-center" style="width: 140px;">
                                                    <a href="/dosen/tugas/tambah?pertemuan_id=<?= e($p['id']) ?>" class="btn btn-table-action-primary w-100 py-1" title="Tambah Tugas">
                                                        <i class="ti ti-plus"></i>
                                                        <span>Tugas</span>
                                                    </a>
                                                    <div class="d-flex gap-1 w-100">
                                                        <a href="/dosen/pertemuan/<?= e($p['id']) ?>/edit" class="btn btn-table-action-secondary flex-fill py-1 px-1.5" title="Edit Pertemuan">
                                                            <i class="ti ti-edit"></i>
                                                            <span style="font-size: 0.76rem;">Edit</span>
                                                        </a>
                                                        <button type="button" class="btn btn-table-action-danger flex-fill py-1 px-1.5" data-bs-toggle="modal" data-bs-target="#deleteModal<?= e($p['id']) ?>" title="Hapus Pertemuan">
                                                            <i class="ti ti-trash"></i>
                                                            <span style="font-size: 0.76rem;">Hapus</span>
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
<?php if (!empty($pertemuanList)): ?>
    <?php foreach ($pertemuanList as $p): ?>
        <div class="modal fade" id="deleteModal<?= e($p['id']) ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= e($p['id']) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-start shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger fw-bold d-flex align-items-center gap-2" id="deleteModalLabel<?= e($p['id']) ?>">
                            <i class="ti ti-alert-triangle fs-2"></i>
                            <span>Konfirmasi Hapus Pertemuan</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">Apakah Anda yakin ingin menghapus <strong>Pertemuan <?= e($p['nomor']) ?> (Kelas <?= e($p['kelas']) ?>): <?= e($p['topik']) ?></strong>?</p>
                        <div class="alert alert-danger small mb-0 d-flex align-items-start gap-2">
                            <i class="ti ti-shield-alert fs-2 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <strong>Perhatian:</strong> Menghapus pertemuan ini juga akan menghapus seluruh tugas, materi, dan berkas submission mahasiswa yang terhubung secara permanen!
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                        <form action="/dosen/pertemuan/<?= e($p['id']) ?>/hapus" method="POST" class="m-0">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger btn-sm px-3 shadow-sm">
                                <i class="ti ti-trash me-1"></i> Ya, Hapus Pertemuan
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
Flight::render('layout', ['body' => $body, 'title' => $title ?? 'Kelola Pertemuan']);
?>
