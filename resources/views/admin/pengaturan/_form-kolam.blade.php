<div style="display:grid;gap:14px;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Nama Kolam <span style="color:red">*</span></label>
            <input type="text" name="nama" required placeholder="Contoh: Kolam Utama"
                style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Ukuran <span style="color:red">*</span></label>
            <select name="ukuran" required style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                <option value="25m">25m — Short Course</option>
                <option value="50m">50m — Long Course</option>
            </select>
        </div>
    </div>
    <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Lokasi / Alamat</label>
        <input type="text" name="lokasi" placeholder="Contoh: Gedung Utama Lt. 1 / GBK Jakarta"
            style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
    </div>
    <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Keterangan</label>
        <textarea name="keterangan" rows="2" placeholder="Jumlah lintasan, kondisi kolam, dll..."
            style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;resize:vertical;"></textarea>
    </div>
    @isset($edit)
    <div>
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;font-weight:600;">
            <input type="checkbox" name="aktif" value="1" style="width:16px;height:16px;">
            Kolam Aktif
        </label>
    </div>
    @endisset
</div>
