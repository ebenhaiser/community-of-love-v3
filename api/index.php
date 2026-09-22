<?php
if (file_exists(__DIR__ . '/../public/index.php')) {
    require __DIR__ . '/../public/index.php';
} else {
    echo "Error: public/index.php not found.";
}