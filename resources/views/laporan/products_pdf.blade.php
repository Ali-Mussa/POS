<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products Report</title>
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <style>
        body { font-size: 11px; }
        h3, h4 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table th, table td { border: 1px solid #ccc; padding: 4px 7px; }
        table th { background: #e8e8e8; }
        .low { color: #c0392b; font-weight: bold; }
        .ok { color: #27ae60; }
    </style>
</head>
<body>
    <h3>Products / Stock Report</h3>
    <h4>Generated: {{ date('d M Y') }}</h4>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Product Name</th>
                <th>Category</th>
                <th style="text-align:right;">Stock</th>
                <th style="text-align:right;">Buy Price</th>
                <th style="text-align:right;">Sell Price</th>
                <th style="text-align:right;">Discount</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach ($produk as $p)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $p->kode_produk }}</td>
                <td>{{ $p->nama_produk }}</td>
                <td>{{ $p->kategori->nama_kategori ?? '-' }}</td>
                <td style="text-align:right;" class="{{ $p->stok <= 5 ? 'low' : 'ok' }}">{{ $p->stok }}</td>
                <td style="text-align:right;">UGX {{ format_uang($p->harga_beli) }}</td>
                <td style="text-align:right;">UGX {{ format_uang($p->harga_jual) }}</td>
                <td style="text-align:right;">{{ $p->diskon }}%</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Total Products</strong></td>
                <td style="text-align:right;"><strong>{{ $produk->count() }}</strong></td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
    <p style="margin-top:10px;font-size:10px;color:#888;">* Red stock = 5 or fewer units remaining</p>
</body>
</html>
