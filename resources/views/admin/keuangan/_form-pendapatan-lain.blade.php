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
                <option value="penjualan_jersey">Penjualan Jersey</option>
                <option value="sewa_kolam">Sewa Kolam</option>
                <option value="sponsor">Sponsor</option>
                <option value="donasi">Donasi</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>
    </div>
    <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Deskripsi <span style="color:red">*</span></label>
        <input type="text" name="deskripsi" required placeholder="Contoh: Penjualan jersey batch April 2026"
            style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Sumber / Dari</label>
            <input type="text" name="sumber" placeholder="Nama orang/instansi"
                style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Jumlah (Rp) <span style="color:red">*</span></label>
            <input type="number" name="jumlah" required min="1" placeholder="500000"
                style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
        </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Metode Pembayaran</label>
            <select name="metode_pembayaran" style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                <option value="">Pilih...</option>
                <option value="Tunai">Tunai</option>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="QRIS">QRIS</option>
                <option value="GoPay">GoPay</option>
                <option value="OVO">OVO</option>
            </select>
        </div>
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Status <span style="color:red">*</span></label>
            <select name="status" required style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                <option value="diterima">Diterima</option>
                <option value="pending">Pending</option>
            </select>
        </div>
    </div>
    <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Keterangan</label>
        <textarea name="keterangan" rows="2" placeholder="Catatan tambahan..."
            style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;resize:vertical;"></textarea>
    </div>
</div>
