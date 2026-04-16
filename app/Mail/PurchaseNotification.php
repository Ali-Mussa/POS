<?php

namespace App\Mail;

use App\Models\Pembelian;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $pembelian;
    public $details;
    public $supplierName;

    /**
     * Create a new message instance.
     */
    public function __construct(Pembelian $pembelian, $details, string $supplierName)
    {
        $this->pembelian = $pembelian;
        $this->details = $details;
        $this->supplierName = $supplierName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Purchase Recorded — #' . $this->pembelian->id_pembelian)
                    ->view('emails.purchase_notification');
    }
}
