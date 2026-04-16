@extends('layouts.master')

@section('title')
Daily Report - {{ date('d M Y') }}
@endsection

@section('breadcrumb')
    @parent
    <li><a href="{{ route('laporan.hub') }}">Reports</a></li>
    <li class="active">Daily Report</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <i class="fa fa-calendar-day"></i> Daily Business Report - {{ tanggal_indonesia($today, false) }}
                </h3>
                <div class="box-tools pull-right">
                    <a href="{{ route('laporan.daily_pdf', $today) }}" class="btn btn-sm btn-danger" target="_blank">
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
                                <span class="info-box-number">UGX {{ number_format($totalTodaySales, 0) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box bg-red">
                            <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Expenses</span>
                                <span class="info-box-number">UGX {{ number_format($totalTodayExpenses, 0) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box bg-blue">
                            <span class="info-box-icon"><i class="fa fa-bar-chart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Net Income</span>
                                <span class="info-box-number">UGX {{ number_format($netIncomeToday, 0) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box bg-yellow">
                            <span class="info-box-icon"><i class="fa fa-list"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Transactions</span>
                                <span class="info-box-number">{{ $totalTodayTransactions }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date Selector -->
                <div class="row" style="margin-bottom: 0; margin-top: 1px;">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Select Date:</label>
                            <input type="date" id="dateSelector" class="form-control" value="{{ $today }}">
                        </div>
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
                                    <th style="padding: 2px 4px; font-size: 11px;">Time</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Customer</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Items</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Total</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Payment</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Cashier</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todaySales as $sale)
                                <tr>
                                    <td style="padding: 2px 4px; font-size: 11px;">{{ tambah_nol_didepan($sale->id_penjualan, 10) }}</td>
                                    <td style="padding: 2px 4px; font-size: 11px;">{{ $sale->created_at->format('H:i:s') }}</td>
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
                                    <th style="padding: 2px 4px; font-size: 11px;">Time</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Description</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Category</th>
                                    <th style="padding: 2px 4px; font-size: 11px;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayExpenses as $expense)
                                <tr>
                                    <td style="padding: 2px 4px; font-size: 11px;">{{ $expense->created_at->format('H:i:s') }}</td>
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
    
    $('#dateSelector').change(function() {
        var selectedDate = $(this).val();
        if(selectedDate) {
            window.location.href = '/laporan/daily/data/' + selectedDate;
        }
    });
});
</script>
@endpush
