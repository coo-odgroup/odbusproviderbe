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
            'fullname'  => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'mobileNo'  => 'required|digits:10',
            'location'  => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'statusCode' => 422,
                'message'    => 'Validation failed',
                'errors'     => $validator->errors()
            ], 422);
        }

        $fullname = trim($request->fullname);
        $email = strtolower(trim($request->email));
        $mobileNo = trim($request->mobileNo);
        $location = trim($request->location);

        DB::beginTransaction();

        try {
            $existingUser = DB::table('user')
                ->where(function ($query) use ($email, $mobileNo) {
                    $query->where('email', $email)
                        ->orWhere('phone', $mobileNo);
                })
                ->first();

            if ($existingUser) {
                if (
                    isset($existingUser->email) &&
                    strtolower($existingUser->email) === $email
                ) {
                    DB::rollBack();

                    return response()->json([
                        'status'     => false,
                        'statusCode' => 409,
                        'message'    => 'Email already exists.'
                    ], 409);
                }

                if (
                    isset($existingUser->phone) &&
                    $existingUser->phone === $mobileNo
                ) {
                    DB::rollBack();

                    return response()->json([
                        'status'     => false,
                        'statusCode' => 409,
                        'message'    => 'Mobile number already exists.'
                    ], 409);
                }
            }

            $clientId = $this->generateClientId();

            $userId = DB::table('user')->insertGetId([
                'name'           => $fullname,
                'email'          => $email,
                'phone'          => $mobileNo,
                'location'       => $location,
                'client_id'      => $clientId,
                'existing_agent' => 1,
                'role_id'        => 3,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            $otp = random_int(100000, 999999);

            $otpExpiry = now()->addMinutes(10);

            DB::table('agent_otp_verification')->insert([
                'agent_id'      => $userId,
                'email_mobile'  => $mobileNo,
                'type'          => 1,
                'purpose'       => 2,
                'otp_value'     => $otp,
                'is_verified'   => 0,
                'verified_at'   => null,
                'expired_at'    => $otpExpiry,
                'attempt_count' => 0,
                'created_at'    => now(),
                'created_by'    => $userId,
            ]);

            $otpSent = $this->sendOtp($mobileNo, $otp);

            if (!$otpSent) {

                DB::rollBack();

                return response()->json([
                    'status'     => false,
                    'statusCode' => 500,
                    'message'    => 'Unable to send OTP. Please try again.'
                ], 500);
            }

            DB::commit();

            $encryptedClientId = encrypt($clientId);

            return response()->json([
                'status'     => true,
                'statusCode' => 200,
                'userId'     => $encryptedClientId,
                'message'    => 'An OTP has been sent to Registered Mobile No'
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status'     => false,
                'statusCode' => 500,
                'message'    => 'Something went wrong.',
                'error'      => $e->getMessage()
            ], 500);
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
            ], 422);
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
                ], 400);
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
                ], 404);
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
                ], 500);
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
            ], 500);
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
            ], 422);
        }


        try {
            try {
                $clientId = decrypt($request->userId);
            } catch (\Exception $e) {

                return response()->json([
                    'status' => false,
                    'statusCode' => 400,
                    'message' => 'Invalid userId'
                ], 400);
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
                ], 404);
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
                ], 400);
            }

            if (now()->greaterThan($otpRecord->expired_at)) {

                return response()->json([
                    'status' => false,
                    'statusCode' => 400,
                    'message' => 'OTP has expired. Please request a new OTP.'
                ], 400);
            }

            if ($otpRecord->attempt_count >= 5) {

                return response()->json([
                    'status' => false,
                    'statusCode' => 400,
                    'message' => 'Maximum OTP attempts exceeded. Please request a new OTP.'
                ], 400);
            }

            if ((string) $otpRecord->otp_value !== (string) $request->otp) {

                DB::table('agent_otp_verification')
                    ->where('id', $otpRecord->id)
                    ->increment('attempt_count');


                return response()->json([
                    'status' => false,
                    'statusCode' => 400,
                    'message' => 'Invalid OTP'
                ], 400);
            }

            DB::table('agent_otp_verification')
                ->where('id', $otpRecord->id)
                ->update([
                    'is_verified' => 1,
                    'verified_at' => now(),
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
            ], 500);
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
            ], 422);
        }


        try {

            try {

                $clientId = decrypt($request->agentId);
            } catch (\Exception $e) {

                return response()->json([
                    'status' => false,
                    'statusCode' => 400,
                    'message' => 'Invalid agentId'
                ], 400);
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
                ], 404);
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
                ], 403);
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
            ], 500);
        }
    }
}
