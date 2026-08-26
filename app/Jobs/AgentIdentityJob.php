<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AgentIdentityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $agentId;

    public function __construct($agentId)
    {
        $this->agentId = $agentId;

        $this->onQueue('agent-identity');
    }

    public function handle()
    {
        Log::info("AgentIdentityJob started for agent_id: {$this->agentId}");
        $identity = DB::table('agent_identity')
            ->where('agent_id', $this->agentId)
            ->first();


        if (!$identity) {
            return;
        }

        $agent = DB::table('user')
            ->where('id', $this->agentId)
            ->where('existing_agent', 1)
            ->first();


        if (!$agent) {
            return;
        }

        if ($identity->duplicate_pan == 1) {

            DB::table('agent_identity')
                ->where('agent_id', $this->agentId)
                ->update([
                    'pan_verification_status' => 'FAILED',
                    'remarks' => 'PAN already registered with another agent.',
                    'updated_at' => now()
                ]);
        } else {

            DB::table('agent_identity')
                ->where('agent_id', $this->agentId)
                ->update([
                    'pan_verification_status' => 'UNDER_REVIEW',
                    'updated_at' => now()
                ]);

            try {

                $panNo = decrypt($identity->pan_encrypted);
            } catch (\Exception $e) {

                DB::table('agent_identity')
                    ->where('agent_id', $this->agentId)
                    ->update([
                        'pan_verification_status' => 'FAILED',
                        'remarks' => 'Unable to decrypt PAN.',
                        'updated_at' => now()
                    ]);

                $panNo = null;
            }


            if ($panNo) {

                $panResult = $this->verifyPan($panNo);


                if ($panResult === true) {

                    DB::table('agent_identity')
                        ->where('agent_id', $this->agentId)
                        ->update([
                            'pan_verified' => 1,
                            'pan_verification_status' => 'VERIFIED',
                            'pan_verified_at' => now(),
                            'updated_at' => now()
                        ]);

                    DB::table('user')->where('id', $this->agentId)
                        ->update([
                            'is_pan_verified' => 1,
                            'updated_at' => now()
                        ]);
                } else {

                    DB::table('agent_identity')
                        ->where('agent_id', $this->agentId)
                        ->update([
                            'pan_verified' => 0,
                            'pan_verification_status' => 'FAILED',
                            'updated_at' => now()
                        ]);
                }
            }
        }

        Log::info("AgentIdentityJob completed for agent_id: {$this->agentId}");

        $identity = DB::table('agent_identity')
            ->where('agent_id', $this->agentId)
            ->first();


        if ($identity->duplicate_aadhaar == 1) {

            DB::table('agent_identity')
                ->where('agent_id', $this->agentId)
                ->update([
                    'aadhaar_verification_status' => 'FAILED',
                    'remarks' => 'Aadhaar already registered with another agent.',
                    'updated_at' => now()
                ]);
        } else {

            DB::table('agent_identity')
                ->where('agent_id', $this->agentId)
                ->update([
                    'aadhaar_verification_status' => 'UNDER_REVIEW',
                    'updated_at' => now()
                ]);

            try {

                $aadhaarNo = decrypt($identity->aadhaar_encrypted);
            } catch (\Exception $e) {

                DB::table('agent_identity')
                    ->where('agent_id', $this->agentId)
                    ->update([
                        'aadhaar_verification_status' => 'FAILED',
                        'remarks' => 'Unable to decrypt Aadhaar.',
                        'updated_at' => now()
                    ]);

                $aadhaarNo = null;
            }


            if ($aadhaarNo) {

                $aadhaarResult = $this->verifyAadhaar($aadhaarNo);


                if ($aadhaarResult === true) {

                    DB::table('agent_identity')
                        ->where('agent_id', $this->agentId)
                        ->update([
                            'aadhaar_verified' => 1,
                            'aadhaar_verification_status' => 'VERIFIED',
                            'aadhaar_verified_at' => now(),
                            'updated_at' => now()
                        ]);

                    DB::table('user')->where('id', $this->agentId)
                        ->update([
                            'is_aadhaar_verified' => 1,
                            'updated_at' => now()
                        ]);
                } else {

                    DB::table('agent_identity')
                        ->where('agent_id', $this->agentId)
                        ->update([
                            'aadhaar_verified' => 0,
                            'aadhaar_verification_status' => 'FAILED',
                            'updated_at' => now()
                        ]);
                }
            }
        }

        $identity = DB::table('agent_identity')
            ->where('agent_id', $this->agentId)
            ->first();


        if (
            $identity->pan_verified == 1 &&
            $identity->aadhaar_verified == 1
        ) {

            DB::table('agent_identity')
                ->where('agent_id', $this->agentId)
                ->update([
                    'remarks' => 'PAN and Aadhaar verified successfully.',
                    'updated_at' => now()
                ]);

            $originalPassword = 'OdBus@' . random_int(1000, 9999);

            DB::table('user')
                ->where('id', $this->agentId)
                ->update([
                    'password' => Hash::make($originalPassword),
                    'adhar_no' => $aadhaarNo,
                    'pancard_no' => $panNo,
                    'status' => 1,
                    'updated_at' => now()
                ]);

            Log::info('Agent password generated', [
                'agent_id' => $this->agentId,
                'password' => $originalPassword
            ]);


            /*
             * Agent verification completed
             *
             * Send SMS / WhatsApp here later.
             */

            // $this->sendAgentConfirmation($agent);


        } else {

            DB::table('agent_identity')
                ->where('agent_id', $this->agentId)
                ->update([
                    'updated_at' => now()
                ]);
        }
    }

    private function verifyPan($panNo)
    {
        try {
            $response = Http::post(url('/api/verify-pan'), [
                'pan' => $panNo
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['response']['valid']) && $data['response']['valid'] == 1) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {

            Log::error('PAN verification failed', [
                'pan' => $panNo,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    private function verifyAadhaar($aadhaarNo)
    {
        return true;
    }
}
