<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstagramAccountDetailsRequest;
use App\Services\CheckBusinessAccountService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Traits\ApiResponse;

class LoginController extends Controller
{
    use ApiResponse; // Adiciona o trait ApiResponse

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

        $profileResponse = Http::withToken($token)->get(env("GRAPH_API_URI") . '/me?fields=id');

        if (!$profileResponse->successful()) {
            return redirect(env("WEB_URL") . "/login?errorResponse=profile_not_found");
        }

        $facebookAccountId = $profileResponse->json()['id'];

        $linkedAccountsResponse = Http::withToken($token)->get(env("GRAPH_API_URI") . "/{$facebookAccountId}/accounts?fields=access_token,tasks,name,id,instagram_business_account");

        if (!$linkedAccountsResponse->successful()) {
            return redirect(env("WEB_URL") . "/login?errorResponse=pages_not_found");
        }

        $linkedAccounts = $linkedAccountsResponse->json()['data'] ?? [];

        $checkBusinessAccountService = new CheckBusinessAccountService($linkedAccounts);

        if (!$checkBusinessAccountService->ensureIsBusinessAccount()) {
            return $this->errorResponse("There's no linked business account.", 500);
        }

        $sessionId = Str::uuid()->toString();
        Cache::put("auth_session:$sessionId", $linkedAccounts, now()->addHour());

        return redirect(env("WEB_URL") . "/login?session_id={$sessionId}");
    }

    public function getSessionAccounts(string $sessionId)
    {
        $accounts = Cache::get("auth_session:$sessionId");

        if (!$accounts) {
            return $this->errorResponse('Accounts not found.', 404);
        }

        return $this->successResponse($accounts, 'Accounts retrieved successfully.');
    }

    public function getInstagramAccountData(InstagramAccountDetailsRequest $request, string $accountId)
    {
        $accessTokenQueryParam = $request->validate([ // for query params
            'access_token' => 'required|string',
        ]);

        $accessToken = $accessTokenQueryParam['access_token'];
        $sessionId = $request->session_id;

        $accounts = Cache::get("auth_session:$sessionId");

        if (!$accounts) {
            return $this->errorResponse('Session not found.', 404);
        }

        $accountsCollection = collect($accounts);

        $instagramAccount = $accountsCollection->first(function($account) use ($accountId) {
            return isset($account['instagram_business_account']['id']) && $account['instagram_business_account']['id'] === $accountId; 
        });

        if (!$instagramAccount) {
            return $this->errorResponse('Account not found.', 404);
        }

        $instagramAccountResponse = Http::withToken($accessToken)->get(env("GRAPH_API_URI") . "/{$accountId}?fields=id,name,profile_picture_url");

        if (!$instagramAccountResponse->successful()) {
            return $this->errorResponse('Instagram account not found.', 404);
        }

        return $this->successResponse($instagramAccountResponse->json(), 'Instagram account data retrieved successfully.', 200);
    }
}