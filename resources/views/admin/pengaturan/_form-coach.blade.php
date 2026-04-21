<div style="display:grid;gap:14px;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Nama Coach <span style="color:red">*</span></label>
            <input type="text" name="nama" required placeholder="Nama lengkap"
                style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Status <span style="color:red">*</span></label>
            <select name="status" required style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                <option value="aktif">Aktif</option>
                <option value="cuti">Cuti</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>
    </div>
    <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Spesialisasi</label>
        <input type="text" name="spesialisasi" placeholder="Contoh: Freestyle, Backstroke"
            style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Pengalaman</label>
            <input type="text" name="pengalaman" placeholder="Contoh: 5 tahun"
                style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">No. Telepon</label>
            <input type="text" name="telepon" placeholder="08xxxxxxxxxx"
                style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
        </div>
    </div>
    <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Email</label>
        <input type="email" name="email" placeholder="coach@email.com"
            style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
    </div>
    <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Bio / Catatan</label>
        <textarea name="bio" rows="2" placeholder="Latar belakang, sertifikasi, dll..."
            style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;resize:vertical;"></textarea>
    </div>
</div>
