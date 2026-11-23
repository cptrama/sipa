<?php
date_default_timezone_set('Asia/Makassar');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/helpers.php';

echo "=== Seeding Tugas dan Simulasi Pengumpulan Tugas (P4 - P7) ===\n";

$db = get_db();
$uploadsDir = config('paths.uploads');
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0755, true);
}

$samplePdf = __DIR__ . '/../Contoh Pengumpulan Tugas.pdf';
$samplePpt = __DIR__ . '/../Contoh Pengumpulan Tugas.pptx';

if (!file_exists($samplePdf) || !file_exists($samplePpt)) {
    echo "[ERROR] Berkas Contoh Pengumpulan Tugas (.pdf / .pptx) tidak ditemukan di root proyek.\n";
    exit(1);
}

// 1. Data Tugas P4 - P7
$tugasList = [
    4 => [
        'judul'     => 'Deploy aplikasi sederhana pada virtual machine atau container, kemudian lakukan konfigurasi virtual network dasar pada platform cloud pilihan Anda (AWS, Azure, atau GCP)',
        'deskripsi' => "Langkah Pengerjaan:\n1. Buat akun free tier pada platform cloud pilihan (AWS, Azure, atau GCP).\n2. Buat satu VM (EC2/Azure VM/Compute Engine) atau container.\n3. Deploy aplikasi sederhana, misalnya halaman web statis atau API kecil.\n4. Konfigurasi subnet dan security group; batasi SSH ke IP administrasi yang diperlukan.\n5. Dokumentasikan tahapan dan hasil berupa tangkapan layar.",
        'buka'      => '2026-10-27 12:00:00',
        'tutup'     => '2026-11-03 10:00:00',
    ],
    5 => [
        'judul'     => 'Analisis ancaman terhadap confidentiality, integrity, dan availability pada sistem cloud, kemudian usulkan kontrol keamanan untuk setiap skenario (CIA Triad)',
        'deskripsi' => "Langkah Pengerjaan:\n1. Pilih tiga skenario layanan cloud sederhana (web, storage, database).\n2. Identifikasi ancaman yang mungkin terjadi pada setiap skenario.\n3. Petakan ancaman ke pilar CIA yang terdampak.\n4. Usulkan kontrol keamanan untuk masing-masing ancaman.\n5. Kumpulkan analisis dan dokumentasi (1-2 halaman) melalui SIPA.",
        'buka'      => '2026-11-03 12:00:00',
        'tutup'     => '2026-11-10 10:00:00',
    ],
    6 => [
        'judul'     => 'Praktik konfigurasi HTTPS/TLS pada layanan cloud serta menguji aturan firewall dan protokol jaringan yang aman',
        'deskripsi' => "Langkah Pengerjaan:\n1. Siapkan web server pada VM atau layanan cloud dari pertemuan 4.\n2. Terbitkan dan pasang sertifikat TLS (boleh self-signed untuk latihan).\n3. Konfigurasikan HTTPS dan redirect otomatis dari HTTP ke HTTPS.\n4. Uji firewall: izinkan traffic HTTPS; batasi SSH ke IP admin dan HTTP bila diperlukan untuk redirect.\n5. Dokumentasikan konfigurasi dan hasil pengujian ke dalam laporan tugas.",
        'buka'      => '2026-11-10 12:00:00',
        'tutup'     => '2026-11-17 10:00:00',
    ],
    7 => [
        'judul'     => 'Praktik enkripsi data sederhana dan mendokumentasikan perbedaan encryption at rest dan in-transit',
        'deskripsi' => "Langkah Pengerjaan:\n1. Praktikkan enkripsi simetris sebuah file dengan tool CLI (misalnya OpenSSL).\n2. Buat pasangan kunci asimetris, uji enkripsi dan dekripsi pesan.\n3. Hitung hash sebuah file, ubah isinya, lalu bandingkan nilai hash-nya.\n4. Aktifkan enkripsi at rest pada storage atau database layanan cloud.\n5. Tulis dokumen singkat perbedaan at rest vs in transit beserta bukti praktik.",
        'buka'      => '2026-11-17 12:00:00',
        'tutup'     => '2026-11-24 10:00:00',
    ],
];

// Ambil data mahasiswa kelas S5
$mhsRows = $db->query("SELECT id, nim, nama FROM users WHERE role = 'mahasiswa' ORDER BY nim ASC")->fetchAll();
if (empty($mhsRows)) {
    echo "[ERROR] Belum ada data mahasiswa di database.\n";
    exit(1);
}

$feedbacks = [
    'Pengerjaan sangat baik, dokumentasi langkah-langkah lengkap dan rapi.',
    'Bagus, konfigurasi dan tangkapan layar sudah sesuai instruksi soal.',
    'Langkah pengerjaan lengkap, hasil uji coba terverifikasi dengan jelas.',
    'Analisis tepat sasaran, pemahaman konsep komputasi awan sangat baik.',
    'Dokumentasi konfigurasi sistem dan pengujian berjalan lancar. Pertahankan.',
    'Bagus sekali, pemaparan rapi dan mudah dipahami.',
    'Hasil pengujian valid dan implementasi keamanan diterapkan dengan baik.'
];

$scores = [80, 82, 85, 87, 88, 90, 92, 95];

foreach ($tugasList as $pNomor => $tData) {
    // Cari ID pertemuan
    $pStmt = $db->prepare("SELECT id FROM pertemuan WHERE kelas = 'S5' AND nomor = :nomor LIMIT 1");
    $pStmt->execute([':nomor' => $pNomor]);
    $pertemuan = $pStmt->fetch();

    if (!$pertemuan) {
        echo "⚠️ Pertemuan {$pNomor} tidak ditemukan. Lewati tugas ini.\n";
        continue;
    }

    $pertemuanId = (int)$pertemuan['id'];
    $dibukaPada = strtotime($tData['buka']);
    $ditutupPada = strtotime($tData['tutup']);

    // Check existing tugas
    $tCheck = $db->prepare("SELECT id FROM tugas WHERE pertemuan_id = :pid LIMIT 1");
    $tCheck->execute([':pid' => $pertemuanId]);
    $existingT = $tCheck->fetch();

    if ($existingT) {
        $tugasId = (int)$existingT['id'];
        $upT = $db->prepare(
            "UPDATE tugas 
             SET judul = :judul, deskripsi = :deskripsi, dibuka_pada = :buka, ditutup_pada = :tutup 
             WHERE id = :id"
        );
        $upT->execute([
            ':judul'     => $tData['judul'],
            ':deskripsi' => $tData['deskripsi'],
            ':buka'      => $dibukaPada,
            ':tutup'     => $ditutupPada,
            ':id'        => $tugasId
        ]);
        echo "✅ Tugas Pertemuan {$pNomor} diperbarui (ID: {$tugasId}).\n";
    } else {
        $inT = $db->prepare(
            "INSERT INTO tugas (pertemuan_id, judul, deskripsi, dibuka_pada, ditutup_pada) 
             VALUES (:pid, :judul, :deskripsi, :buka, :tutup)"
        );
        $inT->execute([
            ':pid'       => $pertemuanId,
            ':judul'     => $tData['judul'],
            ':deskripsi' => $tData['deskripsi'],
            ':buka'      => $dibukaPada,
            ':tutup'     => $ditutupPada
        ]);
        $tugasId = (int)$db->lastInsertId();
        echo "✅ Tugas Pertemuan {$pNomor} dibuat (ID: {$tugasId}).\n";
    }

    // Simulasi Mahasiswa Mengumpulkan Tugas
    // Rama (11203362410133) selalu mengumpulkan
    $submittedCount = 0;

    foreach ($mhsRows as $idx => $mhs) {
        $nim = $mhs['nim'];
        $nama = $mhs['nama'];
        $userId = (int)$mhs['id'];

        // Tentukan apakah mahasiswa ini mengumpulkan atau tidak
        $isSubmitting = true;
        if ($nim !== '11203362410133') { // Jangan pernah skip Rama
            if ($pNomor === 4 && in_array($idx, [5, 12])) $isSubmitting = false; // 2 tidak mengumpulkan (Marliana, Suraya)
            elseif ($pNomor === 5 && in_array($idx, [3, 7, 12])) $isSubmitting = false; // 3 tidak mengumpulkan (Fatimah, Kaffin, Suraya)
            elseif ($pNomor === 6 && in_array($idx, [4, 10, 14])) $isSubmitting = false; // 3 tidak mengumpulkan (Maria, Risna, Zaini)
            elseif ($pNomor === 7 && in_array($idx, [1, 2, 6, 8, 11, 14])) $isSubmitting = false; // 6 belum mengumpulkan (karena masih buka)
        }

        if (!$isSubmitting) {
            // Hapus pengumpulan jika ada sebelumnya
            $delP = $db->prepare("DELETE FROM pengumpulan WHERE tugas_id = :tid AND user_id = :uid");
            $delP->execute([':tid' => $tugasId, ':uid' => $userId]);
            continue;
        }

        // Tentukan tipe file (selang-seling PDF dan PPTX)
        $ext = ($idx % 2 === 0) ? 'pdf' : 'pptx';
        $srcFile = ($ext === 'pdf') ? $samplePdf : $samplePpt;

        $cleanNim = sanitize_filename_base($nim);
        $cleanNama = sanitize_filename_base($nama);
        $savedFilename = "{$cleanNim}_{$cleanNama}_Pertemuan{$pNomor}.{$ext}";
        $originalFilename = "Tugas_P{$pNomor}_{$cleanNama}.{$ext}";

        // Salin berkas ke storage/uploads
        $destPath = $uploadsDir . DIRECTORY_SEPARATOR . $savedFilename;
        @copy($srcFile, $destPath);

        // Waktu upload realistis di antara waktu buka dan waktu tutup
        $waktuUpload = $dibukaPada + (3600 * 24 * ($idx % 4 + 1)) + (rand(100, 3600 * 5));
        if ($waktuUpload > $ditutupPada) {
            $waktuUpload = $ditutupPada - (rand(1800, 7200));
        }

        // Tentukan Nilai dan Feedback
        $nilai = null;
        $feedback = null;

        if ($pNomor < 7) {
            // P4, P5, P6 sudah dinilai
            $nilai = $scores[($userId + $pNomor) % count($scores)];
            $feedback = $feedbacks[($userId + $pNomor) % count($feedbacks)];
        } else {
            // P7 masih terbuka, sebagian dinilai, sebagian belum dinilai
            if ($idx % 2 === 0 || $nim === '11203362410133') {
                $nilai = $scores[($userId + $pNomor) % count($scores)];
                $feedback = $feedbacks[($userId + $pNomor) % count($feedbacks)];
            }
        }

        // Upsert pengumpulan
        $subCheck = $db->prepare("SELECT id FROM pengumpulan WHERE tugas_id = :tid AND user_id = :uid LIMIT 1");
        $subCheck->execute([':tid' => $tugasId, ':uid' => $userId]);
        $existingSub = $subCheck->fetch();

        if ($existingSub) {
            $upSub = $db->prepare(
                "UPDATE pengumpulan 
                 SET file_simpan = :simpan, file_asli = :asli, waktu_upload = :waktu, nilai = :nilai, feedback = :feedback 
                 WHERE id = :id"
            );
            $upSub->execute([
                ':simpan'   => $savedFilename,
                ':asli'     => $originalFilename,
                ':waktu'    => $waktuUpload,
                ':nilai'    => $nilai,
                ':feedback' => $feedback,
                ':id'       => $existingSub['id']
            ]);
        } else {
            $inSub = $db->prepare(
                "INSERT INTO pengumpulan (tugas_id, user_id, file_simpan, file_asli, waktu_upload, nilai, feedback) 
                 VALUES (:tid, :uid, :simpan, :asli, :waktu, :nilai, :feedback)"
            );
            $inSub->execute([
                ':tid'      => $tugasId,
                ':uid'      => $userId,
                ':simpan'   => $savedFilename,
                ':asli'     => $originalFilename,
                ':waktu'    => $waktuUpload,
                ':nilai'    => $nilai,
                ':feedback' => $feedback
            ]);
        }
        $submittedCount++;
    }

    echo "   ↳ {$submittedCount} mahasiswa mengumpulkan tugas P{$pNomor}.\n";
}

echo "\n🎉 SELESAI! Tugas P4 - P7 dan berkas pengumpulan berhasil disiapkan.\n";
