@extends('layouts.master')

@section('title')
Annual Report - {{ date('Y') }}
@endsection

@section('breadcrumb')
    @parent
    <li><a href="{{ route('laporan.hub') }}">Reports</a></li>
    <li class="active">Annual Report</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <i class="fa fa-calendar"></i> Annual Business Report
                    <small>{{ $currentYear }}</small>
                </h3>
                <div class="box-tools pull-right">
                    <a href="{{ route('laporan.annual_pdf', $currentYear) }}" class="btn btn-sm btn-danger" target="_blank">
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
                                <span class="info-box-number">UGX {{ number_format($totalAnnualSales, 0) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box bg-red">
                            <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Expenses</span>
                                <span class="info-box-number">UGX {{ number_format($totalAnnualExpenses, 0) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box bg-blue">
                            <span class="info-box-icon"><i class="fa fa-bar-chart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Net Income</span>
                                <span class="info-box-number">UGX {{ number_format($netIncomeAnnual, 0) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box bg-yellow">
                            <span class="info-box-icon"><i class="fa fa-list"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Transactions</span>
                                <span class="info-box-number">{{ $totalAnnualTransactions }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Year Selector -->
                <div class="row" style="margin-bottom: 0; margin-top: 1px;">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Select Year:</label>
                            <select id="yearSelector" class="form-control">
                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Monthly Performance Chart -->
                <div class="row" style="margin: 0; display: none;">
                    <div class="col-md-12" style="padding: 0;">
                        <canvas id="annualChart" height="0"></canvas>
                    </div>
                </div>

                <!-- Monthly Breakdown Table -->
                <div class="row" style="margin-top: 1px; margin-bottom: 0;">
                    <div class="col-md-12" style="padding: 0;">
                        <h5 style="margin: 2px 0; font-size: 12px;"><i class="fa fa-calendar-alt text-primary"></i> Monthly Breakdown</h5>
                        <table class="table table-bordered table-striped" id="monthlyTable" style="margin-bottom: 2px;">
                            <thead>
                                <tr style="background-color: #f5f5f5;">
                                    <th style="padding: 2px 4px; font-size: 11px;">Month</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Sales</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Expenses</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Net Income</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Transactions</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Avg Transaction</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Growth %</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyBreakdown as $index => $month)
                                <tr>
                                    <td style="padding: 2px 4px; font-size: 11px;">{{ $month['monthName'] }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">UGX {{ number_format($month['sales'], 0) }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">UGX {{ number_format($month['expenses'], 0) }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right {{ $month['net'] >= 0 ? 'text-green' : 'text-red' }}">
                                        UGX {{ number_format($month['net'], 0) }}
                                    </td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">{{ $month['transactions'] }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">
                                        @if($month['transactions'] > 0)
                                            UGX {{ number_format($month['sales'] / $month['transactions'], 0) }}
                                        @else
                                            UGX 0
                                        @endif
                                    </td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">
                                        @if($index > 0 && $monthlyBreakdown[$index - 1]['sales'] > 0)
                                            @php
                                                $growth = (($month['sales'] - $monthlyBreakdown[$index - 1]['sales']) / $monthlyBreakdown[$index - 1]['sales']) * 100;
                                            @endphp
                                            @if($growth > 0)
                                                <span class="text-green">+{{ number_format($growth, 1) }}%</span>
                                            @elseif($growth < 0)
                                                <span class="text-red">{{ number_format($growth, 1) }}%</span>
                                            @else
                                                <span class="text-yellow">0.0%</span>
                                            @endif
                                        @else
                                            <span class="text-yellow">0.0%</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #f5f5f5;">
                                    <th style="padding: 2px 4px; font-size: 11px;">Total {{ $currentYear }}</th>
                                    <th style="padding: 2px 4px; font-size: 11px;" class="text-right">UGX {{ number_format($totalAnnualSales, 0) }}</th>
                                    <th style="padding: 2px 4px; font-size: 11px;" class="text-right">UGX {{ number_format($totalAnnualExpenses, 0) }}</th>
                                    <th style="padding: 2px 4px; font-size: 11px;" class="text-right">UGX {{ number_format($netIncomeAnnual, 0) }}</th>
                                    <th style="padding: 2px 4px; font-size: 11px;" class="text-right">{{ $totalAnnualTransactions }}</th>
                                    <th style="padding: 2px 4px; font-size: 11px;" class="text-right">
                                        UGX {{ number_format($totalAnnualTransactions > 0 ? $totalAnnualSales / $totalAnnualTransactions : 0, 0) }}
                                    </th>
                                    <th style="padding: 2px 4px; font-size: 11px;" class="text-right">
                                        @if($salesChange != 0)
                                            <span class="{{ $salesChange > 0 ? 'text-green' : 'text-red' }}">
                                                {{ $salesChange > 0 ? '+' : '' }}{{ number_format($salesChange, 1) }}%
                                            </span>
                                        @else
                                            <span class="text-yellow">0.0%</span>
                                        @endif
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Quarterly Analysis -->
                <div class="row" style="margin-top: 1px; margin-bottom: 0;">
                    <div class="col-md-12" style="padding: 0;">
                        <h5 style="margin: 2px 0; font-size: 12px;"><i class="fa fa-chart-pie text-purple"></i> Quarterly Analysis</h5>
                        <div class="row">
                            @php
                                $quarters = [
                                    1 => ['months' => [1, 2, 3], 'name' => 'Q1'],
                                    2 => ['months' => [4, 5, 6], 'name' => 'Q2'],
                                    3 => ['months' => [7, 8, 9], 'name' => 'Q3'],
                                    4 => ['months' => [10, 11, 12], 'name' => 'Q4']
                                ];
                            @endphp
                            @foreach($quarters as $quarter => $data)
                            @php
                                $quarterSales = 0;
                                $quarterExpenses = 0;
                                $quarterTransactions = 0;
                                foreach($data['months'] as $month) {
                                    $monthData = collect($monthlyBreakdown)->where('month', $month)->first();
                                    if($monthData) {
                                        $quarterSales += $monthData['sales'];
                                        $quarterExpenses += $monthData['expenses'];
                                        $quarterTransactions += $monthData['transactions'];
                                    }
                                }
                            @endphp
                            <div class="col-md-3" style="padding: 1px;">
                                <div class="box box-{{ $quarter == 1 ? 'info' : ($quarter == 2 ? 'success' : ($quarter == 3 ? 'warning' : 'danger')) }}" style="margin-bottom: 2px;">
                                    <div class="box-header text-center" style="padding: 2px;">
                                        <h5 style="margin: 0; font-size: 12px;">{{ $data['name'] }} {{ $currentYear }}</h5>
                                    </div>
                                    <div class="box-body" style="padding: 3px;">
                                        <table class="table table-condensed" style="margin-bottom: 0;">
                                            <tr>
                                                <td style="padding: 1px 2px; font-size: 11px;">Sales:</td>
                                                <td style="padding: 1px 2px; font-size: 11px;" class="text-right">UGX {{ number_format($quarterSales, 0) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 1px 2px; font-size: 11px;">Expenses:</td>
                                                <td style="padding: 1px 2px; font-size: 11px;" class="text-right">UGX {{ number_format($quarterExpenses, 0) }}</td>
                                            </tr>
                                            <tr class="{{ ($quarterSales - $quarterExpenses) >= 0 ? 'text-green' : 'text-red' }}">
                                                <td style="padding: 1px 2px; font-size: 11px;"><strong>Net:</strong></td>
                                                <td style="padding: 1px 2px; font-size: 11px;" class="text-right"><strong>UGX {{ number_format($quarterSales - $quarterExpenses, 0) }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 1px 2px; font-size: 11px;">Transactions:</td>
                                                <td style="padding: 1px 2px; font-size: 11px;" class="text-right">{{ $quarterTransactions }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Best & Worst Performance -->
                <div class="row" style="margin-top: 1px; margin-bottom: 0;">
                    <div class="col-md-6" style="padding: 0;">
                        <h5 style="margin: 2px 0; font-size: 12px;"><i class="fa fa-trophy text-yellow"></i> Best Performing Months</h5>
                        <table class="table table-bordered" style="margin-bottom: 2px;">
                            <thead>
                                <tr style="background-color: #f5f5f5;">
                                    <th style="padding: 2px 4px; font-size: 11px;">Month</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Sales</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Net Income</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Transactions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $bestMonths = collect($monthlyBreakdown)->sortByDesc('sales')->take(5);
                                @endphp
                                @foreach($bestMonths as $month)
                                <tr>
                                    <td style="padding: 2px 4px; font-size: 11px;">{{ $month['monthName'] }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">UGX {{ number_format($month['sales'], 0) }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right {{ $month['net'] >= 0 ? 'text-green' : 'text-red' }}">
                                        UGX {{ number_format($month['net'], 0) }}
                                    </td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">{{ $month['transactions'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6" style="padding: 0;">
                        <h5 style="margin: 2px 0; font-size: 12px;"><i class="fa fa-exclamation-triangle text-orange"></i> High Expense Months</h5>
                        <table class="table table-bordered" style="margin-bottom: 2px;">
                            <thead>
                                <tr style="background-color: #f5f5f5;">
                                    <th style="padding: 2px 4px; font-size: 11px;">Month</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Expenses</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Net Income</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Expense Ratio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $highExpenseMonths = collect($monthlyBreakdown)->sortByDesc('expenses')->take(5);
                                @endphp
                                @foreach($highExpenseMonths as $month)
                                <tr>
                                    <td style="padding: 2px 4px; font-size: 11px;">{{ $month['monthName'] }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">UGX {{ number_format($month['expenses'], 0) }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right {{ $month['net'] >= 0 ? 'text-green' : 'text-red' }}">
                                        UGX {{ number_format($month['net'], 0) }}
                                    </td>
                                    <td style="padding: 2px 4px; font-size: 11px;" class="text-right">
                                        @if($month['sales'] > 0)
                                            {{ number_format(($month['expenses'] / $month['sales']) * 100, 1) }}%
                                        @else
                                            -
                                        @endif
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
    $('#monthlyTable').DataTable({
        responsive: true,
        order: [[0, 'asc']],
        pageLength: 12
    });
    
    $('#yearSelector').change(function() {
        var selectedYear = $(this).val();
        if(selectedYear) {
            window.location.href = '/laporan/annual/data/' + selectedYear;
        }
    });
    
    // Annual Performance Chart
    var ctx = document.getElementById('annualChart').getContext('2d');
    var monthlyLabels = [
        @foreach($monthlyBreakdown as $month)
        '{{ $month["monthName"] }}',
        @endforeach
    ];
    
    var salesData = [
        @foreach($monthlyBreakdown as $month)
        {{ $month["sales"] }},
        @endforeach
    ];
    
    var expensesData = [
        @foreach($monthlyBreakdown as $month)
        {{ $month["expenses"] }},
        @endforeach
    ];
    
    var netData = [
        @foreach($monthlyBreakdown as $month)
        {{ $month["net"] }},
        @endforeach
    ];
    
    var annualChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Sales',
                data: salesData,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2
            }, {
                label: 'Expenses',
                data: expensesData,
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2
            }, {
                label: 'Net Income',
                data: netData,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2
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
