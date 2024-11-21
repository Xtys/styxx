from flask import Flask, jsonify, request, redirect

app = Flask(__name__)

# Replace with your InfinityFree or file storage URL
BASE_MOD_URL = "http://styxmod.infinityfreeapp.com/"

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
    Return a list of mods.
    """
    return jsonify(MODS)

@app.route('/download', methods=['GET'])
def download_mod():
    """
    Redirect to the file URL on InfinityFree.
    """
    mod_name = request.args.get('mod_name')
    mod = next((m for m in MODS if m["name"] == mod_name), None)
    if mod:
        return redirect(BASE_MOD_URL + mod["file"])
    return jsonify({"error": "Mod not found"}), 404

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
