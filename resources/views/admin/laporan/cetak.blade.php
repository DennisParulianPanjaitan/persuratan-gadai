<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - Gerlian Jaya</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .summary-box {
            border: 1px solid #000;
            padding: 15px;
            margin-bottom: 20px;
        }
        .summary-box table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        .summary-box table td {
            padding: 4px;
            border: none;
            font-size: 14px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 13px;
        }
        .data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: left;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 14px;
        }
        .signature-space {
            margin-top: 80px;
            font-weight: bold;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Print Laporan</button>
    </div>

    <div class="header">
        <h1>GERLIAN JAYA</h1>
        <p>Layanan Gadai Terpercaya<br>Jl. Contoh Alamat No. 123, Kota Anda, Telp: 08123456789</p>
    </div>

    <div class="report-title">
        LAPORAN ARUS KAS GADAI
        <br>
        <span style="font-size: 14px; font-weight: normal;">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span>
    </div>

    <div class="summary-box">
        <table>
            <tr>
                <td width="30%">Total Pengeluaran (Gadai Baru)</td>
                <td width="5%">:</td>
                <td width="65%">Rp {{ number_format($data['summary']['total_pengeluaran'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Pemasukan (Tebus + Bunga + Lelang)</td>
                <td>:</td>
                <td>Rp {{ number_format($data['summary']['total_pemasukan'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Laba / Rugi Bersih</td>
                <td style="font-weight: bold;">:</td>
                <td style="font-weight: bold;">Rp {{ number_format($data['summary']['arus_kas'], 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- 1. GADAI BARU -->
    <div class="section-title">1. RINCIAN PENGELUARAN (GADAI BARU)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Kode Transaksi</th>
                <th width="35%">Pelanggan</th>
                <th width="25%" class="text-right">Uang Pinjaman (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalGadai = 0; @endphp
            @forelse ($data['gadaiBaru'] as $idx => $row)
                @php $totalGadai += $row->uang_pinjaman; @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal_gadai)->format('d/m/Y') }}</td>
                    <td>{{ $row->kode_transaksi }}</td>
                    <td>{{ $row->pelanggan->nama ?? '-' }}</td>
                    <td class="text-right">{{ number_format($row->uang_pinjaman, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Nihil</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total:</th>
                <th class="text-right">{{ number_format($totalGadai, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <!-- 2. PEMASUKAN TEBUS & BUNGA -->
    <div class="section-title">2. RINCIAN PEMASUKAN (PENEBUSAN & PERPANJANGAN)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Kode Transaksi</th>
                <th width="20%">Keterangan</th>
                <th width="40%" class="text-right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalIn = 0; $no = 1; @endphp
            
            @foreach ($data['gadaiTebus'] as $row)
                @php $totalIn += $row->total_ditebus; @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal_ditebus)->format('d/m/Y') }}</td>
                    <td>{{ $row->kode_transaksi }}</td>
                    <td>Tebus Gadai</td>
                    <td class="text-right">{{ number_format($row->total_ditebus, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            @foreach ($data['perpanjangan'] as $row)
                @php $totalIn += $row->biaya_perpanjangan; @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal_perpanjangan)->format('d/m/Y') }}</td>
                    <td>{{ $row->transaksiGadai->kode_transaksi ?? '-' }}</td>
                    <td>Bunga Perpanjangan</td>
                    <td class="text-right">{{ number_format($row->biaya_perpanjangan, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            @if($no == 1)
                <tr><td colspan="5" class="text-center">Nihil</td></tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total:</th>
                <th class="text-right">{{ number_format($totalIn, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <!-- 3. PEMASUKAN LELANG -->
    <div class="section-title">3. RINCIAN PEMASUKAN (PENJUALAN LELANG)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Kode Transaksi</th>
                <th width="35%">Barang</th>
                <th width="25%" class="text-right">Harga Laku (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalLelang = 0; @endphp
            @forelse ($data['penjualan'] as $idx => $row)
                @php $totalLelang += $row->harga_jual; @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal_jual)->format('d/m/Y') }}</td>
                    <td>{{ $row->transaksiGadai->kode_transaksi ?? '-' }}</td>
                    <td>{{ $row->transaksiGadai->barang->nama_barang ?? '-' }}</td>
                    <td class="text-right">{{ number_format($row->harga_jual, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Nihil</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total:</th>
                <th class="text-right">{{ number_format($totalLelang, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }}<br>
        Mengetahui,
        <div class="signature-space">
            ( Pimpinan Gerlian Jaya )
        </div>
    </div>

</body>
</html>
