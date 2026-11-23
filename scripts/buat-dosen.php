<?php
/**
 * CLI Script: Buat Akun Dosen
 * Usage:
 *   php scripts/buat-dosen.php [nim] [nama] [password]
 * Or run interactively.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/helpers.php';

echo "=== Pembuatan Akun Dosen ===\n";

$nim = $argv[1] ?? null;
$nama = $argv[2] ?? null;
$password = $argv[3] ?? null;

if (empty($nim)) {
    echo "Masukkan NIK / NIM Dosen: ";
    $nim = trim((string)fgets(STDIN));
}

if (empty($nama)) {
    echo "Masukkan Nama Dosen: ";
    $nama = trim((string)fgets(STDIN));
}

if (empty($password)) {
    echo "Masukkan Password: ";
    $password = trim((string)fgets(STDIN));
}

if (empty($nim) || empty($nama) || empty($password)) {
    echo "[ERROR] NIK/NIM, Nama, dan Password tidak boleh kosong.\n";
    exit(1);
}

if (strlen($password) < 6) {
    echo "[ERROR] Password minimal 6 karakter.\n";
    exit(1);
}

$db = get_db();

// Check if user already exists
$stmt = $db->prepare("SELECT id FROM users WHERE nim = :nim LIMIT 1");
$stmt->execute([':nim' => $nim]);
$existing = $stmt->fetch();

$passwordHash = password_hash($password, PASSWORD_BCRYPT);

if ($existing) {
    $upStmt = $db->prepare("UPDATE users SET nama = :nama, role = 'dosen', kelas = NULL, password_hash = :hash, wajib_ganti_password = 0 WHERE id = :id");
    $upStmt->execute([
        ':nama' => $nama,
        ':hash' => $passwordHash,
        ':id' => $existing['id']
    ]);
    echo "[SUKSES] Akun dosen dengan NIM/NIK {$nim} ({$nama}) berhasil diperbarui.\n";
} else {
    $inStmt = $db->prepare("INSERT INTO users (nim, nama, kelas, role, password_hash, wajib_ganti_password) VALUES (:nim, :nama, NULL, 'dosen', :hash, 0)");
    $inStmt->execute([
        ':nim' => $nim,
        ':nama' => $nama,
        ':hash' => $passwordHash
    ]);
    echo "[SUKSES] Akun dosen baru dengan NIM/NIK {$nim} ({$nama}) berhasil dibuat.\n";
}

echo "Role: dosen\nWajib Ganti Password: 0\n";
