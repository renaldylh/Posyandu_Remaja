<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class PredictionService
{
    protected $pythonPath;
    protected $scriptPath;

    public function __construct()
    {
        $this->pythonPath = 'python'; // Gunakan 'python3' di Linux/Mac
        $this->scriptPath = base_path('scripts/predict.py');
    }

    public function predict(array $input)
    {
        try {
            // Pastikan input adalah array asosiatif
            if (!is_array($input) || empty($input)) {
                throw new \Exception('Input harus berupa array asosiatif yang tidak kosong');
            }

            // Konversi ke JSON
            $jsonInput = json_encode($input);
            
            // Path ke Python dan script (gunakan path lengkap)
            $pythonPath = 'python';  // Atau 'C:\\path\\to\\python.exe'
            $scriptPath = str_replace('\\', '/', base_path('scripts/predict.py'));
            
            // Buat temporary file untuk input
            $tempDir = sys_get_temp_dir();
            $tempFile = tempnam($tempDir, 'pred_');
            $outputFile = $tempFile . '.out';
            $errorFile = $tempFile . '.error';
            
            // Tulis input ke file
            file_put_contents($tempFile, $jsonInput);
            
            // Bangun perintah
            $command = sprintf(
                '%s %s %s > %s 2> %s',
                escapeshellarg($pythonPath),
                escapeshellarg($scriptPath),
                escapeshellarg($tempFile),
                escapeshellarg($outputFile),
                escapeshellarg($errorFile)
            );

            // Jalankan perintah
            $returnVar = 0;
            $output = [];
            exec($command, $output, $returnVar);
            
            // Baca output dan error
            $outputContent = file_exists($outputFile) ? trim(file_get_contents($outputFile)) : '';
            $errorOutput = file_exists($errorFile) ? trim(file_get_contents($errorFile)) : '';
            
            // Hapus file temporary
            @unlink($tempFile);
            @unlink($outputFile);
            @unlink($errorFile);
            
            // Jika ada error di output error atau return code bukan 0
            if (!empty($errorOutput) || $returnVar !== 0) {
                throw new \Exception('Gagal menjalankan script Python. ' . 
                    'Error: ' . $errorOutput . ' ' .
                    'Output: ' . $outputContent);
            }
            
            // Jika output kosong
            if (empty($outputContent)) {
                throw new \Exception('Script Python tidak mengembalikan output');
            }
            
            // Hapus semua karakter sebelum kurung kurawal buka pertama
            $jsonStart = strpos($outputContent, '{');
            if ($jsonStart !== false) {
                $outputContent = substr($outputContent, $jsonStart);
            }
            
            // Hapus semua karakter setelah kurung kurawal tutup terakhir
            $jsonEnd = strrpos($outputContent, '}');
            if ($jsonEnd !== false) {
                $outputContent = substr($outputContent, 0, $jsonEnd + 1);
            }
            
            // Parse output JSON
            $result = json_decode(trim($outputContent), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Gagal memparse output dari Python. ' . 
                    'Output: ' . $outputContent . ' ' . 
                    'Error: ' . json_last_error_msg());
            }
            
            // Pastikan respons berhasil
            if (isset($result['success']) && $result['success'] === true) {
                return $result;
            } else {
                $errorMsg = $result['error'] ?? 'Tidak ada pesan error';
                throw new \Exception('Prediksi gagal: ' . $errorMsg);
            }
            
        } catch (\Exception $e) {
            $errorMsg = 'Prediction failed: ' . $e->getMessage();
            \Log::error($errorMsg);
            \Log::error('Input data: ' . json_encode($input));
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return [
                'success' => false,
                'error' => 'Terjadi kesalahan saat melakukan prediksi. ' . 
                          'Detail: ' . $e->getMessage()
            ];
        }
    }
}