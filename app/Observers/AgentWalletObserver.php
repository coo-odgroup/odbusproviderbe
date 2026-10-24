<?php

namespace App\Observers;

use App\Models\AgentWallet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AgentWalletObserver
{
    public function retrieved(AgentWallet $wallet)
    {
        Log::info('AgentWallet retrieved event fired', [
            'id' => $wallet->id,
        ]);

        // $response = Http::get('https://nt.adglob.in/api/send-notifications');

        // $data = $response->json();

        $response = Http::post('https://nt.adglob.in/api/send-notifications', [
            'agent_id' => $wallet->user_id,
        ]);

        Log::info($response);
    }

    public function created(AgentWallet $wallet)
    {
        Log::info('AgentWallet created event fired', [
            'id' => $wallet->id,
        ]);
    }

    public function updated(AgentWallet $wallet)
    {
        Log::info('AgentWallet updated event fired', [
            'id' => $wallet->id,
            'changes' => $wallet->getChanges(),
        ]);
    }

    public function saved(AgentWallet $wallet)
    {
        Log::info('AgentWallet saved event fired', [
            'id' => $wallet->id,
        ]);
    }
}
