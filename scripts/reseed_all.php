<?php
/**
 * Complete Database Reseed Script
 * Wipes database, cleans storage uploads, and seeds only essential data:
 * - Dosen Accounts (1166082024001 & 1166082022231)
 * - Mahasiswa Kelas S5 from mahasiswa.csv (with default password = NIM)
 */

date_default_timezone_set('Asia/Makassar');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/helpers.php';

echo "=====================================================\n";
echo "🔄 Reset Total & Seed Data Penting Saja (SIPA S5)\n";
echo "=====================================================\n\n";

$db = get_db();

// 1. Wipe and re-apply schema
$db->exec('PRAGMA foreign_keys = OFF;');
$db->exec('DROP TABLE IF EXISTS login_attempts;');
$db->exec('DROP TABLE IF EXISTS pengumpulan;');
$db->exec('DROP TABLE IF EXISTS tugas;');
$db->exec('DROP TABLE IF EXISTS pertemuan;');
$db->exec('DROP TABLE IF EXISTS users;');
$db->exec('PRAGMA foreign_keys = ON;');

$schemaSql = file_get_contents(__DIR__ . '/../database/schema.sql');
$db->exec($schemaSql);
echo "✅ Schema database berhasil di-reset bersih.\n";

// 2. Bersihkan file berkas di storage/uploads (kecuali .gitkeep)
$uploadsDir = config('paths.uploads');
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0755, true);
} else {
    $files = scandir($uploadsDir);
    $deletedFiles = 0;
    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || $file === '.gitkeep') {
            continue;
        }
        $filePath = $uploadsDir . '/' . $file;
        if (is_file($filePath)) {
            unlink($filePath);
            $deletedFiles++;
        }
    }
    echo "✅ Berkas unggahan lama dibersihkan ({$deletedFiles} berkas dihapus).\n";
}

// 3. Bersihkan foto avatar di public/uploads/avatars (kecuali .gitkeep)
$avatarsDir = config('paths.avatars', __DIR__ . '/../public/uploads/avatars');
if (!is_dir($avatarsDir)) {
    mkdir($avatarsDir, 0755, true);
} else {
    $avatars = scandir($avatarsDir);
    $deletedAvatars = 0;
    foreach ($avatars as $file) {
        if ($file === '.' || $file === '..' || $file === '.gitkeep') {
            continue;
        }
        $filePath = $avatarsDir . '/' . $file;
        if (is_file($filePath)) {
            unlink($filePath);
            $deletedAvatars++;
        }
    }
    echo "✅ Berkas avatar lama dibersihkan ({$deletedAvatars} berkas avatar dihapus).\n";
}

// 4. Seed Akun Dosen
$dosenList = [
    [
        'nim'  => '1166082024001',
        'nama' => 'Dosen Komputasi Awan',
        'pass' => 'admin123'
    ],
    [
        'nim'  => '1166082022231',
        'nama' => 'Fajar Ramadhan, M.Kom',
        'pass' => 'admin123'
    ],
    [
        'nim'  => '1166052017097',
        'nama' => 'Muhammad Zulfadhilah, M.Kom',
        'pass' => 'Zul@12345'
    ]
];

$dosenStmt = $db->prepare(
    "INSERT INTO users (nim, nama, kelas, role, password_hash, foto, wajib_ganti_password) 
     VALUES (:nim, :nama, NULL, 'dosen', :hash, NULL, 0)"
);

foreach ($dosenList as $d) {
    $hash = password_hash($d['pass'], PASSWORD_BCRYPT);
    $dosenStmt->execute([
        ':nim'  => $d['nim'],
        ':nama' => $d['nama'],
        ':hash' => $hash
    ]);
    echo "✅ Akun Dosen dibuat: {$d['nim']} ({$d['nama']}) / {$d['pass']}\n";
}

// 5. Seed Mahasiswa Semester 5 dari mahasiswa.csv
$csvFile = __DIR__ . '/mahasiswa.csv';
$mhsCount = 0;

$studentPasswords = [
    '11203362410118' => 'Alberto@123',
    '11203362410119' => 'Riefky@123',
    '11203362410120' => 'Dewi@123',
    '11203362410121' => 'Fatimah@123',
    '11203362410122' => 'Maria@123',
    '11203362410123' => 'Marliana@123',
    '11203362410125' => 'Hariri@123',
    '11203362410126' => 'Kaffin@123',
    '11203362410127' => 'Luhtfi@123',
    '11203362410128' => 'Nurul@123',
    '11203362410129' => 'Risna@123',
    '11203362410131' => 'Salwa@123',
    '11203362410132' => 'Suraya@123',
    '11203362410133' => 'Rama@123',
    '11203362410134' => 'Zaini@123',
];

if (file_exists($csvFile)) {
    $handle = fopen($csvFile, 'r');
    fgetcsv($handle); // skip header
    
    $mhsInsertStmt = $db->prepare(
        "INSERT INTO users (nim, nama, kelas, role, password_hash, foto, wajib_ganti_password) 
         VALUES (:nim, :nama, :kelas, 'mahasiswa', :hash, NULL, 0)"
    );

    while (($row = fgetcsv($handle)) !== false) {
        if (count($row) < 3) continue;
        $nim = trim($row[0]);
        $nama = trim($row[1]);
        $kelas = strtoupper(trim($row[2]));
        
        $plainPassword = $studentPasswords[$nim] ?? ($nim . '@123');
        $hash = password_hash($plainPassword, PASSWORD_BCRYPT);

        $mhsInsertStmt->execute([
            ':nim'   => $nim,
            ':nama'  => $nama,
            ':kelas' => $kelas,
            ':hash'  => $hash
        ]);
        $mhsCount++;
    }
    fclose($handle);
    echo "✅ {$mhsCount} Akun Mahasiswa Kelas S5 berhasil di-seed (Password = Nama@123).\n";
} else {
    echo "⚠️ File mahasiswa.csv tidak ditemukan.\n";
}

// 6. Seed Pertemuan & Materi PPT Komputasi Awan
$materiList = [
    4 => [
        'topik'   => 'Virtual Machine, Container, dan Jaringan Virtual',
        'file'    => 'Komputasi Awan - Pertemuan 04.pptx',
        'tanggal' => '2026-10-12',
    ],
    5 => [
        'topik'   => 'Cloud Security: CIA Triad, Ancaman, dan Kerentanan',
        'file'    => 'Komputasi Awan - Pertemuan 05.pptx',
        'tanggal' => '2026-10-19',
    ],
    6 => [
        'topik'   => 'Komunikasi Aman: TLS/SSL, HTTPS, dan Firewall',
        'file'    => 'Komputasi Awan - Pertemuan 06.pptx',
        'tanggal' => '2026-10-26',
    ],
    7 => [
        'topik'   => 'Enkripsi, Hashing, dan Key Management',
        'file'    => 'Komputasi Awan - Pertemuan 07.pptx',
        'tanggal' => '2026-11-02',
    ],
    9 => [
        'topik'   => 'IAM: Kontrol Akses dan Autentikasi',
        'file'    => 'Komputasi Awan - Pertemuan 09.pptx',
        'tanggal' => '2026-11-16',
    ],
    10 => [
        'topik'   => 'Implementasi IAM: Role, Policy, dan MFA',
        'file'    => 'Komputasi Awan - Pertemuan 10.pptx',
        'tanggal' => '2026-11-23',
    ],
    11 => [
        'topik'   => 'Sertifikat Digital, CA, dan PKI',
        'file'    => 'Komputasi Awan - Pertemuan 11.pptx',
        'tanggal' => '2026-11-30',
    ],
    12 => [
        'topik'   => 'Certificate Lifecycle Management',
        'file'    => 'Komputasi Awan - Pertemuan 12.pptx',
        'tanggal' => '2026-12-07',
    ],
    13 => [
        'topik'   => 'Security Policy, Availability, dan Disaster Recovery',
        'file'    => 'Komputasi Awan - Pertemuan 13.pptx',
        'tanggal' => '2026-12-14',
    ],
    14 => [
        'topik'   => 'Implementasi Keamanan Cloud Terintegrasi',
        'file'    => 'Komputasi Awan - Pertemuan 14.pptx',
        'tanggal' => '2026-12-21',
    ],
    15 => [
        'topik'   => 'Finalisasi Proyek, Security Testing, dan Dokumentasi',
        'file'    => 'Komputasi Awan - Pertemuan 15.pptx',
        'tanggal' => '2026-12-28',
    ],
];

$pptDir = __DIR__ . '/../PPT Materi Komputasi Awan';
$uploadsDir = config('paths.uploads');
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0755, true);
}

$pertemuanStmt = $db->prepare(
    "INSERT INTO pertemuan (kelas, mata_kuliah, nomor, topik, tanggal, file_materi) 
     VALUES ('S5', 'Komputasi Awan', :nomor, :topik, :tanggal, :file_materi)"
);

$pertemuanCount = 0;
foreach ($materiList as $nomor => $data) {
    $srcFile = $pptDir . '/' . $data['file'];
    $fileMateriJson = null;

    if (file_exists($srcFile)) {
        $destFileName = "materi_S5_p{$nomor}_Komputasi_Awan_" . sprintf("%02d", $nomor) . ".pptx";
        $destPath = $uploadsDir . '/' . $destFileName;
        @copy($srcFile, $destPath);
        $fileMateriJson = json_encode([$destFileName], JSON_UNESCAPED_UNICODE);
    }

    $pertemuanStmt->execute([
        ':nomor'       => $nomor,
        ':topik'       => $data['topik'],
        ':tanggal'     => $data['tanggal'],
        ':file_materi' => $fileMateriJson
    ]);
    $pertemuanCount++;
}
echo "✅ {$pertemuanCount} Materi Pertemuan berhasil di-seed beserta berkas PPTX.\n";

echo "\n=====================================================\n";
echo "🎉 SEEDING SELESAI!\n";
echo "   - Tabel pertemuan, tugas, dan pengumpulan telah KOSONG BERSIH.\n";
echo "   - Total Akun Dosen: " . count($dosenList) . "\n";
echo "   - Total Akun Mahasiswa: {$mhsCount}\n";
echo "   - Siap digunakan untuk perkuliahan Komputasi Awan!\n";
echo "=====================================================\n";
