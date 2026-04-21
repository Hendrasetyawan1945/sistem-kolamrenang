@extends('layouts.admin')

@section('content')
<h1 style="color:#d32f2f; font-size:28px; font-weight:700; margin-bottom:20px; font-style:italic;">JERSEY MAP</h1>

<p style="margin-bottom:20px; color:#666;">Peta visual distribusi jersey per siswa berdasarkan size dan status pemesanan</p>

<!-- Summary Cards -->
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:15px; margin-bottom:30px;">
    @php
        $orders = \App\Models\JerseyOrder::with(['siswa', 'jerseySize'])->get();
        $totalOrders = $orders->count();
        $statusCounts = $orders->groupBy('status')->map->count();
        $sizeCounts = $orders->groupBy('jersey_size_id')->map->count();
        $paymentCounts = $orders->groupBy('status_bayar')->map->count();
    @endphp
    
    <div style="background:linear-gradient(135deg, #2196f3, #1976d2); color:white; padding:20px; border-radius:10px;">
        <div style="font-size:12px; opacity:0.9; margin-bottom:5px;">Total Pesanan</div>
        <div style="font-size:32px; font-weight:700;">{{ $totalOrders }}</div>
    </div>
    
    <div style="background:linear-gradient(135deg, #ff9800, #f57c00); color:white; padding:20px; border-radius:10px;">
        <div style="font-size:12px; opacity:0.9; margin-bottom:5px;">Belum Bayar</div>
        <div style="font-size:32px; font-weight:700;">{{ $paymentCounts['belum_bayar'] ?? 0 }}</div>
    </div>
    
    <div style="background:linear-gradient(135deg, #4caf50, #45a049); color:white; padding:20px; border-radius:10px;">
        <div style="font-size:12px; opacity:0.9; margin-bottom:5px;">Sudah Bayar</div>
        <div style="font-size:32px; font-weight:700;">{{ $paymentCounts['lunas'] ?? 0 }}</div>
    </div>
    
    <div style="background:linear-gradient(135deg, #607d8b, #455a64); color:white; padding:20px; border-radius:10px;">
        <div style="font-size:12px; opacity:0.9; margin-bottom:5px;">Total Pendapatan</div>
        <div style="font-size:32px; font-weight:700;">Rp {{ number_format($orders->where('status_bayar', 'lunas')->sum('harga'), 0, ',', '.') }}</div>
    </div>
</div>

<!-- Filter dan Pencarian -->
<div style="background:white; padding:20px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <form method="GET" style="display:flex; gap:15px; align-items:end; flex-wrap:wrap;">
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Status Pesanan</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="dipesan" {{ request('status') == 'dipesan' ? 'selected' : '' }}>Dipesan</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="diambil" {{ request('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
            </select>
        </div>
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Status Bayar</label>
            <select name="status_bayar" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Pembayaran</option>
                <option value="belum_bayar" {{ request('status_bayar') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="lunas" {{ request('status_bayar') == 'lunas' ? 'selected' : '' }}>Sudah Bayar</option>
            </select>
        </div>
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Size</label>
            <select name="size" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Size</option>
                @foreach(\App\Models\JerseySize::orderBy('id')->get() as $size)
                    <option value="{{ $size->id }}" {{ request('size') == $size->id ? 'selected' : '' }}>{{ $size->nama_size }}</option>
                @endforeach
            </select>
        </div>
    </form>
</div>

@php
    $filteredOrders = $orders;
    if (request('status')) {
        $filteredOrders = $filteredOrders->where('status', request('status'));
    }
    if (request('status_bayar')) {
        $filteredOrders = $filteredOrders->where('status_bayar', request('status_bayar'));
    }
    if (request('size')) {
        $filteredOrders = $filteredOrders->where('jersey_size_id', request('size'));
    }
@endphp

<!-- Jersey Map by Size -->
<div style="background:white; padding:25px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <h3 style="margin:0 0 20px 0; color:#333; font-size:18px;">
        <i class="fas fa-map"></i> Distribusi Jersey Per Size
        @if(request()->hasAny(['status', 'status_bayar', 'size']))
            <span style="font-size:14px; color:#666; font-weight:400;">({{ $filteredOrders->count() }} dari {{ $totalOrders }} pesanan)</span>
        @endif
    </h3>
    
    @php
        $sizes = \App\Models\JerseySize::withCount('orders')->get();
    @endphp
    
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:20px;">
        @foreach($sizes as $size)
            @php
                $sizeOrders = $filteredOrders->where('jersey_size_id', $size->id);
                $sizeOrdersCount = $sizeOrders->count();
            @endphp
            <div style="border:2px solid #e0e0e0; border-radius:10px; padding:20px; text-align:center;">
                <div style="background:#d32f2f; color:white; width:80px; height:80px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 15px; font-size:32px; font-weight:700;">
                    {{ $size->nama_size }}
                </div>
                <div style="font-size:14px; color:#666; margin-bottom:10px;">
                    <strong>{{ $sizeOrdersCount }}</strong> pesanan
                </div>
                <div style="font-size:12px; color:#999; margin-bottom:15px;">
                    Stok tersisa: <strong>{{ $size->stok }}</strong>
                </div>
                
                @if($sizeOrdersCount > 0)
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:15px; font-size:11px;">
                        <div style="background:#ff9800; color:white; padding:4px 8px; border-radius:6px;">
                            Belum Bayar: {{ $sizeOrders->where('status_bayar', 'belum_bayar')->count() }}
                        </div>
                        <div style="background:#4caf50; color:white; padding:4px 8px; border-radius:6px;">
                            Sudah Bayar: {{ $sizeOrders->where('status_bayar', 'lunas')->count() }}
                        </div>
                    </div>
                    
                    <details style="margin-top:15px; text-align:left;">
                        <summary style="cursor:pointer; color:#2196f3; font-size:12px; font-weight:600;">
                            Lihat Detail ({{ $sizeOrdersCount }})
                        </summary>
                        <div style="margin-top:10px; max-height:200px; overflow-y:auto;">
                            @foreach($sizeOrders as $order)
                                <div style="padding:8px; background:#f8f9fa; border-radius:6px; margin-bottom:5px; font-size:11px;">
                                    <div style="font-weight:600; color:#333;">{{ $order->siswa->nama }}</div>
                                    <div style="color:#666; display:flex; justify-content:space-between; align-items:center;">
                                        <span>No: {{ $order->nomor_punggung ?? '-' }}</span>
                                        <span style="background:{{ $order->status_bayar == 'lunas' ? '#4caf50' : '#ff9800' }}; color:white; padding:2px 6px; border-radius:8px;">
                                            {{ $order->status_bayar == 'lunas' ? 'Lunas' : 'Belum Bayar' }}
                                        </span>
                                    </div>
                                    <div style="color:#999; font-size:10px; margin-top:3px;">
                                        Status: {{ ucfirst($order->status) }} | {{ $order->tanggal_pesan->format('d/m/Y') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </details>
                @endif
            </div>
        @endforeach
    </div>
</div>

<!-- Rekap Pembayaran -->
<div style="background:white; padding:25px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <h3 style="margin:0 0 20px 0; color:#333; font-size:18px;">
        <i class="fas fa-money-check"></i> Rekap Pembayaran Jersey
    </h3>
    
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
        <!-- Belum Bayar -->
        <div style="border:2px solid #ff9800; border-radius:10px; padding:20px;">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:15px;">
                <div style="background:#ff9800; color:white; width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700;">
                    {{ $orders->where('status_bayar', 'belum_bayar')->count() }}
                </div>
                <div>
                    <div style="font-weight:700; color:#333; text-transform:uppercase;">Belum Bayar</div>
                    <div style="font-size:11px; color:#666;">
                        Total: Rp {{ number_format($orders->where('status_bayar', 'belum_bayar')->sum('harga'), 0, ',', '.') }}
                    </div>
                </div>
            </div>
            
            @php $belumBayar = $orders->where('status_bayar', 'belum_bayar'); @endphp
            @if($belumBayar->isNotEmpty())
                <div style="max-height:250px; overflow-y:auto;">
                    @foreach($belumBayar as $order)
                        <div style="padding:10px; background:#fff3e0; border-radius:6px; margin-bottom:8px; font-size:12px; border-left:4px solid #ff9800;">
                            <div style="font-weight:600; color:#333; margin-bottom:3px;">{{ $order->siswa->nama }}</div>
                            <div style="color:#666; display:flex; justify-content:space-between; align-items:center;">
                                <span>Size: <strong>{{ $order->jerseySize->nama_size }}</strong></span>
                                <span style="font-weight:700; color:#ff9800;">Rp {{ number_format($order->harga, 0, ',', '.') }}</span>
                            </div>
                            <div style="color:#999; font-size:10px; margin-top:3px;">
                                {{ $order->tanggal_pesan->format('d/m/Y') }} | Status: {{ ucfirst($order->status) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center; padding:20px; color:#999; font-size:12px;">
                    Semua pesanan sudah dibayar
                </div>
            @endif
        </div>
        
        <!-- Sudah Bayar -->
        <div style="border:2px solid #4caf50; border-radius:10px; padding:20px;">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:15px;">
                <div style="background:#4caf50; color:white; width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700;">
                    {{ $orders->where('status_bayar', 'lunas')->count() }}
                </div>
                <div>
                    <div style="font-weight:700; color:#333; text-transform:uppercase;">Sudah Bayar</div>
                    <div style="font-size:11px; color:#666;">
                        Total: Rp {{ number_format($orders->where('status_bayar', 'lunas')->sum('harga'), 0, ',', '.') }}
                    </div>
                </div>
            </div>
            
            @php $sudahBayar = $orders->where('status_bayar', 'lunas'); @endphp
            @if($sudahBayar->isNotEmpty())
                <div style="max-height:250px; overflow-y:auto;">
                    @foreach($sudahBayar as $order)
                        <div style="padding:10px; background:#e8f5e9; border-radius:6px; margin-bottom:8px; font-size:12px; border-left:4px solid #4caf50;">
                            <div style="font-weight:600; color:#333; margin-bottom:3px;">{{ $order->siswa->nama }}</div>
                            <div style="color:#666; display:flex; justify-content:space-between; align-items:center;">
                                <span>Size: <strong>{{ $order->jerseySize->nama_size }}</strong></span>
                                <span style="font-weight:700; color:#4caf50;">Rp {{ number_format($order->harga, 0, ',', '.') }}</span>
                            </div>
                            <div style="color:#999; font-size:10px; margin-top:3px;">
                                Bayar: {{ $order->tanggal_bayar ? $order->tanggal_bayar->format('d/m/Y') : '-' }} | 
                                {{ ucfirst($order->metode_pembayaran ?? '-') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center; padding:20px; color:#999; font-size:12px;">
                    Belum ada pembayaran
                </div>
            @endif
        </div>
    </div>
</div>

<div style="margin-top:20px;">
    <a href="{{ route('admin.pemesanan') }}" class="btn btn-primary">
        <i class="fas fa-shopping-cart"></i> Kelola Pemesanan
    </a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" style="margin-left:10px;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<style>
    .form-select {
        padding:10px 12px;
        border:2px solid #e0e0e0;
        border-radius:6px;
        font-size:14px;
        min-width:150px;
    }
    .form-select:focus {
        outline:none;
        border-color:#d32f2f;
    }
</style>
@endsection
