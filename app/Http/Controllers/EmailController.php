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
            return response()->json([
                'success' => false,
                'message' => 'Você precisa estar logado para enviar e-mails.',
            ], 401);
        }

        $smtpSettings = Smtp::where('user_id', Auth::id())->first();

        if (!$smtpSettings) {
            return response()->json([
                'success' => false,
                'message' => 'Configurações SMTP não encontradas.',
            ], 404);
        }

        Config::set('mail.mailers.smtp.host', $smtpSettings->host);
        Config::set('mail.mailers.smtp.port', $smtpSettings->port);
        Config::set('mail.mailers.smtp.username', $smtpSettings->username);
        Config::set('mail.mailers.smtp.password', $smtpSettings->password);
        Config::set('mail.mailers.smtp.encryption', $smtpSettings->encryption);
        Config::set('mail.from.address', $smtpSettings->from_address);
        Config::set('mail.from.name', $smtpSettings->from_name);
        Config::set('mail.default', $smtpSettings->mailer);

        $request->validate([
            'emails' => ['required', 'string', function ($value, $fail) {
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

        $emails = explode(',', $request->emails);
        $errors = [];

        foreach ($emails as $email) {
            $email = trim($email);

            try {
                Mail::to($email)->send(new Send($request->subject, $request->mensagem));
                Log::info("E-mail enviado para: $email");
                sleep(4);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar e-mail para: ' . $email . ' | Erro: ' . $e->getMessage());
                $errors[] = "Erro ao enviar e-mail para: $email";
            }
        }

        if (count($errors) > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Alguns e-mails não foram enviados.',
                'errors' => $errors,
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'E-mail(s) enviado(s) com sucesso!',
        ]);
    }

    public function index()
    {
        return view('email.email-form');
    }
}
