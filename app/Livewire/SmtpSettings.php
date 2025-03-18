<?php

namespace App\Livewire;

use App\Models\Smtp;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SmtpSettings extends Component
{


    public $mailer, $host, $port, $username, $password, $from_address, $from_name;

    public function mount()
    {
        $smtp = Smtp::where('user_id', Auth::id())->first();

        if ($smtp) {
            $this->mailer = $smtp->mailer;
            $this->host = $smtp->host;
            $this->port = $smtp->port;
            $this->username = $smtp->username;
            $this->password = $smtp->password;
            $this->from_address = $smtp->from_address;
            $this->from_name = $smtp->from_name;
        }
    }

    public function saveSettings()
    {
        $this->validate([
            'mailer' => 'required|string|in:smtp,pop3,imap,mapi,http,xmpp,jmap',
            'host' => 'required|string',
            'port' => 'required|integer',
            'username' => 'required|string',
            'password' => 'required|string',
            'from_address' => 'required|email',
            'from_name' => 'required|string',
        ]);

        Smtp::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'mailer' => $this->mailer,
                'host' => $this->host,
                'port' => $this->port,
                'username' => $this->username,
                'password' => $this->password,
                'from_address' => $this->from_address,
                'from_name' => $this->from_name,
                'user_id' => Auth::id(),
            ]
        );

        session()->flash('success', 'Configuração salva com sucesso!');
    }
    public function render()
    {
        return view('livewire.smtp-settings');
    }
}
