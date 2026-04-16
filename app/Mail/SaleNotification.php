<?php

namespace App\Mail;

use App\Models\Penjualan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SaleNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $penjualan;
    public $details;
    public $cashierName;

    /**
     * Create a new message instance.
     */
    public function __construct(Penjualan $penjualan, $details, string $cashierName)
    {
        $this->penjualan = $penjualan;
        $this->details = $details;
        $this->cashierName = $cashierName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Sale Completed — #' . $this->penjualan->id_penjualan)
                    ->view('emails.sale_notification');
    }
}
