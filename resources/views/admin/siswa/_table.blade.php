<div style="overflow-x:auto;">
    <table style="width:100%; border-collapse:collapse; font-size:14px;">
        <thead>
            <tr style="background:#f5f5f5; border-bottom:2px solid #e0e0e0;">
                <th style="padding:12px 14px; text-align:left;">No</th>
                <th style="padding:12px 14px; text-align:left;">Nama</th>
                <th style="padding:12px 14px; text-align:left;">Tgl Lahir</th>
                <th style="padding:12px 14px; text-align:left;">JK</th>
                <th style="padding:12px 14px; text-align:left;">Kelas</th>
                <th style="padding:12px 14px; text-align:left;">Paket</th>
                <th style="padding:12px 14px; text-align:left;">Telepon</th>
                <th style="padding:12px 14px; text-align:left;">Ortu</th>
                <th style="padding:12px 14px; text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswas as $i => $siswa)
            <tr style="border-bottom:1px solid #f0f0f0; {{ $loop->even ? 'background:#fafafa;' : '' }}">
                <td style="padding:10px 14px;">{{ $i + 1 }}</td>
                <td style="padding:10px 14px; font-weight:600;">{{ $siswa->nama }}</td>
                <td style="padding:10px 14px;">{{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d/m/Y') }}</td>
                <td style="padding:10px 14px;">{{ $siswa->jenis_kelamin }}</td>
                <td style="padding:10px 14px;">{{ ucfirst($siswa->kelas) }}</td>
                <td style="padding:10px 14px;">{{ $siswa->paket }}</td>
                <td style="padding:10px 14px;">{{ $siswa->telepon }}</td>
                <td style="padding:10px 14px;">{{ $siswa->nama_ortu }}</td>
                <td style="padding:10px 14px;">
                    <div style="display:flex; gap:5px; justify-content:center; flex-wrap:wrap;">

                        {{-- Tombol Edit --}}
                        <a href="{{ route('admin.siswa.edit', $siswa) }}"
                           style="padding:5px 10px; font-size:11px; border-radius:5px; background:#2196f3; color:white; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        {{-- Tombol ubah status --}}
                        @foreach($extraActions as $action)
                        <form method="POST" action="{{ route('admin.siswa.update-status', $siswa) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $action['status'] }}">
                            <button type="submit"
                                style="padding:5px 10px; font-size:11px; border-radius:5px; background:{{ $action['color'] }}; color:white; border:none; cursor:pointer;">
                                {!! $action['label'] !!}
                            </button>
                        </form>
                        @endforeach

                        {{-- Tombol Hapus --}}
                        <form method="POST" action="{{ route('admin.siswa.destroy', $siswa) }}" style="display:inline;"
                              onsubmit="return confirm('Yakin hapus data {{ $siswa->nama }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                style="padding:5px 10px; font-size:11px; border-radius:5px; background:#f44336; color:white; border:none; cursor:pointer;">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
