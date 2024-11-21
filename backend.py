from flask import Flask, jsonify, request, send_file
from flask_cors import CORS
import os
import zipfile
import io
import requests

app = Flask(__name__)
CORS(app)  # Enable CORS for cross-origin requests

# Base URL where mods are stored
BASE_MOD_URL = "http://styxmod.infinityfreeapp.com"

# List of available mods
MODS = [
    {"name": "Ako Tamaki v2.3", "file": "Ako_Tamaki_v2.3.zip"},
    {"name": "Kurisu Makise v1.3", "file": "Kurisu_Makise_v1.3.zip"},
    {"name": "Misaka Mikoto v2.3", "file": "Misaka_Mikoto_v2.3.zip"},
    {"name": "Nagisa Shiota v1.1", "file": "Nagisa_Shiota_v1.1.zip"},
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
    try:
        data = request.get_json()
        selected_mods = data.get('mods', [])

        if not selected_mods:
            app.logger.error("No mods selected in request")
            return jsonify({"error": "No mods selected"}), 400

        zip_buffer = io.BytesIO()
        with zipfile.ZipFile(zip_buffer, 'w', zipfile.ZIP_DEFLATED) as zip_file:
            for mod_file in selected_mods:
                mod_url = f"{BASE_MOD_URL}/{mod_file}"
                try:
                    response = requests.get(mod_url, timeout=10)
                    response.raise_for_status()
                    zip_file.writestr(mod_file, response.content)
                except requests.exceptions.RequestException as e:
                    app.logger.error(f"Error fetching {mod_file}: {str(e)}")
                    return jsonify({"error": f"Failed to fetch {mod_file}: {str(e)}"}), 400

        zip_buffer.seek(0)
        return send_file(
            zip_buffer,
            mimetype='application/zip',
            as_attachment=True,
            download_name='mods.zip'
        )
    except Exception as e:
        app.logger.error(f"Unexpected error: {str(e)}")
        return jsonify({"error": "An internal server error occurred"}), 500


if __name__ == '__main__':
    app.run(debug=True)
