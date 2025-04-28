<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')
            ->scopes([
                'email',
                'pages_show_list',
                'instagram_basic',
                'instagram_manage_insights',
                'instagram_content_publish'
            ])
            ->stateless()
            ->redirect();
    }

    public function callback()
    {
        $user = Socialite::driver('facebook')->stateless()->user();
        $token = $user->token;

        $profileResponse = Http::withToken($token)->get('https://graph.facebook.com/me?fields=id');

        if (!$profileResponse->successful()) {
            return redirect("http://localhost:3000/login?error=perfil_nao_encontrado");
        }

        $facebookPageId = $profileResponse->json()['id'];

        $accountsResponse = Http::withToken($token)->get("https://graph.facebook.com/{$facebookPageId}/accounts?fields=id,name,access_token,instagram_business_account");

        if (!$accountsResponse->successful()) {
            return redirect("http://localhost:3000/login?error=paginas_nao_encontradas");
        }

        $accounts = $accountsResponse->json()['data'] ?? [];
        
        $sessionId = Str::uuid()->toString();
        Cache::put("auth_session:$sessionId", $accounts, now()->addMinutes(10));

        return redirect("http://localhost:3000/login?session_id={$sessionId}");
    }

    public function getSessionAccounts($sessionId)
    {
        $accounts = Cache::get("auth_session:$sessionId");

        if (!$accounts) {
            return response()->json(['error' => 'Session not found.'], 404);
        }

        return response()->json($accounts);
    }
}
