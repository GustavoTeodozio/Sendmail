<?php

namespace App\Livewire;

use App\Http\Controllers\EmailController;
use Livewire\Component;

class NotificacaoEmail extends Component
{
    public $loading = false;

    public function notificacao()
    {
        $this->loading = true;
        $this->dispatchBrowserEvent('noticacao');

    }
    
}
