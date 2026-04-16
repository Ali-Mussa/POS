<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF Notes</title>

    <style>
        table td {
            /* font-family: Arial, Helvetica, sans-serif; */
            font-size: 14px;
        }
        table.data td,
        table.data th {
            border: 1px solid #ccc;
            padding: 5px;
        }
        table.data {
            border-collapse: collapse;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            <td rowspan="4" width="60%">
                <img src="{{ public_path($setting->path_logo) }}" alt="{{ $setting->path_logo }}" width="120">
                <br>
                {{ $setting->alamat }}
                <br>
                <br>
            </td>
            <td>Date</td>
            <td>: {{ tanggal_indonesia(date('Y-m-d')) }}</td>
        </tr>
        <tr>
            <td>Customer</td>
            <td>: {{ $penjualan->nama_pelanggan ?? '-' }}</td>
        </tr>
    </table>

    <table class="data" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Name</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Discount</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail as $key => $item)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td>{{ $item->produk->kode_produk }}</td>
                    <td class="text-right">{{ format_uang($item->harga_jual) }}</td>
                    <td class="text-right">{{ format_uang($item->jumlah) }}</td>
                    <td class="text-right">{{ $item->diskon }}</td>
                    <td class="text-right">{{ format_uang($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right"><b>Total Price</b></td>
                <td class="text-right"><b>{{ format_uang($penjualan->total_harga) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Discount</b></td>
                <td class="text-right"><b>{{ format_uang($penjualan->diskon) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Total Pay</b></td>
                <td class="text-right"><b>{{ format_uang($penjualan->bayar) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Payment Method</b></td>
                <td class="text-right">
                    <b>@php
                        $pm = $penjualan->payment_method ?? 'cash';
                        if ($pm === 'cash') { echo 'Cash'; }
                        elseif ($pm === 'card') { echo 'Card (Debit/Credit)'; }
                        elseif ($pm === 'mobile_money') {
                            $prov = $penjualan->mobile_money_provider ?? '';
                            if ($prov === 'mtn_momo') echo 'MTN MoMo';
                            elseif ($prov === 'airtel_money') echo 'Airtel Money';
                            else echo 'Mobile Money';
                        }
                    @endphp</b>
                </td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Received</b></td>
                <td class="text-right"><b>{{ format_uang($penjualan->diterima) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Return</b></td>
                <td class="text-right"><b>{{ format_uang($penjualan->diterima - $penjualan->bayar) }}</b></td>
            </tr>
        </tfoot>
    </table>

    <table width="100%">
        <tr>
            <td><b>Thank you for shopping. We hope to see you again!</b></td>
            <td class="text-center">
                Cashier
                <br>
                <br>
                {{ auth()->user()->name }}
            </td>
        </tr>
    </table>

    <div style="margin-top: 40px; text-align: center; padding: 20px; border: 2px solid #007bff; border-radius: 10px; background: #f8f9fa;">
        <h4 style="margin-bottom: 15px; color: #007bff;">
            <i class="fas fa-qrcode"></i> Scan to verify purchase
        </h4>
        @php
            $qrCodePath = $penjualan->generateQRCode();
            echo '<div style="display: inline-block; padding: 15px; background: white; border: 2px solid #555; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"><img src="' . asset($qrCodePath) . '" alt="QR Code" style="width: 160px; height: 160px; display: block; border-radius: 4px;"></div>';
        @endphp
        <p style="margin-top: 15px; font-size: 12pt; color: #666;">
            <strong>Receipt #{{ tambah_nol_didepan($penjualan->id_penjualan, 10) }}</strong>
        </p>
        <p style="font-size: 10pt; color: #888; margin-top: 5px;">
            Scan with your mobile phone to view purchase details
        </p>
    </div>
</body>
</html>