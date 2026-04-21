@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo">
        <i class="fas fa-search"></i>
    </div>
    <h1 class="club-title">Cari Siswa</h1>
</div>

<div class="dashboard-card">
    <h3 class="card-title">Pencarian Siswa</h3>
    
    <form action="{{ route('admin.cari-siswa') }}" method="GET" class="search-form">
        <select name="filter" class="form-select">
            <option value="nama" {{ request('filter') == 'nama' ? 'selected' : '' }}>Nama</option>
            <option value="kelas" {{ request('filter') == 'kelas' ? 'selected' : '' }}>Kelas</option>
            <option value="status" {{ request('filter') == 'status' ? 'selected' : '' }}>Status</option>
        </select>
        <input type="text" name="keyword" class="form-input" placeholder="Masukkan kata kunci..." value="{{ request('keyword') }}">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i> Cari
        </button>
    </form>
</div>

<!-- Hasil Pencarian -->
<div class="data-table">
    <div class="table-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h4>Hasil Pencarian</h4>
            @if(request('keyword'))
                <span style="color: #666; font-size: 14px;">
                    Menampilkan hasil untuk: "{{ request('keyword') }}"
                </span>
            @endif
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f5f5f5;">
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">No</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Nama</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Kelas</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Status</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Telepon</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse([] as $index => $siswa)
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $index + 1 }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $siswa['nama'] ?? '' }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $siswa['kelas'] ?? '' }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge status-active">{{ $siswa['status'] ?? '' }}</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $siswa['telepon'] ?? '' }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <button class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #666;">
                            @if(request('keyword'))
                                Tidak ada siswa yang ditemukan dengan kata kunci "{{ request('keyword') }}"
                            @else
                                Masukkan kata kunci untuk mencari siswa
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 20px;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>
@endsection