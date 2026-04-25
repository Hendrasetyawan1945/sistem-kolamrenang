{{-- Component untuk menampilkan rapor yang konsisten --}}
@props([
    'rapor',
    'siswa' => null,
    'showActions' => false,
    'userRole' => 'siswa',
    'namaBulan' => []
])

@php
    $siswa = $siswa ?? $rapor->siswa;
    $nilaiRataRata = 0;
    if ($rapor->nilai && count($rapor->nilai) > 0) {
        $totalNilai = collect($rapor->nilai)->sum('nilai');
        $nilaiRataRata = round($totalNilai / count($rapor->nilai), 1);
    }
    $persentaseKehadiran = $rapor->total_pertemuan > 0 ? round(($rapor->kehadiran / $rapor->total_pertemuan) * 100, 1) : 0;
@endphp

<div style="background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.08);overflow:hidden;">
    <!-- Header -->
    <div style="background:linear-gradient(135deg,#d32f2f,#b71c1c);color:white;padding:20px 24px;">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
            <div>
                <h1 style="margin:0 0 4px 0;font-size:20px;font-weight:700;">Rapor: {{ $siswa->nama }}</h1>
                <p style="margin:0;opacity:.9;font-size:13px;">
                    <i class="fas fa-chalkboard"></i> {{ $siswa->kelas }} &nbsp;|&nbsp;
                    <i class="fas fa-calendar"></i> {{ $namaBulan[$rapor->bulan] ?? 'Bulan '.$rapor->bulan }} {{ $rapor->tahun }}
                </p>
            </div>
            <div style="display:flex;gap:8px;align-items:center;">
                <span style="background:{{ $rapor->status === 'selesai' ? 'rgba(76,175,80,.2)' : 'rgba(255,152,0,.2)' }};color:{{ $rapor->status === 'selesai' ? '#4caf50' : '#ff9800' }};padding:6px 12px;border-radius:20px;font-size:12px;font-weight:600;">
                    {{ $rapor->status === 'selesai' ? 'Selesai' : 'Draft' }}
                </span>
                @if($showActions && ($userRole === 'admin' || $userRole === 'coach'))
                <div style="display:flex;gap:6px;">
                    @if($userRole === 'admin')
                    <a href="{{ route('admin.rapor.siswa', $siswa) }}?bulan={{ $rapor->bulan }}&tahun={{ $rapor->tahun }}" 
                        style="background:rgba(255,255,255,.2);color:white;padding:6px 10px;border-radius:6px;text-decoration:none;font-size:11px;">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @elseif($userRole === 'coach')
                    <a href="{{ route('coach.rapor.edit', $rapor) }}" 
                        style="background:rgba(255,255,255,.2);color:white;padding:6px 10px;border-radius:6px;text-decoration:none;font-size:11px;">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <div style="padding:24px;">
        <!-- Ringkasan Nilai -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:16px;margin-bottom:24px;">
            <div style="background:#e3f2fd;padding:16px;border-radius:10px;text-align:center;border-left:4px solid #2196f3;">
                <div style="font-size:24px;font-weight:700;color:#1976d2;">{{ $nilaiRataRata }}</div>
                <div style="font-size:12px;color:#1565c0;margin-top:2px;">Nilai Rata-rata</div>
            </div>
            <div style="background:#e8f5e9;padding:16px;border-radius:10px;text-align:center;border-left:4px solid #4caf50;">
                <div style="font-size:24px;font-weight:700;color:#2e7d32;">{{ $rapor->kehadiran }}/{{ $rapor->total_pertemuan }}</div>
                <div style="font-size:12px;color:#2e7d32;margin-top:2px;">Kehadiran ({{ $persentaseKehadiran }}%)</div>
            </div>
            @if($rapor->templateRapor)
            <div style="background:#fff3e0;padding:16px;border-radius:10px;text-align:center;border-left:4px solid #ff9800;">
                <div style="font-size:14px;font-weight:600;color:#f57c00;">{{ $rapor->templateRapor->nama_template }}</div>
                <div style="font-size:12px;color:#ef6c00;margin-top:2px;">Template Rapor</div>
            </div>
            @endif
        </div>

        <!-- Penilaian Teknik -->
        @if($rapor->nilai && count($rapor->nilai) > 0)
        <div style="margin-bottom:24px;">
            <h3 style="margin:0 0 16px 0;font-size:16px;font-weight:700;color:#333;border-bottom:2px solid #f0f0f0;padding-bottom:8px;">
                <i class="fas fa-star" style="color:#ff9800;"></i> Penilaian Teknik
            </h3>
            <div style="display:grid;gap:12px;">
                @foreach($rapor->nilai as $nilai)
                @php
                    $nilaiNum = (float)($nilai['nilai'] ?? 0);
                    $badgeColor = $nilaiNum >= 80 ? '#4caf50' : ($nilaiNum >= 60 ? '#ff9800' : '#f44336');
                    $badgeText = $nilaiNum >= 80 ? 'Baik' : ($nilaiNum >= 60 ? 'Cukup' : 'Perlu Perbaikan');
                @endphp
                <div style="background:#f8f9fa;border-radius:8px;padding:16px;border-left:4px solid {{ $badgeColor }};">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                        <h4 style="margin:0;font-size:14px;font-weight:600;color:#333;">{{ $nilai['nama'] ?? 'Komponen' }}</h4>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="background:{{ $badgeColor }};color:white;padding:4px 8px;border-radius:12px;font-size:11px;font-weight:600;">
                                {{ $badgeText }}
                            </span>
                            <span style="font-size:18px;font-weight:700;color:{{ $badgeColor }};">{{ $nilaiNum }}</span>
                        </div>
                    </div>
                    @if(!empty($nilai['keterangan']))
                    <p style="margin:0;font-size:12px;color:#666;line-height:1.4;">{{ $nilai['keterangan'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Catatan -->
        <div style="display:grid;gap:16px;">
            @if($rapor->catatan_coach)
            <div style="background:#e3f2fd;border-radius:8px;padding:16px;border-left:4px solid #2196f3;">
                <h4 style="margin:0 0 8px 0;font-size:14px;font-weight:600;color:#1976d2;">
                    <i class="fas fa-comment"></i> Catatan Pelatih
                </h4>
                <p style="margin:0;font-size:13px;color:#1565c0;line-height:1.5;">{{ $rapor->catatan_coach }}</p>
            </div>
            @endif

            @if($rapor->catatan_umum)
            <div style="background:#f3e5f5;border-radius:8px;padding:16px;border-left:4px solid #9c27b0;">
                <h4 style="margin:0 0 8px 0;font-size:14px;font-weight:600;color:#7b1fa2;">
                    <i class="fas fa-clipboard"></i> Saran Pengembangan
                </h4>
                <p style="margin:0;font-size:13px;color:#6a1b9a;line-height:1.5;">{{ $rapor->catatan_umum }}</p>
            </div>
            @endif
        </div>

        <!-- Footer Info -->
        <div style="margin-top:24px;padding-top:16px;border-top:1px solid #f0f0f0;font-size:11px;color:#999;text-align:center;">
            <p style="margin:0;">
                Rapor dibuat pada {{ $rapor->created_at->format('d/m/Y H:i') }} 
                @if($rapor->updated_at != $rapor->created_at)
                | Terakhir diupdate {{ $rapor->updated_at->format('d/m/Y H:i') }}
                @endif
            </p>
        </div>
    </div>
</div>