@extends('layouts.app')

@section('title', 'Hasil Prediksi')

@push('styles')
<style>
    .result-card {
        border-radius: 12px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        border-left: 5px solid #4e73df;
    }
    
    .risk-high {
        border-left-color: #e74a3b !important;
        background: linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%) !important;
    }
    
    .risk-low {
        border-left-color: #1cc88a !important;
        background: linear-gradient(135deg, #f0f9f5 0%, #e6f7ef 100%) !important;
    }
    
    .recommendation-item {
        border-left: 3px solid #4e73df;
        transition: all 0.3s ease;
        background: #fff;
        border-radius: 8px;
        margin-bottom: 0.75rem;
    }
    
    .recommendation-item:hover {
        transform: translateX(5px);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
    }
    
    .data-item {
        background: #f8f9fc;
        border-radius: 8px;
        padding: 12px 15px;
        transition: all 0.2s ease;
        margin-bottom: 0.75rem;
    }
    
    .data-item:hover {
        background: #f1f5ff;
        transform: translateY(-2px);
    }
    
    .progress {
        height: 10px;
        border-radius: 5px;
    }
    
    .status-icon-container {
        background: rgba(78, 115, 223, 0.1);
        border-radius: 50%;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }
    
    .status-icon {
        font-size: 2.5rem;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
</style>
@endpush

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Status Card -->
            <div class="card result-card mb-4" id="resultCard">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-4">
                                    <div class="display-4 fw-bold" id="predictionStatus">-</div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-speedometer2 me-1"></i>
                                            <span id="bmiValue">-</span> BMI
                                            <span class="badge ms-2 rounded-pill" id="bmiCategory">-</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="status-icon-container">
                                <i class="bi bi-check-circle-fill text-primary status-icon" id="statusIcon"></i>
                            </div>
                            <p class="mb-0 small text-muted" id="statusMessage">Hasil prediksi siap</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Data -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">
                        <i class="bi bi-person-lines-fill text-primary me-2"></i>Data Diri
                    </h5>
                    <div class="row g-3" id="userData">
                        <!-- Data will be filled by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Recommendations -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">
                        <i class="bi bi-lightbulb-fill text-warning me-2"></i>Rekomendasi
                    </h5>
                    <div class="row g-3" id="recommendationList">
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="spinner-border spinner-border-sm text-primary me-3" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <div>
                                    <p class="mb-0 small">Memproses rekomendasi...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('prediksi') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <button onclick="window.print()" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-printer me-1"></i> Cetak Hasil
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get result data from the server
        const resultData = @json($result ?? []);
        
        if (Object.keys(resultData).length > 0) {
            updateResultUI(resultData);
        } else {
            // If no data, redirect back to prediction form
            window.location.href = "{{ route('prediksi') }}";
        }
    });

    function updateResultUI(result) {
        const resultCard = document.getElementById('resultCard');
        const predictionStatus = document.getElementById('predictionStatus');
        
        const bmiValue = document.getElementById('bmiValue');
        const bmiCategory = document.getElementById('bmiCategory');
        const statusIcon = document.getElementById('statusIcon');
        const statusMessage = document.getElementById('statusMessage');
        const statusIconContainer = document.querySelector('.status-icon-container');
        const recommendationList = document.getElementById('recommendationList');
        const userDataContainer = document.getElementById('userData');

        // Update risk status
        if (result.prediction === 1) {
            // High risk
            resultCard.classList.add('risk-high');
            resultCard.classList.remove('risk-low');
            predictionStatus.innerHTML = `
                <span class="badge bg-danger bg-opacity-10 text-danger p-2 px-3 d-inline-flex align-items-center rounded-pill">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <span>Berisiko Tinggi</span>
                </span>
            `;
            statusIcon.className = 'bi bi-exclamation-triangle-fill text-danger status-icon';
            statusMessage.textContent = 'Perlu perhatian khusus';
            statusIconContainer.style.background = 'rgba(231, 74, 59, 0.1)';
        } else {
            // Low risk
            resultCard.classList.add('risk-low');
            resultCard.classList.remove('risk-high');
            predictionStatus.innerHTML = `
                <span class="badge bg-success bg-opacity-10 text-success p-2 px-3 d-inline-flex align-items-center rounded-pill">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <span>Tidak Berisiko</span>
                </span>
            `;
            statusIcon.className = 'bi bi-check-circle-fill text-success status-icon';
            statusMessage.textContent = 'Kondisi baik';
            statusIconContainer.style.background = 'rgba(40, 167, 69, 0.1)';
        }

        // Confidence UI removed

        // Update BMI
        const bmi = parseFloat(result.bmi) || 0;
        bmiValue.textContent = bmi.toFixed(1);
        
        // Set BMI category
        let bmiCategoryText = '';
        let bmiClass = '';
        if (bmi < 18.5) {
            bmiCategoryText = 'Kurus';
            bmiClass = 'bg-warning text-dark';
        } else if (bmi < 25) {
            bmiCategoryText = 'Normal';
            bmiClass = 'bg-success';
        } else if (bmi < 30) {
            bmiCategoryText = 'Gemuk';
            bmiClass = 'bg-warning text-dark';
        } else {
            bmiCategoryText = 'Obesitas';
            bmiClass = 'bg-danger';
        }
        bmiCategory.textContent = bmiCategoryText;
        bmiCategory.className = `badge ${bmiClass} rounded-pill`;

        // Add lifestyle warning when BMI is underweight or obese even if prediction is low risk
        if (result.prediction === 0 && (bmi < 18.5 || bmi >= 30)) {
            statusMessage.textContent = 'Kondisi baik - Perlu perbaikan pola hidup';
        }

        // Update user data
        const userData = result.user_data || {};
        const userDataHTML = `
            <div class="col-md-6">
                <div class="data-item animate-fade-in" style="animation-delay: 0.2s">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Usia</span>
                        <span class="fw-medium">${userData.usia ? userData.usia + ' tahun' : '-'}</span>
                    </div>
                </div>
                <div class="data-item animate-fade-in" style="animation-delay: 0.3s">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Jenis Kelamin</span>
                        <span class="fw-medium">${userData.jenis_kelamin || '-'}</span>
                    </div>
                </div>
                <div class="data-item animate-fade-in" style="animation-delay: 0.4s">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Keturunan</span>
                        <span class="fw-medium">${userData.keturunan || '-'}</span>
                    </div>
                </div>
                <div class="data-item animate-fade-in" style="animation-delay: 0.5s">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Merokok</span>
                        <span class="fw-medium">${userData.merokok || '-'}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="data-item animate-fade-in" style="animation-delay: 0.2s">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Berat Badan</span>
                        <span class="fw-medium">${userData.berat_badan ? userData.berat_badan + ' kg' : '-'}</span>
                    </div>
                </div>
                <div class="data-item animate-fade-in" style="animation-delay: 0.3s">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Tinggi Badan</span>
                        <span class="fw-medium">${userData.tinggi_badan ? userData.tinggi_badan + ' cm' : '-'}</span>
                    </div>
                </div>
                <div class="data-item animate-fade-in" style="animation-delay: 0.4s">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Gula Darah</span>
                        <span class="fw-medium">${userData.gula_darah ? userData.gula_darah + ' mg/dL' : '-'}</span>
                    </div>
                </div>
                <div class="data-item animate-fade-in" style="animation-delay: 0.5s">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Tekanan Darah</span>
                        <span class="fw-medium">${userData.tekanan_darah ? userData.tekanan_darah + ' mmHg' : '-'}</span>
                    </div>
                </div>
                <div class="data-item animate-fade-in" style="animation-delay: 0.6s">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Hemoglobin</span>
                        <span class="fw-medium">${userData.hemoglobin ? userData.hemoglobin + ' g/dL' : '-'}</span>
                    </div>
                </div>
                <div class="data-item animate-fade-in" style="animation-delay: 0.7s">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Pola Tidur</span>
                        <span class="fw-medium">${userData.pola_tidur || '-'}</span>
                    </div>
                </div>
                <div class="data-item animate-fade-in" style="animation-delay: 0.8s">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Pola Makan</span>
                        <span class="fw-medium">${userData.pola_makan || '-'}</span>
                    </div>
                </div>
            </div>
        `;
        userDataContainer.innerHTML = userDataHTML;

        // Update recommendations
        const recommendations = result.rekomendasi || ['Tidak ada rekomendasi tersedia'];
        
        const recommendationIcons = result.prediction === 1 ? [
            'exclamation-triangle', 'heart-pulse', 'hospital', 'clipboard2-pulse', 'capsule'
        ] : [
            'check-circle', 'heart', 'activity', 'clipboard-check', 'emoji-smile'
        ];
        
        recommendationList.innerHTML = recommendations.map((rec, index) => {
            const icon = recommendationIcons[Math.min(index, recommendationIcons.length - 1)];
            const iconClass = result.prediction === 1 ? 'text-danger' : 'text-success';
            return `
                <div class="col-12 animate-fade-in" style="animation-delay: ${0.4 + (index * 0.1)}s">
                    <div class="recommendation-item p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-${icon} ${iconClass} me-3"></i>
                            </div>
                            <div>
                                <p class="mb-0">${rec}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }
</script>
@endpush
