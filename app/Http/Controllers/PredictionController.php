<?php

namespace App\Http\Controllers;

use App\Services\PredictionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PredictionController extends Controller
{
    protected $predictionService;

    public function __construct(PredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

    /**
     * Handle prediction request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function predict(Request $request)
    {
        // Log request
        \Log::info('Prediction request received', $request->all());
        
        try {
            // Validate the request data
            $validated = $request->validate([
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'usia' => 'required|integer|min:0|max:120',
                'berat_badan' => 'required|numeric|min:1',
                'tinggi_badan' => 'required|numeric|min:1',
                // Accept formats like 120 or 120/80
                'tekanan_darah' => [
                    'required',
                    'regex:/^\d{2,3}(?:\/\d{2,3})?$/'
                ],
                'hemoglobin' => 'required|numeric|min:0',
                'gula_darah' => 'required|numeric|min:0',
                'keturunan' => 'required|in:Ya,Tidak',
                'pola_tidur' => 'sometimes|in:Ya,Tidak',
                'pola_makan' => 'sometimes|in:Ya,Tidak',
                'merokok' => 'required|in:Ya,Tidak',
            ]);

            // Prepare all required fields for the model
            $input = [
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'usia' => (float)$validated['usia'],
                'gula_darah' => (float)$validated['gula_darah'],
                'berat_badan' => (float)$validated['berat_badan'],
                'tinggi_badan' => (float)$validated['tinggi_badan'],
                'tekanan_darah' => (float)$validated['tekanan_darah'],
                'hemoglobin' => (float)$validated['hemoglobin'],
                'keturunan' => $validated['keturunan'],
                'merokok' => $validated['merokok']
            ];
            
            // Add optional fields if they exist
            if (isset($validated['pola_tidur'])) {
                $input['pola_tidur'] = $validated['pola_tidur'];
            }
            if (isset($validated['pola_makan'])) {
                $input['pola_makan'] = $validated['pola_makan'];
            }
            
            // Log input for debugging
            \Log::info('Input data for prediction:', $input);

            // Get prediction with all required fields
            $result = $this->predictionService->predict($input);
            
            // Log the prediction result (without showing to user)
            Log::info('Prediction result', [
                'input' => $input,
                'result' => $result
            ]);

            // Check if prediction was successful
            if (isset($result['success']) && $result['success'] === true) {
                // Keep BMI numeric for frontend, also compute category if needed
                $bmi = isset($result['bmi']) ? (float)$result['bmi'] : 0.0;
                $bmiCategory = $this->getBmiCategory($bmi);

                // Prepare data for the Blade view, align keys with hasil_prediksi.blade.js
                $viewData = [
                    'success' => true,
                    'prediction' => $result['prediction'] ?? null,
                    'status' => $result['status'] ?? 'Tidak Diketahui',
                    'confidence' => $result['confidence'] ?? 0,
                    'bmi' => $bmi,
                    'bmi_category' => $bmiCategory,
                    'rekomendasi' => $result['rekomendasi'] ?? [
                        'Mohon konsultasikan dengan tenaga medis untuk hasil yang lebih akurat.'
                    ],
                ];

                // Add additional data for display
                $viewData['user_data'] = [
                    'usia' => (int)$validated['usia'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'berat_badan' => (float)$validated['berat_badan'],
                    'tinggi_badan' => (float)$validated['tinggi_badan'],
                    'gula_darah' => (float)$validated['gula_darah'],
                    'keturunan' => $validated['keturunan'],
                    'merokok' => $validated['merokok'],
                    'tekanan_darah' => $validated['tekanan_darah'],
                    'hemoglobin' => (float)$validated['hemoglobin'],
                ];

                if (isset($validated['pola_tidur'])) {
                    $viewData['user_data']['pola_tidur'] = $validated['pola_tidur'];
                }
                if (isset($validated['pola_makan'])) {
                    $viewData['user_data']['pola_makan'] = $validated['pola_makan'];
                }

                // Render hasil_prediksi view
                return view('hasil_prediksi', ['result' => $viewData]);
            } else {
                $error = $result['error'] ?? 'Tidak dapat memproses prediksi saat ini';
                throw new \Exception($error);
            }

        } catch (\Exception $e) {
            // Log the error
            Log::error('Prediction failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withErrors(['predict' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat melakukan prediksi'])
                ->withInput();
        }
    }

    /**
     * Test endpoint for prediction
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function testPredict()
    {
        try {
            $testData = [
                'jenis_kelamin' => 'Laki-laki',
                'usia' => 25,
                'berat_badan' => 60,
                'tinggi_badan' => 165,
                'tekanan_darah' => 120,
                'hemoglobin' => 14.5,
                'gula_darah' => 100,
                'keturunan' => 'Tidak',
                'pola_tidur' => 'Cukup',
                'aktivitas_fisik' => 'Sedang',
                'riwayat_penyakit' => 'Tidak',
                'konsumsi_obat' => 'Tidak',
                'konsumsi_rokok' => 'Tidak',
                'konsumsi_alkohol' => 'Tidak'
            ];

            $request = new \Illuminate\Http\Request($testData);
            return $this->predict($request);

        } catch (\Exception $e) {
            return redirect()->route('prediksi')
                ->withErrors(['predict' => 'Test failed: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Get BMI category based on BMI value
     * 
     * @param float $bmi
     * @return string
     */
    private function getBmiCategory($bmi)
    {
        if ($bmi < 18.5) {
            return 'Kurus';
        } elseif ($bmi >= 18.5 && $bmi < 25) {
            return 'Normal';
        } elseif ($bmi >= 25 && $bmi < 30) {
            return 'Gemuk';
        } else {
            return 'Obesitas';
        }
    }
}