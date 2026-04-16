@extends('layouts.master')

@section('title')
Sales Transactions
@endsection

@push('css')
<style>
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }

    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }

    .table-penjualan tbody tr:last-child {
        display: none;
    }

    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }

    /* ─── Barcode scan area ─────────────────────────────────── */
    .scan-input-group {
        position: relative;
    }
    .scan-input-group .form-control {
        border-radius: 6px 0 0 6px;
        font-size: 15px;
        font-weight: 600;
        letter-spacing: 1px;
        height: 40px;
        border: 2px solid #3c8dbc;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .scan-input-group .form-control:focus {
        border-color: #00a65a;
        box-shadow: 0 0 0 3px rgba(0,166,90,.15);
        outline: none;
    }
    .scan-input-group .btn-scan {
        height: 40px;
        border-radius: 0 6px 6px 0;
        background: #3c8dbc;
        border: none;
        color: #fff;
        font-size: 15px;
        padding: 0 16px;
        transition: background 0.2s;
    }
    .scan-input-group .btn-scan:hover { background: #367fa9; }

    /* ─── Scan result card ──────────────────────────────────── */
    #scan-result-card {
        display: none;
        border: 2px solid #00a65a;
        border-radius: 10px;
        padding: 14px 18px;
        background: linear-gradient(135deg, #f6fffa 0%, #e8f8f0 100%);
        margin-top: 10px;
        position: relative;
        animation: scanFadeIn .25s ease;
    }
    #scan-result-card.scan-error {
        border-color: #dd4b39;
        background: linear-gradient(135deg, #fff7f6 0%, #fde8e5 100%);
    }
    @keyframes scanFadeIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .scan-result-icon {
        font-size: 26px;
        margin-right: 10px;
        vertical-align: middle;
    }
    .scan-product-name {
        font-size: 17px;
        font-weight: 700;
        color: #1a7a40;
        margin: 0 0 4px;
    }
    #scan-result-card.scan-error .scan-product-name { color: #c0392b; }
    .scan-product-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }
    .scan-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #fff;
        border: 1px solid #b2dfca;
        border-radius: 20px;
        padding: 3px 12px;
        font-size: 12px;
        font-weight: 600;
        color: #2c7a4b;
    }
    .scan-badge.badge-price  { border-color: #3c8dbc; color: #2260a0; }
    .scan-badge.badge-stock  { border-color: #f39c12; color: #7a5200; }
    .scan-badge.badge-disc   { border-color: #dd4b39; color: #991c0f; }
    .scan-badge.badge-cat    { border-color: #9b59b6; color: #6a389b; }
    .scan-badge.badge-code   { border-color: #95a5a6; color: #4a5568; }
    .scan-add-btn {
        margin-top: 10px;
        background: #00a65a;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 7px 20px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s;
    }
    .scan-add-btn:hover { background: #008d4c; }
    .scan-close-btn {
        position: absolute;
        top: 8px; right: 10px;
        background: none; border: none;
        font-size: 18px; color: #aaa; cursor: pointer;
        line-height: 1;
    }
    .scan-close-btn:hover { color: #555; }

    /* ─── Payment method buttons ────────────────────────────── */
    .payment-options .btn-payment {
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 8px 4px;
        text-align: center;
        cursor: pointer;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.2s;
        background: #fff;
        color: #555;
        width: 100%;
        margin-bottom: 5px;
    }
    .payment-options .btn-payment:hover { border-color: #3c8dbc; color: #3c8dbc; }
    .payment-options .btn-payment.active { border-color: #3c8dbc; background: #3c8dbc; color: #fff; }
    .payment-options .btn-payment i { display: block; font-size: 1.4em; margin-bottom: 3px; }
    .mobile-provider-options .btn-provider {
        border: 2px solid #ddd;
        border-radius: 6px;
        padding: 5px 4px;
        text-align: center;
        cursor: pointer;
        font-size: 11px;
        font-weight: 600;
        transition: all 0.2s;
        background: #fff;
        color: #555;
        width: 100%;
        margin-bottom: 4px;
    }
    .mobile-provider-options .btn-provider:hover { border-color: #f39c12; color: #f39c12; }
    .mobile-provider-options .btn-provider.active-mtn { border-color: #f39c12; background: #f39c12; color: #fff; }
    .mobile-provider-options .btn-provider.active-airtel { border-color: #e74c3c; background: #e74c3c; color: #fff; }

    /* ─── Payment detail boxes ──────────────────────────────── */
    .payment-detail-box { background: #f9f9f9; border: 1px solid #ddd; border-radius: 6px; padding: 10px 12px; margin-top: 8px; }
    .payment-detail-box .form-group { margin-bottom: 8px; }
    .payment-detail-box label { font-size: 11px; font-weight: 700; color: #666; margin-bottom: 3px; }
    .payment-detail-box .form-control { font-size: 12px; height: 32px; padding: 4px 8px; }
    .card-type-selector .card-type-btn {
        display: inline-block; border: 2px solid #ddd; border-radius: 5px;
        padding: 4px 10px; cursor: pointer; font-size: 11px; font-weight: 700;
        margin-right: 5px; margin-bottom: 4px; color: #555; background: #fff; transition: all 0.2s;
    }
    .card-type-selector .card-type-btn:hover { border-color: #3c8dbc; color: #3c8dbc; }
    .card-type-selector .card-type-btn.active { border-color: #3c8dbc; background: #3c8dbc; color: #fff; }
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Sales Transactions</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                    
                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2" style="font-weight:700; padding-top:10px;">
                            <i class="fa fa-barcode" style="margin-right:4px;"></i> Scan / Code
                        </label>
                        <div class="col-lg-6">
                            <input type="hidden" name="id_penjualan" id="id_penjualan" value="{{ $id_penjualan }}">
                            <input type="hidden" name="id_produk" id="id_produk">
                            <div class="input-group scan-input-group">
                                <input type="text" class="form-control" name="kode_produk" id="kode_produk"
                                    placeholder="Scan barcode or type product code and press Enter..."
                                    autocomplete="off" autofocus>
                                <span class="input-group-btn">
                                    <button onclick="scanBarcode()" class="btn btn-scan" type="button" title="Lookup product">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <button onclick="tampilProduk()" class="btn btn-default btn-flat" type="button" title="Browse products">
                                        <i class="fa fa-list"></i>
                                    </button>
                                </span>
                            </div>
                            {{-- Scan result card --}}
                            <div id="scan-result-card">
                                <button class="scan-close-btn" type="button" onclick="closeScanCard()" title="Dismiss">&times;</button>
                                <div id="scan-result-body"></div>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-stiped table-bordered table-penjualan">
                    <thead>
                        <th width="5%">#</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th width="15%">Quantity</th>
                        <th>Discount</th>
                        <th>Subtotal</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('transaksi.simpan') }}" class="form-penjualan" method="post">
                            @csrf
                            <input type="hidden" name="id_penjualan" value="{{ $id_penjualan }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="bayar" id="bayar">
                            <input type="hidden" name="nama_pelanggan" id="nama_pelanggan" value="{{ $penjualan->nama_pelanggan ?? '' }}">
                            <input type="hidden" name="payment_method" id="payment_method" value="cash">
                            <input type="hidden" name="mobile_money_provider" id="mobile_money_provider" value="">

                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="customer_name" class="col-lg-2 control-label">Member</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter customer name" value="{{ $penjualan->nama_pelanggan ?? '' }}" oninput="document.getElementById('nama_pelanggan').value=this.value">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="diskon" class="col-lg-2 control-label">Discount</label>
                                <div class="col-lg-8">
                                    <input type="number" name="diskon" id="diskon" class="form-control" 
                                        value="{{ $diskon ?? 0 }}">
                                </div>
                            </div>

                            {{-- Hidden payment detail fields --}}
                            <input type="hidden" name="card_type" id="card_type" value="">
                            <input type="hidden" name="card_last_four" id="card_last_four" value="">
                            <input type="hidden" name="payment_reference" id="payment_reference" value="">
                            <input type="hidden" name="mobile_phone" id="mobile_phone" value="">

                            {{-- Payment Method --}}
                            <div class="form-group row">
                                <label class="col-lg-2 control-label">Payment</label>
                                <div class="col-lg-10">
                                    <div class="payment-options">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <div class="btn-payment active" data-method="cash">
                                                    <i class="fa fa-money"></i><br>
                                                    Cash
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                <div class="btn-payment" data-method="card">
                                                    <i class="fa fa-credit-card"></i><br>
                                                    Card
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                <div class="btn-payment" data-method="mobile_money">
                                                    <i class="fa fa-mobile"></i><br>
                                                    Mobile Money
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Card Details (shown when Card is selected) --}}
                                    <div id="card-detail-section" class="payment-detail-box" style="display:none;">
                                        <div class="card-type-selector" style="margin-bottom:8px;">
                                            <small style="display:block; font-weight:700; color:#666; margin-bottom:5px;">Card Type</small>
                                            <span class="card-type-btn" data-card="Visa">💳 Visa</span>
                                            <span class="card-type-btn" data-card="Mastercard">💳 Mastercard</span>
                                            <span class="card-type-btn" data-card="Other">💳 Other</span>
                                        </div>
                                        <div class="form-group">
                                            <label>Last 4 Digits (optional)</label>
                                            <input type="text" class="form-control" id="card_last_four_input"
                                                maxlength="4" placeholder="e.g. 4521"
                                                oninput="$('#card_last_four').val(this.value)">
                                        </div>
                                        <div class="form-group">
                                            <label>Approval / Reference No. (optional)</label>
                                            <input type="text" class="form-control" id="card_ref_input"
                                                placeholder="e.g. APV123456"
                                                oninput="$('#payment_reference').val(this.value)">
                                        </div>
                                    </div>

                                    {{-- Mobile Money Details (shown when Mobile Money is selected) --}}
                                    <div id="mobile-detail-section" class="payment-detail-box" style="display:none;">
                                        <small style="display:block; font-weight:700; color:#666; margin-bottom:6px;">Select Provider</small>
                                        <div class="mobile-provider-options" style="margin-bottom:8px;">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <div class="btn-provider" data-provider="mtn_momo">
                                                        📱 MTN MoMo
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="btn-provider" data-provider="airtel_money">
                                                        📱 Airtel Money
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Customer Phone Number</label>
                                            <input type="text" class="form-control" id="mobile_phone_input"
                                                placeholder="e.g. 0771234567"
                                                oninput="$('#mobile_phone').val(this.value)">
                                        </div>
                                        <div class="form-group">
                                            <label>Transaction Reference (optional)</label>
                                            <input type="text" class="form-control" id="mobile_ref_input"
                                                placeholder="e.g. TRX987654"
                                                oninput="$('#payment_reference').val(this.value)">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="bayar" class="col-lg-2 control-label">Pay</label>
                                <div class="col-lg-8">
                                    <input type="text" id="bayarrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="diterima" class="col-lg-2 control-label">Received</label>
                                <div class="col-lg-8">
                                    <input type="number" id="diterima" class="form-control" name="diterima" value="{{ $penjualan->diterima ?? 0 }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kembali" class="col-lg-2 control-label">Return</label>
                                <div class="col-lg-8">
                                    <input type="text" id="kembali" name="kembali" class="form-control" value="0" readonly>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-success btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Save Transaction</button>
            </div>
        </div>
    </div>
</div>

@includeIf('penjualan_detail.produk')
@endsection

@push('scripts')
<script>
    let table, table2;

    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.table-penjualan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('transaksi.data', $id_penjualan) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'harga_jual'},
                {data: 'jumlah'},
                {data: 'diskon'},
                {data: 'subtotal'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        })
        .on('draw.dt', function () {
            loadForm($('#diskon').val());
            setTimeout(() => {
                $('#diterima').trigger('input');
            }, 300);
        });
        table2 = $('.table-produk').DataTable();

        $(document).on('input', '.quantity', function () {
            let id = $(this).data('id');
            let jumlah = parseInt($(this).val());

            if (jumlah < 1) {
                $(this).val(1);
                alert('The number cannot be less than 1');
                return;
            }
            if (jumlah > 10000) {
                $(this).val(10000);
                alert('The number cannot exceed 10000');
                return;
            }

            $.post(`{{ url('/transaksi') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'jumlah': jumlah
                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        table.ajax.reload(() => loadForm($('#diskon').val()));
                    });
                })
                .fail(errors => {
                    alert('Unable to save data');
                    return;
                });
        });

        $(document).on('input', '#diskon', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($(this).val());
        });

        $('#diterima').on('input', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($('#diskon').val(), $(this).val());
        }).focus(function () {
            $(this).select();
        });

        $('.btn-simpan').on('click', function () {
            var method = $('#payment_method').val();
            // Validate mobile money
            if (method === 'mobile_money') {
                if ($('#mobile_money_provider').val() === '') {
                    alert('Please select a Mobile Money provider (MTN MoMo or Airtel Money).');
                    return;
                }
                if ($.trim($('#mobile_phone_input').val()) === '') {
                    alert('Please enter the customer phone number for Mobile Money.');
                    return;
                }
            }
            $('.form-penjualan').submit();
        });
    });

    // Payment method selection
    $(document).on('click', '.btn-payment', function () {
        var method = $(this).data('method');
        $('#payment_method').val(method);
        $('.btn-payment').removeClass('active');
        $(this).addClass('active');

        // Hide all detail boxes first
        $('#card-detail-section').slideUp(200);
        $('#mobile-detail-section').slideUp(200);

        // Clear card fields if switching away
        if (method !== 'card') {
            $('#card_type').val('');
            $('#card_last_four').val('');
            $('#card_last_four_input').val('');
            $('#card_ref_input').val('');
            $('.card-type-btn').removeClass('active');
        }
        // Clear mobile fields if switching away
        if (method !== 'mobile_money') {
            $('#mobile_money_provider').val('');
            $('#mobile_phone').val('');
            $('#mobile_phone_input').val('');
            $('#mobile_ref_input').val('');
            $('#payment_reference').val('');
            $('.btn-provider').removeClass('active-mtn active-airtel');
        }

        // Show the right detail section
        if (method === 'card') {
            $('#card-detail-section').slideDown(200);
        } else if (method === 'mobile_money') {
            $('#mobile-detail-section').slideDown(200);
        }
    });

    // Card type selection
    $(document).on('click', '.card-type-btn', function () {
        $('.card-type-btn').removeClass('active');
        $(this).addClass('active');
        $('#card_type').val($(this).data('card'));
    });

    // Mobile money provider selection
    $(document).on('click', '.btn-provider', function () {
        var provider = $(this).data('provider');
        $('#mobile_money_provider').val(provider);
        $('.btn-provider').removeClass('active-mtn active-airtel');
        if (provider === 'mtn_momo') {
            $(this).addClass('active-mtn');
        } else {
            $(this).addClass('active-airtel');
        }
    });

    // ─── Barcode / code lookup ─────────────────────────────────────────────
    var scanLookupUrl = '{{ url("/transaksi/produk") }}';

    // Called on Enter key or click of scan button
    function scanBarcode() {
        var kode = $.trim($('#kode_produk').val());
        if (!kode) return;

        var card = $('#scan-result-card');
        card.hide();
        $('#scan-result-body').html('<p style="margin:0;"><i class="fa fa-spinner fa-spin"></i> Looking up product...</p>');
        card.removeClass('scan-error').show();

        $.getJSON(scanLookupUrl + '/' + encodeURIComponent(kode))
            .done(function (data) {
                if (!data.found) {
                    showScanError('Product with code <strong>' + kode + '</strong> was not found.');
                    return;
                }
                showScanResult(data);
            })
            .fail(function (xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : 'Product not found.';
                showScanError('<strong>' + kode + '</strong>: ' + msg);
            });
    }

    function showScanResult(data) {
        var stockColor = data.stok < 5 ? '#e74c3c' : '#27ae60';
        var stockLabel = data.stok < 1 ? '⚠ Out of Stock' : data.stok + ' in stock';
        var discBadge = data.diskon > 0
            ? '<span class="scan-badge badge-disc"><i class="fa fa-tag"></i> Discount: ' + data.diskon + '%</span>'
            : '';

        var html = '<p class="scan-product-name">' +
            '<span class="scan-result-icon">✅</span>' + data.nama_produk + '</p>' +
            '<div class="scan-product-meta">' +
            '  <span class="scan-badge badge-code"><i class="fa fa-barcode"></i> ' + data.kode_produk + '</span>' +
            '  <span class="scan-badge badge-price"><i class="fa fa-money"></i> ' + data.harga_jual_fmt + '</span>' +
            '  <span class="scan-badge badge-stock" style="color:' + stockColor + '; border-color:' + stockColor + ';"><i class="fa fa-cubes"></i> ' + stockLabel + '</span>' +
            '  <span class="scan-badge badge-cat"><i class="fa fa-folder"></i> ' + data.kategori + '</span>' +
            discBadge +
            '</div>' +
            '<button class="scan-add-btn" onclick="addScannedProduct(' + data.id_produk + ', \'' + data.kode_produk + '\')">' +
            '  <i class="fa fa-plus-circle"></i> Add to Cart' +
            '</button>';

        $('#scan-result-body').html(html);
        $('#scan-result-card').removeClass('scan-error').show();
    }

    function showScanError(msg) {
        $('#scan-result-body').html(
            '<p class="scan-product-name"><span class="scan-result-icon">❌</span> Not Found</p>' +
            '<p style="margin:4px 0 0; color:#c0392b; font-size:13px;">' + msg + '</p>'
        );
        $('#scan-result-card').addClass('scan-error').show();
    }

    function closeScanCard() {
        $('#scan-result-card').hide();
        $('#kode_produk').val('').focus();
    }

    function addScannedProduct(idProduk, kode) {
        $('#id_produk').val(idProduk);
        $('#kode_produk').val(kode);
        closeScanCard();
        tambahProduk();
    }

    // Auto-scan on Enter key in the barcode field
    $('#kode_produk').on('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            scanBarcode();
        }
    });

    function tampilProduk() {
        $('#modal-produk').modal('show');
    }

    function hideProduk() {
        $('#modal-produk').modal('hide');
    }

    function pilihProduk(id, kode) {
        $('#id_produk').val(id);
        $('#kode_produk').val(kode);
        hideProduk();
        tambahProduk();
    }

    function tambahProduk() {
        $.post('{{ route('transaksi.store') }}', $('.form-produk').serialize())
            .done(response => {
                $('#kode_produk').val('').focus();
                table.ajax.reload(() => loadForm($('#diskon').val()));
            })
            .fail(errors => {
                alert('Unable to save data');
                return;
            });
    }


    function deleteData(url) {
        if (confirm('Are you sure you want to delete selected data?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                })
                .fail((errors) => {
                    alert('Unable to delete data');
                    return;
                });
        }
    }

    function loadForm(diskon = 0, diterima = 0) {
        $('#total').val($('.total').text());
        $('#total_item').val($('.total_item').text());

        $.get(`{{ url('/transaksi/loadform') }}/${diskon}/${$('.total').text()}/${diterima}`)
            .done(response => {
                $('#totalrp').val('UGX '+ response.totalrp);
                $('#bayarrp').val('UGX '+ response.bayarrp);
                $('#bayar').val(response.bayar);
                $('.tampil-bayar').text('Pay: UGX '+ response.bayarrp);
                $('.tampil-terbilang').text(response.terbilang);

                $('#kembali').val('UGX'+ response.kembalirp);
                if ($('#diterima').val() != 0) {
                    $('.tampil-bayar').text('Return: UGX '+ response.kembalirp);
                    $('.tampil-terbilang').text(response.kembali_terbilang);
                }
            })
            .fail(errors => {
                alert('Unable to display data');
                return;
            })
    }
</script>
@endpush