@extends('layouts.master')

@section('title')
Reports Hub
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Reports</li>
@endsection

@section('content')
<div class="row" style="margin-bottom: 3px;">
    <!-- Sales Report Card -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box bg-green hover-expand-effect">
            <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Sales This Month</span>
                <span class="info-box-number">UGX {{ number_format($totalSalesMonth, 0) }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">{{ $totalTxMonth }} transactions</span>
            </div>
        </div>
    </div>
    <!-- Expenses Card -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box bg-red hover-expand-effect">
            <span class="info-box-icon"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Expenses This Month</span>
                <span class="info-box-number">UGX {{ number_format($totalExpMonth, 0) }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Total expenditure</span>
            </div>
        </div>
    </div>
    <!-- Net Income Card -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua hover-expand-effect">
            <span class="info-box-icon"><i class="fa fa-bar-chart"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Net Income This Month</span>
                <span class="info-box-number">UGX {{ number_format($totalSalesMonth - $totalExpMonth, 0) }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">Sales minus expenses</span>
            </div>
        </div>
    </div>
    <!-- Products Card -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow hover-expand-effect">
            <span class="info-box-icon"><i class="fa fa-cubes"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Products</span>
                <span class="info-box-number">{{ $totalProducts }}</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span class="progress-description">In inventory</span>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-bottom: 3px;">
    <!-- Daily Report Card -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="box box-info">
            <div class="box-header with-border text-center">
                <i class="fa fa-calendar-day fa-2x text-info" style="margin: 5px 0;"></i>
                <h4 class="box-title" style="display:block; margin-bottom:3px;"><strong>Daily Report</strong></h4>
            </div>
            <div class="box-body text-center">
                <p>View today's business performance with sales, expenses, and transaction details.</p>
            </div>
            <div class="box-footer text-center">
                <a href="{{ route('laporan.daily') }}" class="btn btn-info btn-flat btn-block">
                    <i class="fa fa-eye"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Weekly Report Card -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="box box-success">
            <div class="box-header with-border text-center">
                <i class="fa fa-calendar-week fa-2x text-green" style="margin: 5px 0;"></i>
                <h4 class="box-title" style="display:block; margin-bottom:3px;"><strong>Weekly Report</strong></h4>
            </div>
            <div class="box-body text-center">
                <p>Analyze weekly trends and compare performance with previous weeks.</p>
            </div>
            <div class="box-footer text-center">
                <a href="{{ route('laporan.weekly') }}" class="btn btn-success btn-flat btn-block">
                    <i class="fa fa-eye"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Monthly Report Card -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="box box-warning">
            <div class="box-header with-border text-center">
                <i class="fa fa-calendar-alt fa-2x text-yellow" style="margin: 5px 0;"></i>
                <h4 class="box-title" style="display:block; margin-bottom:3px;"><strong>Monthly Report</strong></h4>
            </div>
            <div class="box-body text-center">
                <p>Comprehensive monthly analysis with daily breakdowns and trends.</p>
            </div>
            <div class="box-footer text-center">
                <a href="{{ route('laporan.monthly') }}" class="btn btn-warning btn-flat btn-block">
                    <i class="fa fa-eye"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Annual Report Card -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="box box-danger">
            <div class="box-header with-border text-center">
                <i class="fa fa-calendar fa-2x text-red" style="margin: 5px 0;"></i>
                <h4 class="box-title" style="display:block; margin-bottom:3px;"><strong>Annual Report</strong></h4>
            </div>
            <div class="box-body text-center">
                <p>Year-over-year performance analysis with monthly breakdowns.</p>
            </div>
            <div class="box-footer text-center">
                <a href="{{ route('laporan.annual') }}" class="btn btn-danger btn-flat btn-block">
                    <i class="fa fa-eye"></i> View Report
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 3px;">
    <!-- Sales Report -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="box box-primary">
            <div class="box-header with-border text-center">
                <i class="fa fa-shopping-cart fa-2x text-blue" style="margin: 5px 0;"></i>
                <h4 class="box-title" style="display:block; margin-bottom:3px;"><strong>Sales Report</strong></h4>
            </div>
            <div class="box-body text-center">
                <p>View detailed sales transactions with customer names, amounts, and cashier info.</p>
            </div>
            <div class="box-footer text-center">
                <a href="{{ route('laporan.sales') }}" class="btn btn-primary btn-flat btn-block">
                    <i class="fa fa-eye"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Income Report -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="box box-success">
            <div class="box-header with-border text-center">
                <i class="fa fa-bar-chart fa-2x text-green" style="margin: 5px 0;"></i>
                <h4 class="box-title" style="display:block; margin-bottom:3px;"><strong>Income Report</strong></h4>
            </div>
            <div class="box-body text-center">
                <p>Daily income summary comparing sales, purchases and expenses over a date range.</p>
            </div>
            <div class="box-footer text-center">
                <a href="{{ route('laporan.index') }}" class="btn btn-success btn-flat btn-block">
                    <i class="fa fa-eye"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Expenses Report -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="box box-danger">
            <div class="box-header with-border text-center">
                <i class="fa fa-money fa-2x text-red" style="margin: 5px 0;"></i>
                <h4 class="box-title" style="display:block; margin-bottom:3px;"><strong>Expenses Report</strong></h4>
            </div>
            <div class="box-body text-center">
                <p>Track all business expenses by date range with descriptions and amounts.</p>
            </div>
            <div class="box-footer text-center">
                <a href="{{ route('laporan.expenses') }}" class="btn btn-danger btn-flat btn-block">
                    <i class="fa fa-eye"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Products Report -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="box box-warning">
            <div class="box-header with-border text-center">
                <i class="fa fa-cubes fa-2x text-yellow" style="margin: 5px 0;"></i>
                <h4 class="box-title" style="display:block; margin-bottom:3px;"><strong>Products Report</strong></h4>
            </div>
            <div class="box-body text-center">
                <p>Current product inventory showing stock levels, categories, and pricing.</p>
            </div>
            <div class="box-footer text-center">
                <a href="{{ route('laporan.products') }}" class="btn btn-warning btn-flat btn-block">
                    <i class="fa fa-eye"></i> View Report
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
