@extends('layouts.siswa')

@section('title', 'Catatan Waktu Latihan')

@section('content')
<style>
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 20px;
        color: white;
        margin-bottom: 20px;
    }
    
    .pb-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 15px;
        padding: 20px;
        color: white;
        margin-bottom: 20px;
    }
    
    .recent-card {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        border-radius: 15px;
        padding: 20px;
        color: white;
        margin-bottom: 20px;
    }
    
    .time-display {
        font-size: 1.8rem;
        font-weight: bold;
        font-family: 'Courier New', monospace;
    }
    
    .event-badge {
        background: rgba(255,255,255,0.2);
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
        display: inline-block;
        margin-bottom: 5px;
    }
    
    .improvement-badge {
        font-size: 0.7rem;
        padding: 2px 6px;
        border-radius: 8px;
    }
    
    .table-modern {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .table-modern thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .table-modern thead th {
        border: none;
        padding: 15px;
        font-weight: 600;
    }
    
    .table-modern tbody td {
        padding: 12px 15px;
        border-bottom: 1px solid #f1f3f4;
        vertical-align: middle;
    }
    
    .table-modern tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Header -->
        <div class="col-12 mb-4">
            <h2 class="mb-1">
                <i class="fas fa-stopwatch me-2 text-primary"></i>Catatan Waktu Latihan
            </h2>
            <p class="text-muted">Pantau perkembangan waktu latihan dan personal best Anda</p>
        </div>
        
        <!-- Stats Cards -->
        <div class="col-md-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">Total Catatan</h6>
                        <div class="time-display">{{ $catatanWaktu->total() }}</div>
                        <small>Sesi latihan tercatat</small>
                    </div>
                    <div>
                        <i class="fas fa-chart-line fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="pb-card">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">Personal Best</h6>
                        <div class="time-display">{{ $personalBests->count() }}</div>
                        <small>Nomor lomba dikuasai</small>
                    </div>
                    <div>
                        <i class="fas fa-trophy fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="recent-card">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">Latihan Terakhir</h6>
                        <div class="time-display">
                            @if($catatanTerbaru->count() > 0)
                                {{ $catatanTerbaru->first()->tanggal->diffForHumans() }}
                            @else
                                -
                            @endif
                        </div>
                        <small>Aktivitas terbaru</small>
                    </div>
                    <div>
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Personal Best Section -->
        @if($personalBests->count() > 0)
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-star me-2"></i>Personal Best Records
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($personalBests as $pb)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="border rounded p-3 h-100" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                                <div class="event-badge text-dark">{{ $pb->nomor_lomba }}</div>
                                <div class="time-display text-dark">{{ $pb->best_time }}</div>
                                <small class="text-dark opacity-75">Waktu terbaik</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Recent Training -->
        @if($catatanTerbaru->count() > 0)
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-gradient-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Latihan Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($catatanTerbaru as $recent)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-primary">{{ $recent->nomor_lomba }}</span>
                                    <small class="text-muted">{{ $recent->tanggal->format('d M Y') }}</small>
                                </div>
                                <div class="time-display text-primary mb-1">{{ $recent->waktu }}</div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $recent->jenis_kolam }}</small>
                                    <span class="badge bg-secondary">{{ $recent->event }}</span>
                                </div>
                                @if($recent->lokasi)
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $recent->lokasi }}
                                    </small>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- All Records Table -->
        <div class="col-12">
            <div class="table-modern">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nomor Lomba</th>
                            <th>Waktu</th>
                            <th>Kolam</th>
                            <th>Event</th>
                            <th>Lokasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($catatanWaktu as $catatan)
                        <tr>
                            <td>
                                <strong>{{ $catatan->tanggal->format('d M Y') }}</strong>
                                <br><small class="text-muted">{{ $catatan->tanggal->diffForHumans() }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $catatan->nomor_lomba }}</span>
                                @if(isset($catatan->record_type))
                                    <br><small class="badge {{ $catatan->record_type == 'kompetisi' ? 'bg-success' : 'bg-info' }}">
                                        {{ $catatan->record_type == 'kompetisi' ? 'Kompetisi' : 'Latihan' }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <span class="time-display text-success" style="font-size: 1.1rem;">
                                    {{ $catatan->waktu }}
                                </span>
                                @if(isset($personalBests[$catatan->nomor_lomba]) && $personalBests[$catatan->nomor_lomba]->best_time == $catatan->waktu)
                                    <br><span class="badge bg-warning improvement-badge">
                                        <i class="fas fa-star"></i> PB
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $catatan->jenis_kolam }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $catatan->jenis_event }}</span>
                            </td>
                            <td>
                                @if($catatan->lokasi)
                                    <small>
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $catatan->lokasi }}
                                    </small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('siswa.catatan-waktu.show', $catatan) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-stopwatch fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum Ada Catatan Waktu</h5>
                                <p class="text-muted">Catatan waktu latihan Anda akan muncul di sini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($catatanWaktu->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $catatanWaktu->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection