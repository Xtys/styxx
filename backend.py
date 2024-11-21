from flask import Flask, request, jsonify, send_file
import os
import subprocess
import zipfile
import requests
import tempfile
import shutil

app = Flask(__name__)

# Paths for VPK Creator
VPK_CREATOR_PATH = os.path.join(os.getcwd(), "VPK CREATOR")
VPK_EXECUTABLE = os.path.join(VPK_CREATOR_PATH, "vpk.exe")

@app.route('/compile-vpk', methods=['POST'])
def compile_vpk():
    try:
        # Get the mod URLs from the request
        mod_urls = request.json.get('mods', [])
        if not mod_urls:
            return jsonify({"error": "No mods provided"}), 400

        # Create a unique temporary directory for this request
        with tempfile.TemporaryDirectory() as temp_dir:
            pak_dir = os.path.join(temp_dir, "pak01_dir")

            # Ensure pak01_dir is empty
            if os.path.exists(pak_dir):
                shutil.rmtree(pak_dir)  # Remove existing directory
            os.makedirs(pak_dir, exist_ok=True)

            # Download and extract each mod into the temp pak01_dir
            for index, url in enumerate(mod_urls):
                zip_path = os.path.join(temp_dir, f"mod{index}.zip")
                response = requests.get(url, stream=True)
                if response.status_code == 200:
                    with open(zip_path, "wb") as f:
                        for chunk in response.iter_content(chunk_size=8192):
                            f.write(chunk)
                    # Extract the zip file into pak01_dir
                    with zipfile.ZipFile(zip_path, "r") as zip_ref:
                        zip_ref.extractall(pak_dir)
                else:
                    return jsonify({
                        "error": f"Failed to download {url}",
                        "status_code": response.status_code,
                        "reason": response.reason
                    }), 400

            # Compile the VPK using vpk.exe
            result = subprocess.run(
                [VPK_EXECUTABLE, pak_dir],
                cwd=VPK_CREATOR_PATH,
                capture_output=True
            )

            if result.returncode != 0:
                return jsonify({
                    "error": "VPK compilation failed",
                    "stderr": result.stderr.decode(),
                    "stdout": result.stdout.decode()
                }), 500

            # Find the compiled VPK file
            compiled_vpk = os.path.join(VPK_CREATOR_PATH, "pak01_dir.vpk")
            if not os.path.exists(compiled_vpk):
                return jsonify({"error": "Compiled VPK not found"}), 500

            # Send the compiled VPK file back to the user
            return send_file(
                compiled_vpk,
                as_attachment=True,
                download_name="compiled_mods.vpk"
            )

    except Exception as e:
        return jsonify({"error": str(e)}), 500

