<?php
$configFile = __DIR__ . '/config/config.local.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cfg = [
        'db_host' => trim($_POST['db_host']), 'db_name' => trim($_POST['db_name']),
        'db_user' => trim($_POST['db_user']), 'db_pass' => $_POST['db_pass'],
        'app_url' => '', 'timezone' => 'Asia/Dhaka', 'session_name' => 'travel_books_session'
    ];
    try {
        $pdo = new PDO('mysql:host='.$cfg['db_host'].';dbname='.$cfg['db_name'].';charset=utf8mb4', $cfg['db_user'], $cfg['db_pass'], [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
        $migrations = [
            "ALTER TABLE users ADD COLUMN mobile VARCHAR(40) DEFAULT ''",
            "ALTER TABLE users ADD COLUMN address VARCHAR(255) DEFAULT ''",
            "ALTER TABLE users ADD COLUMN designation VARCHAR(100) DEFAULT ''",
            "ALTER TABLE users ADD COLUMN salary DECIMAL(12,2) NOT NULL DEFAULT 0",
            "ALTER TABLE bookings MODIFY status ENUM('quotation','confirmed','partially_paid','paid','completed','cancelled') NOT NULL DEFAULT 'quotation'"
        ];
        foreach ($migrations as $migration) {
            try { $pdo->exec($migration); } catch (Throwable $ignore) { }
        }
        $sql = file_get_contents(__DIR__.'/database/schema.sql');
        $sql = preg_replace('/CREATE DATABASE.*?;|USE .*?;/is', '', $sql);
        foreach (array_filter(array_map('trim', preg_split('/;\s*\n/', $sql))) as $query) $pdo->exec($query);
        $pdo->prepare("UPDATE users SET password_hash=? WHERE username='admin'")->execute([password_hash($_POST['admin_password'], PASSWORD_DEFAULT)]);
        file_put_contents($configFile, "<?php\nreturn ".var_export($cfg, true).";\n");
        $message = 'ইনস্টল সফল। এখন install.php মুছে /index.html খুলুন।';
    } catch (Throwable $e) { $message = 'Error: '.$e->getMessage(); }
}
?><!doctype html><html lang="bn"><meta charset="utf-8"><title>Travel Books Installer</title><style>body{font:16px Arial;background:#f5f7f1;padding:40px;color:#143b35}.box{max-width:600px;margin:auto;background:white;padding:30px;border-radius:18px}label{display:block;margin:13px 0 5px}input{width:100%;padding:11px;border:1px solid #ddd;border-radius:8px;box-sizing:border-box}button{margin-top:18px;padding:12px 18px;background:#143b35;color:white;border:0;border-radius:8px}.msg{padding:12px;background:#e7f2df;margin-bottom:15px}</style><div class="box"><h1>Travel Books Installer</h1><p>আপনার cPanel MySQL database তৈরি করে তথ্য দিন।</p><?php if($message):?><div class="msg"><?=htmlspecialchars($message)?></div><?php endif;?><form method="post"><label>Database host</label><input name="db_host" value="localhost" required><label>Database name</label><input name="db_name" required><label>Database user</label><input name="db_user" required><label>Database password</label><input name="db_pass" type="password"><label>Admin password</label><input name="admin_password" type="password" value="admin123" required><button>Install application</button></form><p>Default username: <b>admin</b></p></div></html>
