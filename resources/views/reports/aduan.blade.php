<!DOCTYPE html>
<html>

<head>
    <title>Laporan Aduan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Laporan Aduan</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>User</th>
                <th>Kategori</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($aduans as $aduan)
                <tr>
                    <td>{{ $aduan->id }}</td>
                    <td>{{ $aduan->judul }}</td>
                    <td>{{ $aduan->lokasi }}</td>
                    <td>{{ $aduan->status }}</td>
                    <td>{{ $aduan->latitude }}</td>
                    <td>{{ $aduan->longitude }}</td>
                    <td>{{ $aduan->user->name }}</td>
                    <td>{{ $aduan->kategori->nama }}</td> <!-- Perbaiki penutupan tag td di sini -->
                    <td>{{ $aduan->created_at }}</td>
                    <td>{{ $aduan->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
