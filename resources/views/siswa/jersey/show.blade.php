@extends('layouts.siswa')

@section('title', 'Detail Pesanan Jersey')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-tshirt me-2"></i>Detail Pesanan Jersey
                </h2>
                <a href="{{ route('siswa.jersey.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <!-- Detail Pesanan -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        Pesanan #{{ $jerseyOrder->id }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informasi Pesanan</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Tanggal Pesan:</strong></td>
                                    <td>{{ $jerseyOrder->created_at->format('d F Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ukuran:</strong></td>
                                    <td>
                                        <span class="badge bg-info fs-6">{{ $jerseyOrder->ukuran }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah:</strong></td>
                                    <td>{{ $jerseyOrder->jumlah }} pcs</td>
                                </tr>
                                <tr>
                                    <td><strong>Harga Satuan:</strong></td>
                                    <td>Rp {{ number_format($jerseyOrder->harga_satuan, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Harga:</strong></td>
                                    <td>
                                        <h5 class="text-success mb-0">
                                            Rp {{ number_format($jerseyOrder->total_harga, 0, ',', '.') }}
                                        </h5>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Status & Pembayaran</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Status Pesanan:</strong></td>
                                    <td>
                                        @switch($jerseyOrder->status)
                                            @case('pending')
                                                <span class="badge bg-warning fs-6">
                                                    <i class="fas fa-clock me-1"></i>Pending
                                                </span>
                                                @break
                                            @case('diproses')
                                                <span class="badge bg-info fs-6">
                                                    <i class="fas fa-cog me-1"></i>Diproses
                                                </span>
                                                @break
                                            @case('siap')
                                                <span class="badge bg-success fs-6">
                                                    <i class="fas fa-check me-1"></i>Siap Diambil
                                                </span>
                                                @break
                                            @case('selesai')
                                                <span class="badge bg-primary fs-6">
                                                    <i class="fas fa-check-double me-1"></i>Selesai
                                                </span>
                                                @break
                                            @case('dibatalkan')
                                                <span class="badge bg-danger fs-6">
                                                    <i class="fas fa-times me-1"></i>Dibatalkan
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary fs-6">{{ $jerseyOrder->status }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status Pembayaran:</strong></td>
                                    <td>
                                        @if($jerseyOrder->status_pembayaran === 'lunas')
                                            <span class="badge bg-success fs-6">
                                                <i class="fas fa-check me-1"></i>Lunas
                                            </span>
                                        @elseif($jerseyOrder->status_pembayaran === 'belum_bayar')
                                            <span class="badge bg-warning fs-6">
                                                <i class="fas fa-clock me-1"></i>Belum Bayar
                                            </span>
                                        @else
                                            <span class="badge bg-secondary fs-6">{{ $jerseyOrder->status_pembayaran }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($jerseyOrder->tanggal_bayar)
                                <tr>
                                    <td><strong>Tanggal Bayar:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($jerseyOrder->tanggal_bayar)->format('d F Y') }}</td>
                                </tr>
                                @endif
                                @if($jerseyOrder->metode_pembayaran)
                                <tr>
                                    <td><strong>Metode Pembayaran:</strong></td>
                                    <td>{{ $jerseyOrder->metode_pembayaran }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($jerseyOrder->keterangan)
                        <hr>
                        <h5>Keterangan</h5>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ $jerseyOrder->keterangan }}
                        </div>
                    @endif

                    <!-- Timeline Status -->
                    <hr>
                    <h5>Timeline Pesanan</h5>
                    <div class="timeline">
                        <div class="timeline-item {{ in_array($jerseyOrder->status, ['pending', 'diproses', 'siap', 'selesai']) ? 'active' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Pesanan Dibuat</h6>
                                <small class="text-muted">{{ $jerseyOrder->created_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>
                        
                        @if(in_array($jerseyOrder->status, ['diproses', 'siap', 'selesai']))
                        <div class="timeline-item active">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Sedang Diproses</h6>
                                <small class="text-muted">Jersey sedang dalam proses produksi</small>
                            </div>
                        </div>
                        @endif
                        
                        @if(in_array($jerseyOrder->status, ['siap', 'selesai']))
                        <div class="timeline-item active">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Siap Diambil</h6>
                                <small class="text-muted">Jersey siap untuk diambil</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($jerseyOrder->status === 'selesai')
                        <div class="timeline-item active">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Selesai</h6>
                                <small class="text-muted">Pesanan telah selesai</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($jerseyOrder->status === 'dibatalkan')
                        <div class="timeline-item cancelled">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Dibatalkan</h6>
                                <small class="text-muted">Pesanan dibatalkan</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -22px;
    top: 8px;
    bottom: -20px;
    width: 2px;
    background: #dee2e6;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #dee2e6;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-item.active .timeline-marker {
    background: #28a745;
    box-shadow: 0 0 0 2px #28a745;
}

.timeline-item.cancelled .timeline-marker {
    background: #dc3545;
    box-shadow: 0 0 0 2px #dc3545;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}
</style>
@endsection