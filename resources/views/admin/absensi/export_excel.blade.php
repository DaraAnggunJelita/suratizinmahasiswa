<table>
    <thead>
        <tr>
            <th colspan="4" style="font-weight: bold; text-align: center;">REKAP ABSENSI KELAS {{ $kelas }}</th>
        </tr>
    </thead>
    <tbody>
        @php $p = 1; @endphp
        @foreach($groupedAbsensi as $tanggal => $items)
            <tr>
                <td colspan="4" style="background-color: #FFFF00; font-weight: bold;">
                    PERTEMUAN {{ $p++ }} ({{ date('d-m-Y', strtotime($tanggal)) }})
                </td>
            </tr>
            <tr>
                <th style="border: 1px solid #000;">No</th>
                <th style="border: 1px solid #000;">Nama</th>
                <th style="border: 1px solid #000;">NIM</th>
                <th style="border: 1px solid #000;">Status</th>
            </tr>
            @foreach($items as $i => $abs)
            <tr>
                <td style="border: 1px solid #000;">{{ $i + 1 }}</td>
                <td style="border: 1px solid #000;">{{ strtoupper($abs->nama_mahasiswa) }}</td>
                <td style="border: 1px solid #000;">{{ $abs->nim_mahasiswa }}</td>
                <td style="border: 1px solid #000;">{{ $abs->status }}</td>
            </tr>
            @endforeach
            <tr><td colspan="4"></td></tr> {{-- Jarak antar pertemuan --}}
        @endforeach
    </tbody>
</table>
