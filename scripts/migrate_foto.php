<?php
require_once __DIR__ . '/../config/database.php';

$db = get_db();
$cols = $db->query('PRAGMA table_info(users)')->fetchAll(PDO::FETCH_ASSOC);
$colNames = array_column($cols, 'name');

echo "Existing columns: " . implode(', ', $colNames) . PHP_EOL;

if (!in_array('foto', $colNames, true)) {
    $db->exec("ALTER TABLE users ADD COLUMN foto TEXT NULL");
    echo "Column 'foto' successfully added to 'users' table." . PHP_EOL;
} else {
    echo "Column 'foto' already exists." . PHP_EOL;
}

// Synchronize default Dosen accounts
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

foreach ($dosenList as $d) {
    $checkStmt = $db->prepare("SELECT id FROM users WHERE nim = :nim LIMIT 1");
    $checkStmt->execute([':nim' => $d['nim']]);
    $existing = $checkStmt->fetch();
    $hash = password_hash($d['pass'], PASSWORD_BCRYPT);

    if ($existing) {
        $upStmt = $db->prepare("UPDATE users SET nama = :nama, role = 'dosen', password_hash = :hash, wajib_ganti_password = 0 WHERE id = :id");
        $upStmt->execute([
            ':nama' => $d['nama'],
            ':hash' => $hash,
            ':id'   => $existing['id']
        ]);
        echo "✅ Akun Dosen diperbarui: {$d['nim']} ({$d['nama']})" . PHP_EOL;
    } else {
        $inStmt = $db->prepare("INSERT INTO users (nim, nama, kelas, role, password_hash, foto, wajib_ganti_password) VALUES (:nim, :nama, NULL, 'dosen', :hash, NULL, 0)");
        $inStmt->execute([
            ':nim'  => $d['nim'],
            ':nama' => $d['nama'],
            ':hash' => $hash
        ]);
        echo "✅ Akun Dosen baru ditambahkan: {$d['nim']} ({$d['nama']})" . PHP_EOL;
    }
}
