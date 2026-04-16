<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <style>
        body { font-size: 12px; }
        h3, h4 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table th, table td { border: 1px solid #ccc; padding: 5px 8px; }
        table th { background: #e8e8e8; }
        .total-row td { font-weight: bold; background: #f5f5f5; }
    </style>
</head>
<body>
    <h3>Sales Report</h3>
    <h4>{{ tanggal_indonesia($awal, false) }} &mdash; {{ tanggal_indonesia($akhir, false) }}</h4>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tx ID</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Cashier</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach ($penjualan as $p)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ tambah_nol_didepan($p->id_penjualan, 6) }}</td>
                <td>{{ tanggal_indonesia($p->created_at, false) }}</td>
                <td>{{ $p->nama_pelanggan ?? '-' }}</td>
                <td style="text-align:right;">{{ format_uang($p->total_item) }}</td>
                <td style="text-align:right;">UGX {{ format_uang($p->total_harga) }}</td>
                <td style="text-align:right;">UGX {{ format_uang($p->bayar) }}</td>
                <td>{{ $p->user->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" style="text-align:right;"><strong>Grand Total</strong></td>
                <td style="text-align:right;"><strong>UGX {{ format_uang($total) }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
