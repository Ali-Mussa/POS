<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Notification</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f6f9; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); overflow: hidden;">
                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #f39c12, #e67e22); padding: 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 600;">
                                📦 New Purchase Recorded
                            </h1>
                            <p style="color: rgba(255,255,255,0.85); margin: 8px 0 0; font-size: 15px;">
                                Purchase #{{ $pembelian->id_pembelian }}
                            </p>
                        </td>
                    </tr>

                    {{-- Summary --}}
                    <tr>
                        <td style="padding: 30px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #fdf8f0; border-radius: 6px; margin-bottom: 20px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="font-size: 14px; color: #555; margin: 0 0 8px;">
                                            <strong>Date:</strong> {{ $pembelian->created_at->format('M d, Y — h:i A') }}
                                        </p>
                                        <p style="font-size: 14px; color: #555; margin: 0 0 8px;">
                                            <strong>Supplier:</strong> {{ $supplierName }}
                                        </p>
                                        <p style="font-size: 14px; color: #555; margin: 0 0 8px;">
                                            <strong>Total Items:</strong> {{ $pembelian->total_item }}
                                        </p>
                                        <p style="font-size: 14px; color: #555; margin: 0;">
                                            <strong>Discount:</strong> {{ $pembelian->diskon }}%
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Items Table --}}
                            <h3 style="font-size: 16px; color: #333; margin: 0 0 12px;">Items Purchased</h3>
                            <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; font-size: 13px;">
                                <thead>
                                    <tr style="background-color: #f39c12; color: #fff;">
                                        <th style="padding: 10px 12px; text-align: left; border-radius: 4px 0 0 0;">Product</th>
                                        <th style="padding: 10px 12px; text-align: center;">Qty</th>
                                        <th style="padding: 10px 12px; text-align: right;">Cost</th>
                                        <th style="padding: 10px 12px; text-align: right; border-radius: 0 4px 0 0;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $item)
                                    <tr style="border-bottom: 1px solid #eee;">
                                        <td style="padding: 10px 12px; color: #333;">{{ $item->produk->nama_produk ?? 'N/A' }}</td>
                                        <td style="padding: 10px 12px; text-align: center; color: #555;">{{ $item->jumlah }}</td>
                                        <td style="padding: 10px 12px; text-align: right; color: #555;">UGX {{ number_format($item->harga_beli) }}</td>
                                        <td style="padding: 10px 12px; text-align: right; color: #333; font-weight: 600;">UGX {{ number_format($item->subtotal) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- Totals --}}
                            <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 16px; font-size: 14px;">
                                <tr>
                                    <td style="padding: 8px 12px; text-align: right; color: #666;">Subtotal:</td>
                                    <td style="padding: 8px 12px; text-align: right; color: #333; width: 140px;"><strong>UGX {{ number_format($pembelian->total_harga) }}</strong></td>
                                </tr>
                                @if($pembelian->diskon > 0)
                                <tr>
                                    <td style="padding: 8px 12px; text-align: right; color: #666;">Discount ({{ $pembelian->diskon }}%):</td>
                                    <td style="padding: 8px 12px; text-align: right; color: #e74c3c; width: 140px;">- UGX {{ number_format($pembelian->total_harga * $pembelian->diskon / 100) }}</td>
                                </tr>
                                @endif
                                <tr style="border-top: 2px solid #f39c12;">
                                    <td style="padding: 12px; text-align: right; color: #333; font-size: 16px;"><strong>Total Paid:</strong></td>
                                    <td style="padding: 12px; text-align: right; color: #e67e22; font-size: 18px; width: 140px;"><strong>UGX {{ number_format($pembelian->bayar) }}</strong></td>
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
