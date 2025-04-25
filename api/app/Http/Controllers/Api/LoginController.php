<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;

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
        $encoded = urlencode(json_encode($accounts));

        return redirect("http://localhost:3000/login?accounts={$encoded}");
    }

    public function selectAccount(int $instagramPageId)
    {
        $accountResponse = Http::get("https://graph.facebook.com/{$instagramPageId}?fields=instagram_business_account");

        if (!$accountResponse->successful()) {
            return response()->json(['error' => 'Erro ao obter a conta do Instagram.'], 500);
        }

        $instagramAccount = $accountResponse->json();

        return response()->json([
            'instagram_account' => $instagramAccount['instagram_business_account'] ?? null
        ]);
    }
}
