<?php

// Path ke Python (sesuaikan dengan path Python di sistem Anda)
$pythonPath = 'python';

// Path ke script Python
$scriptPath = __DIR__ . '/scripts/predict.py';

// Data uji
$testData = [60, 165, 120, 14.5, 90, 0, 1, 1, 0, 22.04];

// Konversi ke JSON
$jsonData = escapeshellarg(json_encode($testData));

// Perintah untuk menjalankan script Python
$command = "$pythonPath $scriptPath $jsonData 2>&1";

echo "Menjalankan perintah: $command\n\n";

// Eksekusi perintah
$output = shell_exec($command);

// Tampilkan output
echo "Output dari Python script:\n";
print_r($output);

// Debug: Tampilkan isi direktori scripts/
echo "\nIsi direktori scripts/:\n";
print_r(scandir(__DIR__ . '/scripts'));

echo "\nApakah file predict.py ada? " . (file_exists($scriptPath) ? 'Ya' : 'Tidak');
echo "\nPath lengkap: $scriptPath";
