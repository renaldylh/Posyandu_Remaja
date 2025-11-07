import joblib
import sys

try:
    # Load the model
    model_path = r'c:\xampp\htdocs\Posyandu_Remaja\storage\app\models\model_pipeline_with_meta.joblib'
    model = joblib.load(model_path)
    
    # Check if model has feature names
    if hasattr(model, 'feature_names_in_'):
        features = list(model.feature_names_in_)
        print(f"Model uses {len(features)} features:")
        for i, feature in enumerate(features, 1):
            print(f"{i}. {feature}")
    elif hasattr(model, 'steps') and hasattr(model.steps[0][1], 'get_feature_names_out'):
        # If it's a pipeline with a preprocessor that has feature names
        features = list(model.steps[0][1].get_feature_names_out())
        print(f"Model uses {len(features)} features:")
        for i, feature in enumerate(features, 1):
            print(f"{i}. {feature}")
    else:
        # Try to get the number of features the model expects
        if hasattr(model, 'n_features_in_'):
            print(f"Model expects {model.n_features_in_} features, but feature names are not available.")
        else:
            print("Could not determine the number of features. Model structure:")
            print(model)
            
    # Print model type and attributes for debugging
    print("\nModel type:", type(model).__name__)
    if hasattr(model, 'steps'):
        print("\nPipeline steps:")
        for name, step in model.steps:
            print(f"- {name}: {type(step).__name__}")
            
except Exception as e:
    print(f"Error: {str(e)}", file=sys.stderr)
    sys.exit(1)
