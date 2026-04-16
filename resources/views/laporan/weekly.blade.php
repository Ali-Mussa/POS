@extends('layouts.master')

@section('title')
Weekly Report - {{ tanggal_indonesia($startOfWeek, false) }} to {{ tanggal_indonesia($endOfWeek, false) }}
@endsection

@section('breadcrumb')
    @parent
    <li><a href="{{ route('laporan.hub') }}">Reports</a></li>
    <li class="active">Weekly Report</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <i class="fa fa-calendar-week"></i> Weekly Business Report
                    <small>{{ tanggal_indonesia($startOfWeek, false) }} - {{ tanggal_indonesia($endOfWeek, false) }}</small>
                </h3>
                <div class="box-tools pull-right">
                    <a href="{{ route('laporan.weekly_pdf', [$startOfWeek, $endOfWeek]) }}" class="btn btn-sm btn-danger" target="_blank">
                        <i class="fa fa-file-pdf-o"></i> Export PDF
                    </a>
                </div>
            </div>
            <div class="box-body" style="padding: 3px;">
                <!-- Summary Cards -->
                <div class="row" style="margin-bottom: 0;">
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box bg-green">
                            <span class="info-box-icon"><i class="fa fa-money"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Sales</span>
                                <span class="info-box-number">UGX {{ number_format($totalWeeklySales, 0) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box bg-red">
                            <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Expenses</span>
                                <span class="info-box-number">UGX {{ number_format($totalWeeklyExpenses, 0) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box bg-blue">
                            <span class="info-box-icon"><i class="fa fa-bar-chart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Net Income</span>
                                <span class="info-box-number">UGX {{ number_format($netIncomeWeekly, 0) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box bg-yellow">
                            <span class="info-box-icon"><i class="fa fa-list"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Transactions</span>
                                <span class="info-box-number">{{ $totalWeeklyTransactions }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Week Selector -->
                <div class="row" style="margin-bottom: 0; margin-top: 1px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Week Range:</label>
                            <div class="input-group">
                                <input type="date" id="weekStart" class="form-control" value="{{ $startOfWeek }}">
                                <span class="input-group-addon">to</span>
                                <input type="date" id="weekEnd" class="form-control" value="{{ $endOfWeek }}">
                                <span class="input-group-btn">
                                    <button type="button" id="updateWeek" class="btn btn-info">Update</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daily Breakdown Chart -->
                <div class="row" style="margin: 0; display: none;">
                    <div class="col-md-12" style="padding: 0; display: none;">
                        <canvas id="weeklyChart" height="0"></canvas>
                    </div>
                </div>

                <!-- Sales Table -->
                <div class="row" style="margin-top: 1px; margin-bottom: 0;">
                    <div class="col-md-12" style="padding: 0;">
                        <h5 style="margin: 2px 0; font-size: 12px;"><i class="fa fa-shopping-cart text-green"></i> Sales Transactions</h5>
                        <table class="table table-bordered table-striped" id="salesTable" style="margin-bottom: 2px;">
                            <thead>
                                <tr style="background-color: #f5f5f5;">
                                    <th style="padding: 2px 4px; font-size: 11px;">Receipt #</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Date & Time</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Customer</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Items</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Total</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Payment</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Cashier</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($weeklySales as $sale)
                                <tr>
                                    <td style="padding: 2px 4px; font-size: 11px;">{{ tambah_nol_didepan($sale->id_penjualan, 10) }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;">{{ $sale->created_at->format('d M Y H:i:s') }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;">{{ $sale->nama_pelanggan ?? 'Guest' }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">{{ $sale->total_item }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">UGX {{ number_format($sale->bayar, 0) }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;">
                                        @php
                                            $pm = $sale->payment_method ?? 'cash';
                                            if ($pm === 'cash') { echo 'Cash'; }
                                            elseif ($pm === 'card') { echo 'Card'; }
                                            elseif ($pm === 'mobile_money') {
                                                $prov = $sale->mobile_money_provider ?? '';
                                                if ($prov === 'mtn_momo') echo 'MTN MoMo';
                                                elseif ($prov === 'airtel_money') echo 'Airtel Money';
                                                else echo 'Mobile Money';
                                            }
                                        @endphp
                                    </td>
                                    <td style="padding: 2px 4px; font-size: 11px;">{{ $sale->user->name ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Expenses Table -->
                <div class="row" style="margin-top: 1px; margin-bottom: 0;">
                    <div class="col-md-12" style="padding: 0;">
                        <h5 style="margin: 2px 0; font-size: 12px;"><i class="fa fa-money text-red"></i> Expenses</h5>
                        <table class="table table-bordered table-striped" id="expensesTable" style="margin-bottom: 2px;">
                            <thead>
                                <tr style="background-color: #f5f5f5;">
                                    <th style="padding: 2px 4px; font-size: 11px;">Date & Time</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Description</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Category</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($weeklyExpenses as $expense)
                                <tr>
                                    <td style="padding: 2px 4px; font-size: 11px;">{{ $expense->created_at->format('d M Y H:i:s') }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;">{{ $expense->deskripsi }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;">{{ $expense->kategori ?? 'General' }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">UGX {{ number_format($expense->nominal, 0) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    $('#salesTable').DataTable({
        responsive: true,
        order: [[1, 'desc']]
    });
    
    $('#expensesTable').DataTable({
        responsive: true,
        order: [[0, 'desc']]
    });
    
    $('#updateWeek').click(function() {
        var start = $('#weekStart').val();
        var end = $('#weekEnd').val();
        if(start && end) {
            window.location.href = '/laporan/weekly/data/' + start + '/' + end;
        }
    });
    
    // Weekly Performance Chart
    var ctx = document.getElementById('weeklyChart').getContext('2d');
    var weeklyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
            ],
            datasets: [{
                label: 'Sales',
                data: [
                    @php
                        for($i = 0; $i < 7; $i++) {
                            $date = date('Y-m-d', strtotime($startOfWeek . ' +' . $i . ' days'));
                            $sales = \App\Models\Penjualan::whereDate('created_at', $date)->where('bayar', '>', 0)->sum('bayar');
                            echo $sales . ', ';
                        }
                    @endphp
                ],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: true
            }, {
                label: 'Expenses',
                data: [
                    @php
                        for($i = 0; $i < 7; $i++) {
                            $date = date('Y-m-d', strtotime($startOfWeek . ' +' . $i . ' days'));
                            $expenses = \App\Models\Pengeluaran::whereDate('created_at', $date)->sum('nominal');
                            echo $expenses . ', ';
                        }
                    @endphp
                ],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'UGX ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': UGX ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
