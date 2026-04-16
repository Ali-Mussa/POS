<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    protected $guarded = [];

    public function member()
    {
        return $this->hasOne(Member::class, 'id_member', 'id_member');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function generateQRCode()
    {
        $qrData = [
            'receipt_id' => $this->id_penjualan,
            'total_amount' => $this->total_harga,
            'date' => $this->created_at->format('Y-m-d H:i:s'),
            'customer' => $this->nama_pelanggan ?? 'Guest',
            'payment_method' => $this->payment_method ?? 'cash'
        ];

        $qrString = json_encode($qrData);
        $filename = 'qr_' . $this->id_penjualan . '_' . time() . '.png';
        $filepath = public_path('qrcodes/' . $filename);
        
        try {
            // Try to generate QR code with default settings first
            $qrCode = QrCode::format('png')
                ->size(200)
                ->errorCorrection('H')
                ->generate($qrString);
            
            // Save to file
            file_put_contents($filepath, $qrCode);
            
            // Return the relative path for display
            return 'qrcodes/' . $filename;
            
        } catch (\Exception $e) {
            // Fallback: generate SVG and convert to PNG
            $svg = QrCode::format('svg')
                ->size(200)
                ->errorCorrection('H')
                ->generate($qrString);
            
            // For SVG fallback, save as SVG file
            $svgFilename = 'qr_' . $this->id_penjualan . '_' . time() . '.svg';
            $svgFilepath = public_path('qrcodes/' . $svgFilename);
            file_put_contents($svgFilepath, $svg);
            
            return 'qrcodes/' . $svgFilename;
        }
    }

    public function generateQRCodeBase64()
    {
        $qrData = [
            'receipt_id' => $this->id_penjualan,
            'total_amount' => $this->total_harga,
            'date' => $this->created_at->format('Y-m-d H:i:s'),
            'customer' => $this->nama_pelanggan ?? 'Guest',
            'payment_method' => $this->payment_method ?? 'cash'
        ];

        $qrString = json_encode($qrData);
        
        try {
            // Try to generate QR code with default settings first
            return QrCode::format('png')
                ->size(200)
                ->errorCorrection('H')
                ->generate($qrString);
        } catch (\Exception $e) {
            // Fallback: generate SVG and convert to base64
            $svg = QrCode::format('svg')
                ->size(200)
                ->errorCorrection('H')
                ->generate($qrString);
            
            // Return SVG as base64 encoded data URI
            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        }
    }

    public function getQRCodeDataAttribute()
    {
        return [
            'receipt_id' => $this->id_penjualan,
            'total_amount' => $this->total_harga,
            'date' => $this->created_at->format('Y-m-d H:i:s'),
            'customer' => $this->nama_pelanggan ?? 'Guest',
            'payment_method' => $this->payment_method ?? 'cash'
        ];
    }
}
