{{-- Component untuk form rapor yang konsisten antara admin dan coach --}}
@props([
    'siswa',
    'rapor' => null,
    'templates' => collect(),
    'hadir' => 0,
    'totalPertemuan' => 0,
    'bulan',
    'tahun',
    'namaBulan' => [],
    'userRole' => 'admin',
    'personalBest' => null,
    'actionRoute',
    'method' => 'POST'
])

<div style="display:grid;grid-template-columns:1fr 300px;gap:22px;align-items:start;">
    <!-- Form Rapor -->
    <div style="background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.08);overflow:hidden;">
        <div style="padding:16px 22px;border-bottom:1px solid #f0f0f0;background:#fafafa;">
            <h3 style="margin:0;font-size:15px;font-weight:600;">
                <i class="fas fa-file-alt" style="color:#d32f2f;"></i> 
                {{ $rapor ? 'Edit' : 'Isi' }} Rapor - {{ $siswa->nama }}
            </h3>
            <p style="margin:4px 0 0 0;font-size:12px;color:#666;">
                <i class="fas fa-chalkboard"></i> {{ $siswa->kelas }} &nbsp;|&nbsp;
                <i class="fas fa-calendar"></i> {{ $namaBulan[$bulan] ?? 'Bulan '.$bulan }} {{ $tahun }}
            </p>
        </div>
        
        <form method="POST" action="{{ $actionRoute }}" style="padding:22px;display:grid;gap:18px;">
            @csrf
            @if($method === 'PUT') @method('PUT') @endif
            
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
                <div style="font-size:13px;font-weight:700;color:#333;margin-bottom:12px;">
                    <i class="fas fa-calendar-check" style="color:#4caf50;"></i> Kehadiran
                </div>
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
            <div style="background:#f8f9fa;border-radius:8px;padding:16px;">
                <div style="font-size:13px;font-weight:700;color:#333;margin-bottom:12px;">
                    <i class="fas fa-star" style="color:#ff9800;"></i> Komponen Penilaian
                </div>
                <div id="komponenContainer">
                    @if($rapor && $rapor->nilai)
                        @foreach($rapor->nilai as $index => $nilai)
                        <div class="komponen-item" style="background:white;border-radius:6px;padding:12px;margin-bottom:8px;border:1px solid #e0e0e0;">
                            <div style="display:grid;grid-template-columns:2fr 1fr auto;gap:8px;align-items:center;">
                                <input type="text" name="nilai[{{ $index }}][nama]" placeholder="Nama komponen" 
                                    value="{{ $nilai['nama'] ?? '' }}"
                                    style="padding:6px 8px;border:1px solid #ddd;border-radius:4px;font-size:12px;">
                                <input type="number" name="nilai[{{ $index }}][nilai]" placeholder="Nilai" min="0" max="100" 
                                    value="{{ $nilai['nilai'] ?? '' }}"
                                    style="padding:6px 8px;border:1px solid #ddd;border-radius:4px;font-size:12px;">
                                <button type="button" onclick="removeKomponen(this)" 
                                    style="background:#f44336;color:white;border:none;padding:6px 8px;border-radius:4px;font-size:11px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <textarea name="nilai[{{ $index }}][keterangan]" placeholder="Keterangan (opsional)" 
                                style="width:100%;margin-top:8px;padding:6px 8px;border:1px solid #ddd;border-radius:4px;font-size:12px;resize:vertical;height:50px;">{{ $nilai['keterangan'] ?? '' }}</textarea>
                        </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" onclick="addKomponen()" 
                    style="background:#4caf50;color:white;border:none;padding:8px 12px;border-radius:6px;font-size:12px;">
                    <i class="fas fa-plus"></i> Tambah Komponen
                </button>
            </div>

            <!-- Catatan -->
            <div style="display:grid;gap:12px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">
                        <i class="fas fa-comment" style="color:#2196f3;"></i> Catatan {{ $userRole === 'admin' ? 'Coach' : 'Pelatih' }}
                    </label>
                    <textarea name="catatan_coach" placeholder="Catatan dari pelatih untuk siswa..."
                        style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;font-size:13px;resize:vertical;height:80px;">{{ $rapor->catatan_coach ?? '' }}</textarea>
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">
                        <i class="fas fa-clipboard" style="color:#9c27b0;"></i> Catatan Umum
                    </label>
                    <textarea name="catatan_umum" placeholder="Catatan umum atau saran pengembangan..."
                        style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;font-size:13px;resize:vertical;height:80px;">{{ $rapor->catatan_umum ?? '' }}</textarea>
                </div>
            </div>

            <!-- Status dan Tombol -->
            <div style="display:flex;justify-content:space-between;align-items:center;padding-top:12px;border-top:1px solid #f0f0f0;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Status Rapor</label>
                    <select name="status" style="padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                        <option value="draft" {{ ($rapor->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="selesai" {{ ($rapor->status ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div style="display:flex;gap:8px;">
                    <a href="{{ $userRole === 'admin' ? route('admin.rapor', ['bulan'=>$bulan,'tahun'=>$tahun]) : route('coach.rapor.index', ['bulan'=>$bulan]) }}" 
                        style="background:#6c757d;color:white;padding:10px 16px;border-radius:6px;text-decoration:none;font-size:13px;">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" 
                        style="background:#d32f2f;color:white;border:none;padding:10px 16px;border-radius:6px;font-size:13px;">
                        <i class="fas fa-save"></i> {{ $rapor ? 'Update' : 'Simpan' }} Rapor
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Sidebar Info Siswa -->
    <div style="display:grid;gap:16px;">
        <!-- Info Siswa -->
        <div style="background:white;border-radius:10px;padding:16px;box-shadow:0 2px 8px rgba(0,0,0,.08);">
            <h4 style="margin:0 0 12px 0;font-size:14px;font-weight:700;color:#d32f2f;">
                <i class="fas fa-user"></i> Info Siswa
            </h4>
            <div style="display:grid;gap:8px;font-size:12px;">
                <div><strong>Nama:</strong> {{ $siswa->nama }}</div>
                <div><strong>Kelas:</strong> {{ $siswa->kelas }}</div>
                <div><strong>Status:</strong> 
                    <span style="background:{{ $siswa->status === 'aktif' ? '#4caf50' : '#ff9800' }};color:white;padding:2px 6px;border-radius:10px;font-size:10px;">
                        {{ ucfirst($siswa->status) }}
                    </span>
                </div>
                @if($siswa->tanggal_lahir)
                <div><strong>Umur:</strong> {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->age }} tahun</div>
                @endif
                @if($siswa->nomor_hp)
                <div><strong>HP:</strong> {{ $siswa->nomor_hp }}</div>
                @endif
            </div>
        </div>

        <!-- Personal Best (jika ada data) -->
        @if($personalBest && $personalBest->count() > 0)
        <div style="background:white;border-radius:10px;padding:16px;box-shadow:0 2px 8px rgba(0,0,0,.08);">
            <h4 style="margin:0 0 12px 0;font-size:14px;font-weight:700;color:#2196f3;">
                <i class="fas fa-trophy"></i> Personal Best
            </h4>
            <div style="display:grid;gap:6px;font-size:11px;">
                @foreach($personalBest->take(5) as $pb)
                <div style="display:flex;justify-content:space-between;padding:4px 0;border-bottom:1px solid #f0f0f0;">
                    <span>{{ $pb->nomor_lomba }}</span>
                    <strong style="color:#d32f2f;">{{ $pb->waktu_terbaik }}</strong>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Tips Pengisian -->
        <div style="background:#e3f2fd;border-radius:10px;padding:16px;border-left:4px solid #2196f3;">
            <h4 style="margin:0 0 8px 0;font-size:13px;font-weight:700;color:#1976d2;">
                <i class="fas fa-lightbulb"></i> Tips Pengisian
            </h4>
            <ul style="margin:0;padding-left:16px;font-size:11px;color:#1565c0;line-height:1.4;">
                <li>Gunakan template untuk konsistensi penilaian</li>
                <li>Nilai 0-100 untuk setiap komponen</li>
                <li>Berikan catatan yang konstruktif</li>
                <li>Simpan sebagai draft untuk melanjutkan nanti</li>
                <li>Status "Selesai" akan terlihat oleh siswa</li>
            </ul>
        </div>
    </div>
</div>

<script>
let komponenIndex = {{ $rapor && $rapor->nilai ? count($rapor->nilai) : 0 }};

function addKomponen() {
    const container = document.getElementById('komponenContainer');
    const div = document.createElement('div');
    div.className = 'komponen-item';
    div.style.cssText = 'background:white;border-radius:6px;padding:12px;margin-bottom:8px;border:1px solid #e0e0e0;';
    div.innerHTML = `
        <div style="display:grid;grid-template-columns:2fr 1fr auto;gap:8px;align-items:center;">
            <input type="text" name="nilai[${komponenIndex}][nama]" placeholder="Nama komponen" 
                style="padding:6px 8px;border:1px solid #ddd;border-radius:4px;font-size:12px;">
            <input type="number" name="nilai[${komponenIndex}][nilai]" placeholder="Nilai" min="0" max="100" 
                style="padding:6px 8px;border:1px solid #ddd;border-radius:4px;font-size:12px;">
            <button type="button" onclick="removeKomponen(this)" 
                style="background:#f44336;color:white;border:none;padding:6px 8px;border-radius:4px;font-size:11px;">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <textarea name="nilai[${komponenIndex}][keterangan]" placeholder="Keterangan (opsional)" 
            style="width:100%;margin-top:8px;padding:6px 8px;border:1px solid #ddd;border-radius:4px;font-size:12px;resize:vertical;height:50px;"></textarea>
    `;
    container.appendChild(div);
    komponenIndex++;
}

function removeKomponen(button) {
    button.closest('.komponen-item').remove();
}

function loadTemplate(select) {
    if (!select.value) return;
    
    const option = select.options[select.selectedIndex];
    const komponen = JSON.parse(option.dataset.komponen || '[]');
    
    // Clear existing components
    document.getElementById('komponenContainer').innerHTML = '';
    komponenIndex = 0;
    
    // Add components from template
    komponen.forEach(k => {
        addKomponen();
        const lastItem = document.querySelector('.komponen-item:last-child');
        lastItem.querySelector('input[name*="[nama]"]').value = k.nama || '';
    });
}
</script>