<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\Send;
use App\Models\Smtp;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class EmailController extends Controller
{
    public function send(Request $request)
    {
        if (!Auth::check()) {
            // Log::warning('Tentativa de envio de e-mail sem estar logado.');
            return redirect()->route('login')->with('error', 'Você precisa estar logado para enviar e-mails.');
        }

        $smtpSettings = Smtp::where('user_id', Auth::id())->first();


        if (!$smtpSettings) {
            // Log::error('Configurações SMTP não encontradas para o usuário: ' . Auth::id());
            return redirect()->route('email.form')->with('error', 'Configurações SMTP não encontradas.');
        } else {
            // Log::info('Configurações SMTP carregadas para o usuário: ' . Auth::id());
        }



        // Config::set('mail.mailers.smtp', [
        //     'transport' => 'smtp',
        //     'host' => $smtpSettings->host,
        //     'port' => $smtpSettings->port,
        //     'encryption' => 'tls',
        //     'username' => $smtpSettings->username,
        //     'password' => $smtpSettings->password,
        //     'timeout' => null,
        //     'auth_mode' => null,
        // ]);

        // Config::set('mail.from', [
        //     'address' => $smtpSettings->from_address,
        //     'name' => $smtpSettings->from_name,
        // ]);

        // $configEmail = [
        //     'driver' => 'smtp',
        //     'mailer' => $request->input('mailer'),
        //     'host' => $request->input('host'),
        //     'port' => $request->input('port'),
        //     'username' => $request->input('username'),
        //     'password' => $request->input('password'),
        //     'encryption' => $request->input('encryption', 'tls'),
        //     'from' => [
        //         'address' => $request->input('from_address', 'admin@localhost'),
        //         'name' => $request->input('from_name', 'Admin'),
        //     ]
        // ];

        // Log::info('configuração recebida');

        // Config::set('mail.mailers.smtp', [
        //     'transport' => $configEmail['driver'],
        //     'host' => $configEmail['host'],
        //     'port' => $configEmail['port'],
        //     'encryption' => $configEmail['encryption'],
        //     'username' => $configEmail['username'],
        //     'password' => $configEmail['password'],
        //     'from' => [
        //         'address' => $configEmail['from']['address'],
        //         'name' => $configEmail['from']['name'],
        //     ],
        // ]);

        // Config::set('mail.from.address', $configEmail['from']['address']);
        // Config::set('mail.from.name', $configEmail['from']['name']);

        // Config::set('mail.mailers.smtp.host', 'smtp.gmail.com');
        // Config::set('mail.mailers.smtp.port', 587);
        // Config::set('mail.mailers.smtp.username', 'gustavo.sampaio195@gmail.com');
        // Config::set('mail.mailers.smtp.password', 'vwgjsnxfynkiazxo');
        // Config::set('mail.mailers.smtp.encryption', 'tls');

        $configEmail = Smtp::first();

        Config::set('mail.mailers.smtp.host', $configEmail->host);
        Config::set('mail.mailers.smtp.port', $configEmail->port);
        Config::set('mail.mailers.smtp.username', $configEmail->username);
        Config::set('mail.mailers.smtp.password', $configEmail->password);
        Config::set('mail.mailers.smtp.encryption', $configEmail->encryption);

        Config::set('mail.from.address', $configEmail->from_address);
        Config::set('mail.from.name', $configEmail->from_name);

        Config::set('mail.default', $configEmail->mailer);



        $request->validate([
            'emails' => ['required', 'string', function ($attribute, $value, $fail) {
                $emails = explode(',', $value);
                foreach ($emails as $email) {
                    if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                        $fail('Um ou mais e-mails são inválidos.');
                    }
                }
            }],
            'subject' => 'required|string',
            'mensagem' => 'required|string',
        ]);
        // Log::info('configuração aplicada');

        $emails = explode(',', $request->emails);

        foreach ($emails as $email) {
            $email = trim($email);

            try {
                Mail::to($email)->send(new Send($request->subject, $request->mensagem));
               
                sleep(4);

                Log::info("enviando email para $email");
            } catch (\Exception $e) {
                Log::info('Erro ao enviar e-mail para: ' . $email . ' | Erro: ' . $e->getMessage());
            }
        }

        return redirect()->route('email.form')->with('success', 'E-mail(s) enviado(s) com sucesso!');
    }

    public function index()
    {
        return view('email.email-form');
    }
}
