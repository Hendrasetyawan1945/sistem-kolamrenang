@extends('layouts.admin')
@section('content')

<!-- Header -->
<div style="background:linear-gradient(135deg,#d32f2f,#b71c1c);color:white;padding:22px 28px;border-radius:12px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;">
    <div>
        <a href="{{ route('admin.rapor', ['bulan'=>$bulan,'tahun'=>$tahun]) }}" style="color:rgba(255,255,255,.7);font-size:12px;text-decoration:none;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Rapor
        </a>
        <h1 style="margin:6px 0 4px 0;font-size:22px;font-weight:700;">Rapor: {{ $siswa->nama }}</h1>
        <p style="margin:0;opacity:.85;font-size:13px;">
            <i class="fas fa-chalkboard"></i> {{ $siswa->kelas }} &nbsp;|&nbsp;
            <i class="fas fa-calendar"></i> {{ $namaBulan[$bulan] }} {{ $tahun }}
        </p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.rapor', ['bulan'=>$bulan,'tahun'=>$tahun]) }}" style="background:rgba(255,255,255,.2);color:white;padding:8px 16px;border-radius:8px;text-decoration:none;font-size:13px;">
            <i class="fas fa-list"></i> Semua Siswa
        </a>
    </div>
</div>

@if(session('success'))
<div style="background:#d4edda;color:#155724;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #c3e6cb;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 300px;gap:22px;align-items:start;">

    <!-- Form Rapor -->
    <div style="background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.08);overflow:hidden;">
        <div style="padding:16px 22px;border-bottom:1px solid #f0f0f0;background:#fafafa;">
            <h3 style="margin:0;font-size:15px;font-weight:600;"><i class="fas fa-file-alt" style="color:#d32f2f;"></i> Isi Rapor</h3>
        </div>
        <form method="POST" action="{{ $rapor ? route('admin.rapor.update', $rapor) : route('admin.rapor.store') }}" style="padding:22px;display:grid;gap:18px;">
            @csrf
            @if($rapor) @method('PUT') @endif
            <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
            <input type="hidden" name="bulan" value="{{ $bulan }}">
            <input type="hidden" name="tahun" value="{{ $tahun }}">

            <!-- Pilih Template -->
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Template Rapor</label>
                <select name="template_rapor_id" id="templateSelect" onchange="loadTemplate(this)"
                    style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                    <option value="">-- Tanpa Template (Isi Manual) --</option>
                    @foreach($templates as $t)
                    <option value="{{ $t->id }}" data-komponen="{{ json_encode($t->komponen) }}"
                        {{ $rapor && $rapor->template_rapor_id == $t->id ? 'selected' : '' }}>
                        {{ $t->nama_template }} ({{ $t->kelas }})
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Kehadiran -->
            <div style="background:#f8f9fa;border-radius:8px;padding:16px;">
                <div style="font-size:13px;font-weight:700;color:#333;margin-bottom:12px;"><i class="fas fa-calendar-check" style="color:#4caf50;"></i> Kehadiran</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Jumlah Hadir</label>
                        <input type="number" name="kehadiran" value="{{ $rapor->kehadiran ?? $hadir }}" min="0"
                            style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Total Pertemuan</label>
                        <input type="number" name="total_pertemuan" value="{{ $rapor->total_pertemuan ?? $totalPertemuan }}" min="0"
                            style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                    </div>
                </div>
                @if($totalPertemuan > 0)
                <div style="margin-top:8px;font-size:12px;color:#666;">
                    <i class="fas fa-info-circle"></i> Data dari absensi: {{ $hadir }}/{{ $totalPertemuan }} pertemuan
                </div>
                @endif
            </div>

            <!-- Komponen Penilaian -->
            <div>
                <div style="font-size:13px;font-weight:700;color:#333;margin-bottom:12px;"><i class="fas fa-star" style="color:#ffc107;"></i> Komponen Penilaian</div>
                <div id="komponenContainer" style="display:grid;gap:10px;">
                    @if($rapor && $rapor->nilai)
                        @foreach($rapor->nilai as $i => $komp)
                        <div class="komp-row" style="display:grid;grid-template-columns:2fr 1fr 2fr;gap:10px;align-items:center;background:#f8f9fa;padding:10px;border-radius:8px;">
                            <div>
                                <input type="text" name="komponen_nama[]" value="{{ $komp['nama'] }}" placeholder="Nama komponen"
                                    style="width:100%;padding:7px 9px;border:1px solid #ddd;border-radius:6px;font-size:12px;box-sizing:border-box;">
                            </div>
                            <div>
                                <input type="number" name="komponen_nilai[]" value="{{ $komp['nilai'] }}" min="0" max="100" placeholder="Nilai"
                                    style="width:100%;padding:7px 9px;border:1px solid #ddd;border-radius:6px;font-size:12px;box-sizing:border-box;text-align:center;">
                            </div>
                            <div style="display:flex;gap:6px;">
                                <input type="text" name="komponen_keterangan[]" value="{{ $komp['keterangan'] ?? '' }}" placeholder="Keterangan"
                                    style="flex:1;padding:7px 9px;border:1px solid #ddd;border-radius:6px;font-size:12px;box-sizing:border-box;">
                                <button type="button" onclick="this.closest('.komp-row').remove()"
                                    style="background:#ffebee;color:#f44336;border:none;padding:7px 9px;border-radius:6px;cursor:pointer;font-size:11px;">✕</button>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div style="color:#999;font-size:13px;text-align:center;padding:15px;">
                        Pilih template atau tambah komponen manual
                    </div>
                    @endif
                </div>
                <button type="button" onclick="tambahKomponen()"
                    style="margin-top:10px;background:#e3f2fd;color:#1565c0;border:none;padding:7px 14px;border-radius:6px;font-size:12px;cursor:pointer;">
                    <i class="fas fa-plus"></i> Tambah Komponen
                </button>
            </div>

            <!-- Catatan Coach -->
            <div>
                <label style="display:block;font-size:13px;font-weight:700;margin-bottom:5px;"><i class="fas fa-comment" style="color:#2196f3;"></i> Catatan Coach</label>
                <textarea name="catatan_coach" rows="3" placeholder="Perkembangan siswa, hal yang perlu ditingkatkan, dll..."
                    style="width:100%;padding:9px 11px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;resize:vertical;">{{ $rapor->catatan_coach ?? '' }}</textarea>
            </div>

            <!-- Catatan Umum -->
            <div>
                <label style="display:block;font-size:13px;font-weight:700;margin-bottom:5px;"><i class="fas fa-sticky-note" style="color:#ff9800;"></i> Catatan Umum / Rekomendasi</label>
                <textarea name="catatan_umum" rows="2" placeholder="Rekomendasi untuk orang tua, target bulan depan, dll..."
                    style="width:100%;padding:9px 11px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;resize:vertical;">{{ $rapor->catatan_umum ?? '' }}</textarea>
            </div>

            <!-- Status -->
            <div style="display:flex;gap:12px;align-items:center;">
                <label style="font-size:13px;font-weight:600;">Status:</label>
                <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:8px 14px;border:2px solid #ddd;border-radius:8px;">
                    <input type="radio" name="status" value="draft" {{ (!$rapor || $rapor->status=='draft')?'checked':'' }}>
                    <span style="font-size:13px;">Draft</span>
                </label>
                <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:8px 14px;border:2px solid #ddd;border-radius:8px;">
                    <input type="radio" name="status" value="selesai" {{ ($rapor && $rapor->status=='selesai')?'checked':'' }}>
                    <span style="font-size:13px;font-weight:600;color:#2e7d32;">Selesai ✓</span>
                </label>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:8px;border-top:1px solid #f0f0f0;">
                <a href="{{ route('admin.rapor', ['bulan'=>$bulan,'tahun'=>$tahun]) }}"
                    style="padding:10px 20px;border:1px solid #ddd;background:white;border-radius:8px;font-size:14px;text-decoration:none;color:#666;">
                    Batal
                </a>
                <button type="submit" name="status" value="draft"
                    style="padding:10px 20px;background:#ff9800;color:white;border:none;border-radius:8px;font-size:14px;cursor:pointer;">
                    <i class="fas fa-save"></i> Simpan Draft
                </button>
                <button type="submit" name="status" value="selesai"
                    style="padding:10px 20px;background:#4caf50;color:white;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
                    <i class="fas fa-check"></i> Selesaikan Rapor
                </button>
            </div>
        </form>
    </div>

    <!-- Panel Info Siswa -->
    <div style="position:sticky;top:20px;display:grid;gap:15px;">

        <!-- Info Siswa -->
        <div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
            <div style="padding:14px 18px;border-bottom:1px solid #f0f0f0;background:#fafafa;">
                <h3 style="margin:0;font-size:14px;font-weight:600;"><i class="fas fa-user" style="color:#d32f2f;"></i> Info Siswa</h3>
            </div>
            <div style="padding:14px 18px;font-size:13px;">
                <div style="margin-bottom:8px;"><strong>Nama:</strong> {{ $siswa->nama }}</div>
                <div style="margin-bottom:8px;"><strong>Kelas:</strong> {{ $siswa->kelas }}</div>
                <div style="margin-bottom:8px;"><strong>Paket:</strong> {{ $siswa->paket }}</div>
                <div><strong>Ortu:</strong> {{ $siswa->nama_ortu }}</div>
            </div>
        </div>

        <!-- Personal Best -->
        <div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
            <div style="padding:14px 18px;border-bottom:1px solid #f0f0f0;background:#fafafa;">
                <h3 style="margin:0;font-size:14px;font-weight:600;"><i class="fas fa-trophy" style="color:#ffc107;"></i> Personal Best</h3>
            </div>
            <div style="padding:10px 14px;">
                @forelse($personalBests as $pb)
                <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #f5f5f5;font-size:12px;">
                    <span style="color:#555;">{{ $pb->nomor_lomba }} ({{ $pb->jenis_kolam }})</span>
                    <strong style="color:#d32f2f;">{{ $pb->best_waktu }}</strong>
                </div>
                @empty
                <p style="color:#999;font-size:12px;text-align:center;padding:10px 0;">Belum ada catatan waktu</p>
                @endforelse
            </div>
        </div>

        <!-- Kehadiran Bulan Ini -->
        <div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
            <div style="padding:14px 18px;border-bottom:1px solid #f0f0f0;background:#fafafa;">
                <h3 style="margin:0;font-size:14px;font-weight:600;"><i class="fas fa-calendar-check" style="color:#4caf50;"></i> Kehadiran {{ $namaBulan[$bulan] }}</h3>
            </div>
            <div style="padding:16px 18px;text-align:center;">
                @php $pct = $totalPertemuan > 0 ? ($hadir/$totalPertemuan*100) : 0; @endphp
                <div style="font-size:32px;font-weight:700;color:{{ $pct>=80?'#2e7d32':($pct>=60?'#e65100':'#c62828') }};">
                    {{ $hadir }}/{{ $totalPertemuan }}
                </div>
                <div style="font-size:12px;color:#999;margin-bottom:8px;">pertemuan</div>
                <div style="background:#f0f0f0;border-radius:6px;height:8px;overflow:hidden;">
                    <div style="background:{{ $pct>=80?'#4caf50':($pct>=60?'#ff9800':'#f44336') }};height:8px;width:{{ $pct }}%;"></div>
                </div>
                <div style="font-size:12px;color:#666;margin-top:5px;">{{ number_format($pct,0) }}% kehadiran</div>
            </div>
        </div>
    </div>
</div>

<script>
const templateData = {};
@foreach($templates as $t)
templateData[{{ $t->id }}] = @json($t->komponen);
@endforeach

function loadTemplate(sel) {
    const id = sel.value;
    if (!id || !templateData[id]) return;
    const container = document.getElementById('komponenContainer');
    container.innerHTML = '';
    templateData[id].forEach(k => addKomponenRow(k.nama, '', k.keterangan || ''));
}

function tambahKomponen() { addKomponenRow('', '', ''); }

function addKomponenRow(nama, nilai, ket) {
    const container = document.getElementById('komponenContainer');
    const div = document.createElement('div');
    div.className = 'komp-row';
    div.style.cssText = 'display:grid;grid-template-columns:2fr 1fr 2fr;gap:10px;align-items:center;background:#f8f9fa;padding:10px;border-radius:8px;';
    div.innerHTML = `
        <input type="text" name="komponen_nama[]" value="${nama}" placeholder="Nama komponen"
            style="width:100%;padding:7px 9px;border:1px solid #ddd;border-radius:6px;font-size:12px;box-sizing:border-box;">
        <input type="number" name="komponen_nilai[]" value="${nilai}" min="0" max="100" placeholder="Nilai (0-100)"
            style="width:100%;padding:7px 9px;border:1px solid #ddd;border-radius:6px;font-size:12px;box-sizing:border-box;text-align:center;">
        <div style="display:flex;gap:6px;">
            <input type="text" name="komponen_keterangan[]" value="${ket}" placeholder="Keterangan"
                style="flex:1;padding:7px 9px;border:1px solid #ddd;border-radius:6px;font-size:12px;box-sizing:border-box;">
            <button type="button" onclick="this.closest('.komp-row').remove()"
                style="background:#ffebee;color:#f44336;border:none;padding:7px 9px;border-radius:6px;cursor:pointer;font-size:11px;">✕</button>
        </div>
    `;
    container.appendChild(div);
}
</script>
@endsection
