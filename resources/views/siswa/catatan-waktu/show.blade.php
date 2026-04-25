@extends('layouts.siswa')

@section('title', 'Detail Catatan Waktu')

@section('content')
<style>
    .detail-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 25px;
        color: white;
        margin-bottom: 20px;
    }
    
    .time-display {
        font-size: 3rem;
        font-weight: bold;
        font-family: 'Courier New', monospace;
        text-align: center;
        margin: 20px 0;
    }
    
    .info-item {
        background: rgba(255,255,255,0.1);
        border-radius: 8px;
        padding: 10px 15px;
        margin-bottom: 10px;
    }
    
    .comparison-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .pb-indicator {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        display: inline-block;
        margin-bottom: 15px;
    }
    
    .history-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Back Button -->
        <div class="col-12 mb-3">
            <a href="{{ route('siswa.catatan-waktu.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
            </a>
        </div>
        
        <!-- Main Detail Card -->
        <div class="col-md-8">
            <div class="detail-card">
                <div class="text-center">
                    <h3 class="mb-3">{{ $catatanWaktu->nomor_lomba }}</h3>
                    
                    @if($personalBest && $personalBest->id == $catatanWaktu->id)
                        <div class="pb-indicator">
                            <i class="fas fa-star me-2"></i>Personal Best Record
                        </div>
                    @endif
                    
                    <div class="time-display">{{ $catatanWaktu->waktu }}</div>
                    
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="info-item">
                                <i class="fas fa-calendar-alt d-block mb-2"></i>
                                <strong>{{ $catatanWaktu->tanggal->format('d M Y') }}</strong>
                                <br><small>{{ $catatanWaktu->tanggal->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="info-item">
                                <i class="fas fa-swimming-pool d-block mb-2"></i>
                                <strong>{{ $catatanWaktu->jenis_kolam }}</strong>
                                <br><small>Jenis Kolam</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="info-item">
                                <i class="fas fa-trophy d-block mb-2"></i>
                                <strong>{{ $catatanWaktu->event }}</strong>
                                <br><small>Event</small>
                            </div>
                        </div>
                    </div>
                    
                    @if($catatanWaktu->lokasi)
                    <div class="info-item mt-3">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        <strong>{{ $catatanWaktu->lokasi }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Comparison with Personal Best -->
        <div class="col-md-4">
            @if($personalBest)
            <div class="comparison-card">
                <h5 class="mb-3">
                    <i class="fas fa-chart-line me-2 text-primary"></i>Perbandingan dengan PB
                </h5>
                
                <div class="text-center mb-3">
                    <div class="mb-2">
                        <small class="text-muted">Personal Best</small>
                        <div class="h4 text-success font-monospace">{{ $personalBest->waktu }}</div>
                    </div>
                    
                    @if($personalBest->id != $catatanWaktu->id)
                        @php
                            // Convert time to seconds for comparison
                            $currentSeconds = \Carbon\Carbon::parse('1970-01-01 ' . $catatanWaktu->waktu)->getTimestamp() - \Carbon\Carbon::parse('1970-01-01 00:00:00')->getTimestamp();
                            $pbSeconds = \Carbon\Carbon::parse('1970-01-01 ' . $personalBest->waktu)->getTimestamp() - \Carbon\Carbon::parse('1970-01-01 00:00:00')->getTimestamp();
                            $diff = $currentSeconds - $pbSeconds;
                        @endphp
                        
                        <div class="mb-2">
                            <small class="text-muted">Selisih</small>
                            <div class="h5 {{ $diff > 0 ? 'text-danger' : 'text-success' }}">
                                {{ $diff > 0 ? '+' : '' }}{{ gmdate('i:s.v', abs($diff)) }}
                            </div>
                        </div>
                        
                        @if($diff > 0)
                            <div class="alert alert-warning py-2">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    Waktu ini {{ gmdate('i:s.v', $diff) }} lebih lambat dari PB
                                </small>
                            </div>
                        @elseif($diff < 0)
                            <div class="alert alert-success py-2">
                                <small>
                                    <i class="fas fa-trophy me-1"></i>
                                    Ini adalah Personal Best baru!
                                </small>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-success py-2">
                            <small>
                                <i class="fas fa-star me-1"></i>
                                Ini adalah Personal Best Anda!
                            </small>
                        </div>
                    @endif
                </div>
                
                <small class="text-muted">
                    PB dicapai pada {{ $personalBest->tanggal->format('d M Y') }}
                </small>
            </div>
            @endif
        </div>
        
        <!-- History for Same Event -->
        @if($riwayatNomor->count() > 0)
        <div class="col-12">
            <div class="history-table">
                <div class="bg-gradient-primary text-white p-3">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Riwayat {{ $catatanWaktu->nomor_lomba }}
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Kolam</th>
                                <th>Event</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayatNomor as $riwayat)
                            <tr>
                                <td>
                                    <strong>{{ $riwayat->tanggal->format('d M Y') }}</strong>
                                    <br><small class="text-muted">{{ $riwayat->tanggal->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <span class="font-monospace h6">{{ $riwayat->waktu }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $riwayat->jenis_kolam }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $riwayat->event }}</span>
                                </td>
                                <td>
                                    @if($riwayat->lokasi)
                                        <small>{{ $riwayat->lokasi }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($personalBest && $personalBest->id == $riwayat->id)
                                        <span class="badge bg-warning">
                                            <i class="fas fa-star"></i> PB
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
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
@endsection