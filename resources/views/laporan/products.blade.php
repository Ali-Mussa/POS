@extends('layouts.master')

@section('title')
Products / Stock Report
@endsection
@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap.min.css">
@endpush

@section('breadcrumb')
    @parent
    <li><a href="{{ route('laporan.hub') }}">Reports</a></li>
    <li class="active">Products Report</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-cubes"></i> Products / Stock Report</h3>
                <div class="box-tools pull-right">
                    <a href="{{ route('laporan.products_pdf') }}" target="_blank" class="btn btn-danger btn-flat btn-sm">
                        <i class="fa fa-file-pdf-o"></i> Export PDF
                    </a>
                </div>
            </div>
            <div class="box-body">
                <p class="text-muted"><i class="fa fa-info-circle"></i> Current stock levels for all products.</p>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-bordered table-hover table-products">
                    <thead>
                        <th width="5%">#</th>
                        <th>Code</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Buy Price</th>
                        <th>Sell Price</th>
                        <th>Discount</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script>
    $(function () {
        $('.table-products').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
            buttons: [
                { extend: 'csvHtml5',   text: '<i class="fa fa-file-text-o"></i> CSV',   className: 'btn btn-sm btn-default btn-flat', title: 'Products Report' },
                { extend: 'excelHtml5', text: '<i class="fa fa-file-excel-o"></i> Excel', className: 'btn btn-sm btn-success btn-flat', title: 'Products Report' },
                { extend: 'print',      text: '<i class="fa fa-print"></i> Print',       className: 'btn btn-sm btn-info btn-flat',    title: 'Products Report' },
            ],
            ajax: { url: '{{ route('laporan.products_data') }}' },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'kode_produk' },
                { data: 'nama_produk' },
                { data: 'kategori_nama' },
                { data: 'stok_badge', searchable: false },
                { data: 'harga_beli_fmt', searchable: false },
                { data: 'harga_jual_fmt', searchable: false },
                { data: 'diskon', searchable: false },
            ]
        });
    });
</script>
@endpush
