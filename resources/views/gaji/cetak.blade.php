<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji</title>

    <style type="text/css">
        * {
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif;
        }

        .container {
            position: absolute;
            top: -40px;
            left: -40px;
            right: -40px;
            bottom: -40px;
            padding: 0 20px;
            border: 3px solid #605ca7;
            background: #eceaea;
        }
        .box-title {
            position: absolute;
            top: 30px;
            left: 0;
            width: 200px;
            height: 50px;
            background: #605ca7;
            color: #fff;
            padding: 20px;
            border-radius: 0px 50% 50% 0;
        }
        .title {
            position: absolute;
            left: 20%;
            top: 0;
            width: 200px;
            height: 50px;
            color: #fff;
            font-size: 34px;
            font-weight: bold;
        }

        .logo {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 70px;
            height: 70px;
            background: #605ca7;
            color: #fff;
            border-radius: 50%;
        }

        .top-text {
            position: absolute;
            top: 150px;
            left: 30%;
            font-size: 12px;
            text-align: center;
            width: 200px;
            height: 50px;
            color: #423cb7
        }

        .top-text p {
            margin: 0;
            padding: 0;
        }

        .top-text p:first-child {
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .top-text p:nth-child(2) {
            text-transform:capitalize
        }

        .top-text h4 {
            text-transform: uppercase;
            font-size: 14px;
        }

        .table {
            position: absolute;
            top: 280px;
            width: 500px;
            border-collapse: collapse;
        }

        .table, th, td {
            border: 1px solid #423cb7;
        }

        .table thead {
            background: #605ca7;
            color: #fff;
        }

        .bottom-text {
            position: absolute;
            top: 550px;
            left: 25%;
            font-size: 12px;
            text-align: center;
            width: 270px;
            color: #000
        }

        .signature {
            position: absolute;
            bottom: -20px;
            left: 25%;
            font-size: 12px;
            text-align: center;
            width: 270px;
            color: #000
        }

        .bottom-side {
            position: absolute;
            bottom: -80px;
            right: 0;
            width: 60%;
            height: 15px;
            background: #605ca7;
            color: #fff;
            padding: 20px;
            border-radius: 50% 0 0 0;
        }

        th, td {
            padding: 10px;
        }

        th {
            text-align: left;
            font-size: 12px;
        }

        td {
            font-size: 11px;
            background: #fff
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="box-title">
            <h1 class="title">Slip Gaji</h1>
        </div>
        <img src="{{ asset('img/logo-20230410041830.png') }}" alt="" class="logo">
        <div class="top-text">
            <p>Kepada:</p>
            <p>{{ $pegawai->name }}</p>
            <p>{{ $pegawai->no_telp }}</p>
            <p>{{ $pegawai->alamat }}</p>
            <h4>Rincian Pekerjaan</h4>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Pesanan</th>
                    <th>Tanggal Selesai</th>
                    <th>Pokok</th>
                    <th>Bonus</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($gaji as $item)
                @php
                    $total += $item->total;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ date('d M Y', strtotime($item->selesai)) }}</td>
                    <td>Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->bonus, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="5" style="text-align: center; font-weight: bold">Total Keseluruhan</td>
                    <td style="font-weight: bold;">Rp{{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        <p class="bottom-text">
            Pembayaran ini meliputi gaji untuk satu bulan penuh bekerja, adapun jika terjadi kesalahan bisa menghubungi pihak dari Wangi Project
            <br />
            Terima kasih sudah bekerja sama
        </p>
        <p class="signature">Mr. Wangi Project</p>
        <div class="bottom-side"></div>
        </div>
    </div>

</body>
</html>
