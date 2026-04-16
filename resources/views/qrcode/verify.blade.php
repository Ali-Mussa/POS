<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Purchase - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .receipt-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border: 2px solid #28a745;
        }
        .header-section {
            text-align: center;
            border-bottom: 2px solid #28a745;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .info-item {
            margin: 15px 0;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
            font-size: 14px;
        }
        .info-value {
            font-size: 16px;
            color: #212529;
            margin-top: 5px;
        }
        .verified-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 12px 25px;
            border-radius: 30px;
            display: inline-block;
            margin-bottom: 20px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }
        .product-section {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            border: 1px solid #2196f3;
        }
        .total-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 25px;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="receipt-container">
            <div class="header-section">
                <h2><i class="fas fa-store"></i> {{ config('app.name') }}</h2>
                <div class="verified-badge">
                    <i class="fas fa-check-circle"></i> Verified Purchase
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Pharmacy:</div>
                <div class="info-value">{{ config('app.name') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Receipt:</div>
                <div class="info-value">#{{ tambah_nol_didepan($penjualan->id_penjualan, 10) }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Date:</div>
                <div class="info-value">{{ $penjualan->created_at->format('d M, Y h:i A') }}</div>
            </div>

            @foreach ($penjualan->detail as $item)
            <div class="product-section">
                <div class="info-label">Product:</div>
                <div class="info-value">{{ $item->produk->nama_produk }}</div>
                
                <div class="row mt-3">
                    <div class="col-6">
                        <div class="info-label">Quantity:</div>
                        <div class="info-value">{{ $item->jumlah }}</div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="info-label">Price:</div>
                        <div class="info-value">{{ format_uang($item->harga_jual) }}</div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="total-section">
                <div class="row align-items-center">
                    <div class="col-6">
                        <div style="font-size: 18px; font-weight: 600;">Total:</div>
                    </div>
                    <div class="col-6 text-end">
                        <div style="font-size: 24px; font-weight: bold;">
                            {{ format_uang($penjualan->bayar) }}
                        </div>
                    </div>
                </div>
                
                @if ($penjualan->nama_pelanggan)
                <div class="row mt-3">
                    <div class="col-12">
                        <div style="font-size: 14px; opacity: 0.9;">Customer: {{ $penjualan->nama_pelanggan }}</div>
                    </div>
                </div>
                @endif
            </div>

            <div class="text-center mt-4">
                <p class="text-muted">
                    <i class="fas fa-shield-alt"></i> This is a verified purchase receipt<br>
                    <small>Generated on {{ now()->format('d M Y H:i:s') }}</small>
                </p>
                <a href="{{ url('/') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> Back to Store
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
