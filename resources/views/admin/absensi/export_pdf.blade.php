<!DOCTYPE html>
<html>
<head>
    <title>Rekap Absensi {{ $kelas }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .title { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .pertemuan-header { background-color: #eee; font-weight: bold; padding: 5px; border: 1px solid #000; }
    </style>
</head>
<body>
    <div class="title">
        <h2>REKAP ABSENSI MAHASISWA</h2>
        <h3>Kelas: {{ $kelas }}</h3>
    </div>

    @php $p = 1; @endphp
    @foreach($groupedAbsensi as $tanggal => $items)
        <div class="pertemuan-header">PERTEMUAN {{ $p++ }} - {{ date('d/m/Y', strtotime($tanggal)) }}</div>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="50%">Nama Mahasiswa</th>
                    <th width="25%">NIM</th>
                    <th width="20%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $i => $abs)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ strtoupper($abs->nama_mahasiswa) }}</td>
                    <td>{{ $abs->nim_mahasiswa }}</td>
                    <td>{{ $abs->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
