<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale Notification</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f6f9; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); overflow: hidden;">
                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #3c8dbc, #2c6f9e); padding: 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 600;">
                                💰 New Sale Completed
                            </h1>
                            <p style="color: rgba(255,255,255,0.85); margin: 8px 0 0; font-size: 15px;">
                                Transaction #{{ $penjualan->id_penjualan }}
                            </p>
                        </td>
                    </tr>

                    {{-- Summary --}}
                    <tr>
                        <td style="padding: 30px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f9fafb; border-radius: 6px; margin-bottom: 20px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="font-size: 14px; color: #555; margin: 0 0 8px;">
                                            <strong>Date:</strong> {{ $penjualan->created_at->format('M d, Y — h:i A') }}
                                        </p>
                                        <p style="font-size: 14px; color: #555; margin: 0 0 8px;">
                                            <strong>Cashier:</strong> {{ $cashierName }}
                                        </p>
                                        @if($penjualan->nama_pelanggan)
                                        <p style="font-size: 14px; color: #555; margin: 0 0 8px;">
                                            <strong>Customer:</strong> {{ $penjualan->nama_pelanggan }}
                                        </p>
                                        @endif
                                        <p style="font-size: 14px; color: #555; margin: 0 0 8px;">
                                            <strong>Total Items:</strong> {{ $penjualan->total_item }}
                                        </p>
                                        <p style="font-size: 14px; color: #555; margin: 0 0 8px;">
                                            <strong>Discount:</strong> {{ $penjualan->diskon }}%
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Items Table --}}
                            <h3 style="font-size: 16px; color: #333; margin: 0 0 12px;">Items Sold</h3>
                            <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; font-size: 13px;">
                                <thead>
                                    <tr style="background-color: #3c8dbc; color: #fff;">
                                        <th style="padding: 10px 12px; text-align: left; border-radius: 4px 0 0 0;">Product</th>
                                        <th style="padding: 10px 12px; text-align: center;">Qty</th>
                                        <th style="padding: 10px 12px; text-align: right;">Price</th>
                                        <th style="padding: 10px 12px; text-align: right; border-radius: 0 4px 0 0;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $item)
                                    <tr style="border-bottom: 1px solid #eee;">
                                        <td style="padding: 10px 12px; color: #333;">{{ $item->produk->nama_produk ?? 'N/A' }}</td>
                                        <td style="padding: 10px 12px; text-align: center; color: #555;">{{ $item->jumlah }}</td>
                                        <td style="padding: 10px 12px; text-align: right; color: #555;">UGX {{ number_format($item->harga_jual) }}</td>
                                        <td style="padding: 10px 12px; text-align: right; color: #333; font-weight: 600;">UGX {{ number_format($item->subtotal) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- Totals --}}
                            <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 16px; font-size: 14px;">
                                <tr>
                                    <td style="padding: 8px 12px; text-align: right; color: #666;">Subtotal:</td>
                                    <td style="padding: 8px 12px; text-align: right; color: #333; width: 140px;"><strong>UGX {{ number_format($penjualan->total_harga) }}</strong></td>
                                </tr>
                                @if($penjualan->diskon > 0)
                                <tr>
                                    <td style="padding: 8px 12px; text-align: right; color: #666;">Discount ({{ $penjualan->diskon }}%):</td>
                                    <td style="padding: 8px 12px; text-align: right; color: #e74c3c; width: 140px;">- UGX {{ number_format($penjualan->total_harga * $penjualan->diskon / 100) }}</td>
                                </tr>
                                @endif
                                <tr style="border-top: 2px solid #3c8dbc;">
                                    <td style="padding: 12px; text-align: right; color: #333; font-size: 16px;"><strong>Amount Paid:</strong></td>
                                    <td style="padding: 12px; text-align: right; color: #00a65a; font-size: 18px; width: 140px;"><strong>UGX {{ number_format($penjualan->bayar) }}</strong></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 20px 30px; text-align: center; border-top: 1px solid #eee;">
                            <p style="font-size: 12px; color: #999; margin: 0;">
                                © {{ date('Y') }} {{ config('app.name') }} — This is an automated notification.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
