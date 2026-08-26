<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AgentRegdController extends Controller
{
    public function agentRegd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobileNo' => 'required|digits:10',
            'location' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 200);
        }

        $fullname = trim($request->fullname);
        $email = strtolower(trim($request->email));
        $mobileNo = trim($request->mobileNo);
        $location = trim($request->location);
        $businessName = trim($request->businessName);

        DB::beginTransaction();

        try {

            $emailUser = DB::table('user')
                ->whereRaw('LOWER(email) = ?', [strtolower($email)])
                ->first();

            $mobileUser = DB::table('user')
                ->where('phone', $mobileNo)
                ->first();

            $errorMessage = null;
            $status = true;
            $statusCode = 200;

            // New Mobile + New Email
            if (!$emailUser && !$mobileUser) {

                $clientId = $this->generateClientId();

                $userId = DB::table('user')->insertGetId([
                    'name' => $fullname,
                    'email' => $email,
                    'phone' => $mobileNo,
                    'location' => $location,
                    'organization_name' => $businessName,
                    'client_id' => $clientId,
                    'existing_agent' => 1,
                    'role_id' => 3,
                    'is_mobile_verified' => 0,
                    'is_email_verified' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Same Email + Same Mobile
            elseif (
                $emailUser &&
                $mobileUser &&
                $emailUser->id == $mobileUser->id
            ) {

                $errorMessage = 'Existing agent found.';
                $status = false;
                $statusCode = 409;
            }

            // Existing Mobile + New Email
            elseif ($mobileUser && !$emailUser) {

                // Check whether EMAIL is already verified
                if ($mobileUser->is_email_verified == 1) {

                    $errorMessage = 'Email is already verified. You cannot update the email.';
                    $status = false;
                    $statusCode = 409;
                }

                // Check verified documents
                elseif (
                    DB::table('agent_documents')
                    ->where('agent_id', $mobileUser->id)
                    ->where('is_active', 1)
                    ->where('status', 'VERIFIED')
                    ->exists()
                ) {

                    $errorMessage = 'Agent documents are already verified. You cannot update the email.';
                    $status = false;
                    $statusCode = 409;
                }

                // Allow email update
                else {

                    DB::table('user')
                        ->where('id', $mobileUser->id)
                        ->update([
                            'email' => $email,
                            'is_email_verified' => 0,
                            'updated_at' => now(),
                        ]);

                    $userId = $mobileUser->id;
                    $clientId = $mobileUser->client_id;
                }
            }

            // Existing Email + New Mobile
            elseif ($emailUser && !$mobileUser) {

                // Check whether MOBILE is already verified
                if ($emailUser->is_mobile_verified == 1) {

                    $errorMessage = 'Mobile number is already verified. You cannot update the mobile number.';
                    $status = false;
                    $statusCode = 409;
                }

                // Check verified documents
                elseif (
                    DB::table('agent_documents')
                    ->where('agent_id', $emailUser->id)
                    ->where('is_active', 1)
                    ->where('status', 'VERIFIED')
                    ->exists()
                ) {

                    $errorMessage = 'Agent documents are already verified. You cannot update the mobile number.';
                    $status = false;
                    $statusCode = 409;
                }

                // Allow mobile update
                else {

                    DB::table('user')
                        ->where('id', $emailUser->id)
                        ->update([
                            'phone' => $mobileNo,
                            'is_mobile_verified' => 0,
                            'updated_at' => now(),
                        ]);

                    $userId = $emailUser->id;
                    $clientId = $emailUser->client_id;
                }
            }

            // Email + Mobile Belong To Different Agents
            elseif (
                $emailUser &&
                $mobileUser &&
                $emailUser->id != $mobileUser->id
            ) {

                $errorMessage = 'Email and mobile number belong to different agents.';
                $status = false;
                $statusCode = 409;
            }

            if ($errorMessage) {

                DB::rollBack();
            } else {

                $otp = random_int(100000, 999999);

                $otpData = [
                    'agent_id' => $userId,
                    'email_mobile' => $mobileNo,
                    'type' => 1,
                    'purpose' => 2,
                    'otp_value' => $otp,
                    'is_verified' => 0,
                    'verified_at' => null,
                    'expired_at' => now()->addMinutes(10),
                    'attempt_count' => 0,
                    'created_at' => now(),
                    'created_by' => $userId,
                ];

                DB::table('agent_otp_verification')->insert($otpData);

                $otpSent = $this->sendOtp($mobileNo, $otp);

                if (!$otpSent) {

                    DB::rollBack();

                    $errorMessage = 'Unable to send OTP. Please try again.';
                    $status = false;
                    $statusCode = 500;
                } else {

                    DB::commit();
                }
            }

            return response()->json([
                'status' => $status,
                'statusCode' => $statusCode,
                'userId' => $errorMessage ? null : encrypt($clientId),
                'message' => $errorMessage ?? 'An OTP has been sent to Registered Mobile No'
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'statusCode' => 500,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 200);
        }
    }

    private function generateClientId()
    {
        do {

            $clientId = 'AG' . strtoupper(
                substr(
                    md5(uniqid(mt_rand(), true)),
                    0,
                    10
                )
            );

            $exists = DB::table('user')
                ->where('client_id', $clientId)
                ->exists();
        } while ($exists);

        return $clientId;
    }

    private function sendOtp($mobileNo, $otp)
    {
        return true;
    }

    public function agentRegdSendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 200);
        }

        DB::beginTransaction();

        try {
            try {
                $clientId = decrypt($request->userId);
            } catch (\Exception $e) {

                return response()->json([
                    'status' => false,
                    'statusCode' => 400,
                    'message' => 'Invalid userId'
                ], 200);
            }

            $agent = DB::table('user')
                ->where('client_id', $clientId)
                ->where('existing_agent', 1)
                ->first();

            if (!$agent) {

                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'statusCode' => 404,
                    'message' => 'Agent not found'
                ], 200);
            }

            DB::table('agent_otp_verification')
                ->where('agent_id', $agent->id)
                ->where('type', 1)
                ->where('purpose', 2)
                ->where('is_verified', 0)
                ->update([
                    'expired_at' => now(),
                    'updated_at' => now()
                ]);

            $otp = random_int(100000, 999999);

            $otpExpiry = now()->addMinutes(10);

            DB::table('agent_otp_verification')->insert([
                'agent_id' => $agent->id,
                'email_mobile' => $agent->phone,
                'type' => 1,
                'purpose' => 2,
                'otp_value' => $otp,
                'is_verified' => 0,
                'verified_at' => null,
                'expired_at' => $otpExpiry,
                'attempt_count' => 0,
                'created_at' => now(),
                'created_by' => $agent->id,
            ]);

            $otpSent = $this->sendOtp($agent->phone, $otp);

            if (!$otpSent) {

                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'statusCode' => 500,
                    'message' => 'Unable to send OTP. Please try again.'
                ], 200);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'statusCode' => 200,
                'userId' => $request->userId,
                'message' => 'OTP has been sent to Registered Mobile No'
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'statusCode' => 500,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 200);
        }
    }

    public function agentRegdOtpVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|string',
            'type' => 'required|integer',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 200);
        }


        try {
            try {
                $clientId = decrypt($request->userId);
            } catch (\Exception $e) {

                return response()->json([
                    'status' => false,
                    'statusCode' => 400,
                    'message' => 'Invalid userId'
                ], 200);
            }

            $agent = DB::table('user')
                ->where('client_id', $clientId)
                ->where('existing_agent', 1)
                ->first();


            if (!$agent) {

                return response()->json([
                    'status' => false,
                    'statusCode' => 404,
                    'message' => 'Agent not found'
                ], 200);
            }

            $otpRecord = DB::table('agent_otp_verification')
                ->where('agent_id', $agent->id)
                ->where('type', $request->type)
                ->where('purpose', 2)
                ->where('is_verified', 0)
                ->orderBy('id', 'DESC')
                ->first();


            if (!$otpRecord) {

                return response()->json([
                    'status' => false,
                    'statusCode' => 400,
                    'message' => 'OTP not found. Please request a new OTP.'
                ], 200);
            }

            if (now()->greaterThan($otpRecord->expired_at)) {

                return response()->json([
                    'status' => false,
                    'statusCode' => 400,
                    'message' => 'OTP has expired. Please request a new OTP.'
                ], 200);
            }

            if ($otpRecord->attempt_count >= 5) {

                return response()->json([
                    'status' => false,
                    'statusCode' => 400,
                    'message' => 'Maximum OTP attempts exceeded. Please request a new OTP.'
                ], 200);
            }

            if ((string) $otpRecord->otp_value !== (string) $request->otp) {

                DB::table('agent_otp_verification')
                    ->where('id', $otpRecord->id)
                    ->increment('attempt_count');

                return response()->json([
                    'status' => false,
                    'statusCode' => 400,
                    'message' => 'Invalid OTP'
                ], 200);
            }

            DB::table('agent_otp_verification')
                ->where('id', $otpRecord->id)
                ->update([
                    'is_verified' => 1,
                    'verified_at' => now(),
                    'updated_at' => now()
                ]);

            DB::table('user')->where('id', $agent->id)
                ->update([
                    'is_mobile_verified' => 1,
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => true,
                'statusCode' => 200,
                'userId' => $request->userId,
                'message' => 'Your OTP is verified'
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'statusCode' => 500,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 200);
        }
    }

    public function agentUpdateData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agentId' => 'required|string',

            'panNo' => [
                'required',
                'string',
                'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'
            ],

            'panImage' => 'required|image|mimes:jpg,jpeg,png|max:5120',

            'adhaarNo' => 'required|digits:12',

            'adhaarImage' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => false,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 200);
        }


        try {

            try {

                $clientId = decrypt($request->agentId);
            } catch (\Exception $e) {

                return response()->json([
                    'status' => false,
                    'statusCode' => 400,
                    'message' => 'Invalid agentId'
                ], 200);
            }

            $agent = DB::table('user')
                ->where('client_id', $clientId)
                ->where('existing_agent', 1)
                ->first();

            if (!$agent) {

                return response()->json([
                    'status' => false,
                    'statusCode' => 404,
                    'message' => 'Agent not found'
                ], 200);
            }

            $otpVerified = DB::table('agent_otp_verification')
                ->where('agent_id', $agent->id)
                ->where('type', 1)
                ->where('purpose', 2)
                ->where('is_verified', 1)
                ->exists();

            if (!$otpVerified) {

                return response()->json([
                    'status' => false,
                    'statusCode' => 403,
                    'message' => 'Please verify your mobile number first.'
                ], 200);
            }

            $panNo = strtoupper(trim($request->panNo));
            $aadhaarNo = trim($request->adhaarNo);

            $panDirectory = public_path('uploads/agent/pan');
            $aadhaarDirectory = public_path('uploads/agent/aadhaar');


            if (!file_exists($panDirectory)) {
                mkdir($panDirectory, 0755, true);
            }


            if (!file_exists($aadhaarDirectory)) {
                mkdir($aadhaarDirectory, 0755, true);
            }

            $panImage = $request->file('panImage');

            $panImageName = time()
                . '_pan_'
                . $agent->id
                . '.'
                . $panImage->getClientOriginalExtension();


            $panImage->move(
                $panDirectory,
                $panImageName
            );

            $panImagePath = 'uploads/agent/pan/' . $panImageName;

            $aadhaarImage = $request->file('adhaarImage');

            $aadhaarImageName = time()
                . '_aadhaar_'
                . $agent->id
                . '.'
                . $aadhaarImage->getClientOriginalExtension();

            $aadhaarImage->move(
                $aadhaarDirectory,
                $aadhaarImageName
            );

            $aadhaarImagePath = 'uploads/agent/aadhaar/' . $aadhaarImageName;

            $panHash = hash('sha256', $panNo);

            $panLast4 = substr($panNo, -4);

            $aadhaarHash = hash('sha256', $aadhaarNo);

            $aadhaarLast4 = substr($aadhaarNo, -4);

            $duplicatePan = DB::table('agent_identity')
                ->where('pan_hash', $panHash)
                ->where('agent_id', '!=', $agent->id)
                ->exists();


            $duplicateAadhaar = DB::table('agent_identity')
                ->where('aadhaar_hash', $aadhaarHash)
                ->where('agent_id', '!=', $agent->id)
                ->exists();

            DB::table('agent_documents')->insert([
                'agent_id' => $agent->id,
                'document_number' => $panNo,
                'document_type' => 'PAN',
                'file_path' => $panImagePath,
                'file_name' => $panImageName,
                'status' => 'UPLOADED',
                'uploaded_at' => now(),
                'verified_at' => null,
                'verified_by' => null,
                'verification_source' => null,
                'remarks' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('agent_documents')->insert([
                'agent_id' => $agent->id,
                'document_number' => $aadhaarNo,
                'document_type' => 'AADHAAR',
                'file_path' => $aadhaarImagePath,
                'file_name' => $aadhaarImageName,
                'status' => 'UPLOADED',
                'uploaded_at' => now(),
                'verified_at' => null,
                'verified_by' => null,
                'verification_source' => null,
                'remarks' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $identityData = [

                'agent_id' => $agent->id,

                'pan_encrypted' => encrypt($panNo),
                'pan_hash' => $panHash,
                'pan_last4' => $panLast4,

                'aadhaar_encrypted' => encrypt($aadhaarNo),
                'aadhaar_hash' => $aadhaarHash,
                'aadhaar_last4' => $aadhaarLast4,

                'pan_verified' => 0,
                'pan_verification_status' => 'PENDING',
                'pan_verified_at' => null,

                'aadhaar_verified' => 0,
                'aadhaar_verification_status' => 'PENDING',
                'aadhaar_verified_at' => null,

                'duplicate_pan' => $duplicatePan ? 1 : 0,
                'duplicate_aadhaar' => $duplicateAadhaar ? 1 : 0,

                'remarks' => null,
                'updated_at' => now(),
            ];

            $existingIdentity = DB::table('agent_identity')
                ->where('agent_id', $agent->id)
                ->first();


            if ($existingIdentity) {

                DB::table('agent_identity')
                    ->where('agent_id', $agent->id)
                    ->update($identityData);
            } else {

                $identityData['created_at'] = now();

                DB::table('agent_identity')
                    ->insert($identityData);
            }

            // \App\Jobs\AgentIdentityJob::dispatch($agent->id);
            (new \App\Jobs\AgentIdentityJob($agent->id))->handle();

            return response()->json([
                'status' => true,
                'statusCode' => 200,
                'userId' => $request->agentId,
                'message' => 'KYC details submitted successfully. Verification is in progress.'
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'statusCode' => 500,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 200);
        }
    }

    public function checkEmailExist(Request $request)
    {
        $exists = DB::table('user')
            ->whereRaw('LOWER(email) = ?', [strtolower($request->email)])
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => true,
                'statusCode' => 200,
                'message' => 'Email already exists'
            ], 200);
        }

        return response()->json([
            'status' => false,
            'statusCode' => 200,
            'message' => 'New Email id Valid for registration'
        ], 200);
    }
}
