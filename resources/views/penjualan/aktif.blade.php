@extends('layouts.master')

@section('title')
Active Transactions
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Active Transactions</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Member</th>
                            <th>Total Items</th>
                            <th>Total Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $i)
                            <tr>
                                <td>{{ $i->id_penjualan }}</td>
                                <td>{{ $i->created_at }}</td>
                                <td>{{ $i->member->kode_member ?? '-' }}</td>
                                <td>{{ $i->total_item }}</td>
                                <td>UGX {{ number_format($i->total_harga, 0) }}</td>
                                <td>
                                    <form action="{{ route('transaksi.resume', $i->id_penjualan) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        <button class="btn btn-xs btn-primary"><i class="fa fa-play"></i> Resume</button>
                                    </form>
                                    <form action="{{ route('penjualan.destroy', $i->id_penjualan) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Cancel this transaction?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-xs btn-danger"><i class="fa fa-times"></i> Cancel</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No active transactions</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
