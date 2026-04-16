<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    public function verify($receiptId)
    {
        $penjualan = Penjualan::with(['detail.produk', 'user'])
            ->where('id_penjualan', $receiptId)
            ->firstOrFail();

        return view('qrcode.verify', compact('penjualan'));
    }

    public function scan()
    {
        return view('qrcode.scan');
    }

    public function lookup(Request $request)
    {
        $request->validate([
            'receipt_id' => 'required|integer'
        ]);

        try {
            $penjualan = Penjualan::with(['detail.produk', 'user'])
                ->where('id_penjualan', $request->receipt_id)
                ->firstOrFail();

            return view('qrcode.verify', compact('penjualan'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Receipt not found. Please check the receipt ID and try again.');
        }
    }

    public function apiVerify($receiptId)
    {
        try {
            $penjualan = Penjualan::with(['detail.produk', 'user'])
                ->where('id_penjualan', $receiptId)
                ->firstOrFail();

            // Format the data as requested
            $response = "Pharmacy: " . config('app.name') . "\n";
            $response .= "Receipt: #" . str_pad($penjualan->id_penjualan, 10, '0', STR_PAD_LEFT) . "\n";
            $response .= "Date: " . $penjualan->created_at->format('d M, Y h:i A') . "\n";
            
            foreach ($penjualan->detail as $item) {
                $response .= "Product: " . $item->produk->nama_produk . "\n";
                $response .= "Quantity: " . $item->jumlah . "\n";
            }
            
            $response .= "Total: UGX " . number_format($penjualan->bayar, 0);

            return response($response, 200)
                ->header('Content-Type', 'text/plain');
                
        } catch (\Exception $e) {
            return response('Receipt not found', 404)
                ->header('Content-Type', 'text/plain');
        }
    }
}
