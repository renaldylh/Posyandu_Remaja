import joblib
import numpy as np
from sklearn.linear_model import LogisticRegression
from sklearn.preprocessing import StandardScaler
from sklearn.pipeline import make_pipeline

# Data dummy untuk contoh
# Format: [berat_badan, tinggi_badan, tekanan_darah, hemoglobin]
X = np.array([
    [50, 160, 120, 13.5],  # Tidak berisiko
    [65, 170, 130, 12.0],  # Berisiko
    [55, 165, 125, 14.0],  # Tidak berisiko
    [70, 175, 140, 11.5],  # Berisiko
])

# Label (0 = Tidak Berisiko, 1 = Berisiko)
y = np.array([0, 1, 0, 1])

# Buat dan latih model
model = make_pipeline(
    StandardScaler(),
    LogisticRegression(max_iter=1000, random_state=42)
)
model.fit(X, y)

# Simpan model dan scaler ke model_terbaru.joblib (sesuai dengan predict.py)
model_path = r'c:\xampp\htdocs\Posyandu_Remaja\storage\app\models\model_terbaru.joblib'
joblib.dump(model, model_path)
print(f"Model berhasil dilatih dan disimpan ke: {model_path}")
