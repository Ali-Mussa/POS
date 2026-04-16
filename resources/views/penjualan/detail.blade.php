<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail-label">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="background:#3c8dbc; color:#fff; border-radius:4px 4px 0 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    style="color:#fff; opacity:.85;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modal-detail-label">
                    <i class="fa fa-file-text-o"></i>
                    Transaction Detail
                    <small id="detail-txn-id" style="font-size:13px; margin-left:8px; opacity:.85;"></small>
                </h4>
            </div>

            <div class="modal-body" style="padding:20px;">

                {{-- ── Transaction Summary ─────────────────────────────── --}}
                <div id="detail-summary" style="display:none;">
                    <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:18px;">

                        <div class="detail-info-card" style="flex:1 1 160px;">
                            <span class="detail-label"><i class="fa fa-calendar"></i> Date</span>
                            <span class="detail-value" id="d-tanggal">—</span>
                        </div>
                        <div class="detail-info-card" style="flex:1 1 160px;">
                            <span class="detail-label"><i class="fa fa-user"></i> Customer</span>
                            <span class="detail-value" id="d-customer">—</span>
                        </div>
                        <div class="detail-info-card" style="flex:1 1 160px;">
                            <span class="detail-label"><i class="fa fa-id-badge"></i> Cashier</span>
                            <span class="detail-value" id="d-cashier">—</span>
                        </div>
                        <div class="detail-info-card" style="flex:1 1 160px;">
                            <span class="detail-label"><i class="fa fa-credit-card"></i> Payment</span>
                            <span class="detail-value" id="d-payment">—</span>
                        </div>

                    </div>

                    <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:20px;">

                        <div class="detail-stat-card">
                            <div class="detail-stat-label">Items</div>
                            <div class="detail-stat-value" id="d-total-item">—</div>
                        </div>
                        <div class="detail-stat-card">
                            <div class="detail-stat-label">Subtotal</div>
                            <div class="detail-stat-value" id="d-total-harga">—</div>
                        </div>
                        <div class="detail-stat-card" style="border-color:#f39c12;">
                            <div class="detail-stat-label" style="color:#d68910;">Discount</div>
                            <div class="detail-stat-value" style="color:#d68910;" id="d-diskon">—</div>
                        </div>
                        <div class="detail-stat-card" style="border-color:#3c8dbc;">
                            <div class="detail-stat-label" style="color:#2471a3;">Total Pay</div>
                            <div class="detail-stat-value" style="color:#2471a3; font-size:18px;" id="d-bayar">—</div>
                        </div>
                        <div class="detail-stat-card">
                            <div class="detail-stat-label">Received</div>
                            <div class="detail-stat-value" id="d-diterima">—</div>
                        </div>
                        <div class="detail-stat-card" style="border-color:#00a65a;">
                            <div class="detail-stat-label" style="color:#008d4c;">Change</div>
                            <div class="detail-stat-value" style="color:#008d4c;" id="d-kembali">—</div>
                        </div>

                    </div>
                    <hr style="margin:0 0 14px;">
                </div>

                {{-- ── Loading spinner ─────────────────────────────────── --}}
                <div id="detail-loading" style="text-align:center; padding:20px;">
                    <i class="fa fa-spinner fa-spin fa-2x text-muted"></i>
                    <p class="text-muted" style="margin-top:8px;">Loading...</p>
                </div>

                {{-- ── Product table ────────────────────────────────────── --}}
                <div id="detail-table-wrap" style="display:none;">
                    <h5 style="font-weight:700; margin:0 0 10px; color:#555;">
                        <i class="fa fa-shopping-cart"></i> Items Purchased
                    </h5>
                    <table class="table table-striped table-bordered table-detail table-hover" style="font-size:13px;">
                        <thead style="background:#f4f4f4;">
                            <th width="5%">#</th>
                            <th>Code</th>
                            <th>Product Name</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </thead>
                    </table>
                </div>

            </div>{{-- /modal-body --}}

            <div class="modal-footer" style="background:#f9f9f9; border-top:1px solid #e5e5e5;">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">
                    <i class="fa fa-times"></i> Close
                </button>
            </div>

        </div>
    </div>
</div>

<style>
    .detail-info-card {
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 10px 14px;
        min-width: 140px;
    }
    .detail-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #888;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 3px;
    }
    .detail-value {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }
    .detail-stat-card {
        flex: 1 1 110px;
        background: #fff;
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 10px 14px;
        text-align: center;
        min-width: 100px;
    }
    .detail-stat-label {
        font-size: 11px;
        font-weight: 700;
        color: #888;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 4px;
    }
    .detail-stat-value {
        font-size: 16px;
        font-weight: 700;
        color: #333;
    }
</style>