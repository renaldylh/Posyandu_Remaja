import sys
import json
import joblib
import os
import numpy as np
import warnings
from sklearn.exceptions import InconsistentVersionWarning

# Fungsi untuk debug print (mengarah ke stderr)
def debug_print(*args, **kwargs):
    print(*args, file=sys.stderr, **kwargs)

# Suppress scikit-learn version warnings
warnings.filterwarnings('ignore', category=InconsistentVersionWarning)
warnings.filterwarnings('ignore', category=UserWarning)

def load_model():
    # Gunakan path absolut ke model terbaru
    model_path = r'c:\xampp\htdocs\Posyandu_Remaja\storage\app\models\model_terbaru.joblib'
    
    # Pastikan file ada
    if not os.path.exists(model_path):
        return None, f"File model tidak ditemukan di: {model_path}"
    
    try:
        debug_print("Memuat model dari:", model_path)  # Debug
        # Muat model dengan metadata
        with warnings.catch_warnings():
            warnings.simplefilter('ignore')
            model_data = joblib.load(model_path)
            
        # Debug: Cek isi model_data
        if hasattr(model_data, '__dict__'):
            debug_print("Model memiliki atribut:", model_data.__dict__.keys())
        else:
            debug_print("Model tipe:", type(model_data))
            
        return model_data, None
    except Exception as e:
        error_msg = f"Gagal memuat model: {str(e)}"
        print(error_msg)  # Debug
        return None, error_msg

def predict(input_data):
    """Fungsi untuk melakukan prediksi menggunakan model machine learning.
    
    Args:
        input_data (dict): Dictionary berisi data input untuk prediksi
        
    Returns:
        dict: Hasil prediksi atau pesan error
    """
    # Inisialisasi variabel
    model = None
    model_data = None
    error = None
    
    # Fungsi debug_print sudah dipindahkan ke atas file
    
    # 1. Muat model
    try:
        model_data, error = load_model()
        if error or model_data is None:
            raise Exception(error or "Gagal memuat model: Tidak ada data model yang dikembalikan")
            
        # Jika model_data adalah model langsung (bukan dictionary)
        if not isinstance(model_data, dict):
            model = model_data
            model_data = {'model': model}
        else:
            model = model_data.get('model')
        
        if model is None:
            raise ValueError("Model tidak valid atau tidak ditemukan dalam data model")
            
        debug_print("Model berhasil dimuat. Tipe:", type(model))
            
    except Exception as e:
        error_msg = f"Kesalahan saat memuat model: {str(e)}"
        debug_print(error_msg)
        return {
            "success": False, 
            "error": error_msg, 
            "message": "Gagal memuat model"
        }
    
    # 2. Validasi input data
    required_fields = [
        'jenis_kelamin', 'usia', 'berat_badan', 'tinggi_badan',
        'tekanan_darah', 'hemoglobin', 'gula_darah', 'keturunan', 'merokok'
    ]
    
    # Cek field yang hilang
    missing_fields = [field for field in required_fields if field not in input_data]
    if missing_fields:
        error_msg = "Data tidak lengkap. Mohon isi semua field yang diperlukan."
        return {
            "success": False, 
            "error": error_msg, 
            "missing_fields": missing_fields,
            "message": error_msg
        }
    
    # 3. Proses prediksi
    try:
        # Konversi input ke tipe yang benar
        try:
            # Hitung BMI
            tinggi_m = float(input_data['tinggi_badan']) / 100
            bmi = float(input_data['berat_badan']) / (tinggi_m ** 2)
            
            # Pastikan nilai dalam rentang yang masuk akal
            if not (10 <= bmi <= 100):
                raise ValueError("Nilai BMI tidak valid")
                
        except (ValueError, ZeroDivisionError) as e:
            return {
                "success": False,
                "error": "Data tinggi atau berat badan tidak valid",
                "message": "Pastikan tinggi dan berat badan diisi dengan benar"
            }
        
        # Mapping jenis kelamin
        gender_mapping = {
            'Laki-laki': 'Male',
            'Perempuan': 'Female'
        }
        
        # Handle blood pressure input (could be a number or 'systolic/diastolic' string)
        try:
            if isinstance(input_data['tekanan_darah'], str) and '/' in input_data['tekanan_darah']:
                # If it's in 'systolic/diastolic' format, take the systolic value
                blood_pressure = float(input_data['tekanan_darah'].split('/')[0])
            else:
                # If it's already a number or a string number, convert to float directly
                blood_pressure = float(input_data['tekanan_darah'])
        except (ValueError, TypeError):
            # If conversion fails, use a default value and log a warning
            blood_pressure = 120.0  # Default normal systolic pressure
            debug_print(f"Warning: Could not parse blood pressure value: {input_data['tekanan_darah']}, using default: {blood_pressure}")
        
        # Validasi dan konversi input
        try:
            input_features = {
                'Gender': gender_mapping.get(input_data['jenis_kelamin'], input_data['jenis_kelamin']),
                'Age': max(0, min(120, float(input_data['usia']))),  # Batasi usia 0-120
                'Glucose': max(0, float(input_data['gula_darah'])),
                'BMI': bmi,
                'Family History': 'Yes' if input_data['keturunan'] == 'Ya' else 'No',
                'Smoking Status': 'Yes' if input_data['merokok'] == 'Ya' else 'No',
                # Gunakan sistolik yang sudah diparse dari input (blood_pressure)
                'Blood Pressure': max(0, float(blood_pressure))
            }
        except (ValueError, TypeError) as e:
            return {
                "success": False,
                "error": "Format data tidak valid",
                "message": "Pastikan semua data diisi dengan angka yang benar"
            }
        
        # 3.1 Lakukan prediksi
        debug_print("Melakukan prediksi...")
        
        # Check untuk gula darah >140 - auto risiko
        glucose_level = float(input_data.get('gula_darah', 0))
        keturunan = input_data.get('keturunan', 'Tidak')
        
        # Auto risiko jika gula darah >140
        if glucose_level > 140:
            debug_print(f"Gula darah tinggi terdeteksi: {glucose_level} > 140 - auto risiko")
            return {
                "success": True,
                "prediction": 1,
                "confidence": 95.0,
                "status": "Berisiko",
                "rekomendasi": [
                    "Segera konsultasi dengan dokter untuk pemeriksaan lebih lanjut",
                    "Kurangi konsumsi gula dan karbohidrat sederhana",
                    "Lakukan monitoring gula darah secara teratur",
                    "Tingkatkan aktivitas fisik dan jaga berat badan ideal"
                ],
                "bmi": round(bmi, 1) if 'bmi' in locals() else None,
                "warning": "Gula darah tinggi (>140 mg/dL) terdeteksi"
            }
        
        # Auto risiko jika gula darah <140 tapi ada keturunan diabetes
        if glucose_level <= 140 and keturunan == 'Ya':
            debug_print(f"Gula darah normal ({glucose_level}) tapi ada keturunan diabetes - auto risiko")
            return {
                "success": True,
                "prediction": 1,
                "confidence": 85.0,
                "status": "Berisiko",
                "rekomendasi": [
                    "Lakukan pemeriksaan gula darah rutin karena faktor keturunan",
                    "Pertahankan pola makan sehat dan hindari gula berlebih",
                    "Tingkatkan aktivitas fisik untuk mencegah diabetes",
                    "Monitor berat badan dan jaga BMI normal"
                ],
                "bmi": round(bmi, 1) if 'bmi' in locals() else None,
                "warning": "Risiko tinggi karena faktor keturunan diabetes"
            }
        
        if not hasattr(model, 'predict'):
            raise AttributeError("Model tidak memiliki method 'predict'")
        
        # Dapatkan komponen model
        scaler = model_data.get('scaler', None)
        encoders = model_data.get('encoders', {})
        
        debug_print(f"Model: {type(model).__name__}")  # Debug
        debug_print(f"Scaler: {type(scaler).__name__ if scaler else 'Tidak ada'}")  # Debug
        debug_print(f"Jumlah encoder: {len(encoders)}")  # Debug
        
        if not all([model, scaler]):
            return {
                "success": False,
                "error": "Model tidak valid",
                "message": "Komponen model tidak lengkap"
            }
        
        # Siapkan data input sesuai urutan yang dibutuhkan
        debug_print("Mempersiapkan data input...")  # Debug
        encoded_data = []
        
        # Daftar fitur yang dibutuhkan (sesuai dengan model terbaru)
        feature_order = [
            'Gender', 'Age', 'Glucose', 'BMI', 
            'Family History', 'Smoking Status', 'Blood Pressure',
            'Hemoglobin'  # Menambahkan Hemoglobin yang dibutuhkan model
        ]
        
        # Pastikan semua fitur ada dalam input_data
        for feature in feature_order:
            if feature.lower() not in input_data:
                debug_print(f"Peringatan: Fitur {feature} tidak ditemukan dalam input")
        debug_print(f"Urutan fitur yang diharapkan: {feature_order}")  # Debug
        
        # Mapping nama fitur dari input ke format yang diharapkan model
        feature_mapping = {
            'Gender': input_data.get('jenis_kelamin', 'Laki-laki'),
            'Age': input_data.get('usia', 0),
            'Glucose': input_data.get('gula_darah', 0),
            'BMI': input_data.get('berat_badan', 0) / ((input_data.get('tinggi_badan', 1) / 100) ** 2),
            'Family History': input_data.get('keturunan', 'Tidak'),
            'Smoking Status': input_data.get('merokok', 'Tidak'),
            'Blood Pressure': input_data.get('tekanan_darah', 120),
            'Hemoglobin': input_data.get('hemoglobin', 0)  # Pastikan hemoglobin ada di input
        }
        
        # Konversi ke format numerik
        for feature in feature_order:
            value = feature_mapping[feature]
            
            # Handle categorical features
            if feature in encoders:
                try:
                    encoded_value = encoders[feature].transform([str(value)])[0]
                except Exception as e:
                    # Fallback ke encoding sederhana
                    encoded_value = 1 if str(value).lower() in ['ya', 'yes', '1', 'true'] else 0
            else:
                # Untuk fitur numerik
                try:
                    encoded_value = float(value)
                except (ValueError, TypeError):
                    encoded_value = 0.0  # Nilai default jika konversi gagal
            
            encoded_data.append(encoded_value)
            debug_print(f"{feature}: {value} -> {encoded_value}")  # Debug
        
        # Konversi ke numpy array dan reshape
        try:
            input_array = np.array(encoded_data, dtype=float).reshape(1, -1)
            debug_print(f"Input array shape: {input_array.shape}")  # Debug
            debug_print(f"Input array values: {input_array}")  # Debug
        except Exception as e:
            error_msg = f"Gagal membuat array input: {str(e)}"
            debug_print(error_msg)  # Debug
            return {
                "success": False,
                "error": "Gagal memproses data",
                "message": error_msg,
                "encoded_data": str(encoded_data)
            }
        
        # Proses scaling jika ada scaler
        try:
            if scaler is not None:
                debug_print("Melakukan scaling data...")  # Debug
                input_scaled = scaler.transform(input_array)
                debug_print(f"Data setelah scaling: {input_scaled}")  # Debug
            else:
                debug_print("Tidak ada scaler, menggunakan data asli")  # Debug
                input_scaled = input_array
        except Exception as e:
            error_msg = f"Gagal melakukan scaling data: {str(e)}"
            debug_print(error_msg)  # Debug
            return {
                "success": False,
                "error": "Gagal memproses data",
                "message": error_msg,
                "input_shape": str(input_array.shape) if 'input_array' in locals() else 'Tidak diketahui',
                "scaler_type": type(scaler).__name__ if scaler else 'Tidak ada'
            }
        
        prediction = model.predict(input_scaled)
        debug_print(f"Hasil prediksi mentah: {prediction}")
        
        # 3.2 Konversi hasil prediksi
        try:
            if hasattr(prediction, 'item'):
                prediction = int(prediction.item())
            elif hasattr(prediction, '__getitem__') and len(prediction) > 0:
                prediction = int(prediction[0])
            else:
                prediction = int(prediction)
        except (TypeError, ValueError, IndexError) as e:
            debug_print(f"Peringatan: Gagal mengkonversi prediksi ke integer: {e}")
            prediction = 0  # Nilai default jika konversi gagal
        
        debug_print(f"Hasil prediksi: {prediction}")  # Debug
        
        # Hitung confidence score
        confidence = 100.0
        if hasattr(model, 'predict_proba'):
            try:
                proba = model.predict_proba(input_scaled)
                debug_print(f"Probabilitas prediksi: {proba}")  # Debug
                if hasattr(proba, 'shape') and len(proba.shape) > 1 and proba.shape[1] > 1:
                    confidence = round(float(max(proba[0])) * 100, 2)
                else:
                    confidence = round(float(proba[0][1]) * 100, 2) if len(proba[0]) > 1 else 100.0
            except (TypeError, ValueError, IndexError) as e:
                debug_print(f"Peringatan: Gagal mengkonversi probabilitas prediksi: {e}")
                confidence = 50.0  # Nilai default jika konversi gagal
        
        # Tentukan status dan rekomendasi
        status = "Berisiko" if prediction == 1 else "Tidak Berisiko"
        
        rekomendasi = [
            "Pertahankan pola hidup sehat",
            "Lakukan aktivitas fisik secara teratur",
            "Konsumsi makanan bergizi seimbang"
        ]
        
        # Return hasil prediksi
        return {
            "success": True,
            "prediction": prediction,
            "confidence": confidence,
            "status": status,
            "rekomendasi": rekomendasi,
            "bmi": round(bmi, 1) if 'bmi' in locals() else None
        }
    
    except Exception as e:
        return {
            "success": False,
            "error": "Gagal melakukan prediksi",
            "message": str(e)
        }

def main():
    # Simpan stderr asli
    old_stderr = None
    devnull = None
    
    try:
        # Baca input dari argumen command line
        input_data = {}

        if len(sys.argv) > 1:
            try:
                # Coba baca input sebagai JSON langsung dari argumen
                input_data = json.loads(sys.argv[1])
            except json.JSONDecodeError:
                try:
                    # Jika gagal, coba baca dari file
                    with open(sys.argv[1], 'rb') as f:
                        content = f.read()
                        # Hapus BOM jika ada
                        if content.startswith(b'\xef\xbb\xbf'):
                            content = content[3:]
                        input_data = json.loads(content.decode('utf-8').strip())
                except Exception as e:
                    result = {
                        "success": False,
                        "error": "Gagal memproses input",
                        "message": f"Format input tidak valid: {str(e)}"
                    }
                    print(json.dumps(result))
                    sys.exit(1)
        
        # Pastikan input_data adalah dictionary
        if not isinstance(input_data, dict):
            result = {
                "success": False,
                "error": "Format input tidak valid",
                "message": "Input harus berupa dictionary JSON"
            }
            print(json.dumps(result))
            sys.exit(1)

        # Redirect stderr ke /dev/null untuk menyembunyikan warning
        old_stderr = os.dup(sys.stderr.fileno())
        devnull = os.open(os.devnull, os.O_WRONLY)
        os.dup2(devnull, sys.stderr.fileno())
        
        # Panggil fungsi prediksi
        result = predict(input_data)
        
        # Pastikan result adalah dictionary
        if not isinstance(result, dict):
            result = {
                "success": False,
                "error": "Format output tidak valid",
                "message": "Fungsi predict tidak mengembalikan dictionary"
            }
        
        # Konversi numpy types ke native Python types jika diperlukan
        def convert_numpy(obj):
            if hasattr(obj, 'item'):
                return obj.item()
            elif hasattr(obj, 'tolist'):
                return obj.tolist()
            elif isinstance(obj, dict):
                return {k: convert_numpy(v) for k, v in obj.items()}
            elif isinstance(obj, (list, tuple)):
                return [convert_numpy(x) for x in obj]
            return obj
        
        # Output hasil
        result = convert_numpy(result)
        
    except Exception as e:
        result = {
            "success": False,
            "error": "Kesalahan sistem",
            "message": f"Terjadi kesalahan: {str(e)}"
        }
    
    finally:
        # Kembalikan stderr ke kondisi semula
        if old_stderr is not None and devnull is not None:
            os.dup2(old_stderr, sys.stderr.fileno())
            os.close(devnull)
    
    # Hanya print hasil akhir (JSON)
    print(json.dumps(result, ensure_ascii=False))

if __name__ == "__main__":
    main()
