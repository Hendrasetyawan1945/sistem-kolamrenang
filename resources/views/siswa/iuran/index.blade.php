@extends('layouts.siswa')

@section('title', 'Riwayat Iuran')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Pembayaran Lunas -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-money-bill-wave me-2"></i>Riwayat Pembayaran Iuran
                    </h3>
                </div>
                <div class="card-body">
                    @if($pembayaranList->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Bulan/Tahun</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pembayaranList as $pembayaran)
                                    <tr>
                                        <td>
                                            <strong>
                                                {{ \Carbon\Carbon::create($pembayaran->tahun, $pembayaran->bulan)->format('F Y') }}
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ ucwords(str_replace('_', ' ', $pembayaran->jenis_pembayaran)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-success">
                                                Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                        <td>
                                            @if($pembayaran->tanggal_bayar)
                                                {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y') }}
                                                <br><small class="text-muted">{{ $pembayaran->metode_pembayaran ?? 'Tunai' }}</small>
                                            @else
                                                <span class="text-muted">Belum dibayar</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('siswa.iuran.show', $pembayaran) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $pembayaranList->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Riwayat Pembayaran</h5>
                            <p class="text-muted">Riwayat pembayaran iuran Anda akan muncul di sini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Angsuran/Cicilan -->
        @if($angsuranList->count() > 0)
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-credit-card me-2"></i>Tagihan Angsuran
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Jenis Tagihan</th>
                                    <th>Total Tagihan</th>
                                    <th>Sudah Dibayar</th>
                                    <th>Sisa Tagihan</th>
                                    <th>Status</th>
                                    <th>Tanggal Tagihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($angsuranList as $angsuran)
                                <tr>
                                    <td>
                                        <strong>{{ $angsuran->jenis_tagihan }}</strong>
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($angsuran->total_tagihan, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        <span class="text-success">
                                            Rp {{ number_format($angsuran->total_dibayar, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-warning">
                                            Rp {{ number_format($angsuran->sisa_tagihan, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($angsuran->status === 'lunas')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Lunas
                                            </span>
                                        @elseif($angsuran->status === 'aktif')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Belum Lunas
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($angsuran->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($angsuran->tanggal_tagihan)->format('d M Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@endsection