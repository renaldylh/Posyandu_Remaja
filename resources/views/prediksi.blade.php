@extends('layouts.app')

@section('title', 'Prediksi')

@push('styles')
<style>
    .result-card {
        border-left: 5px solid #198754;
        transition: all 0.3s ease;
    }
    .result-card.risiko-tinggi {
        border-left-color: #dc3545;
    }
    .rekomendasi-list {
        list-style-type: none;
        padding-left: 0;
    }
    .rekomendasi-list li {
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }
    .rekomendasi-list li:last-child {
        border-bottom: none;
    }
    .result-card .card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .modal-header.border-danger {
        border-bottom: 2px solid #dc3545;
    }
    .rekomendasi-list li {
        padding: 0.75rem;
        border-radius: 8px;
        background-color: #f8f9fa;
        transition: all 0.2s ease;
    }
    .rekomendasi-list li:hover {
        background-color: #f1f3f5;
        transform: translateX(5px);
    }
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }
    .progress-bar {
        border-radius: 10px;
    }
    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
    }
</style>
@endpush

@section('content')

<div class="container my-5">
    <h3 class="text-center mb-4 fw-bold">Masukan Data</h3>
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <div class="fw-bold mb-1">Terjadi kesalahan validasi:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('predict') }}" method="POST" class="p-4 rounded shadow-sm" style="border: 1px solid #ccc; border-radius: 12px;">
        @csrf
        <div class="row g-4">

            <!-- Kolom kiri -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select class="form-select rounded-pill" name="jenis_kelamin" required>
                        <option value="" disabled hidden {{ old('jenis_kelamin') ? '' : 'selected' }}>Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin')=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin')=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Usia (Tahun)</label>
                    <input type="number" name="usia" class="form-control rounded-pill" min="0" max="120" value="{{ old('usia') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Berat Badan (Kg)</label>
                    <input type="number" name="berat_badan" class="form-control rounded-pill" value="{{ old('berat_badan') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tinggi Badan (Cm)</label>
                    <input type="number" name="tinggi_badan" id="tinggi_badan" class="form-control rounded-pill" value="{{ old('tinggi_badan') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tekanan Darah (mmHg)</label>
                    <input type="text" name="tekanan_darah" class="form-control rounded-pill" placeholder="120 atau 120/80" value="{{ old('tekanan_darah') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hemoglobin (gram/dL)</label>
                    <input type="number" step="0.1" name="hemoglobin" class="form-control rounded-pill" value="{{ old('hemoglobin') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gula Darah (mg/dL)</label>
                    <input type="number" name="gula_darah" class="form-control rounded-pill" value="{{ old('gula_darah') }}" required>
                </div>
            </div>

            <!-- Kolom kanan -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Keturunan</label>
                    <select class="form-select rounded-pill" name="keturunan" required>
                        <option value="" disabled hidden {{ old('keturunan') ? '' : 'selected' }}>Pilih</option>
                        <option value="Tidak" {{ old('keturunan')=='Tidak' ? 'selected' : '' }}>Tidak</option>
                        <option value="Ya" {{ old('keturunan')=='Ya' ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pola Tidur</label>
                    <small class="text-muted">(Begadang atau Tidak)</small>
                    <select class="form-select rounded-pill" name="pola_tidur" required>
                        <option value="" disabled hidden {{ old('pola_tidur') ? '' : 'selected' }}>Pilih</option>
                        <option value="Tidak" {{ old('pola_tidur')=='Tidak' ? 'selected' : '' }}>Tidak</option>
                        <option value="Ya" {{ old('pola_tidur')=='Ya' ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pola Makan</label>
                    <small class="text-muted">(Seimbang antara Protein, Karbohidrat, Zat basi, Vitamin dan yang lain-nya)</small>
                    <select class="form-select rounded-pill" name="pola_makan" required>
                        <option value="" disabled hidden {{ old('pola_makan') ? '' : 'selected' }}>Pilih</option>
                        <option value="Tidak" {{ old('pola_makan')=='Tidak' ? 'selected' : '' }}>Tidak</option>
                        <option value="Ya" {{ old('pola_makan')=='Ya' ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Merokok</label>
                    <select class="form-select rounded-pill" name="merokok" required>
                        <option value="" disabled hidden {{ old('merokok') ? '' : 'selected' }}>Pilih</option>
                        <option value="Tidak" {{ old('merokok')=='Tidak' ? 'selected' : '' }}>Tidak</option>
                        <option value="Ya" {{ old('merokok')=='Ya' ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">BMI (Body Mass Index)</label>
                    <input type="text" name="bmi" id="bmi_result" class="form-control rounded-pill bg-light" readonly>
                    <small class="text-muted">Berat Badan (kg) / (Tinggi Badan (m))²</small>
                </div>
            </div>
        </div>

        <!-- Tombol -->
        <div class="d-flex justify-content-end mt-4">
            <a href="/" class="btn btn-light me-2 px-4 rounded-pill">Batal</a>
            <button type="submit" class="btn btn-success px-4 rounded-pill">
                <span class="submit-text">Periksa</span>
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            </button>
        </div>
    </form>
</div>

<!-- Modal Hasil Prediksi -->
<div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="resultModalLabel">
                    <i class="bi bi-clipboard2-pulse me-2"></i>Hasil Pemeriksaan Kesehatan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Status Utama -->
                <div class="card result-card mb-4 border-0 shadow-sm" id="resultCard">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="card-title fw-bold mb-3">
                                    <i class="bi bi-heart-pulse me-2"></i>Status Kesehatan
                                </h5>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        <div class="display-4 fw-bold" id="predictionStatus">-</div>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Tingkat Keyakinan</div>
                                        <div class="h4 mb-0" id="confidenceLevel">-</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="me-4">
                                        <div class="text-muted small">Indeks Massa Tubuh</div>
                                        <div class="h5 mb-0">
                                            <span id="bmiValue" class="fw-bold">-</span>
                                            <small class="text-muted ms-1">kg/m²</small>
                                            <span id="bmiCategory" class="badge ms-2"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="border rounded p-3 bg-light">
                                    <i class="bi bi-info-circle-fill text-primary fs-1"></i>
                                    <p class="small mt-2 mb-0">Hasil ini berdasarkan analisis data yang Anda berikan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Pengguna -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="card-title fw-bold mb-3">
                            <i class="bi bi-person-lines-fill me-2"></i>Data Anda
                        </h6>
                        <div id="userData" class="row g-3">
                            <!-- Data akan diisi oleh JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Rekomendasi -->
                <div class="card border-0 shadow-sm mb-0">
                    <div class="card-body p-4">
                        <h6 class="card-title fw-bold mb-3">
                            <i class="bi bi-lightbulb-fill text-warning me-2"></i>Rekomendasi
                        </h6>
                        <ul class="list-unstyled m-0" id="recommendationList">
                            <li class="d-flex align-items-center mb-2">
                                <i class="bi bi-arrow-right-circle-fill text-primary me-2"></i>
                                <span>Sedang memproses rekomendasi...</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i> Tutup
                </button>
                <a href="{{ route('prediksi') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-repeat me-1"></i> Cek Lagi
                </a>
            </div>
        </div>
    </div>
</div>



@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const beratBadanInput = document.querySelector('input[name="berat_badan"]');
        const tinggiBadanInput = document.getElementById('tinggi_badan');
        const bmiResult = document.getElementById('bmi_result');
        const tekananInput = document.querySelector('input[name="tekanan_darah"]');
        const resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
        const submitButton = document.querySelector('button[type="submit"]');
        const submitText = submitButton.querySelector('.submit-text');
        const spinner = submitButton.querySelector('.spinner-border');

        function calculateBMI() {
            const berat = parseFloat(beratBadanInput.value);
            const tinggiCm = parseFloat(tinggiBadanInput.value);
            
            if (berat && tinggiCm) {
                const tinggiMeter = tinggiCm / 100;
                const bmi = (berat / (tinggiMeter * tinggiMeter)).toFixed(1);
                bmiResult.value = bmi;
                return bmi;
            } else {
                bmiResult.value = '';
                return null;
            }
        }

        // Add event listeners
        beratBadanInput.addEventListener('input', calculateBMI);
        tinggiBadanInput.addEventListener('input', calculateBMI);

        // Format number with thousands separator
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        // Input mask for blood pressure: allow "123" or "123/45"
        tekananInput.addEventListener('input', function(e) {
            let v = e.target.value.replace(/[^0-9\/]/g, '');
            // Only one slash allowed
            const parts = v.split('/');
            if (parts.length > 2) {
                v = parts[0] + '/' + parts[1];
            }
            // Limit lengths: systolic up to 3 digits, diastolic up to 3 digits
            const [sys, dia] = v.split('/');
            let sysTrim = (sys || '').slice(0,3);
            let diaTrim = typeof dia !== 'undefined' ? dia.slice(0,3) : undefined;
            e.target.value = typeof diaTrim === 'undefined' ? sysTrim : sysTrim + '/' + diaTrim;
        });

        // Remove AJAX submit: allow normal form POST to server which renders hasil_prediksi view
    });
</script>
@endpush
