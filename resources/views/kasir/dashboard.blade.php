@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
<!-- Metrics Row -->
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>UGX {{ number_format($todaySales ?? 0, 0) }}</h3>
                <p>Today's Sales</p>
            </div>
            <div class="icon"><i class="fa fa-money"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $todayTxCount ?? 0 }}</h3>
                <p>Transactions Today</p>
            </div>
            <div class="icon"><i class="fa fa-exchange"></i></div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-12">
        <div class="small-box bg-yellow">
            <div class="inner text-center">
                <h3>New Transaction</h3>
                <a href="{{ route('transaksi.baru') }}" class="btn btn-success btn-lg">Start</a>
            </div>
            <div class="icon"><i class="fa fa-shopping-cart"></i></div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="box">
            <div class="box-header with-border"><h3 class="box-title">Recent Sales</h3></div>
            <div class="box-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Member</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($recentSales ?? []) as $s)
                            <tr>
                                <td>{{ $s->id_penjualan }}</td>
                                <td>{{ $s->created_at }}</td>
                                <td>{{ $s->member->kode_member ?? '-' }}</td>
                                <td>UGX {{ number_format($s->bayar, 0) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">No recent sales</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="box">
            <div class="box-header with-border"><h3 class="box-title">Best-selling Products (Today)</h3></div>
            <div class="box-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($bestProducts ?? []) as $p)
                            <tr>
                                <td>{{ $p->nama_produk }}</td>
                                <td>{{ $p->qty }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center">No sales yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="box">
            <div class="box-header with-border"><h3 class="box-title">Notifications</h3></div>
            <div class="box-body">
                <ul class="list-group">
                    @forelse(($lowStock ?? []) as $prod)
                        <li class="list-group-item">
                            <span class="label label-warning">Low stock</span>
                            &nbsp;{{ $prod->nama_produk }}
                            <span class="badge">{{ $prod->stok }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-center">No notifications</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection