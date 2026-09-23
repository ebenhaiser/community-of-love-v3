<?php
// try {
//     require __DIR__ . '/../public/index.php';
// } catch (\Throwable $e) {
//     header("Content-Type: text/plain", true, 500);
//     echo "FATAL ERROR: " . $e->getMessage() . "\n\n";
//     echo $e->getTraceAsString();
// }

// Pastikan folder storage dan sessions di /tmp otomatis dibuat
@mkdir('/tmp/storage/framework/sessions', 0777, true);
@mkdir('/tmp/storage/framework/views', 0777, true);
@mkdir('/tmp/storage/framework/cache', 0777, true);

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    header("Content-Type: text/plain", true, 500);
    echo "FATAL ERROR: " . $e->getMessage() . "\n\n";
    echo $e->getTraceAsString();
}