<?php ob_start(); ?>

<!-- Page Title & Header Action -->
<div class="row mb-3 mb-md-4 align-items-center">
    <div class="col-12 col-md-7">
        <h3 class="fw-bold mb-0 d-flex align-items-center gap-2 text-body fs-2">
            <i class="ti ti-table text-primary"></i>
            <span>Rekap Nilai Tugas Mahasiswa</span>
        </h3>
        <p class="text-secondary small mb-0 mt-1">Matriks nilai perkuliahan Semester 5 dan ekspor laporan ke format CSV.</p>
    </div>
    <div class="col-12 col-md-5 text-md-end mt-3 mt-md-0">
        <a href="/dosen/rekap/export" class="btn btn-table-action-success py-2 px-3.5 fw-semibold shadow-sm d-inline-flex align-items-center gap-1.5" title="Unduh berkas CSV rekap nilai">
            <i class="ti ti-file-spreadsheet fs-2"></i>
            <span>Ekspor Rekap CSV</span>
        </a>
    </div>
</div>

<!-- Matrix Table Card -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border rounded-3 overflow-hidden">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-2.5 px-3 px-md-4 border-bottom">
                <h4 class="mb-0 fw-bold text-body d-flex align-items-center gap-2 fs-3">
                    <i class="ti ti-report-analytics text-primary"></i>
                    <span>Rekap Nilai Semester 5</span>
                </h4>
                <span class="badge bg-primary text-white fw-bold px-2.5 py-1">
                    <?= count($students) ?> Mahasiswa &bull; <?= count($tasks) ?> Tugas
                </span>
            </div>
            
            <div class="card-body p-0">
                <?php if (empty($students)): ?>
                    <div class="text-center py-5 text-secondary">
                        <i class="ti ti-users-minus display-4 mb-2 d-block opacity-50"></i>
                        <h4 class="fw-bold">Belum ada mahasiswa di Semester 5.</h4>
                        <p class="small mb-0">Jalankan seeder untuk memuat data mahasiswa.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-secondary text-uppercase fw-bold py-3" style="width: 55px;">No</th>
                                    <th scope="col" class="text-secondary text-uppercase fw-bold py-3 ps-3" style="min-width: 220px;">Mahasiswa</th>
                                    <?php foreach ($tasks as $t): ?>
                                        <th scope="col" class="text-center py-2.5 px-3" style="min-width: 150px;">
                                            <div class="fw-bold text-primary text-uppercase lh-sm" style="font-size: 0.82rem; letter-spacing: 0.04em;">Pertemuan <?= e($t['pertemuan_nomor']) ?></div>
                                            <div class="table-th-subtitle text-secondary fw-normal text-truncate mx-auto mt-0.5" style="max-width: 150px; font-size: 0.72rem;" title="<?= e($t['judul']) ?>">
                                                <?= e($t['judul']) ?>
                                            </div>
                                        </th>
                                    <?php endforeach; ?>
                                    <th scope="col" class="text-center text-primary text-uppercase fw-bold py-3" style="width: 120px;">Rata-Rata</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $taskTotals = [];
                                $taskCountGraded = [];
                                foreach ($tasks as $t) {
                                    $taskTotals[$t['id']] = 0;
                                    $taskCountGraded[$t['id']] = 0;
                                }

                                foreach ($students as $idx => $mhs): 
                                    $studentTotal = 0;
                                    $studentGraded = 0;
                                ?>
                                    <tr>
                                        <td class="text-center text-secondary small"><?= $idx + 1 ?></td>
                                        <td class="ps-3 py-2">
                                            <div class="d-flex flex-column gap-0.5">
                                                <div class="d-flex align-items-center gap-2">
                                                    <?= render_avatar($mhs, 24) ?>
                                                    <span class="fw-semibold text-body"><?= e($mhs['nama']) ?></span>
                                                </div>
                                                <div style="padding-left: 32px;">
                                                    <code class="text-primary font-monospace" style="font-size: 0.76rem;"><?= e($mhs['nim']) ?></code>
                                                </div>
                                            </div>
                                        </td>
                                        <?php foreach ($tasks as $t): 
                                            $val = $gradesMap[$mhs['id']][$t['id']] ?? null;
                                        ?>
                                            <td class="text-center">
                                                <?php if ($val !== null && $val !== ''): 
                                                    $num = (float)$val;
                                                    $studentTotal += $num;
                                                    $studentGraded++;
                                                    $taskTotals[$t['id']] += $num;
                                                    $taskCountGraded[$t['id']]++;
                                                ?>
                                                    <span class="badge bg-success-lt text-success fw-bold px-2.5 py-1 fs-4">
                                                        <?= number_format($num, 1) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-secondary opacity-40">&mdash;</span>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                        <td class="text-center">
                                            <?php if ($studentGraded > 0): ?>
                                                <span class="badge bg-primary text-white fw-bold px-2.5 py-1 fs-4">
                                                    <?= number_format($studentTotal / $studentGraded, 2) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-secondary opacity-40">&mdash;</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <?php if (!empty($tasks)): ?>
                                <tfoot class="border-top bg-body-tertiary">
                                    <tr>
                                        <td colspan="2" class="text-end py-3 pe-3">
                                            <div class="d-flex align-items-center justify-content-end gap-1.5 fw-bold text-secondary text-uppercase fs-5">
                                                <i class="ti ti-calculator fs-2 text-primary"></i>
                                                <span>Rata-Rata Kelas:</span>
                                            </div>
                                        </td>
                                        <?php 
                                        $overallTotal = 0;
                                        $overallGraded = 0;
                                        foreach ($tasks as $t): 
                                            $avg = $taskCountGraded[$t['id']] > 0 ? ($taskTotals[$t['id']] / $taskCountGraded[$t['id']]) : null;
                                            if ($avg !== null) {
                                                $overallTotal += $taskTotals[$t['id']];
                                                $overallGraded += $taskCountGraded[$t['id']];
                                            }
                                        ?>
                                            <td class="text-center py-3">
                                                <?php if ($avg !== null): ?>
                                                    <span class="badge bg-primary-lt text-primary fw-bold px-2.5 py-1 fs-4">
                                                        <?= number_format($avg, 2) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-secondary opacity-40">&mdash;</span>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                        <td class="text-center py-3">
                                            <?php if ($overallGraded > 0): ?>
                                                <span class="badge bg-primary text-white fw-bold px-2.5 py-1 fs-4 shadow-sm">
                                                    <?= number_format($overallTotal / $overallGraded, 2) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-secondary opacity-40">&mdash;</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            <?php endif; ?>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php 
$body = ob_get_clean(); 
Flight::render('layout', ['body' => $body, 'title' => $title ?? 'Rekap Nilai']);
?>
