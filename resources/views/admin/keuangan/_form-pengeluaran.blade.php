<div style="display:grid;gap:14px;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Tanggal <span style="color:red">*</span></label>
            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Kategori <span style="color:red">*</span></label>
            <select name="kategori" required style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                <option value="">Pilih...</option>
                <option value="operasional">Operasional</option>
                <option value="gaji">Gaji / Honor</option>
                <option value="peralatan">Peralatan</option>
                <option value="maintenance">Maintenance</option>
                <option value="event">Event / Kejuaraan</option>
                <option value="administrasi">Administrasi</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>
    </div>
    <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Keterangan <span style="color:red">*</span></label>
        <input type="text" name="keterangan" required placeholder="Contoh: Bayar listrik kolam renang bulan April"
            style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Jumlah (Rp) <span style="color:red">*</span></label>
            <input type="number" name="jumlah" required min="1" placeholder="500000"
                style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Metode Pembayaran</label>
            <select name="metode_pembayaran" style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                <option value="">Pilih...</option>
                <option value="Tunai">Tunai</option>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="QRIS">QRIS</option>
                <option value="Kartu Debit">Kartu Debit</option>
            </select>
        </div>
    </div>
</div>
