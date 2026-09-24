<?php

namespace App\Http\Controllers;

use App\Models\ApiVendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Str;


class ApiVendorController extends Controller
{
    public function getVendors(Request $request)
    {
        try {

            $query = ApiVendor::leftJoin(
                'user',
                'user.id',
                '=',
                'api_vendors.created_by'
            )
                ->select(
                    'api_vendors.*',
                    'user.name as created_by_name'
                );

            if ($request->filled('search')) {

                $search = trim($request->search);

                $query->where(function ($q) use ($search) {

                    $q->where('api_vendors.vendor_code', 'LIKE', "%{$search}%")
                        ->orWhere('api_vendors.company_name', 'LIKE', "%{$search}%")
                        ->orWhere('api_vendors.contact_name', 'LIKE', "%{$search}%")
                        ->orWhere('api_vendors.contact_email', 'LIKE', "%{$search}%")
                        ->orWhere('api_vendors.contact_phone', 'LIKE', "%{$search}%")
                        ->orWhere('api_vendors.rate_limit_per_minute', 'LIKE', "%{$search}%");

                    // Search GST also
                    if (strtolower($search) === 'yes' || strtolower($search) === 'gst') {
                        $q->orWhere('api_vendors.has_gst', 1);
                    }

                    if (strtolower($search) === 'no') {
                        $q->orWhere('api_vendors.has_gst', 0);
                    }
                });
            }

            if ($request->has('has_gst') && $request->has_gst !== '') {

                $query->where(
                    'api_vendors.has_gst',
                    $request->has_gst
                );
            }
            if ($request->has('status') && $request->status !== '') {

                $query->where(
                    'api_vendors.status',
                    $request->status
                );
            }
            $vendors = $query
                ->orderBy('api_vendors.id', 'DESC')
                ->get();

            return response()->json([
                'status' => 1,
                'message' => 'Vendor list fetched successfully',
                'data' => $vendors
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getVendor(Request $request)
    {
        try {

            $vendor = ApiVendor::where('id', $request->id)->first();

            if (!$vendor) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Vendor not found'
                ], 404);
            }

            $address = DB::table('api_vendor_address')
                ->where('vendor_id', $vendor->id)
                ->first();

            $environments = DB::table('api_vendor_environments')
                ->where('vendor_id', $vendor->id)
                ->get();

            return response()->json([
                'status' => 1,
                'message' => 'Vendor fetched successfully',
                'data' => [
                    'vendor' => $vendor,
                    'address' => $address,
                    'environments' => $environments
                ]
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function addVendor(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [

                'company_name' => 'required|string|max:255',
                'contact_name' => 'required|string|max:255',
                'contact_email' => 'required|email|max:255',
                'contact_phone' => 'required|string|max:10',
                'rate_limit_per_minute' => 'required|integer|min:1',
                'has_gst' => 'nullable|boolean',
                'address' => 'required|string|max:500',
                'street' => 'nullable|string|max:255',
                'landmark' => 'nullable|string|max:255',
                'city' => 'required|string|max:100',
                'pincode' => 'required|string|max:10',
                'state' => 'required|integer|exists:state,id',
                'sandbox' => 'nullable|boolean',
                'production' => 'nullable|boolean',
                'created_by' => 'required|integer',
                'updated_by' => 'nullable|integer',
            ]);


            if ($validator->fails()) {

                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }


            do {

                $vendorCode = random_int(100000, 999999);
            } while (
                ApiVendor::where(
                    'vendor_code',
                    $vendorCode
                )->exists()
            );


            $vendor = new ApiVendor();
            $vendor->vendor_code = $vendorCode;
            $vendor->company_name = $request->company_name;
            $vendor->contact_name = $request->contact_name;
            $vendor->contact_email = $request->contact_email;
            $vendor->contact_phone = $request->contact_phone;
            $vendor->rate_limit_per_minute = $request->rate_limit_per_minute;
            $vendor->has_gst = $request->has_gst ? 1 : 0;
            $vendor->status = 1;
            $vendor->activated_at = Carbon::now();
            $vendor->suspended_at = null;
            $vendor->risk_score = $request->risk_score ?? null;
            $vendor->created_by = $request->created_by;
            $vendor->updated_by = $request->updated_by ?? $request->created_by;
            $vendor->save();

            DB::table('api_vendor_address')->insert([

                'vendor_id' => $vendor->id,
                'address' => $request->address,
                'street' => $request->street,
                'landmark' => $request->landmark,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'state' => $request->state,
                'created_at' => Carbon::now(),
                'created_by' => $request->created_by,
                'updated_at' => Carbon::now(),
                'updated_by' => $request->updated_by ?? $request->created_by,
            ]);


            $environments = [];
            if ((int) $request->sandbox === 1) {

                $environments[] = [

                    'vendor_id' => $vendor->id,
                    'environment' => 'sandbox',
                    'status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            if ((int) $request->production === 1) {

                $environments[] = [

                    'vendor_id' => $vendor->id,
                    'environment' =>   'production',
                    'status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            foreach ($environments as $environment) {


                $environmentId = DB::table('api_vendor_environments')->insertGetId($environment);

                DB::table('api_vendor_access_settings')->insert([

                    'vendor_id' => $vendor->id,
                    'vendor_environment_id' => $environmentId,
                    'default_allowed_start_time' => '00:00:00',
                    'default_allowed_end_time' => '23:59:59',
                    'status' => 1,
                    'created_at' => Carbon::now(),
                    'created_by' => $request->created_by,
                    'updated_at' => Carbon::now(),
                    'updated_by' => $request->updated_by ?? $request->created_by,
                ]);
            }

            return response()->json([

                'status' => 1,
                'message' =>
                'Vendor added successfully',
                'data' => [
                    'vendor' => $vendor,
                    'address' => $request->only([
                        'address',
                        'street',
                        'landmark',
                        'city',
                        'pincode',
                        'state'
                    ]),
                    'environments' => $environments,
                ]

            ], 201);
        } catch (\Exception $e) {

            return response()->json([

                'status' => 0,
                'message' =>
                $e->getMessage()

            ], 500);
        }
    }

    public function updateVendor(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [

                'id' => 'required|integer',
                'company_name' => 'required|string|max:255',
                'contact_name' => 'required|string|max:255',
                'contact_email' => 'required|email|max:255',
                'contact_phone' => 'required|string|max:10',
                'rate_limit_per_minute' => 'required|integer|min:1',
                'has_gst' => 'nullable|boolean',
                'address' => 'required|string|max:500',
                'street' => 'nullable|string|max:255',
                'landmark' => 'nullable|string|max:255',
                'city' => 'required|string|max:100',
                'pincode' => 'required|string|max:10',
                'state' => 'required|integer|exists:state,id',
                'updated_by' => 'nullable|integer',

            ]);

            if ($validator->fails()) {

                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $vendor = ApiVendor::where(
                'id',
                $request->id
            )->first();

            if (!$vendor) {

                return response()->json([
                    'status' => 0,
                    'message' => 'Vendor not found'
                ], 404);
            }

            $vendor->company_name = $request->company_name;
            $vendor->contact_name = $request->contact_name;
            $vendor->contact_email = $request->contact_email;
            $vendor->contact_phone = $request->contact_phone;
            $vendor->rate_limit_per_minute = $request->rate_limit_per_minute;
            $vendor->has_gst = $request->has_gst ? 1 : 0;

            if ($request->has('risk_score')) {
                $vendor->risk_score = $request->risk_score;
            }

            $vendor->updated_by = $request->updated_by;
            $vendor->save();

            DB::table('api_vendor_address')
                ->updateOrInsert(

                    [
                        'vendor_id' => $vendor->id
                    ],
                    [
                        'address' => $request->address,
                        'street' => $request->street,
                        'landmark' => $request->landmark,
                        'city' => $request->city,
                        'pincode' => $request->pincode,
                        'state' => $request->state,
                        'updated_at' => Carbon::now(),
                        'updated_by' => $request->updated_by
                    ]
                );

            $address = DB::table('api_vendor_address')
                ->where(
                    'vendor_id',
                    $vendor->id
                )
                ->first();


            return response()->json([

                'status' => 1,
                'message' =>
                'Vendor updated successfully',
                'data' => [

                    'vendor' => $vendor,
                    'address' => $address
                ]

            ], 200);
        } catch (\Exception $e) {

            return response()->json([

                'status' => 0,
                'message' =>
                $e->getMessage()

            ], 500);
        }
    }


    public function changeVendorStatus(Request $request)
    {
        try {

            $vendor = ApiVendor::where('id', $request->id)->first();

            if (!$vendor) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Vendor not found'
                ], 404);
            }

            $newStatus = $vendor->status == 1 ? 0 : 1;

            if ($newStatus == 1) {

                $vendor->status = 1;
                $vendor->activated_at = Carbon::now();
                $vendor->suspended_at = null;
            } else {

                $vendor->status = 0;
                $vendor->suspended_at = Carbon::now();
            }

            $vendor->updated_by = Auth::id();

            $vendor->save();

            return response()->json([
                'status' => 1,
                'message' => $newStatus == 1
                    ? 'Vendor activated successfully'
                    : 'Vendor deactivated successfully',
                'data' => [
                    'id' => $vendor->id,
                    'status' => $vendor->status,
                    'activated_at' => $vendor->activated_at,
                    'suspended_at' => $vendor->suspended_at
                ]
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getStates(Request $request)
    {
        try {

            $states = DB::table('state')
                ->select(
                    'id',
                    'state_name'
                )
                ->where('status', 1)
                ->orderBy('state_name', 'ASC')
                ->get();

            return response()->json([
                'status' => 1,
                'message' => 'States fetched successfully',
                'data' => $states
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function generateVendorCredential(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required|integer|exists:api_vendors,id',
                'environment' => 'required|in:sandbox,production',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $vendor = ApiVendor::where('id', $request->vendor_id)->first();

            if (!$vendor) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Vendor not found'
                ], 404);
            }

            $environment = DB::table('api_vendor_environments')
                ->where('vendor_id', $vendor->id)
                ->where('environment', $request->environment)
                ->first();

            if (!$environment) {
                return response()->json([
                    'status' => 0,
                    'message' => ucfirst($request->environment) .
                        ' environment not found for this vendor'
                ], 404);
            }
            $existingCredential = DB::table('api_vendor_credential')
                ->where('vendor_id', $vendor->id)
                ->where('vendor_environment_id', $environment->id)
                ->first();

            if ($existingCredential) {

                $clientSecret = '';

                try {
                    $clientSecret = Crypt::decryptString(
                        $existingCredential->client_secret_encrypted
                    );
                } catch (DecryptException $e) {
                    $clientSecret = '';
                }

                return response()->json([
                    'status' => 1,
                    'message' => 'Credential already exists',
                    'data' => [
                        'exists' => true,
                        'environment' => $request->environment,
                        'vendor_environment_id' => $environment->id,
                        'client_id' => $existingCredential->client_id,
                        'client_secret' => $clientSecret,
                        'is_active' => $existingCredential->is_active
                    ]
                ], 200);
            }
            $prefix = $request->environment === 'sandbox' ? 'test_' : 'prod_';
            $clientId = $prefix . Str::lower($vendor->vendor_code) .
                '_' . Str::random(32);

            $clientSecret = Str::random(40);
            return response()->json([
                'status' => 1,
                'message' => 'Credential generated successfully',
                'data' => [
                    'exists' => false,
                    'environment' => $request->environment,
                    'vendor_environment_id' => $environment->id,
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'is_active' => 1
                ]
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function saveVendorCredentials(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required|integer|exists:api_vendors,id',
                'credentials' => 'required|array|min:1',
                'credentials.*.environment' =>
                'required|in:sandbox,production',
                'credentials.*.vendor_environment_id' =>
                'required|integer',
                'credentials.*.client_id' =>
                'required|string|max:255',
                'credentials.*.client_secret' =>
                'required|string|min:1',

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $vendor = ApiVendor::where('id', $request->vendor_id)->first();

            if (!$vendor) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Vendor not found'
                ], 404);
            }

            $savedCredentials = [];

            DB::transaction(function () use (
                $request,
                $vendor,
                &$savedCredentials
            ) {

                foreach ($request->credentials as $credential) {

                    $environment = DB::table('api_vendor_environments')
                        ->where('id', $credential['vendor_environment_id'])
                        ->where('vendor_id', $vendor->id)
                        ->where('environment', $credential['environment'])
                        ->first();

                    if (!$environment) {
                        throw new \Exception(
                            'Invalid ' .
                                ucfirst($credential['environment']) .
                                ' environment for this vendor.'
                        );
                    }

                    $existing = DB::table('api_vendor_credential')
                        ->where('vendor_id', $vendor->id)
                        ->where(
                            'vendor_environment_id',
                            $environment->id
                        )
                        ->first();

                    if ($existing) {
                        $savedCredentials[] = [
                            'environment' => $credential['environment'],
                            'vendor_environment_id' => $environment->id,
                            'client_id' => $existing->client_id,
                            'already_exists' => true
                        ];

                        continue;
                    }

                    $encryptedSecret = Crypt::encryptString(
                        $credential['client_secret']
                    );

                    $secretHash = hash(
                        'sha256',
                        $credential['client_secret']
                    );

                    DB::table('api_vendor_credential')->insert([
                        'vendor_id' => $vendor->id,
                        'vendor_environment_id' => $environment->id,
                        'client_id' => $credential['client_id'],
                        'client_secret_encrypted' => $encryptedSecret,
                        'client_secret_hash' => $secretHash,
                        'is_active' => 1,
                        'last_used_at' => null,
                        'expires_at' => null,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    $savedCredentials[] = [
                        'environment' => $credential['environment'],
                        'vendor_environment_id' => $environment->id,
                        'client_id' => $credential['client_id'],
                        'already_exists' => false
                    ];
                }
            });

            return response()->json([
                'status' => 1,
                'message' => 'Vendor credentials saved successfully',
                'data' => $savedCredentials
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getVendorCredentials(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'vendor_id' =>
                'required|integer|exists:api_vendors,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $credentials = DB::table('api_vendor_credential as vc')
                ->join(
                    'api_vendor_environments as ve',
                    've.id',
                    '=',
                    'vc.vendor_environment_id'
                )
                ->where('vc.vendor_id', $request->vendor_id)
                ->select(
                    'vc.id',
                    'vc.vendor_id',
                    'vc.vendor_environment_id',
                    'vc.client_id',
                    'vc.client_secret_encrypted',
                    'vc.is_active',
                    'vc.last_used_at',
                    'vc.expires_at',
                    've.environment',
                    've.status as environment_status'
                )
                ->orderBy('ve.environment', 'ASC')
                ->get();

            $credentials = $credentials->map(function ($credential) {

                try {

                    $credential->client_secret = Crypt::decryptString($credential->client_secret_encrypted);
                } catch (DecryptException $e) {

                    $credential->client_secret = '';
                }

                unset($credential->client_secret_encrypted);
                return $credential;
            });

            return response()->json([
                'status' => 1,
                'message' => 'Vendor credentials fetched successfully',
                'data' => $credentials
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function changeVendorEnvironmentStatus(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required|integer|exists:api_vendors,id',
                'environment' => 'required|in:sandbox,production',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $environment = DB::table('api_vendor_environments')
                ->where('vendor_id', $request->vendor_id)
                ->where('environment', $request->environment)
                ->first();

            if (!$environment) {
                return response()->json([
                    'status' => 0,
                    'message' =>
                    ucfirst($request->environment) .
                        ' environment not found'
                ], 404);
            }

            DB::table('api_vendor_environments')
                ->where('id', $environment->id)
                ->update([
                    'status' => $request->status,
                    'updated_at' => Carbon::now()
                ]);

            return response()->json([
                'status' => 1,
                'message' =>
                ucfirst($request->environment) .
                    ' environment ' .
                    ($request->status ? 'activated' : 'deactivated') .
                    ' successfully'
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getVendorIps(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required|integer|exists:api_vendors,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $ips = DB::table('api_vendor_ips as vi')
                ->join(
                    'api_vendor_environments as ve',
                    've.id',
                    '=',
                    'vi.vendor_environment_id'
                )
                ->where('vi.vendor_id', $request->vendor_id)
                ->select(
                    'vi.id',
                    'vi.vendor_id',
                    'vi.vendor_environment_id',
                    'vi.ip_address',
                    'vi.is_active',
                    've.environment'
                )
                ->orderBy('vi.id', 'ASC')
                ->get();

            $productionIps = $ips
                ->where('environment', 'production')
                ->values();

            $sandboxIps = $ips
                ->where('environment', 'sandbox')
                ->values();

            return response()->json([
                'status' => 1,
                'message' => 'Vendor IPs fetched successfully',
                'data' => [
                    'production' => $productionIps,
                    'sandbox' => $sandboxIps
                ]
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function saveVendorIps(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required|integer|exists:api_vendors,id',
                'production_ips' => 'nullable|array',
                'production_ips.*.id' => 'nullable|integer',
                'production_ips.*.ip_address' => 'required|ip',
                'production_ips.*.is_active' => 'required|boolean',
                'sandbox_ips' => 'nullable|array',
                'sandbox_ips.*.id' => 'nullable|integer',
                'sandbox_ips.*.ip_address' => 'required|ip',
                'sandbox_ips.*.is_active' => 'required|boolean',
                'created_by' => 'nullable|integer',
                'updated_by' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $vendor = ApiVendor::find($request->vendor_id);

            if (!$vendor) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Vendor not found'
                ], 404);
            }

            $productionEnvironment = DB::table('api_vendor_environments')
                ->where('vendor_id', $vendor->id)
                ->where('environment', 'production')
                ->first();

            $sandboxEnvironment = DB::table('api_vendor_environments')
                ->where('vendor_id', $vendor->id)
                ->where('environment', 'sandbox')
                ->first();

            if (!$productionEnvironment || !$sandboxEnvironment) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Vendor environments are not configured properly'
                ], 404);
            }

            $createdBy = $request->created_by;
            $updatedBy = $request->updated_by;

            DB::transaction(function () use (
                $request,
                $vendor,
                $productionEnvironment,
                $sandboxEnvironment,
                $createdBy,
                $updatedBy
            ) {

                foreach ($request->production_ips ?? [] as $ip) {

                    if (!empty($ip['id'])) {


                        DB::table('api_vendor_ips')
                            ->where('id', $ip['id'])
                            ->where('vendor_id', $vendor->id)
                            ->where(
                                'vendor_environment_id',
                                $productionEnvironment->id
                            )
                            ->update([
                                'ip_address' => $ip['ip_address'],
                                'is_active' => $ip['is_active'],
                                'updated_at' => Carbon::now(),
                                'updated_by' => $updatedBy
                            ]);
                    } else {
                        DB::table('api_vendor_ips')->insert([
                            'vendor_id' => $vendor->id,
                            'vendor_environment_id' =>
                            $productionEnvironment->id,
                            'ip_address' => $ip['ip_address'],
                            'is_active' => $ip['is_active'],
                            'created_at' => Carbon::now(),
                            'created_by' => $createdBy,
                            'updated_at' => Carbon::now(),
                            'updated_by' => $updatedBy
                        ]);
                    }
                }


                foreach ($request->sandbox_ips ?? [] as $ip) {

                    if (!empty($ip['id'])) {

                        DB::table('api_vendor_ips')
                            ->where('id', $ip['id'])
                            ->where('vendor_id', $vendor->id)
                            ->where(
                                'vendor_environment_id',
                                $sandboxEnvironment->id
                            )
                            ->update([
                                'ip_address' => $ip['ip_address'],
                                'is_active' => $ip['is_active'],
                                'updated_at' => Carbon::now(),
                                'updated_by' => $updatedBy
                            ]);
                    } else {

                        DB::table('api_vendor_ips')->insert([
                            'vendor_id' => $vendor->id,
                            'vendor_environment_id' =>
                            $sandboxEnvironment->id,
                            'ip_address' => $ip['ip_address'],
                            'is_active' => $ip['is_active'],
                            'created_at' => Carbon::now(),
                            'created_by' => $createdBy,
                            'updated_at' => Carbon::now(),
                            'updated_by' => $updatedBy
                        ]);
                    }
                }
            });

            return response()->json([
                'status' => 1,
                'message' => 'Vendor IPs saved successfully'
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function changeVendorIpStatus(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:api_vendor_ips,id',
                'is_active' => 'required|boolean',
                'updated_by' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $ip = DB::table('api_vendor_ips')
                ->where('id', $request->id)
                ->first();

            if (!$ip) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Vendor IP not found'
                ], 404);
            }

            DB::table('api_vendor_ips')
                ->where('id', $request->id)
                ->update([
                    'is_active' => $request->is_active,
                    'updated_at' => Carbon::now(),
                    'updated_by' => $request->updated_by
                ]);

            return response()->json([
                'status' => 1,
                'message' => $request->is_active
                    ? 'IP activated successfully'
                    : 'IP deactivated successfully'
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getVendorScopes(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required|integer|exists:api_vendors,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $vendorId = $request->vendor_id;
            $environments = DB::table('api_vendor_environments')
                ->where('vendor_id', $vendorId)
                ->whereIn('environment', [
                    'sandbox',
                    'production'
                ])
                ->get()
                ->keyBy('environment');

            if (
                !isset($environments['sandbox']) ||
                !isset($environments['production'])
            ) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Vendor environments are not configured properly'
                ], 404);
            }

            $sandboxEnvironmentId = $environments['sandbox']->id;
            $productionEnvironmentId = $environments['production']->id;
            $scopes = DB::table('api_scope')
                ->select(
                    'id',
                    'name',
                    'description'
                )
                ->orderBy('id', 'ASC')
                ->get();


            $vendorScopes = DB::table('api_vendor_scope')
                ->where('vednor_id', $vendorId)
                ->whereIn(
                    'vendor_environment_id',
                    [
                        $sandboxEnvironmentId,
                        $productionEnvironmentId
                    ]
                )
                ->get();



            $sandboxScopes = $scopes->map(function ($scope) use (
                $vendorScopes,
                $sandboxEnvironmentId
            ) {

                $assigned = $vendorScopes
                    ->where(
                        'vendor_environment_id',
                        $sandboxEnvironmentId
                    )
                    ->where(
                        'scope_id',
                        $scope->id
                    )
                    ->first();

                return [
                    'id' => $scope->id,
                    'name' => $scope->name,
                    'description' => $scope->description,
                    'vendor_scope_id' => $assigned ? $assigned->id : null,
                    'checked' => $assigned ? true : false,
                    'status' => $assigned ? (int) $assigned->status : 0
                ];
            });

            $productionScopes = $scopes->map(function ($scope) use (
                $vendorScopes,
                $productionEnvironmentId
            ) {

                $assigned = $vendorScopes
                    ->where(
                        'vendor_environment_id',
                        $productionEnvironmentId
                    )
                    ->where(
                        'scope_id',
                        $scope->id
                    )
                    ->first();

                return [
                    'id' => $scope->id,
                    'name' => $scope->name,
                    'description' => $scope->description,
                    'vendor_scope_id' => $assigned ? $assigned->id : null,
                    'checked' => $assigned ? true : false,
                    'status' => $assigned ? (int) $assigned->status : 0
                ];
            });

            return response()->json([
                'status' => 1,
                'message' => 'Vendor scopes fetched successfully',
                'data' => [
                    'sandbox' => $sandboxScopes->values(),
                    'production' => $productionScopes->values()
                ]
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function saveVendorScopes(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [

                'vendor_id' =>
                'required|integer|exists:api_vendors,id',
                'sandbox_scopes' =>
                'nullable|array',
                'sandbox_scopes.*.id' =>
                'required|integer|exists:api_scope,id',
                'sandbox_scopes.*.checked' =>
                'required|boolean',
                'sandbox_scopes.*.status' =>
                'required|boolean',
                'production_scopes' =>
                'nullable|array',
                'production_scopes.*.id' =>
                'required|integer|exists:api_scope,id',
                'production_scopes.*.checked' =>
                'required|boolean',
                'production_scopes.*.status' =>
                'required|boolean',
                'created_by' =>
                'nullable|integer',
                'updated_by' =>
                'nullable|integer',
            ]);


            if ($validator->fails()) {

                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }


            $vendor = ApiVendor::find(
                $request->vendor_id
            );


            if (!$vendor) {

                return response()->json([
                    'status' => 0,
                    'message' => 'Vendor not found'
                ], 404);
            }

            $sandboxEnvironment =
                DB::table('api_vendor_environments')
                ->where('vendor_id', $vendor->id)
                ->where('environment', 'sandbox')
                ->first();


            $productionEnvironment =
                DB::table('api_vendor_environments')
                ->where('vendor_id', $vendor->id)
                ->where('environment', 'production')
                ->first();


            if (
                !$sandboxEnvironment || !$productionEnvironment
            ) {

                return response()->json([
                    'status' => 0,
                    'message' =>
                    'Vendor environments are not configured properly'
                ], 404);
            }


            $createdBy = $request->created_by;
            $updatedBy = $request->updated_by;


            DB::transaction(function () use (
                $request,
                $vendor,
                $sandboxEnvironment,
                $productionEnvironment,
                $createdBy,
                $updatedBy
            ) {


                DB::table('api_vendor_scope')
                    ->where(
                        'vednor_id',
                        $vendor->id
                    )
                    ->where(
                        'vendor_environment_id',
                        $sandboxEnvironment->id
                    )
                    ->delete();

                foreach (
                    $request->sandbox_scopes ?? []
                    as $scope
                ) {

                    if (
                        !empty($scope['checked'])
                    ) {

                        DB::table('api_vendor_scope')
                            ->insert([
                                'vednor_id' => $vendor->id,
                                'vendor_environment_id' => $sandboxEnvironment->id,
                                'scope_id' => $scope['id'],
                                'status' => 1,
                                'created_at' => Carbon::now(),
                                'created_by' => $createdBy,
                                'updated_at' => Carbon::now(),
                                'updated_by' => $updatedBy,
                            ]);
                    }
                }

                DB::table('api_vendor_scope')
                    ->where(
                        'vednor_id',
                        $vendor->id
                    )
                    ->where(
                        'vendor_environment_id',
                        $productionEnvironment->id
                    )
                    ->delete();

                foreach (
                    $request->production_scopes ?? []
                    as $scope
                ) {

                    if (
                        !empty($scope['checked'])
                    ) {

                        DB::table('api_vendor_scope')
                            ->insert([
                                'vednor_id' => $vendor->id,
                                'vendor_environment_id' => $productionEnvironment->id,
                                'scope_id' => $scope['id'],
                                'status' => 1,
                                'created_at' => Carbon::now(),
                                'created_by' => $createdBy,
                                'updated_at' => Carbon::now(),
                                'updated_by' => $updatedBy,
                            ]);
                    }
                }
            });


            return response()->json([
                'status' => 1,
                'message' =>
                'Vendor scopes saved successfully'
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' =>
                $e->getMessage()
            ], 500);
        }
    }

    public function changeVendorScopeStatus(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'vendor_id' =>
                'required|integer|exists:api_vendors,id',
                'environment' =>
                'required|in:sandbox,production',
                'scope_id' =>
                'required|integer|exists:api_scope,id',
                'status' =>
                'required|boolean',
                'updated_by' =>
                'nullable|integer',
            ]);

            if ($validator->fails()) {

                return response()->json([
                    'status' => 0,
                    'message' =>
                    $validator->errors()->first(),
                    'errors' =>
                    $validator->errors()
                ], 422);
            }

            $environment = DB::table('api_vendor_environments')
                ->where(
                    'vendor_id',
                    $request->vendor_id
                )
                ->where(
                    'environment',
                    $request->environment
                )
                ->first();


            if (!$environment) {

                return response()->json([
                    'status' => 0,
                    'message' =>
                    ucfirst($request->environment) .
                        ' environment not found'
                ], 404);
            }


            $vendorScope = DB::table('api_vendor_scope')
                ->where(
                    'vednor_id',
                    $request->vendor_id
                )
                ->where(
                    'vendor_environment_id',
                    $environment->id
                )
                ->where(
                    'scope_id',
                    $request->scope_id
                )
                ->first();

            if ($vendorScope) {

                DB::table('api_vendor_scope')
                    ->where(
                        'id',
                        $vendorScope->id
                    )
                    ->update([
                        'status' => $request->status,
                        'updated_at' => Carbon::now(),
                        'updated_by' => $request->updated_by
                    ]);

                return response()->json([
                    'status' => 1,
                    'message' => $request->status ? 'Scope activated successfully' : 'Scope deactivated successfully',
                    'data' => [
                        'vendor_scope_id' => $vendorScope->id,
                        'status' => (int) $request->status
                    ]
                ], 200);
            }


            if ((int) $request->status === 1) {

                $vendorScopeId =
                    DB::table('api_vendor_scope')
                    ->insertGetId([
                        'vednor_id' => $request->vendor_id,
                        'vendor_environment_id' => $environment->id,
                        'scope_id' => $request->scope_id,
                        'status' => 1,
                        'created_at' => Carbon::now(),
                        'created_by' => $request->updated_by,
                        'updated_at' => Carbon::now(),
                        'updated_by' => $request->updated_by
                    ]);

                return response()->json([
                    'status' => 1,
                    'message' =>
                    'Scope activated successfully',
                    'data' => [
                        'vendor_scope_id' => $vendorScopeId,
                        'status' => 1
                    ]
                ], 200);
            }


            return response()->json([
                'status' => 1,
                'message' =>
                'Scope is already inactive'
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' =>
                $e->getMessage()
            ], 500);
        }
    }

    public function getVendorRateLimits(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required|integer|exists:api_vendors,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $vendorId = $request->vendor_id;
            $environments = DB::table('api_vendor_environments')
                ->where('vendor_id', $vendorId)
                ->whereIn('environment', [
                    'sandbox',
                    'production'
                ])
                ->get()
                ->keyBy('environment');

            if (
                !isset($environments['sandbox']) ||
                !isset($environments['production'])
            ) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Vendor environments are not configured properly'
                ], 404);
            }

            $sandboxEnvironmentId = $environments['sandbox']->id;
            $productionEnvironmentId = $environments['production']->id;
            $vendorScopes = DB::table('api_vendor_scope as vs')
                ->join(
                    'api_scope as s',
                    's.id',
                    '=',
                    'vs.scope_id'
                )
                ->join(
                    'api_vendor_environments as ve',
                    've.id',
                    '=',
                    'vs.vendor_environment_id'
                )
                ->where(
                    'vs.vednor_id',
                    $vendorId
                )
                ->where('vs.status', 1)
                ->whereIn(
                    'vs.vendor_environment_id',
                    [
                        $sandboxEnvironmentId,
                        $productionEnvironmentId
                    ]
                )
                ->select(
                    'vs.id as vendor_scope_id',
                    'vs.vednor_id as vendor_id',
                    'vs.vendor_environment_id',
                    'vs.scope_id',
                    'vs.status',
                    's.name as scope_name',
                    's.description as scope_description',
                    've.environment'
                )
                ->orderBy('vs.scope_id', 'ASC')
                ->get();

            $rateLimits = DB::table('api_vendor_rate_limits')
                ->where('vendor_id', $vendorId)
                ->whereIn(
                    'vendor_environment_id',
                    [
                        $sandboxEnvironmentId,
                        $productionEnvironmentId
                    ]
                )
                ->get();

            $sandbox = $vendorScopes
                ->where(
                    'vendor_environment_id',
                    $sandboxEnvironmentId
                )
                ->map(function ($scope) use ($rateLimits) {

                    $rateLimit = $rateLimits
                        ->where(
                            'vendor_environment_id',
                            $scope->vendor_environment_id
                        )
                        ->where(
                            'scope_id',
                            $scope->scope_id
                        )
                        ->first();

                    return [
                        'vendor_scope_id' => $scope->vendor_scope_id,
                        'vendor_id' => $scope->vendor_id,
                        'vendor_environment_id' => $scope->vendor_environment_id,
                        'scope_id' => $scope->scope_id,
                        'scope_name' => $scope->scope_name,
                        'scope_description' => $scope->scope_description,
                        'endpoint' => $rateLimit ? $rateLimit->endpoint : $scope->scope_name,
                        'requests_per_minute' => $rateLimit ? $rateLimit->requests_per_minute : null,
                        'requests_per_hour' => $rateLimit ? $rateLimit->requests_per_hour : null,
                        'burst_limit' => $rateLimit ? $rateLimit->burst_limit : null,
                        'rate_limit_id' => $rateLimit ? $rateLimit->id : null,
                        'is_active' => $rateLimit ? (int) $rateLimit->is_active : 1
                    ];
                })
                ->values();

            $production = $vendorScopes
                ->where(
                    'vendor_environment_id',
                    $productionEnvironmentId
                )
                ->map(function ($scope) use ($rateLimits) {

                    $rateLimit = $rateLimits
                        ->where(
                            'vendor_environment_id',
                            $scope->vendor_environment_id
                        )
                        ->where(
                            'scope_id',
                            $scope->scope_id
                        )
                        ->first();

                    return [
                        'vendor_scope_id' => $scope->vendor_scope_id,
                        'vendor_id' => $scope->vendor_id,
                        'vendor_environment_id' => $scope->vendor_environment_id,
                        'scope_id' => $scope->scope_id,
                        'scope_name' => $scope->scope_name,
                        'scope_description' => $scope->scope_description,
                        'endpoint' => $rateLimit ? $rateLimit->endpoint : $scope->scope_name,
                        'requests_per_minute' => $rateLimit ? $rateLimit->requests_per_minute : null,
                        'requests_per_hour' => $rateLimit ? $rateLimit->requests_per_hour : null,
                        'burst_limit' => $rateLimit ? $rateLimit->burst_limit : null,
                        'rate_limit_id' => $rateLimit ? $rateLimit->id : null,
                        'is_active' => $rateLimit ? (int) $rateLimit->is_active : 1
                    ];
                })
                ->values();


            return response()->json([
                'status' => 1,
                'message' => 'Vendor rate limits fetched successfully',
                'data' => [
                    'sandbox' => $sandbox,
                    'production' => $production
                ]
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function saveVendorRateLimits(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [

                'vendor_id' =>
                'required|integer|exists:api_vendors,id',
                'sandbox' =>
                'nullable|array',
                'sandbox.*.scope_id' =>
                'required|integer|exists:api_scope,id',
                'sandbox.*.requests_per_minute' =>
                'required|integer|min:1',
                'sandbox.*.requests_per_hour' =>
                'required|integer|min:1',
                'sandbox.*.burst_limit' =>
                'required|integer|min:1',
                'production' =>
                'nullable|array',
                'production.*.scope_id' =>
                'required|integer|exists:api_scope,id',
                'production.*.requests_per_minute' =>
                'required|integer|min:1',
                'production.*.requests_per_hour' =>
                'required|integer|min:1',
                'production.*.burst_limit' =>
                'required|integer|min:1',
                'created_by' =>
                'nullable|integer',
                'updated_by' =>
                'nullable|integer',
            ]);


            if ($validator->fails()) {

                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }


            $vendor = ApiVendor::find(
                $request->vendor_id
            );


            if (!$vendor) {

                return response()->json([
                    'status' => 0,
                    'message' => 'Vendor not found'
                ], 404);
            }

            $sandboxEnvironment = DB::table(
                'api_vendor_environments'
            )
                ->where(
                    'vendor_id',
                    $vendor->id
                )
                ->where(
                    'environment',
                    'sandbox'
                )
                ->first();


            $productionEnvironment = DB::table(
                'api_vendor_environments'
            )
                ->where(
                    'vendor_id',
                    $vendor->id
                )
                ->where(
                    'environment',
                    'production'
                )
                ->first();


            if (
                !$sandboxEnvironment ||
                !$productionEnvironment
            ) {

                return response()->json([
                    'status' => 0,
                    'message' =>
                    'Vendor environments are not configured properly'
                ], 404);
            }


            $createdBy = $request->created_by;
            $updatedBy = $request->updated_by;

            DB::transaction(function () use (
                $request,
                $vendor,
                $sandboxEnvironment,
                $productionEnvironment,
                $createdBy,
                $updatedBy
            ) {


                foreach (
                    $request->sandbox ?? []
                    as $rate
                ) {

                    $vendorScope =
                        DB::table('api_vendor_scope')
                        ->where(
                            'vednor_id',
                            $vendor->id
                        )
                        ->where(
                            'vendor_environment_id',
                            $sandboxEnvironment->id
                        )
                        ->where(
                            'scope_id',
                            $rate['scope_id']
                        )
                        ->where(
                            'status',
                            1
                        )
                        ->first();


                    if (!$vendorScope) {
                        continue;
                    }

                    $scope =
                        DB::table('api_scope')
                        ->where(
                            'id',
                            $rate['scope_id']
                        )
                        ->first();


                    if (!$scope) {
                        continue;
                    }


                    $existing =
                        DB::table(
                            'api_vendor_rate_limits'
                        )
                        ->where(
                            'vendor_id',
                            $vendor->id
                        )
                        ->where(
                            'vendor_environment_id',
                            $sandboxEnvironment->id
                        )
                        ->where(
                            'scope_id',
                            $scope->id
                        )
                        ->first();


                    $data = [

                        'vendor_id' => $vendor->id,
                        'vendor_environment_id' => $sandboxEnvironment->id,
                        'scope_id' => $scope->id,
                        'endpoint' => $scope->name,
                        'requests_per_minute' => $rate['requests_per_minute'],
                        'requests_per_hour' => $rate['requests_per_hour'],
                        'burst_limit' => $rate['burst_limit'],
                        'is_active' => 1,
                        'updated_at' => Carbon::now(),
                        'updated_by' => $updatedBy
                    ];


                    if ($existing) {

                        DB::table(
                            'api_vendor_rate_limits'
                        )
                            ->where(
                                'id',
                                $existing->id
                            )
                            ->update($data);
                    } else {

                        $data['created_at'] =
                            Carbon::now();

                        $data['created_by'] =
                            $createdBy;

                        DB::table(
                            'api_vendor_rate_limits'
                        )
                            ->insert($data);
                    }
                }

                foreach (
                    $request->production ?? []
                    as $rate
                ) {


                    $vendorScope =
                        DB::table('api_vendor_scope')
                        ->where(
                            'vednor_id',
                            $vendor->id
                        )
                        ->where(
                            'vendor_environment_id',
                            $productionEnvironment->id
                        )
                        ->where(
                            'scope_id',
                            $rate['scope_id']
                        )
                        ->where(
                            'status',
                            1
                        )
                        ->first();
                    if (!$vendorScope) {
                        continue;
                    }

                    $scope =
                        DB::table('api_scope')
                        ->where(
                            'id',
                            $rate['scope_id']
                        )
                        ->first();
                    if (!$scope) {
                        continue;
                    }

                    $existing =
                        DB::table(
                            'api_vendor_rate_limits'
                        )
                        ->where(
                            'vendor_id',
                            $vendor->id
                        )
                        ->where(
                            'vendor_environment_id',
                            $productionEnvironment->id
                        )
                        ->where(
                            'scope_id',
                            $scope->id
                        )
                        ->first();


                    $data = [

                        'vendor_id' => $vendor->id,
                        'vendor_environment_id' => $productionEnvironment->id,
                        'scope_id' => $scope->id,
                        'endpoint' => $scope->name,
                        'requests_per_minute' => $rate['requests_per_minute'],
                        'requests_per_hour' => $rate['requests_per_hour'],
                        'burst_limit' => $rate['burst_limit'],
                        'is_active' => 1,
                        'updated_at' => Carbon::now(),
                        'updated_by' => $updatedBy
                    ];


                    if ($existing) {

                        DB::table(
                            'api_vendor_rate_limits'
                        )
                            ->where(
                                'id',
                                $existing->id
                            )
                            ->update($data);
                    } else {

                        $data['created_at'] = Carbon::now();
                        $data['created_by'] = $createdBy;
                        DB::table(
                            'api_vendor_rate_limits'
                        )
                            ->insert($data);
                    }
                }
            });


            return response()->json([
                'status' => 1,
                'message' =>
                'Vendor rate limits saved successfully'
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' =>
                $e->getMessage()
            ], 500);
        }
    }

    public function getVendorViewDetails(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required|integer|exists:api_vendors,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' =>
                    $validator->errors()->first(),
                    'errors' =>
                    $validator->errors()
                ], 422);
            }

            $vendorId = $request->vendor_id;
            $vendor = DB::table('api_vendors')
                ->where('id', $vendorId)
                ->first();

            if (!$vendor) {

                return response()->json([
                    'status' => 0,
                    'message' => 'Vendor not found'
                ], 404);
            }

            $address = DB::table(
                'api_vendor_address as va'
            )
                ->leftJoin(
                    'state as s',
                    's.id',
                    '=',
                    'va.state'
                )
                ->where(
                    'va.vendor_id',
                    $vendorId
                )
                ->select(
                    'va.id',
                    'va.vendor_id',
                    'va.address',
                    'va.street',
                    'va.landmark',
                    'va.city',
                    'va.pincode',
                    'va.state',
                    's.state_name'
                )
                ->first();


            $environments = DB::table(
                'api_vendor_environments'
            )
                ->where(
                    'vendor_id',
                    $vendorId
                )
                ->whereIn(
                    'environment',
                    [
                        'sandbox',
                        'production'
                    ]
                )
                ->get()
                ->keyBy('environment');


            $sandboxEnvironmentId = isset($environments['sandbox']) ? $environments['sandbox']->id : null;
            $productionEnvironmentId = isset($environments['production']) ? $environments['production']->id : null;
            $sandbox = [
                'credentials' => [],
                'ips' => [],
                'scopes' => [],
                'rate_limits' => [],
                'access_rules' => []
            ];

            $production = [
                'credentials' => [],
                'ips' => [],
                'scopes' => [],
                'rate_limits' => [],
                'access_rules' => []
            ];

            $credentials = DB::table(
                'api_vendor_credential'
            )
                ->where(
                    'vendor_id',
                    $vendorId
                )
                ->whereIn(
                    'vendor_environment_id',
                    array_filter([
                        $sandboxEnvironmentId,
                        $productionEnvironmentId
                    ])
                )
                ->get();


            foreach ($credentials as $credential) {
                $clientSecret = '';

                try {
                    if (
                        !empty($credential->client_secret_encrypted)
                    ) {
                        $clientSecret =
                            Crypt::decryptString(
                                $credential
                                    ->client_secret_encrypted
                            );
                    }
                } catch (\Exception $e) {
                    $clientSecret = '';
                }

                $credentialData = [
                    'id' => $credential->id,
                    'client_id' => $credential->client_id,
                    'client_secret' => $clientSecret,
                    'is_active' => (int) $credential->is_active,
                    'last_used_at' => $credential->last_used_at,
                    'expires_at' => $credential->expires_at,
                    'created_at' => $credential->created_at
                ];

                if (
                    $sandboxEnvironmentId &&
                    $credential->vendor_environment_id ==
                    $sandboxEnvironmentId
                ) {

                    $sandbox['credentials'][] =
                        $credentialData;
                } elseif (
                    $productionEnvironmentId &&
                    $credential->vendor_environment_id ==
                    $productionEnvironmentId
                ) {

                    $production['credentials'][] =
                        $credentialData;
                }
            }

            $ips = DB::table(
                'api_vendor_ips'
            )
                ->where(
                    'vendor_id',
                    $vendorId
                )
                ->whereIn(
                    'vendor_environment_id',
                    array_filter([
                        $sandboxEnvironmentId,
                        $productionEnvironmentId
                    ])
                )
                ->orderBy('id', 'ASC')
                ->get();


            foreach ($ips as $ip) {

                $ipData = [
                    'id' => $ip->id,
                    'ip_address' => $ip->ip_address,
                    'is_active' => (int) $ip->is_active,
                    'created_at' => $ip->created_at
                ];

                if (
                    $sandboxEnvironmentId &&
                    $ip->vendor_environment_id ==
                    $sandboxEnvironmentId
                ) {

                    $sandbox['ips'][] =
                        $ipData;
                } elseif (
                    $productionEnvironmentId &&
                    $ip->vendor_environment_id ==
                    $productionEnvironmentId
                ) {

                    $production['ips'][] =
                        $ipData;
                }
            }

            $vendorScopes = DB::table(
                'api_vendor_scope as vs'
            )
                ->join(
                    'api_scope as s',
                    's.id',
                    '=',
                    'vs.scope_id'
                )
                ->where(
                    'vs.vednor_id',
                    $vendorId
                )
                ->whereIn(
                    'vs.vendor_environment_id',
                    array_filter([
                        $sandboxEnvironmentId,
                        $productionEnvironmentId
                    ])
                )
                ->select(
                    'vs.id',
                    'vs.vednor_id',
                    'vs.vendor_environment_id',
                    'vs.scope_id',
                    'vs.status',
                    's.name',
                    's.description'
                )
                ->orderBy('vs.id', 'ASC')
                ->get();


            foreach ($vendorScopes as $scope) {

                if ((int) $scope->status !== 1) {
                    continue;
                }

                $scopeData = [
                    'id' => $scope->id,
                    'scope_id' => $scope->scope_id,
                    'name' => $scope->name,
                    'description' => $scope->description,
                    'status' => (int) $scope->status
                ];


                if (
                    $sandboxEnvironmentId &&
                    $scope->vendor_environment_id ==
                    $sandboxEnvironmentId
                ) {

                    $sandbox['scopes'][] =
                        $scopeData;
                } elseif (
                    $productionEnvironmentId &&
                    $scope->vendor_environment_id ==
                    $productionEnvironmentId
                ) {

                    $production['scopes'][] =
                        $scopeData;
                }
            }

            $rateLimits = DB::table(
                'api_vendor_rate_limits as rl'
            )
                ->leftJoin(
                    'api_scope as s',
                    's.id',
                    '=',
                    'rl.scope_id'
                )
                ->where(
                    'rl.vendor_id',
                    $vendorId
                )
                ->whereIn(
                    'rl.vendor_environment_id',
                    array_filter([
                        $sandboxEnvironmentId,
                        $productionEnvironmentId
                    ])
                )
                ->select(
                    'rl.id',
                    'rl.vendor_id',
                    'rl.vendor_environment_id',
                    'rl.scope_id',
                    'rl.endpoint',
                    'rl.requests_per_minute',
                    'rl.requests_per_hour',
                    'rl.burst_limit',
                    'rl.is_active',
                    's.name as scope_name'
                )
                ->orderBy('rl.id', 'ASC')
                ->get();


            foreach ($rateLimits as $rateLimit) {

                $rateLimitData = [
                    'id' => $rateLimit->id,
                    'scope_id' => $rateLimit->scope_id,
                    'scope_name' => $rateLimit->scope_name,
                    'endpoint' => $rateLimit->endpoint,
                    'requests_per_minute' => $rateLimit->requests_per_minute,
                    'requests_per_hour' => $rateLimit->requests_per_hour,
                    'burst_limit' => $rateLimit->burst_limit,
                    'is_active' => (int) $rateLimit->is_active
                ];


                if (
                    $sandboxEnvironmentId &&
                    $rateLimit->vendor_environment_id ==
                    $sandboxEnvironmentId
                ) {

                    $sandbox['rate_limits'][] =
                        $rateLimitData;
                } elseif (
                    $productionEnvironmentId &&
                    $rateLimit->vendor_environment_id ==
                    $productionEnvironmentId
                ) {

                    $production['rate_limits'][] =
                        $rateLimitData;
                }
            }

            $accessRules = DB::table(
                'api_vendor_access_rules as ar'
            )
                ->join(
                    'api_scope as s',
                    's.id',
                    '=',
                    'ar.scope_id'
                )
                ->where(
                    'ar.vendor_id',
                    $vendorId
                )
                ->whereIn(
                    'ar.vendor_environment_id',
                    array_filter([
                        $sandboxEnvironmentId,
                        $productionEnvironmentId
                    ])
                )
                ->where(
                    's.category',
                    2
                )
                ->select(
                    'ar.id',
                    'ar.vendor_id',
                    'ar.vendor_environment_id',
                    'ar.scope_id',
                    's.name as scope_name',
                    's.description',
                    'ar.allowed_start_time',
                    'ar.allowed_end_time',
                    'ar.status'
                )
                ->orderBy(
                    'ar.id',
                    'ASC'
                )
                ->get();


            foreach ($accessRules as $accessRule) {

                $accessRuleData = [
                    'id' => $accessRule->id,
                    'scope_id' => $accessRule->scope_id,
                    'scope_name' => $accessRule->scope_name,
                    'description' => $accessRule->description,
                    'allowed_start_time' => $accessRule->allowed_start_time ? substr($accessRule->allowed_start_time, 0, 5) : '',
                    'allowed_end_time' => $accessRule->allowed_end_time ? substr($accessRule->allowed_end_time, 0, 5) : '',
                    'status' => (int) $accessRule->status
                ];


                if (
                    $sandboxEnvironmentId &&
                    $accessRule->vendor_environment_id ==
                    $sandboxEnvironmentId
                ) {

                    $sandbox['access_rules'][] =
                        $accessRuleData;
                } elseif (
                    $productionEnvironmentId &&
                    $accessRule->vendor_environment_id ==
                    $productionEnvironmentId
                ) {

                    $production['access_rules'][] =
                        $accessRuleData;
                }
            }


            return response()->json([
                'status' => 1,
                'message' =>
                'Vendor details fetched successfully',
                'data' => [

                    'vendor' => $vendor,
                    'address' => $address,
                    'sandbox' => $sandbox,
                    'production' => $production
                ]

            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' =>
                $e->getMessage()
            ], 500);
        }
    }

    public function getVendorAppAccessRules(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required|integer|exists:api_vendors,id',
            ]);

            if ($validator->fails()) {

                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $vendorId = $request->vendor_id;
            $vendor = DB::table('api_vendors')
                ->where('id', $vendorId)
                ->first();
            $environments = DB::table('api_vendor_environments')
                ->where('vendor_id', $vendorId)
                ->whereIn('environment', [
                    'sandbox',
                    'production'
                ])
                ->get()
                ->keyBy('environment');
            $accessRules = DB::table('api_vendor_access_rules')
                ->where('vendor_id', $vendorId)
                ->get();
            $getEnvironmentScopes = function (
                $environmentId
            ) use (
                $vendorId,
                $accessRules
            ) {

                $scopes = DB::table('api_vendor_scope as vs')
                    ->join(
                        'api_scope as s',
                        's.id',
                        '=',
                        'vs.scope_id'
                    )
                    ->where(
                        'vs.vednor_id',
                        $vendorId
                    )
                    ->where(
                        'vs.vendor_environment_id',
                        $environmentId
                    )
                    ->where(
                        'vs.status',
                        1
                    )
                    ->where(
                        's.category',
                        2
                    )
                    ->select(
                        's.id as scope_id',
                        's.name as scope_name',
                        's.description'
                    )
                    ->orderBy(
                        's.id',
                        'ASC'
                    )
                    ->get();
                return $scopes->map(
                    function ($scope) use (
                        $accessRules,
                        $environmentId
                    ) {

                        $rule = $accessRules
                            ->where(
                                'vendor_environment_id',
                                $environmentId
                            )
                            ->where(
                                'scope_id',
                                $scope->scope_id
                            )
                            ->first();


                        return [

                            'scope_id' => $scope->scope_id,
                            'scope_name' => $scope->scope_name,
                            'description' => $scope->description,
                            'vendor_environment_id' => $environmentId,
                            'rule_id' => $rule ? $rule->id : null,
                            'start_time' => $rule && $rule->allowed_start_time ? substr($rule->allowed_start_time, 0, 5) : '',
                            'end_time' => $rule && $rule->allowed_end_time ? substr($rule->allowed_end_time, 0, 5) : '',
                            'status' => $rule ? (int) $rule->status : null
                        ];
                    }
                )->values();
            };

            $sandbox = [];

            if (isset($environments['sandbox'])) {

                $sandbox =
                    $getEnvironmentScopes(
                        $environments['sandbox']->id
                    );
            }
            $production = [];

            if (isset($environments['production'])) {

                $production =
                    $getEnvironmentScopes(
                        $environments['production']->id
                    );
            }


            return response()->json([

                'status' => 1,
                'message' =>
                'Vendor app access rules fetched successfully',
                'data' => [
                    'vendor' => $vendor,
                    'sandbox' => $sandbox,
                    'production' => $production
                ]

            ], 200);
        } catch (\Exception $e) {

            return response()->json([

                'status' => 0,
                'message' => $e->getMessage()

            ], 500);
        }
    }

    public function saveVendorAppAccessRules(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [

                'vendor_id' =>
                'required|integer|exists:api_vendors,id',
                'sandbox' =>
                'nullable|array',
                'sandbox.*.scope_id' =>
                'required|integer|exists:api_scope,id',
                'sandbox.*.vendor_environment_id' =>
                'required|integer',
                'sandbox.*.start_time' =>
                'nullable|date_format:H:i',
                'sandbox.*.end_time' =>
                'nullable|date_format:H:i',
                'production' =>
                'nullable|array',
                'production.*.scope_id' =>
                'required|integer|exists:api_scope,id',
                'production.*.vendor_environment_id' =>
                'required|integer',
                'production.*.start_time' =>
                'nullable|date_format:H:i',
                'production.*.end_time' =>
                'nullable|date_format:H:i',
                'created_by' =>
                'nullable|integer',
                'updated_by' =>
                'nullable|integer',
            ]);


            if ($validator->fails()) {

                return response()->json([

                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()

                ], 422);
            }


            $vendorId = $request->vendor_id;
            $createdBy = $request->created_by;
            $updatedBy = $request->updated_by ?? $request->created_by;


            DB::transaction(function () use (
                $request,
                $vendorId,
                $createdBy,
                $updatedBy
            ) {

                $allRules = array_merge(
                    $request->sandbox ?? [],
                    $request->production ?? []

                );


                foreach ($allRules as $rule) {


                    $environment =
                        DB::table(
                            'api_vendor_environments'
                        )
                        ->where(
                            'id',
                            $rule['vendor_environment_id']
                        )
                        ->where(
                            'vendor_id',
                            $vendorId
                        )
                        ->first();


                    if (!$environment) {

                        throw new \Exception(
                            'Invalid vendor environment.'
                        );
                    }
                    $vendorScope =
                        DB::table(
                            'api_vendor_scope as vs'
                        )
                        ->join(
                            'api_scope as s',
                            's.id',
                            '=',
                            'vs.scope_id'
                        )
                        ->where(
                            'vs.vednor_id',
                            $vendorId
                        )
                        ->where(
                            'vs.vendor_environment_id',
                            $environment->id
                        )
                        ->where(
                            'vs.scope_id',
                            $rule['scope_id']
                        )
                        ->where(
                            'vs.status',
                            1
                        )
                        ->where(
                            's.category',
                            2
                        )
                        ->first();


                    if (!$vendorScope) {

                        throw new \Exception(
                            'Invalid Category 2 scope for vendor environment.'
                        );
                    }


                    $startTime = !empty($rule['start_time']) ? $rule['start_time'] . ':00' : null;
                    $endTime = !empty($rule['end_time']) ? $rule['end_time'] . ':00' : null;
                    if (
                        empty($startTime) &&
                        empty($endTime)
                    ) {

                        DB::table(
                            'api_vendor_access_rules'
                        )
                            ->where(
                                'vendor_id',
                                $vendorId
                            )
                            ->where(
                                'vendor_environment_id',
                                $environment->id
                            )
                            ->where(
                                'scope_id',
                                $rule['scope_id']
                            )
                            ->delete();

                        continue;
                    }

                    if (
                        empty($startTime) ||
                        empty($endTime)
                    ) {

                        throw new \Exception(
                            'Both Start Time and End Time are required for scope: ' .
                                $vendorScope->name
                        );
                    }


                    $existingRule =
                        DB::table(
                            'api_vendor_access_rules'
                        )
                        ->where(
                            'vendor_id',
                            $vendorId
                        )
                        ->where(
                            'vendor_environment_id',
                            $environment->id
                        )
                        ->where(
                            'scope_id',
                            $rule['scope_id']
                        )
                        ->first();

                    if ($existingRule) {

                        DB::table(
                            'api_vendor_access_rules'
                        )
                            ->where(
                                'id',
                                $existingRule->id
                            )
                            ->update([

                                'allowed_start_time' => $startTime,
                                'allowed_end_time' => $endTime,
                                'status' => 1,
                                'updated_at' => Carbon::now(),
                                'updated_by' => $updatedBy

                            ]);
                    } else {

                        DB::table(
                            'api_vendor_access_rules'
                        )->insert([

                            'vendor_id' => $vendorId,
                            'vendor_environment_id' => $environment->id,
                            'scope_id' => $rule['scope_id'],
                            'allowed_start_time' => $startTime,
                            'allowed_end_time' => $endTime,
                            'status' => 1,
                            'created_at' => Carbon::now(),
                            'created_by' => $createdBy,
                            'updated_at' => Carbon::now(),
                            'updated_by' => $updatedBy

                        ]);
                    }
                }
            });


            return response()->json([

                'status' => 1,
                'message' =>
                'Vendor app access rules saved successfully'

            ], 200);
        } catch (\Exception $e) {

            return response()->json([

                'status' => 0,
                'message' =>
                $e->getMessage()

            ], 500);
        }
    }

    public function changeVendorAppAccessRuleStatus(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [

                'id' =>
                'required|integer|exists:api_vendor_access_rules,id',
                'vendor_id' =>
                'required|integer|exists:api_vendors,id',
                'status' =>
                'required|boolean',
                'updated_by' =>
                'nullable|integer',
            ]);

            if ($validator->fails()) {

                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }
            $accessRule = DB::table(
                'api_vendor_access_rules'
            )
                ->where('id', $request->id)
                ->where('vendor_id', $request->vendor_id)
                ->first();

            if (!$accessRule) {

                return response()->json([
                    'status' => 0,
                    'message' =>
                    'Vendor app access rule not found'
                ], 404);
            }

            DB::table('api_vendor_access_rules')
                ->where('id', $accessRule->id)
                ->update([
                    'status' => (int) $request->status,
                    'updated_at' => Carbon::now(),
                    'updated_by' => $request->updated_by
                ]);

            return response()->json([
                'status' => 1,
                'message' => $request->status ? 'App access rule activated successfully' : 'App access rule deactivated successfully',
                'data' => [
                    'id' => $accessRule->id,
                    'status' => (int) $request->status
                ]
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
