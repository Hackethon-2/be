<?php

namespace App\Mail;

use App\Models\Aduan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AduanStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    protected $aduan;
    protected $oldStatus;
    protected $adminEmail;
    protected $adminName;

    public function __construct(Aduan $aduan, $oldStatus, $adminEmail, $adminName)
    {
        $this->aduan = $aduan;
        $this->oldStatus = $oldStatus;
        $this->adminEmail = $adminEmail;
        $this->adminName = $adminName;
    }

    public function build()
    {
        return $this->from($this->adminEmail, $this->adminName)
                    ->subject('Aduan Status Updated')
                    ->view('emails.aduan_status_updated')
                    ->with([
                        'aduan' => $this->aduan,
                        'oldStatus' => $this->oldStatus,
                    ]);
    }
}
