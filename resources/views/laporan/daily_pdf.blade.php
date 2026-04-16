<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Report - {{ $date }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f5f5f5; }
        .text-right { text-align: right; }
        .summary-box { background: #f9f9f9; padding: 10px; margin-bottom: 15px; border-left: 4px solid #333; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daily Report - {{ tanggal_indonesia($date, false) }}</h1>
        <p>Generated on: {{ date('d M Y H:i:s') }}</p>
    </div>

    <div class="summary-box">
        <h3>Summary</h3>
        <table>
            <tr>
                <td><strong>Total Sales:</strong></td>
                <td class="text-right">UGX {{ number_format($totalSales, 0) }}</td>
            </tr>
            <tr>
                <td><strong>Total Expenses:</strong></td>
                <td class="text-right">UGX {{ number_format($totalExpenses, 0) }}</td>
            </tr>
            <tr>
                <td><strong>Net Income:</strong></td>
                <td class="text-right">UGX {{ number_format($netIncome, 0) }}</td>
            </tr>
            <tr>
                <td><strong>Total Transactions:</strong></td>
                <td class="text-right">{{ count($sales) }}</td>
            </tr>
        </table>
    </div>

    <h3>Sales Transactions</h3>
    <table>
        <thead>
            <tr>
                <th>Receipt #</th>
                <th>Time</th>
                <th>Customer</th>
                <th>Items</th>
                <th class="text-right">Total</th>
                <th>Payment</th>
                <th>Cashier</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td>{{ tambah_nol_didepan($sale->id_penjualan, 10) }}</td>
                <td>{{ $sale->created_at->format('H:i:s') }}</td>
                <td>{{ $sale->nama_pelanggan ?? 'Guest' }}</td>
                <td>{{ $sale->total_item }}</td>
                <td class="text-right">UGX {{ number_format($sale->bayar, 0) }}</td>
                <td>{{ $sale->payment_method ?? 'Cash' }}</td>
                <td>{{ $sale->user->name ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Expenses</h3>
    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>Description</th>
                <th>Category</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
            <tr>
                <td>{{ $expense->created_at->format('H:i:s') }}</td>
                <td>{{ $expense->deskripsi }}</td>
                <td>{{ $expense->kategori ?? 'General' }}</td>
                <td class="text-right">UGX {{ number_format($expense->nominal, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 10px; text-align: center; color: #666;">
        <p>© {{ date('Y') }} POS System. All rights reserved.</p>
    </div>
</body>
</html>
