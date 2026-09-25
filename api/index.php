<?php

// try {
//     require __DIR__ . '/../public/index.php';
// } catch (\Throwable $e) {
//     header("Content-Type: text/plain", true, 500);
//     echo "FATAL ERROR: " . $e->getMessage() . "\n\n";
//     echo $e->getTraceAsString();
// }

// error_log('CA TEST: ' . (__DIR__ . '/../certs/ca.pem') . ' | EXISTS: ' . (file_exists(__DIR__ . '/../certs/ca.pem') ? 'YES' : 'NO'));

$ca = __DIR__.'/../certs/ca.pem';

if (isset($_GET['test-ca'])) {
    header('Content-Type: text/plain');

    echo 'DIR: '.__DIR__.PHP_EOL;
    echo 'CA: '.$ca.PHP_EOL;
    echo 'EXISTS: '.(file_exists($ca) ? 'YES' : 'NO').PHP_EOL;
    echo 'READABLE: '.(is_readable($ca) ? 'YES' : 'NO').PHP_EOL;

    exit;
}

// Pastikan folder storage dan sessions di /tmp otomatis dibuat
@mkdir('/tmp/storage/framework/sessions', 0777, true);
@mkdir('/tmp/storage/framework/views', 0777, true);
@mkdir('/tmp/storage/framework/cache', 0777, true);

try {
    require __DIR__.'/../public/index.php';
} catch (Throwable $e) {
    header('Content-Type: text/plain', true, 500);
    echo 'FATAL ERROR: '.$e->getMessage()."\n\n";
    echo $e->getTraceAsString();
}
