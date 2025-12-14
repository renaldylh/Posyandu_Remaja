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

    /* ============================================
       PRINT STYLES - Professional PDF Layout
       ============================================ */
    @media print {
        /* Reset page margins and setup */
        @page {
            size: A4;
            margin: 15mm;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        body {
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            background: white;
        }

        /* Hide unnecessary elements for print */
        nav, footer, .btn, .no-print,
        .navbar, .sidebar, .breadcrumb {
            display: none !important;
        }

        /* Container adjustments */
        .container {
            width: 100%;
            max-width: 100%;
            padding: 0;
            margin: 0;
        }

        /* Header for printed document */
        .result-card::before {
            content: "LAPORAN HASIL PREDIKSI RISIKO DIABETES";
            display: block;
            font-size: 18pt;
            font-weight: bold;
            text-align: center;
            padding: 15px 0;
            margin-bottom: 20px;
            border-bottom: 3px solid #333;
            color: #333;
        }

        /* Print metadata */
        .result-card::after {
            content: "Tanggal Cetak: " attr(data-print-date);
            display: block;
            font-size: 9pt;
            text-align: right;
            margin-top: 10px;
            color: #666;
        }

        /* Cards styling for print */
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
            page-break-inside: avoid;
            margin-bottom: 15pt !important;
            border-radius: 0 !important;
        }

        .card-body {
            padding: 15pt !important;
        }

        .card-title {
            font-size: 14pt !important;
            font-weight: bold !important;
            color: #333 !important;
            border-bottom: 2px solid #eee;
            padding-bottom: 8pt;
            margin-bottom: 12pt !important;
        }

        /* Result card special styling */
        .result-card {
            background: white !important;
            border: 2px solid #333 !important;
            margin-bottom: 20pt !important;
        }

        .result-card.risk-high {
            border-color: #dc3545 !important;
            background: #fff5f5 !important;
        }

        .result-card.risk-low {
            border-color: #28a745 !important;
            background: #f0f9f5 !important;
        }

        /* Status badge */
        #predictionStatus .badge {
            border: 2px solid currentColor !important;
            padding: 8pt 15pt !important;
            font-size: 14pt !important;
            font-weight: bold !important;
        }

        .badge.bg-danger {
            background-color: #dc3545 !important;
            color: white !important;
        }

        .badge.bg-success {
            background-color: #28a745 !important;
            color: white !important;
        }

        /* BMI display */
        .badge.bg-light {
            background-color: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
            color: #333 !important;
        }

        .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #000 !important;
        }

        /* Data items */
        .data-item {
            background: #f8f9fa !important;
            border: 1px solid #e9ecef !important;
            border-radius: 4pt !important;
            padding: 8pt 12pt !important;
            margin-bottom: 8pt !important;
        }

        .data-item .text-muted {
            color: #666 !important;
            font-weight: 500;
        }

        .data-item .fw-medium {
            color: #000 !important;
            font-weight: 600 !important;
        }

        /* Recommendations */
        .recommendation-item {
            border: 1px solid #dee2e6 !important;
            border-left: 4px solid #007bff !important;
            background: white !important;
            padding: 10pt !important;
            margin-bottom: 8pt !important;
            page-break-inside: avoid;
        }

        .recommendation-item i {
            font-size: 14pt !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .text-success {
            color: #28a745 !important;
        }

        /* Icon styling */
        .bi {
            font-weight: bold;
        }

        /* Status icon container */
        .status-icon-container {
            width: 60pt !important;
            height: 60pt !important;
            border: 3px solid currentColor;
            border-radius: 50%;
        }

        .status-icon {
            font-size: 30pt !important;
        }

        /* Typography */
        h5, .h5 {
            font-size: 13pt !important;
            font-weight: bold !important;
            color: #333 !important;
        }

        p {
            margin-bottom: 8pt;
        }

        /* Remove animations and transitions */
        * {
            animation: none !important;
            transition: none !important;
        }

        /* Grid layout */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -5pt;
        }

        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 5pt;
        }

        .col-12 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        /* Footer info */
        .card:last-child::after {
            content: "Catatan: Hasil prediksi ini bersifat informatif. Konsultasikan dengan tenaga medis untuk diagnosis yang lebih akurat.";
            display: block;
            font-size: 9pt;
            font-style: italic;
            color: #666;
            text-align: center;
            margin-top: 20pt;
            padding-top: 15pt;
            border-top: 1px solid #ddd;
        }

        /* Page breaks */
        .card {
            page-break-before: auto;
            page-break-after: auto;
        }

        /* Ensure good spacing */
        .mb-4 {
            margin-bottom: 15pt !important;
        }

        .mb-3 {
            margin-bottom: 10pt !important;
        }

        /* Print-specific utilities */
        .print-only {
            display: block !important;
        }

        .no-print {
            display: none !important;
        }
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
                            <!-- Status Prediksi -->
                            <div class="mb-3">
                                <div class="display-6 fw-bold" id="predictionStatus">-</div>
                            </div>
                            <!-- BMI Info -->
                            <div>
                                <span class="badge bg-light text-dark border p-2">
                                    <i class="bi bi-speedometer2 me-1"></i>
                                    <span id="bmiValue">-</span> BMI
                                    <span class="badge ms-2 rounded-pill" id="bmiCategory">-</span>
                                </span>
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

            <!-- Informasi Lebih Lanjut -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">
                        <i class="bi bi-info-circle-fill text-info me-2"></i>Informasi Lebih Lanjut – Kunjungi:
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded h-100">
                                <h6 class="fw-bold text-primary mb-2">
                                    <i class="bi bi-hospital me-2"></i>Puskesmas Pembantu Kuta
                                </h6>
                                <p class="mb-0 small text-muted">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    Alamat: Kuta Lor RT 03/01, Desa Kuta, Kecamatan Belik, Kabupaten Pemalang, Jawa Tengah, Indonesia.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded h-100">
                                <h6 class="fw-bold text-primary mb-2">
                                    <i class="bi bi-hospital me-2"></i>Puskesmas Belik
                                </h6>
                                <p class="mb-0 small text-muted">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    Alamat: Jl. Raya Belik–Watukumpul, Keretan, Belik, Kabupaten Pemalang, Jawa Tengah 52356.
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <p class="mb-0 small text-center text-muted fst-italic">
                                <i class="bi bi-info-circle me-1"></i>
                                Kunjungi langsung untuk mendapatkan informasi layanan, jadwal pelayanan, serta program kesehatan yang tersedia.
                            </p>
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

        // Set print date when print button is clicked
        const printButton = document.querySelector('[onclick="window.print()"]');
        if (printButton) {
            printButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Set current date for print
                const now = new Date();
                const dateStr = now.toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                document.getElementById('resultCard').setAttribute('data-print-date', dateStr);
                
                // Trigger print dialog
                setTimeout(() => window.print(), 100);
            });
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
            // Check if it's low blood sugar risk
            if (result.status === 'Berisiko Gula Darah Rendah') {
                // Low blood sugar risk - use warning/orange color
                resultCard.classList.add('risk-high');
                resultCard.classList.remove('risk-low');
                predictionStatus.innerHTML = `
                    <span class="badge bg-warning bg-opacity-10 text-warning p-2 px-3 d-inline-flex align-items-center rounded-pill">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <span>Berisiko Gula Darah Rendah</span>
                    </span>
                `;
                statusIcon.className = 'bi bi-exclamation-triangle-fill text-warning status-icon';
                statusMessage.textContent = 'Gula darah di bawah normal';
                statusIconContainer.style.background = 'rgba(255, 193, 7, 0.1)';
            } else {
                // High risk (diabetes)
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
            }
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
