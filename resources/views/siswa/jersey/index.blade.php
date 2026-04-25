@extends('layouts.siswa')

@section('title', 'Pesanan Jersey')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tshirt me-2"></i>Riwayat Pesanan Jersey
                    </h3>
                </div>
                <div class="card-body">
                    @if($jerseyOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal Pesan</th>
                                        <th>Ukuran</th>
                                        <th>Jumlah</th>
                                        <th>Total Harga</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jerseyOrders as $order)
                                    <tr>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $order->jerseySize->nama_size ?? 'N/A' }}</span>
                                        </td>
                                        <td>1 pcs</td>
                                        <td>
                                            <strong class="text-success">
                                                Rp {{ number_format($order->harga ?? 0, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                        <td>
                                            @switch($order->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock me-1"></i>Pending
                                                    </span>
                                                    @break
                                                @case('diproses')
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-cog me-1"></i>Diproses
                                                    </span>
                                                    @break
                                                @case('siap')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Siap Diambil
                                                    </span>
                                                    @break
                                                @case('selesai')
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-check-double me-1"></i>Selesai
                                                    </span>
                                                    @break
                                                @case('dibatalkan')
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times me-1"></i>Dibatalkan
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $order->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <a href="{{ route('siswa.jersey.show', $order) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-tshirt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Pesanan Jersey</h5>
                            <p class="text-muted">Riwayat pesanan jersey Anda akan muncul di sini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection