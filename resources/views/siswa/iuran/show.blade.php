@extends('layouts.siswa')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-receipt me-2"></i>Detail Pembayaran
                </h2>
                <a href="{{ route('siswa.iuran.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <!-- Detail Pembayaran -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        Pembayaran {{ ucwords(str_replace('_', ' ', $pembayaran->jenis_pembayaran)) }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Periode:</strong></td>
                                    <td>
                                        {{ \Carbon\Carbon::create($pembayaran->tahun, $pembayaran->bulan)->format('F Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Pembayaran:</strong></td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucwords(str_replace('_', ' ', $pembayaran->jenis_pembayaran)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Jumlah:</strong></td>
                                    <td>
                                        <h5 class="text-success mb-0">
                                            Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                        </h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($pembayaran->status === 'lunas')
                                            <span class="badge bg-success fs-6">
                                                <i class="fas fa-check me-1"></i>Lunas
                                            </span>
                                        @elseif($pembayaran->status === 'belum_lunas')
                                            <span class="badge bg-warning fs-6">
                                                <i class="fas fa-clock me-1"></i>Belum Lunas
                                            </span>
                                        @else
                                            <span class="badge bg-secondary fs-6">{{ $pembayaran->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                @if($pembayaran->tanggal_bayar)
                                <tr>
                                    <td><strong>Tanggal Bayar:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d F Y') }}</td>
                                </tr>
                                @endif
                                @if($pembayaran->metode_pembayaran)
                                <tr>
                                    <td><strong>Metode Pembayaran:</strong></td>
                                    <td>{{ $pembayaran->metode_pembayaran }}</td>
                                </tr>
                                @endif
                                @if($pembayaran->sisa_bayar > 0)
                                <tr>
                                    <td><strong>Sisa Bayar:</strong></td>
                                    <td>
                                        <span class="text-danger fw-bold">
                                            Rp {{ number_format($pembayaran->sisa_bayar, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                                @if($pembayaran->keterangan)
                                <tr>
                                    <td><strong>Keterangan:</strong></td>
                                    <td>{{ $pembayaran->keterangan }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Angsuran (jika ada) -->
            @if($angsuranList->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Riwayat Angsuran
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Jumlah Bayar</th>
                                    <th>Metode</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($angsuranList as $index => $angsuran)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($angsuran->tanggal_bayar)->format('d M Y') }}</td>
                                    <td>
                                        <strong class="text-success">
                                            Rp {{ number_format($angsuran->jumlah_bayar, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                    <td>{{ $angsuran->metode_pembayaran ?? 'Tunai' }}</td>
                                    <td>{{ $angsuran->keterangan ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection