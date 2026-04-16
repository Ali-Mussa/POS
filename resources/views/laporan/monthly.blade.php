@extends('layouts.master')

@section('title')
Monthly Report - {{ date('F Y') }}
@endsection

@section('breadcrumb')
    @parent
    <li><a href="{{ route('laporan.hub') }}">Reports</a></li>
    <li class="active">Monthly Report</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <i class="fa fa-calendar-alt"></i> Monthly Business Report
                    <small>{{ date('F Y') }}</small>
                </h3>
                <div class="box-tools pull-right">
                    <a href="{{ route('laporan.monthly_pdf', [date('Y'), date('m')]) }}" class="btn btn-sm btn-danger" target="_blank">
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
                                <span class="info-box-number">UGX {{ number_format($totalMonthlySales, 0) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box bg-red">
                            <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Expenses</span>
                                <span class="info-box-number">UGX {{ number_format($totalMonthlyExpenses, 0) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box bg-blue">
                            <span class="info-box-icon"><i class="fa fa-bar-chart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Net Income</span>
                                <span class="info-box-number">UGX {{ number_format($netIncomeMonthly, 0) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box bg-yellow">
                            <span class="info-box-icon"><i class="fa fa-list"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Transactions</span>
                                <span class="info-box-number">{{ $totalMonthlyTransactions }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Month Selector -->
                <div class="row" style="margin-bottom: 0; margin-top: 1px;">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Select Month:</label>
                            <input type="month" id="monthSelector" class="form-control" value="{{ date('Y-m') }}">
                        </div>
                    </div>
                </div>

                <!-- Daily Breakdown Chart -->
                <div class="row" style="margin: 0; display: none;">
                    <div class="col-md-12" style="padding: 0;">
                        <canvas id="monthlyChart" height="0"></canvas>
                    </div>
                </div>

                <!-- Daily Breakdown Table -->
                <div class="row" style="margin-top: 1px; margin-bottom: 0;">
                    <div class="col-md-12" style="padding: 0;">
                        <h5 style="margin: 2px 0; font-size: 12px;"><i class="fa fa-calendar-day text-primary"></i> Daily Breakdown</h5>
                        <table class="table table-bordered table-striped" id="dailyTable" style="margin-bottom: 2px;">
                            <thead>
                                <tr style="background-color: #f5f5f5;">
                                    <th style="padding: 2px 4px; font-size: 11px;">Date</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Sales</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Expenses</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Net Income</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Transactions</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Avg Transaction</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyBreakdown as $day)
                                <tr>
                                    <td style="padding: 2px 4px; font-size: 11px;">{{ tanggal_indonesia($day['date'], false) }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">UGX {{ number_format($day['sales'], 0) }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">UGX {{ number_format($day['expenses'], 0) }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right {{ $day['net'] >= 0 ? 'text-green' : 'text-red' }}">
                                        UGX {{ number_format($day['net'], 0) }}
                                    </td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">{{ $day['transactions'] }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">
                                        @if($day['transactions'] > 0)
                                            UGX {{ number_format($day['sales'] / $day['transactions'], 0) }}
                                        @else
                                            UGX 0
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #f5f5f5;">
                                    <th style="padding: 2px 4px; font-size: 11px;">Total</th>
                                    <th style="padding: 2px 4px; font-size: 11px;" class="text-right">UGX {{ number_format($totalMonthlySales, 0) }}</th>
                                    <th style="padding: 2px 4px; font-size: 11px;" class="text-right">UGX {{ number_format($totalMonthlyExpenses, 0) }}</th>
                                    <th style="padding: 2px 4px; font-size: 11px;" class="text-right">UGX {{ number_format($netIncomeMonthly, 0) }}</th>
                                    <th style="padding: 2px 4px; font-size: 11px;" class="text-right">{{ $totalMonthlyTransactions }}</th>
                                    <th style="padding: 2px 4px; font-size: 11px;" class="text-right">
                UGX {{ number_format($totalMonthlyTransactions > 0 ? $totalMonthlySales / $totalMonthlyTransactions : 0, 0) }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Top Sales Days -->
                <div class="row" style="margin-top: 1px; margin-bottom: 0;">
                    <div class="col-md-6" style="padding: 0;">
                        <h5 style="margin: 2px 0; font-size: 12px;"><i class="fa fa-trophy text-yellow"></i> Best Sales Days</h5>
                        <table class="table table-bordered" style="margin-bottom: 2px;">
                            <thead>
                                <tr style="background-color: #f5f5f5;">
                                    <th style="padding: 2px 4px; font-size: 11px;">Date</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Sales Amount</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Transactions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $bestDays = collect($dailyBreakdown)->sortByDesc('sales')->take(5);
                                @endphp
                                @foreach($bestDays as $day)
                                <tr>
                                    <td style="padding: 2px 4px; font-size: 11px;">{{ tanggal_indonesia($day['date'], false) }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">UGX {{ number_format($day['sales'], 0) }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">{{ $day['transactions'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6" style="padding: 0;">
                        <h5 style="margin: 2px 0; font-size: 12px;"><i class="fa fa-exclamation-triangle text-orange"></i> Highest Expense Days</h5>
                        <table class="table table-bordered" style="margin-bottom: 2px;">
                            <thead>
                                <tr style="background-color: #f5f5f5;">
                                    <th style="padding: 2px 4px; font-size: 11px;">Date</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Expense Amount</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Net Income</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $highExpenseDays = collect($dailyBreakdown)->sortByDesc('expenses')->take(5);
                                @endphp
                                @foreach($highExpenseDays as $day)
                                <tr>
                                    <td style="padding: 2px 4px; font-size: 11px;">{{ tanggal_indonesia($day['date'], false) }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">UGX {{ number_format($day['expenses'], 0) }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right {{ $day['net'] >= 0 ? 'text-green' : 'text-red' }}">
                                        UGX {{ number_format($day['net'], 0) }}
                                    </td>
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
    $('#dailyTable').DataTable({
        responsive: true,
        order: [[0, 'desc']],
        pageLength: 31
    });
    
    $('#monthSelector').change(function() {
        var selectedMonth = $(this).val();
        if(selectedMonth) {
            var parts = selectedMonth.split('-');
            window.location.href = '/laporan/monthly/data/' + parts[0] + '/' + parts[1];
        }
    });
    
    // Monthly Performance Chart
    var ctx = document.getElementById('monthlyChart').getContext('2d');
    var dailyLabels = [
        @foreach($dailyBreakdown as $day)
        '{{ $day["day"] }}',
        @endforeach
    ];
    
    var salesData = [
        @foreach($dailyBreakdown as $day)
        {{ $day["sales"] }},
        @endforeach
    ];
    
    var expensesData = [
        @foreach($dailyBreakdown as $day)
        {{ $day["expenses"] }},
        @endforeach
    ];
    
    var monthlyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Sales',
                data: salesData,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }, {
                label: 'Expenses',
                data: expensesData,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
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
