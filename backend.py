from flask import Flask, request, jsonify, send_file
import os
import subprocess
import zipfile
import requests

app = Flask(__name__)

# Define paths
VPK_CREATOR_PATH = "./VPK CREATOR"
VPK_EXECUTABLE = os.path.join(VPK_CREATOR_PATH, "vpk.exe")
PAK_DIR = os.path.join(VPK_CREATOR_PATH, "pak01_dir")

@app.route('/compile-vpk', methods=['POST'])
def compile_vpk():
    try:
        # Get the mod URLs from the request
        mod_urls = request.json.get('mods', [])
        if not mod_urls:
            return jsonify({"error": "No mods provided"}), 400

        # Ensure pak01_dir is clean
        if os.path.exists(PAK_DIR):
            for file in os.listdir(PAK_DIR):
                file_path = os.path.join(PAK_DIR, file)
                if os.path.isfile(file_path):
                    os.unlink(file_path)

        # Download and extract each mod
        for index, url in enumerate(mod_urls):
            zip_path = os.path.join(VPK_CREATOR_PATH, f"mod{index}.zip")
            response = requests.get(url, stream=True)
            with open(zip_path, "wb") as f:
                for chunk in response.iter_content(chunk_size=8192):
                    f.write(chunk)
            with zipfile.ZipFile(zip_path, 'r') as zip_ref:
                zip_ref.extractall(PAK_DIR)

        # Run the VPK compiler
        subprocess.run([VPK_EXECUTABLE, "pak01_dir"], cwd=VPK_CREATOR_PATH, check=True)

        # Return the compiled VPK file
        vpk_path = os.path.join(VPK_CREATOR_PATH, "pak01_dir.vpk")
        return send_file(vpk_path, as_attachment=True)

    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
