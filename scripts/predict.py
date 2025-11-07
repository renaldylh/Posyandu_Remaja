import sys
import json
import joblib
import os
import numpy as np
import warnings
from sklearn.exceptions import InconsistentVersionWarning

# Suppress scikit-learn version warnings
warnings.filterwarnings('ignore', category=InconsistentVersionWarning)
warnings.filterwarnings('ignore', category=UserWarning)

def load_model():
    # Gunakan path absolut ke model
    model_path = r'c:\xampp\htdocs\Posyandu_Remaja\storage\app\models\model_pipeline_with_meta.joblib'
    
    # Pastikan file ada
    if not os.path.exists(model_path):
        return None, f"File model tidak ditemukan di: {model_path}"
    
    try:
        # Muat model dengan metadata
        with warnings.catch_warnings():
            warnings.simplefilter('ignore')
            model_data = joblib.load(model_path)
        return model_data, None
    except Exception as e:
        return None, f"Gagal memuat model: {str(e)}"

def predict(input_data):
    try:
        # Muat model dan metadata
        model_data, error = load_model()
        if error:
            return {"success": False, "error": error, "message": "Gagal memuat model"}
        
        # Daftar field yang dibutuhkan dari form
        required_fields = [
            'jenis_kelamin', 'usia', 'berat_badan', 'tinggi_badan',
            'tekanan_darah', 'hemoglobin', 'gula_darah', 'keturunan', 'merokok'
        ]
        
        # Cek field yang hilang
        missing_fields = [field for field in required_fields if field not in input_data]
        if missing_fields:
            error_msg = f"Data tidak lengkap. Mohon isi semua field yang diperlukan."
            return {"success": False, "error": error_msg, "missing_fields": missing_fields}
        
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
                print(f"Warning: Could not parse blood pressure value: {input_data['tekanan_darah']}, using default: {blood_pressure}")
            
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
            
            try:
                # Get model components
                model = model_data.get('model')
                scaler = model_data.get('scaler')
                encoders = model_data.get('encoders', {})
                
                if not all([model, scaler]):
                    return {
                        "success": False,
                        "error": "Model tidak valid",
                        "message": "Komponen model tidak lengkap"
                    }
                
                # Prepare input data in the correct order
                encoded_data = []
                feature_order = ['Gender', 'Age', 'Glucose', 'BMI', 'Family History', 'Smoking Status', 'Blood Pressure']
                
                for feature in feature_order:
                    if feature in encoders and feature in input_features:
                        try:
                            # Handle categorical features
                            encoded_value = encoders[feature].transform([input_features[feature]])[0]
                        except Exception as e:
                            # Fallback to simple encoding if transformation fails
                            encoded_value = 1 if input_features[feature] == 'Yes' else 0
                    else:
                        # For numerical features
                        encoded_value = input_features[feature]
                    encoded_data.append(encoded_value)
                
                # Convert to numpy array and reshape
                input_array = np.array(encoded_data, dtype=float).reshape(1, -1)
                
                # Make prediction with error handling
                with warnings.catch_warnings():
                    warnings.simplefilter('ignore')
                    
                    # Scale the input data
                    try:
                        input_scaled = scaler.transform(input_array)
                    except Exception as e:
                        return {
                            "success": False,
                            "error": "Gagal memproses data",
                            "message": "Terjadi kesalahan saat memproses data input"
                        }
                    
                    # Make prediction
                    try:
                        prediction = int(model.predict(input_scaled)[0])
                        
                        # Get prediction confidence
                        confidence = 100.0
                        if hasattr(model, 'predict_proba'):
                            try:
                                proba = model.predict_proba(input_scaled)[0]
                                confidence = round(max(proba) * 100, 2)
                            except:
                                pass
                        
                        # Determine status and recommendations
                        status = "Berisiko" if prediction == 1 else "Tidak Berisiko"
                        
                        rekomendasi = [
                            "Pertahankan pola hidup sehat",
                            "Lakukan aktivitas fisik secara teratur",
                            "Konsumsi makanan bergizi seimbang"
                        ]
                        
                        if prediction == 1:
                            rekomendasi = [
                                "Segera konsultasikan dengan tenaga medis",
                                "Lakukan pemeriksaan kesehatan rutin",
                                "Ikuti saran dan pengobatan dari dokter"
                            ]
                        
                        return {
                            "success": True,
                            "prediction": prediction,
                            "status": status,
                            "confidence": confidence,
                            "bmi": round(bmi, 1),
                            "rekomendasi": rekomendasi
                        }
                        
                    except Exception as e:
                        return {
                            "success": False,
                            "error": "Gagal melakukan prediksi",
                            "message": "Terjadi kesalahan saat memproses prediksi"
                        }
                        
            except Exception as e:
                return {
                    "success": False,
                    "error": "Kesalahan sistem",
                    "message": "Terjadi kesalahan saat memproses permintaan"
                }
                
            except Exception as e:
                return {"success": False, "error": f"Gagal melakukan prediksi: {str(e)}"}
            
        except (ValueError, TypeError) as e:
            return {"success": False, "error": f"Error konversi data: {str(e)}"}
        
    except Exception as e:
        return {"success": False, "error": f"Terjadi kesalahan: {str(e)}"}

def main():
    try:
        # Baca input dari file yang diberikan sebagai argumen
        input_str = '{}'
        if len(sys.argv) > 1:
            try:
                # Baca file sebagai binary untuk menangani BOM
                with open(sys.argv[1], 'rb') as f:
                    content = f.read()
                    # Hapus BOM jika ada
                    if content.startswith(b'\xef\xbb\xbf'):
                        content = content[3:]
                    input_str = content.decode('utf-8').strip()
            except Exception as e:
                print(json.dumps({
                    "success": False,
                    "error": f"Error reading input file: {str(e)}"
                }))
                return
        
        # Parse input JSON
        try:
            # Hapus karakter non-ASCII dan karakter kontrol
            input_str_clean = ''.join(char for char in input_str if 32 <= ord(char) <= 126 or char in '\n\r\t')
            input_data = json.loads(input_str_clean)
        except json.JSONDecodeError as e:
            print(json.dumps({
                "success": False,
                "error": f"Invalid JSON input: {str(e)}"
            }))
            return
        
        # Lakukan prediksi
        result = predict(input_data)
        
        # Pastikan hasil adalah dictionary
        if not isinstance(result, dict):
            result = {
                "success": False,
                "error": f"Unexpected result type: {type(result).__name__}"
            }
        
        # Hanya print hasil JSON ke stdout
        print(json.dumps(result))
        
    except Exception as e:
        print(json.dumps({
            "success": False,
            "error": f"Unexpected error: {str(e)}"
        }))
        sys.exit(1)

if __name__ == "__main__":
    main()
