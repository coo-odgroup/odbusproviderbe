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

                // Vendor Details
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


            /*
         * Generate random unique 6 digit Vendor Code
         */
            do {

                $vendorCode = random_int(100000, 999999);
            } while (
                ApiVendor::where(
                    'vendor_code',
                    $vendorCode
                )->exists()
            );


            /*
         * Create Vendor
         */
            $vendor = new ApiVendor();

            $vendor->vendor_code = $vendorCode;

            $vendor->company_name =
                $request->company_name;

            $vendor->contact_name =
                $request->contact_name;

            $vendor->contact_email =
                $request->contact_email;

            $vendor->contact_phone =
                $request->contact_phone;

            $vendor->rate_limit_per_minute =
                $request->rate_limit_per_minute;

            $vendor->has_gst =
                $request->has_gst ? 1 : 0;

            $vendor->status = 1;

            $vendor->activated_at =
                Carbon::now();

            $vendor->suspended_at = null;

            $vendor->risk_score =
                $request->risk_score ?? null;

            $vendor->created_by =
                $request->created_by;

            $vendor->updated_by =
                $request->updated_by ??
                $request->created_by;


            /*
         * Save Vendor
         */
            $vendor->save();


            /*
         * ============================================
         * SAVE VENDOR ADDRESS
         * ============================================
         */

            DB::table('api_vendor_address')->insert([

                'vendor_id' =>
                $vendor->id,

                'address' =>
                $request->address,

                'street' =>
                $request->street,

                'landmark' =>
                $request->landmark,

                'city' =>
                $request->city,

                'pincode' =>
                $request->pincode,

                'state' =>
                $request->state,

                'created_at' =>
                Carbon::now(),

                'created_by' =>
                $request->created_by,

                'updated_at' =>
                Carbon::now(),

                'updated_by' =>
                $request->updated_by ??
                    $request->created_by,
            ]);


            /*
         * ============================================
         * SAVE API ENVIRONMENTS
         * ============================================
         */

            $environments = [];


            // Sandbox
            if ((int) $request->sandbox === 1) {

                $environments[] = [

                    'vendor_id' =>
                    $vendor->id,

                    'environment' =>
                    'sandbox',

                    'status' => 1,

                    'created_at' =>
                    Carbon::now(),

                    'updated_at' =>
                    Carbon::now(),
                ];
            }


            // Production
            if ((int) $request->production === 1) {

                $environments[] = [

                    'vendor_id' =>
                    $vendor->id,

                    'environment' =>
                    'production',

                    'status' => 1,

                    'created_at' =>
                    Carbon::now(),

                    'updated_at' =>
                    Carbon::now(),
                ];
            }


            /*
         * Insert selected environments
         */
            if (!empty($environments)) {

                DB::table(
                    'api_vendor_environments'
                )->insert($environments);
            }


            /*
         * Response
         */
            return response()->json([

                'status' => 1,

                'message' =>
                'Vendor added successfully',

                'data' => [

                    'vendor' =>
                    $vendor,

                    'address' =>
                    $request->only([
                        'address',
                        'street',
                        'landmark',
                        'city',
                        'pincode',
                        'state'
                    ]),

                    'environments' =>
                    $environments,
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

                // Vendor
                'id' => 'required|integer',

                'company_name' => 'required|string|max:255',
                'contact_name' => 'required|string|max:255',
                'contact_email' => 'required|email|max:255',
                'contact_phone' => 'required|string|max:10',
                'rate_limit_per_minute' => 'required|integer|min:1',
                'has_gst' => 'nullable|boolean',

                // Address
                'address' => 'required|string|max:500',
                'street' => 'nullable|string|max:255',
                'landmark' => 'nullable|string|max:255',
                'city' => 'required|string|max:100',
                'pincode' => 'required|string|max:10',
                'state' => 'required|integer|exists:state,id',

                // User
                'updated_by' => 'nullable|integer',

            ]);

            if ($validator->fails()) {

                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }


            // =====================================================
            // GET VENDOR
            // =====================================================

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


            // =====================================================
            // UPDATE VENDOR
            // =====================================================

            // Vendor code should NOT be changed

            $vendor->company_name =
                $request->company_name;

            $vendor->contact_name =
                $request->contact_name;

            $vendor->contact_email =
                $request->contact_email;

            $vendor->contact_phone =
                $request->contact_phone;

            $vendor->rate_limit_per_minute =
                $request->rate_limit_per_minute;

            $vendor->has_gst =
                $request->has_gst ? 1 : 0;


            if ($request->has('risk_score')) {

                $vendor->risk_score =
                    $request->risk_score;
            }


            // Don't depend on Auth::id()
            $vendor->updated_by =
                $request->updated_by;


            $vendor->save();


            // =====================================================
            // UPDATE / INSERT VENDOR ADDRESS
            // =====================================================

            DB::table('api_vendor_address')
                ->updateOrInsert(

                    // Find address by vendor ID
                    [
                        'vendor_id' => $vendor->id
                    ],

                    // Update / insert these fields
                    [
                        'address' =>
                        $request->address,

                        'street' =>
                        $request->street,

                        'landmark' =>
                        $request->landmark,

                        'city' =>
                        $request->city,

                        'pincode' =>
                        $request->pincode,

                        'state' =>
                        $request->state,

                        'updated_at' =>
                        Carbon::now(),

                        'updated_by' =>
                        $request->updated_by
                    ]
                );


            // =====================================================
            // RESPONSE
            // =====================================================

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

                    'vendor' =>
                    $vendor,

                    'address' =>
                    $address
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

            /*
         * Find the actual environment row.
         */
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

            /*
         * IMPORTANT:
         * If credential already exists, do NOT generate another one.
         */
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

            /*
         * Generate Client ID
         *
         * Example:
         * test_125455_a7F3kP9xQ2mL8vR4tY6nB1cD5eH0jK3zS
         */
            $prefix = $request->environment === 'sandbox'
                ? 'test_'
                : 'prod_';

            $clientId =
                $prefix .
                Str::lower($vendor->vendor_code) .
                '_' .
                Str::random(32);

            /*
         * Generate Client Secret
         */
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

                    /*
                 * Always verify the environment belongs
                 * to this vendor.
                 */
                    $environment = DB::table('api_vendor_environments')
                        ->where('id', $credential['vendor_environment_id'])
                        ->where('vendor_id', $vendor->id)
                        ->where(
                            'environment',
                            $credential['environment']
                        )
                        ->first();

                    if (!$environment) {
                        throw new \Exception(
                            'Invalid ' .
                                ucfirst($credential['environment']) .
                                ' environment for this vendor.'
                        );
                    }

                    /*
                 * Check if credential already exists.
                 *
                 * This prevents generating/saving another credential.
                 */
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

                    $credential->client_secret =
                        Crypt::decryptString(
                            $credential->client_secret_encrypted
                        );
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
                'vendor_id' =>
                'required|integer|exists:api_vendors,id',

                'environment' =>
                'required|in:sandbox,production',

                'status' =>
                'required|boolean',
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
}
