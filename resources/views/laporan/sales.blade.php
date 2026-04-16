@extends('layouts.master')

@section('title')
Sales Report {{ tanggal_indonesia($tanggalAwal, false) }} &mdash; {{ tanggal_indonesia($tanggalAkhir, false) }}
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap.min.css">
@endpush

@section('breadcrumb')
    @parent
    <li><a href="{{ route('laporan.hub') }}">Reports</a></li>
    <li class="active">Sales Report</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-shopping-cart"></i> Sales Report</h3>
                <div class="box-tools pull-right">
                    <button onclick="updatePeriode()" class="btn btn-primary btn-flat btn-sm">
                        <i class="fa fa-calendar"></i> Change Date Range
                    </button>
                    <a href="{{ route('laporan.sales_pdf', [$tanggalAwal, $tanggalAkhir]) }}" target="_blank" class="btn btn-danger btn-flat btn-sm">
                        <i class="fa fa-file-pdf-o"></i> Export PDF
                    </a>
                </div>
            </div>
            <div class="box-body">
                <p class="text-muted">
                    <i class="fa fa-calendar-o"></i>
                    Period: <strong>{{ tanggal_indonesia($tanggalAwal, false) }}</strong>
                    &mdash;
                    <strong>{{ tanggal_indonesia($tanggalAkhir, false) }}</strong>
                </p>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-bordered table-hover table-sales">
                    <thead>
                        <th width="5%">#</th>
                        <th>Transaction ID</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Cashier</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Date Range Modal -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('laporan.sales') }}" method="get" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Select Date Range</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-lg-3 control-label">Start Date</label>
                        <div class="col-lg-8">
                            <input type="text" name="tanggal_awal" class="form-control datepicker" value="{{ $tanggalAwal }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 control-label">End Date</label>
                        <div class="col-lg-8">
                            <input type="text" name="tanggal_akhir" class="form-control datepicker" value="{{ $tanggalAkhir }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success btn-flat btn-sm"><i class="fa fa-check"></i> Apply</button>
                    <button type="button" class="btn btn-default btn-flat btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    $(function () {
        $('.table-sales').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
            buttons: [
                { extend: 'csvHtml5',   text: '<i class="fa fa-file-text-o"></i> CSV',   className: 'btn btn-sm btn-default btn-flat', title: 'Sales Report' },
                { extend: 'excelHtml5', text: '<i class="fa fa-file-excel-o"></i> Excel', className: 'btn btn-sm btn-success btn-flat', title: 'Sales Report' },
                { extend: 'print',      text: '<i class="fa fa-print"></i> Print',       className: 'btn btn-sm btn-info btn-flat',    title: 'Sales Report' },
            ],
            ajax: { url: '{{ route('laporan.sales_data', [$tanggalAwal, $tanggalAkhir]) }}' },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'id_penjualan' },
                { data: 'tanggal' },
                { data: 'customer' },
                { data: 'total_item' },
                { data: 'total_harga' },
                { data: 'bayar_fmt' },
                { data: 'kasir' },
            ]
        });
        $('.datepicker').datepicker({ format: 'yyyy-mm-dd', autoclose: true });
    });

    function updatePeriode() { $('#modal-form').modal('show'); }
</script>
@endpush
