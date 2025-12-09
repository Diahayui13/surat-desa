<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Surat;

class SuratSelesaiNotification extends Notification
{
    use Queueable;

    protected $surat;

    public function __construct(Surat $surat)
    {
        $this->surat = $surat;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'surat_id' => $this->surat->id,
            'jenis_surat' => $this->surat->jenis_surat,
            'nomor_surat' => $this->surat->nomor_surat,
            'tanggal_selesai' => $this->surat->tanggal_selesai,
            'message' => 'Surat ' . $this->surat->jenis_surat . ' Anda telah selesai diproses dan siap untuk dicetak.',
            'action_url' => route('warga.surat.preview', $this->surat->id),
        ];
    }
}