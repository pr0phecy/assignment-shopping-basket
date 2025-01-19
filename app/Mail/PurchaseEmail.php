<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class PurchaseEmail extends Mailable
{
    public function build()
    {
        return $this->subject('Thank you for your purchase!')
            ->view('emails.purchase_email');
    }
}
