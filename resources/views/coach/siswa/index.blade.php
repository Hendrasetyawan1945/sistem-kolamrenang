@extends('layouts.coach')
@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-users me-2 text-primary"></i>Daftar Siswa Saya</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th class="px-4 py-3" style="font-size:12px;color:#666;">No</th>
                        <th class="py-3" style="font-size:12px;color:#666;">Nama</th>
                        <th class="py-3" style="font-size:12px;color:#666;">Kelas</th>
                        <th class="py-3" style="font-size:12px;color:#666;">Tgl Lahir</th>
                        <th class="py-3" style="font-size:12px;color:#666;">Status</th>
                        <th class="py-3" style="font-size:12px;color:#666;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaList as $siswa)
                    <tr>
                        <td class="px-4">{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-semibold">{{ $siswa->nama }}</div>
                            <small class="text-muted">{{ $siswa->nomor_induk ?? '-' }}</small>
                        </td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $siswa->kelas ?? '-' }}</span></td>
                        <td><small>{{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d/m/Y') : '-' }}</small></td>
                        <td>
                            <span class="badge {{ $siswa->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($siswa->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('coach.siswa.show', $siswa) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-users fa-2x mb-2 d-block opacity-25"></i>
                            Belum ada siswa di kelas Anda
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($siswaList->hasPages())
        <div class="px-4 py-3">{{ $siswaList->links() }}</div>
        @endif
    </div>
</div>
@endsection
