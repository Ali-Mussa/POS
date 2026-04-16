@extends('layouts.master')

@section('title')
    Sales List
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Sales List</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-penjualan table-hover">
                    <thead>
                        <th width="5%">#</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Discount</th>
                        <th>Total Pay</th>
                        <th>Payment</th>
                        <th>Cashier</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- visit "codeastro" for more projects! -->
@includeIf('penjualan.detail')
@endsection

@push('scripts')
<script>
    let table, table1;

    $(function () {
        table = $('.table-penjualan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('penjualan.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'kode_member'},
                {data: 'total_item'},
                {data: 'total_harga'},
                {data: 'diskon'},
                {data: 'bayar'},
                {data: 'payment_method'},
                {data: 'kasir'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'harga_jual'},
                {data: 'jumlah'},
                {data: 'subtotal'},
            ]
        })
    });

    function showDetail(url) {
        // Extract the sale ID from the show URL  (…/penjualan/{id})
        var id = url.toString().split('/').pop();
        var headerUrl = '{{ url("/penjualan") }}/' + id + '/header';

        // Reset modal state
        $('#detail-loading').show();
        $('#detail-summary').hide();
        $('#detail-table-wrap').hide();
        $('#detail-txn-id').text('');
        $('#modal-detail').modal('show');

        // Fetch header summary
        $.getJSON(headerUrl)
            .done(function (h) {
                $('#detail-txn-id').text('#' + h.id_penjualan);
                $('#d-tanggal').text(h.tanggal);
                $('#d-customer').text(h.customer);
                $('#d-cashier').text(h.cashier);
                $('#d-payment').text(h.payment);
                $('#d-total-item').text(h.total_item);
                $('#d-total-harga').text(h.total_harga);
                $('#d-diskon').text(h.diskon);
                $('#d-bayar').text(h.bayar);
                $('#d-diterima').text(h.diterima);
                $('#d-kembali').text(h.kembali);
                $('#detail-loading').hide();
                $('#detail-summary').show();
                $('#detail-table-wrap').show();
            })
            .fail(function () {
                $('#detail-loading').html('<p class="text-danger"><i class="fa fa-exclamation-triangle"></i> Failed to load transaction details.</p>');
            });

        // Load product rows into DataTable
        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function deleteData(url) {
        if (confirm('Are you sure you want to delete selected data?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Unable to delete data');
                    return;
                });
        }
    }
</script>
@endpush