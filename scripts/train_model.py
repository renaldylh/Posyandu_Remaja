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

# Simpan model dan scaler
joblib.dump(model, 'storage/app/models/new_model.joblib')
print("Model berhasil dilatih dan disimpan")
