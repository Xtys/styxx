from flask import Flask, jsonify, request, send_file
import os
import zipfile
import io

app = Flask(__name__)

# Base URL where mods are stored
BASE_MOD_URL = "http://styxmod.infinityfreeapp.com/"

# List of available mods
MODS = [
    {"name": "Ako Tamaki v2.3", "file": "Ako_Tamaki_v2.3.zip"},
    {"name": "Kurisu Makise v1.3", "file": "Kurisu_Makise_v1.3.zip"},
    {"name": "Misaka Mikoto v2.3", "file": "Misaka_Mikoto_v2.3.zip"},
    {"name": "Nagisa Shiota v1.1", "file": "Nagisa_Shiota_v1.1.zip"},
    # Add more mods as needed
]

@app.route('/mods', methods=['GET'])
def list_mods():
    """
    Return a list of available mods.
    """
    return jsonify(MODS)

@app.route('/download', methods=['POST'])
def download_mods():
    """
    Receive a list of mods, fetch their files, and return them as a ZIP archive.
    """
    data = request.get_json()
    selected_mods = data.get('mods', [])

    if not selected_mods:
        return jsonify({"error": "No mods selected"}), 400

    # Create a ZIP file in memory
    zip_buffer = io.BytesIO()
    with zipfile.ZipFile(zip_buffer, 'w', zipfile.ZIP_DEFLATED) as zip_file:
        for mod_file in selected_mods:
            # Fetch the mod file from the base URL
            mod_url = os.path.join(BASE_MOD_URL, mod_file)
            response = requests.get(mod_url)
            if response.status_code == 200:
                # Add the file to the ZIP archive
                zip_file.writestr(mod_file, response.content)
            else:
                return jsonify({"error": f"Failed to fetch {
::contentReference[oaicite:0]{index=0}
 
