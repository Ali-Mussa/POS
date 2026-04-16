<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Annual Report - {{ $year }}</title>
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
        <h1>Annual Report - {{ $year }}</h1>
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
        </table>
    </div>

    <h3>Monthly Breakdown</h3>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th class="text-right">Sales</th>
                <th class="text-right">Expenses</th>
                <th class="text-right">Net Income</th>
            </tr>
        </thead>
        <tbody>
            @php
                $monthlyData = [];
                for ($m = 1; $m <= 12; $m++) {
                    $monthStr = sprintf("%04d-%02d", $year, $m);
                    $monthSales = $sales->where('created_at', 'LIKE', "$monthStr%")->sum('bayar');
                    $monthExpenses = $expenses->where('created_at', 'LIKE', "$monthStr%")->sum('nominal');
                    $monthlyData[] = [
                        'name' => date('F', mktime(0, 0, 0, $m, 1)),
                        'sales' => $monthSales,
                        'expenses' => $monthExpenses,
                        'net' => $monthSales - $monthExpenses
                    ];
                }
            @endphp
            @foreach($monthlyData as $month)
            <tr>
                <td>{{ $month['name'] }}</td>
                <td class="text-right">UGX {{ number_format($month['sales'], 0) }}</td>
                <td class="text-right">UGX {{ number_format($month['expenses'], 0) }}</td>
                <td class="text-right">UGX {{ number_format($month['net'], 0) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f5f5f5; font-weight: bold;">
                <td>Total</td>
                <td class="text-right">UGX {{ number_format($totalSales, 0) }}</td>
                <td class="text-right">UGX {{ number_format($totalExpenses, 0) }}</td>
                <td class="text-right">UGX {{ number_format($netIncome, 0) }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 30px; font-size: 10px; text-align: center; color: #666;">
        <p>© {{ $year }} POS System. All rights reserved.</p>
    </div>
</body>
</html>
