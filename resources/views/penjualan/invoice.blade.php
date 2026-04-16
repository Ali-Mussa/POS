<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ tambah_nol_didepan($penjualan->id_penjualan, 10) }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 14px; 
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-info h1 {
            margin: 0;
            color: #007bff;
            font-size: 28px;
        }
        .company-info p {
            margin: 5px 0;
            color: #666;
        }
        .invoice-details {
            text-align: right;
        }
        .invoice-details h2 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .invoice-details p {
            margin: 5px 0;
            color: #666;
        }
        .customer-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .customer-section, .transaction-section {
            flex: 1;
        }
        .customer-section h3, .transaction-section h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 16px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background: #007bff;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        .items-table .text-right {
            text-align: right;
        }
        .items-table .text-center {
            text-align: center;
        }
        .totals-section {
            width: 50%;
            margin-left: auto;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }
        .totals-section table {
            width: 100%;
        }
        .totals-section td {
            padding: 8px 0;
        }
        .totals-section .total-row td {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #007bff;
            padding-top: 10px;
        }
        .qr-section {
            margin-top: 40px;
            text-align: center;
            padding: 30px;
            border: 2px dashed #007bff;
            border-radius: 10px;
            background: #f8f9fa;
        }
        .qr-section h3 {
            color: #007bff;
            margin-bottom: 20px;
        }
        .qr-code {
            display: inline-block;
            padding: 15px;
            background: white;
            border: 2px solid #555555;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .qr-code img {
            display: block;
            border-radius: 4px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="company-info">
                <h1>{{ $setting->nama_perusahaan }}</h1>
                <p>{{ $setting->alamat }}</p>
                <p>Phone: {{ $setting->telepon ?? 'N/A' }}</p>
            </div>
            <div class="invoice-details">
                <h2>INVOICE</h2>
                <p><strong>No:</strong> {{ tambah_nol_didepan($penjualan->id_penjualan, 10) }}</p>
                <p><strong>Date:</strong> {{ tanggal_indonesia($penjualan->created_at, false) }}</p>
                <p><strong>Time:</strong> {{ $penjualan->created_at->format('H:i:s') }}</p>
            </div>
        </div>

        <div class="customer-info">
            <div class="customer-section">
                <h3>Customer Information</h3>
                <p><strong>Name:</strong> {{ $penjualan->nama_pelanggan ?? 'Guest Customer' }}</p>
                <p><strong>Receipt ID:</strong> #{{ tambah_nol_didepan($penjualan->id_penjualan, 10) }}</p>
            </div>
            <div class="transaction-section">
                <h3>Transaction Details</h3>
                <p><strong>Cashier:</strong> {{ $penjualan->user->name ?? 'N/A' }}</p>
                <p><strong>Payment Method:</strong> 
                    @php
                        $pm = $penjualan->payment_method ?? 'cash';
                        if ($pm === 'cash') { echo 'Cash'; }
                        elseif ($pm === 'card') { echo 'Card (Debit/Credit)'; }
                        elseif ($pm === 'mobile_money') {
                            $prov = $penjualan->mobile_money_provider ?? '';
                            if ($prov === 'mtn_momo') echo 'MTN MoMo';
                            elseif ($prov === 'airtel_money') echo 'Airtel Money';
                            else echo 'Mobile Money';
                        }
                    @endphp
                </p>
            </div>
        </div>

        <h3>Order Details</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="15%">Product Code</th>
                    <th width="35%">Product Name</th>
                    <th width="15%" class="text-right">Unit Price</th>
                    <th width="10%" class="text-center">Quantity</th>
                    <th width="10%" class="text-right">Discount</th>
                    <th width="10%" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $key => $item)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $item->produk->kode_produk }}</td>
                        <td>{{ $item->produk->nama_produk }}</td>
                        <td class="text-right">{{ format_uang($item->harga_jual) }}</td>
                        <td class="text-center">{{ $item->jumlah }}</td>
                        <td class="text-right">{{ $item->diskon }}</td>
                        <td class="text-right">{{ format_uang($item->subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-section">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">{{ format_uang($penjualan->total_harga) }}</td>
                </tr>
                @if ($penjualan->diskon > 0)
                <tr>
                    <td>Discount:</td>
                    <td class="text-right">-{{ format_uang($penjualan->diskon) }}</td>
                </tr>
                @endif
                <tr>
                    <td><strong>Total Amount:</strong></td>
                    <td class="text-right"><strong>{{ format_uang($penjualan->bayar) }}</strong></td>
                </tr>
                <tr>
                    <td>Amount Received:</td>
                    <td class="text-right">{{ format_uang($penjualan->diterima) }}</td>
                </tr>
                <tr class="total-row">
                    <td><strong>Change:</strong></td>
                    <td class="text-right">{{ format_uang($penjualan->diterima - $penjualan->bayar) }}</td>
                </tr>
            </table>
        </div>

        <div class="qr-section">
            <h3><i class="fas fa-qrcode"></i> Scan to Verify This Purchase</h3>
            @php
                $qrCodePath = $penjualan->generateQRCode();
                echo '<div class="qr-code"><img src="' . asset($qrCodePath) . '" alt="QR Code" style="width: 200px; height: 200px;"></div>';
            @endphp
            <p style="margin-top: 15px; color: #666;">
                <strong>Receipt #{{ tambah_nol_didepan($penjualan->id_penjualan, 10) }}</strong><br>
                Scan with your mobile phone to view and verify this purchase
            </p>
        </div>

        <div class="footer">
            <p><strong>Thank you for your business!</strong></p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p>Generated on {{ now()->format('d M Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
