<?php
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    header("Content-Type: text/plain", true, 500);
    echo "FATAL ERROR: " . $e->getMessage() . "\n\n";
    echo $e->getTraceAsString();
}