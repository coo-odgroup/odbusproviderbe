<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        return true;
    }

    private function verifyAadhaar($aadhaarNo)
    {
        return true;
    }
}
