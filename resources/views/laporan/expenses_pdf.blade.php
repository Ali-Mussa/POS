<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expenses Report</title>
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
    <h3>Expenses Report</h3>
    <h4>{{ tanggal_indonesia($awal, false) }} &mdash; {{ tanggal_indonesia($akhir, false) }}</h4>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Description</th>
                <th style="text-align:right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach ($pengeluaran as $e)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ tanggal_indonesia($e->created_at, false) }}</td>
                <td>{{ $e->deskripsi }}</td>
                <td style="text-align:right;">UGX {{ format_uang($e->nominal) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" style="text-align:right;"><strong>Total Expenses</strong></td>
                <td style="text-align:right;"><strong>UGX {{ format_uang($total) }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
