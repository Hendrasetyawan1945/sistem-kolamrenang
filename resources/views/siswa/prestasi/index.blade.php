@extends('layouts.siswa')

@section('title', 'Prestasi Saya')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-stopwatch fa-2x mb-2"></i>
                    <h4 class="mb-0">{{ $totalCatatan }}</h4>
                    <small>Total Catatan Waktu</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-swimming-pool fa-2x mb-2"></i>
                    <h4 class="mb-0">{{ $totalNomorLomba }}</h4>
                    <small>Nomor Lomba Dikuasai</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                    <h4 class="mb-0">{{ $bulanIni }}</h4>
                    <small>Latihan Bulan Ini</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-2x mb-2"></i>
                    <h4 class="mb-0">
                        @if($bulanLalu > 0)
                            {{ number_format((($bulanIni - $bulanLalu) / $bulanLalu) * 100, 1) }}%
                        @else
                            {{ $bulanIni > 0 ? '+100%' : '0%' }}
                        @endif
                    </h4>
                    <small>Progress vs Bulan Lalu</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Personal Best -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-trophy me-2 text-warning"></i>Personal Best
                    </h4>
                </div>
                <div class="card-body">
                    @if($personalBests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nomor Lomba</th>
                                        <th>Waktu Terbaik</th>
                                        <th>Percobaan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($personalBests as $pb)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $pb->nomor_lomba }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-success">
                                                {{ $pb->best_time }}
                                            </strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $pb->total_attempts }}x</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('siswa.catatan-waktu.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>Lihat Semua Catatan
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada catatan waktu</p>
                            <small class="text-muted">Catatan waktu akan muncul setelah coach memasukkan data latihan Anda</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Catatan Terbaru -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-clock me-2 text-info"></i>Catatan Waktu Terbaru
                    </h4>
                </div>
                <div class="card-body">
                    @if($catatanTerbaru->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nomor Lomba</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($catatanTerbaru as $catatan)
                                    <tr>
                                        <td>
                                            <small>{{ \Carbon\Carbon::parse($catatan->tanggal)->format('d M') }}</small>
                                        </td>
                                        <td>
                                            <small>{{ $catatan->nomor_lomba }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $catatan->waktu }}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('siswa.catatan-waktu.index') }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-history me-1"></i>Riwayat Lengkap
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada catatan waktu</p>
                            <small class="text-muted">Catatan waktu terbaru akan muncul di sini</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2 text-success"></i>Grafik Perkembangan
                    </h4>
                    @if(count($grafikData) > 0)
                    <select id="nomorLombaSelect" class="form-select form-select-sm" style="width:auto;">
                        @foreach($grafikData as $nomor => $data)
                            <option value="{{ $nomor }}">{{ $nomor }}</option>
                        @endforeach
                    </select>
                    @endif
                </div>
                <div class="card-body">
                    @if(count($grafikData) > 0)
                        <canvas id="grafikPerkembangan" height="100"></canvas>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data catatan waktu untuk ditampilkan</p>
                            <small class="text-muted">Grafik akan muncul setelah ada catatan waktu kompetisi</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(count($grafikData) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const grafikData = @json($grafikData);

    const colors = ['#0d6efd','#198754','#dc3545','#fd7e14','#6f42c1','#0dcaf0'];
    let chartInstance = null;

    function renderChart(nomor) {
        const data = grafikData[nomor];
        const labels = data.map(d => d.tanggal);
        const values = data.map(d => d.detik);
        const waktuLabels = data.map(d => d.waktu);

        const ctx = document.getElementById('grafikPerkembangan').getContext('2d');

        if (chartInstance) chartInstance.destroy();

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: nomor,
                    data: values,
                    borderColor: colors[0],
                    backgroundColor: colors[0] + '22',
                    borderWidth: 2.5,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ' Waktu: ' + waktuLabels[ctx.dataIndex],
                        }
                    }
                },
                scales: {
                    y: {
                        reverse: true,
                        title: { display: true, text: 'Waktu (detik) — lebih kecil lebih baik' },
                        ticks: {
                            callback: (val) => val + 's'
                        }
                    },
                    x: {
                        title: { display: true, text: 'Tanggal' }
                    }
                }
            }
        });
    }

    const select = document.getElementById('nomorLombaSelect');
    renderChart(select.value);
    select.addEventListener('change', () => renderChart(select.value));
</script>
@endif
@endsection