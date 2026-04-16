@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ number_format($todaySales) }}</h3>
                <p>Today's Sales</p>
            </div>
            <div class="icon">
                <i class="fa fa-dollar"></i>
            </div>
            <a href="{{ route('penjualan.history') }}" class="small-box-footer">View Details <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $todayTxCount }}</h3>
                <p>Today's Transactions</p>
            </div>
            <div class="icon">
                <i class="fa fa-cart-plus"></i>
            </div>
            <a href="{{ route('penjualan.history') }}" class="small-box-footer">View Details <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $activeTransactions }}</h3>
                <p>Active Transactions</p>
            </div>
            <div class="icon">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
            <a href="{{ route('transaksi.aktif') }}" class="small-box-footer">View Active <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $lowStockCount }}</h3>
                <p>Low Stock Items</p>
            </div>
            <div class="icon">
                <i class="fa fa-warning"></i>
            </div>
            <a href="{{ route('produk.index') }}" class="small-box-footer">Check Inventory <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Quick Actions -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Quick Actions</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('transaksi.baru') }}" class="btn btn-app bg-olive">
                            <i class="fa fa-cart-plus"></i> New Transaction
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('produk.index') }}" class="btn btn-app bg-purple">
                            <i class="fa fa-cubes"></i> View Products
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('kategori.index') }}" class="btn btn-app bg-teal">
                            <i class="fa fa-cube"></i> View Categories
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('laporan.index') }}" class="btn btn-app bg-blue">
                            <i class="fa fa-file-pdf-o"></i> Income Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Recent Sales</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentSales as $sale)
                        <tr>
                            <td>{{ $sale->id_penjualan }}</td>
                            <td>{{ optional($sale->member)->nama ?? 'Walk-in Customer' }}</td>
                            <td>{{ number_format($sale->bayar) }}</td>
                            <td>
                                <span class="label {{ $sale->status == 'success' ? 'label-success' : 'label-warning' }}">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </td>
                            <td>{{ $sale->created_at->format('H:i:s') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Best Selling Products Today -->
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Best Selling Today</h3>
            </div>
            <div class="box-body">
                <ul class="products-list product-list-in-box">
                    @foreach($bestProducts as $product)
                    <li class="item">
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">
                                {{ $product->nama_produk }}
                                <span class="label label-info pull-right">{{ $product->qty }} sold</span>
                            </a>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Low Stock Alert</h3>
            </div>
            <div class="box-body">
                <ul class="products-list product-list-in-box">
                    @foreach($lowStock as $product)
                    <li class="item">
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">
                                {{ $product->nama_produk }}
                                <span class="label label-danger pull-right">{{ $product->stok }} left</span>
                            </a>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        // Auto refresh the dashboard every 5 minutes
        setTimeout(function() {
            location.reload();
        }, 300000);
    });
</script>
@endpush
