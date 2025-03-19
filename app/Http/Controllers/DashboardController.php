<?php

namespace App\Http\Controllers;

use App\Models\SaveEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $emailsSent = SaveEmail::where('user_id', Auth::id())
            ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();


        $emailsSent = $emailsSent->map(function ($email) {

            $email->formatted_date = Carbon::parse($email->date)->format('d/m/Y');
            return $email;
        });


        $latestEmails = SaveEmail::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $latestEmails = $latestEmails->map(function ($email) {

            $email->formatted_date = $email->created_at ? $email->created_at->format('H:i:s d/m/Y') : 'Data não disponível';
            return $email;
        });


        return view('dashboard', [
            'emailsSent' => $emailsSent,
            'latestEmails' => $latestEmails
        ]);
    }

    public function destroy($id)
    {

        $email = SaveEmail::where('user_id', Auth::id())->findOrFail($id);

        $email->delete();


        return redirect()->route('dashboard')->with('success', 'E-mail excluído com sucesso!');
    }
}
