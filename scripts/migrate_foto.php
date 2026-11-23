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

// Reset & Seed Mahasiswa S5 from mahasiswa.csv with Nama@123 passwords
$csvFile = __DIR__ . '/mahasiswa.csv';
if (file_exists($csvFile)) {
    $db->exec("DELETE FROM users WHERE role = 'mahasiswa';");
    echo "🗑️ Seluruh data mahasiswa lama telah dihapus." . PHP_EOL;

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

    $handle = fopen($csvFile, 'r');
    fgetcsv($handle); // skip header
    $mhsInsertStmt = $db->prepare(
        "INSERT INTO users (nim, nama, kelas, role, password_hash, foto, wajib_ganti_password) 
         VALUES (:nim, :nama, :kelas, 'mahasiswa', :hash, NULL, 0)"
    );

    $mhsCount = 0;
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
    echo "✅ {$mhsCount} Akun Mahasiswa baru Kelas S5 berhasil di-seed (Password = Nama@123, wajib_ganti_password = 0)." . PHP_EOL;
}
