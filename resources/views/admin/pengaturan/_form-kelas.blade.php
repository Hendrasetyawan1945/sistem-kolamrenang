<div style="display:grid;gap:14px;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Nama Kelas <span style="color:red">*</span></label>
            <input type="text" name="nama_kelas" required placeholder="Contoh: KU-12"
                style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Level <span style="color:red">*</span></label>
            <select name="level" required style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                <option value="">Pilih Level...</option>
                <option value="pemula">Pemula</option>
                <option value="menengah">Menengah</option>
                <option value="lanjut">Lanjut</option>
                <option value="prestasi">Prestasi</option>
            </select>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Kapasitas Siswa <span style="color:red">*</span></label>
            <input type="number" name="kapasitas" required min="1" placeholder="15"
                style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Harga per Bulan (Rp) <span style="color:red">*</span></label>
            <input type="number" name="harga" required min="0" step="10000" placeholder="500000"
                style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
        </div>
    </div>
    <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Coach Penanggung Jawab</label>
        <select name="coach_id" style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
            <option value="">-- Pilih Coach --</option>
            @foreach($coaches as $c)
            <option value="{{ $c->id }}">{{ $c->nama }} — {{ $c->spesialisasi ?? 'Umum' }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Jadwal Latihan</label>
        <div id="jadwalContainer" style="display:grid;gap:8px;">
            <div class="jadwal-row" style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:8px;align-items:center;">
                <select name="jadwal_hari[]" style="padding:7px 8px;border:1px solid #ddd;border-radius:6px;font-size:12px;">
                    <option value="">Pilih Hari</option>
                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h)
                    <option value="{{ $h }}">{{ $h }}</option>
                    @endforeach
                </select>
                <input type="time" name="jadwal_jam_mulai[]" value="07:00" style="padding:7px 8px;border:1px solid #ddd;border-radius:6px;font-size:12px;">
                <input type="time" name="jadwal_jam_selesai[]" value="09:00" style="padding:7px 8px;border:1px solid #ddd;border-radius:6px;font-size:12px;">
                <button type="button" onclick="removeJadwal(this)" style="background:#ffebee;color:#f44336;border:none;padding:7px 10px;border-radius:6px;cursor:pointer;font-size:12px;">✕</button>
            </div>
        </div>
        <button type="button" onclick="addJadwal()" style="margin-top:8px;background:#e3f2fd;color:#1565c0;border:none;padding:6px 12px;border-radius:6px;font-size:12px;cursor:pointer;">
            + Tambah Jadwal
        </button>
    </div>
    <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Deskripsi</label>
        <textarea name="deskripsi" rows="2" placeholder="Deskripsi kelas, usia target, dll..."
            style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;resize:vertical;"></textarea>
    </div>
    @isset($edit)
    <div>
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;font-weight:600;">
            <input type="checkbox" name="aktif" value="1" style="width:16px;height:16px;">
            Kelas Aktif
        </label>
    </div>
    @endisset
</div>

<script>
function addJadwal() {
    const container = document.getElementById('jadwalContainer');
    const row = document.createElement('div');
    row.className = 'jadwal-row';
    row.style.cssText = 'display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:8px;align-items:center;';
    row.innerHTML = `
        <select name="jadwal_hari[]" style="padding:7px 8px;border:1px solid #ddd;border-radius:6px;font-size:12px;">
            <option value="">Pilih Hari</option>
            ${['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'].map(h => `<option value="${h}">${h}</option>`).join('')}
        </select>
        <input type="time" name="jadwal_jam_mulai[]" value="07:00" style="padding:7px 8px;border:1px solid #ddd;border-radius:6px;font-size:12px;">
        <input type="time" name="jadwal_jam_selesai[]" value="09:00" style="padding:7px 8px;border:1px solid #ddd;border-radius:6px;font-size:12px;">
        <button type="button" onclick="removeJadwal(this)" style="background:#ffebee;color:#f44336;border:none;padding:7px 10px;border-radius:6px;cursor:pointer;font-size:12px;">✕</button>
    `;
    container.appendChild(row);
}

function removeJadwal(btn) {
    const rows = document.querySelectorAll('.jadwal-row');
    if (rows.length > 1) btn.closest('.jadwal-row').remove();
}
</script>
