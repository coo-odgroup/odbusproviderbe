<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Services\AgentWalletService;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use App\AppValidator\AgentWalletValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Repositories\AgentWalletRepository;
use Illuminate\Support\Facades\DB;
use App\Models\AgentWallet;
use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AgentWalletController extends Controller
{
    use ApiResponser;
    protected $agentWalletService;
    protected $agentWalletValidator;
    protected $agentWalletRepository;

    public function __construct(AgentWalletService $agentWalletService, AgentWalletValidator $agentWalletValidator, AgentWalletRepository $agentWalletRepository)
    {
        $this->agentWalletService = $agentWalletService;
        $this->agentWalletValidator = $agentWalletValidator;
        $this->agentWalletRepository = $agentWalletRepository;
    }

    public function getAllData(Request $request)
    {
        try {
            $wallet = $this->agentWalletService->getAllData($request);
            return $this->successResponse($wallet, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function agentWalletBalancedetails(Request $request)
    {
        try {
            $wallet = $this->agentWalletRepository->agentWalletBalancedetails($request);
            return $this->successResponse($wallet, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function agentAllTransaction(Request $request)
    {
        try {
            $wallet = $this->agentWalletRepository->agentAllTransaction($request);
            return $this->successResponse($wallet, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function getData(Request $request)
    {
        // return $request->all();
        try {
            $wallet = $this->agentWalletService->getData($request);
            return $this->successResponse($wallet, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function addAgentWallet(Request $request)
    {

        // return $request->all();
        $data = $request->only(['transaction_id', 'reference_id', 'payment_via', 'amount', 'remarks', 'user_id']);
        $agentWalletValidator = $this->agentWalletValidator->validate($data);

        if ($agentWalletValidator->fails()) {
            $errors = $agentWalletValidator->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        DB::beginTransaction();
        try {
            $this->agentWalletRepository->save($request);

            DB::commit();
            return $this->successResponse($data, "Wallet request Added", Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function walletMakePayment(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'amount' => 'required|numeric|min:100|max:49999',
            ]);

            $user = json_decode(
                json_encode(
                    DB::table('user')
                        ->where('id', $request->user_id)
                        ->first()
                ),
                true
            );

            if (!$user) {

                return response()->json([
                    'status' => 0,
                    'message' => 'User not found'
                ]);
            }

            $receiptId = 'WALLET_' . time() . rand(1000, 9999);
            $key = env('CASHFREE_KEY');
            $secretKey = env('CASHFREE_SECRET');
            $apiUrl = env('CASHFREE_API_URL');
            $response = Http::withHeaders([

                'x-client-id' => $key,
                'x-client-secret' => $secretKey,
                'x-api-version' => '2023-08-01',
                'Content-Type' => 'application/json',

            ])->post($apiUrl, [

                "order_id" => $receiptId,
                "order_amount" => (float)$request->amount,
                "order_currency" => "INR",

                "customer_details" => [
                    "customer_id" => (string)$user['id'],
                    "customer_name" => $user['name'],
                    "customer_email" => $user['email'],
                    "customer_phone" => $user['phone'] ?? '9999999999',

                ],
                "order_meta" => [

                    "notify_url" =>
                    url('/api/walletWebhook')

                ],


            ]);

            $responseData = $response->json();

            Log::info(
                json_encode(
                    $responseData,
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );

            if (!isset($responseData['payment_session_id'])) {

                DB::rollBack();

                return response()->json([

                    'status' => 0,
                    'message' => 'Unable to create payment session',
                    'response' => $responseData

                ]);
            }

            DB::table('wallet_request')->insert([

                'transaction_id' => $responseData['order_id'],
                'amount' => $responseData['order_amount'],
                'credited_amount' => null,
                'payment_status' => 0,
                'transaction_type' => 'c',
                'remarks' => $request->remarks,
                'user_id' => $responseData['customer_details']['customer_id'],
                'created_by' => $responseData['customer_details']['customer_name'],
                'payment_via' => 'Cashfree',
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),

            ]);

            DB::commit();

            return response()->json([

                'status' => 1,
                'message' => 'Payment Session Created',

                'data' => [
                    'payment_session_id' => $responseData['payment_session_id'],
                    'order_id' => $responseData['order_id'],
                    'amount' => $request->amount

                ]

            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'status' => 0,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),

            ]);
        }
    }

    public function walletWebhook(Request $request)
    {
        DB::beginTransaction();

        try {

            $post = file_get_contents('php://input');

            DB::table('payment_webhook')->insert([
                'date' => now(),
                'body' => json_encode(
                    json_decode($post),
                    JSON_PRETTY_PRINT
                ),
                'type' => 'cashfree_wallet',
            ]);

            $res = json_decode($post);

            Log::info(
                json_encode(
                    $res,
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );

            if (

                isset($res->data->payment) &&
                isset($res->data->order) &&
                $res->data->payment->payment_status == 'SUCCESS'

            ) {

                $orderId = $res->data->order->order_id;
                $paymentId = $res->data->payment->cf_payment_id;
                $paymentAmount = $res->data->payment->payment_amount;

                $walletRequest = DB::table('wallet_request')

                    ->where('transaction_id', $orderId)
                    ->first();

                if (!$walletRequest) {

                    DB::rollBack();
                    return response()->json([

                        'status' => false,
                        'message' => 'Wallet request not found'

                    ]);
                }

                DB::table('wallet_request')

                    ->where('transaction_id', $orderId)
                    ->update([

                        'payment_status' => 1,
                        'credited_amount' => $paymentAmount,
                        'reference_id' => $paymentId,
                        'status' => 1,
                        'updated_at' => now(),

                    ]);

                $lastWallet = AgentWallet::where(
                    'user_id',
                    $walletRequest->user_id
                )
                    ->orderBy('id', 'DESC')
                    ->first();

                $previousBalance = $lastWallet ? $lastWallet->balance : 0;
                $newBalance = $previousBalance + $paymentAmount;
                AgentWallet::insert([

                    'transaction_id' => $orderId,
                    'reference_id' => $paymentId,
                    'payment_via' => 'Cashfree',
                    'amount' => $paymentAmount,
                    'transaction_type' => 'c',
                    'balance' => $newBalance,
                    'remarks' => $walletRequest->remarks,
                    'user_id' => $walletRequest->user_id,
                    'created_by' => $walletRequest->created_by,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),

                ]);

                DB::commit();

                return response()->json([

                    'status' => true,
                    'message' => 'Wallet recharge successful'

                ]);
            }

            DB::rollBack();

            return response()->json([

                'status' => false,
                'message' => 'Payment failed'

            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error($e);

            return response()->json([

                'status' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),

            ]);
        }
    }


    public function agentTransByAdmin(Request $request)
    {
        try {
            $data = $this->agentWalletRepository->agentTransByAdmin($request);
            return $this->successResponse($data, "Wallet request Added", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
        // $data = $request->only(['transaction_id','reference_id','payment_via','amount','remarks','user_id']);

        // $agentWalletValidator = $this->agentWalletValidator->validate($data);
        // if ($agentWalletValidator->fails()) {
        //     $errors = $agentWalletValidator->errors();

        //     return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        //   }
        // try {
        //    $this->agentWalletService->agentTransByAdmin($request);
        //    return $this->successResponse($data,"Wallet request Added",Response::HTTP_CREATED);
        // } catch (Exception $e) {
        //    return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        // }

    }


    public function changeStatus(Request $request, $id)
    {
        // return $request->all();
        $data = $this->agentWalletService->changeStatus($request, $id);
        if ($data == 'Invalid OTP') {
            return $this->errorResponse($data, Response::HTTP_PARTIAL_CONTENT);
        } else {
            return $this->successResponse($data, "Wallet request Updated", Response::HTTP_CREATED);
        }
    }

    public function declineWlletReqStatus(Request $request, $id)
    {
        $dd = $this->agentWalletRepository->declineWalletReq($request, $id);

        if ($dd != ' ') {
            return $this->successResponse($dd, "Wallet Request Declined!", Response::HTTP_CREATED);
        } else {
            return $this->errorResponse($dd, Response::HTTP_PARTIAL_CONTENT);
        }
    }


    public function agentWalletBalance($id)
    {
        try {
            $wallet = $this->agentWalletRepository->balance($id);
            return $this->successResponse($wallet, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    public function redeemCommission(Request $request)
    {
        try {

            Log::info('REDEEM REQUEST', $request->all());

            $pnrs    = $request->pnrs;
            $agentId = $request->user_id;

            if (!is_array($pnrs) || count($pnrs) === 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'PNRs missing'
                ], 400);
            }

            DB::beginTransaction();

            $bookings = Booking::whereIn('pnr', $pnrs)
                ->where('user_id', $agentId)
                ->whereIn('status', [1, 2])
                ->where('redeem_status', 0)
                ->where('with_tds_commission', '>', 0)
                ->lockForUpdate()
                ->get();

            if ($bookings->isEmpty()) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'No redeemable commission found'
                ], 400);
            }

            $totalCommission = $bookings->sum('with_tds_commission');

            if ($totalCommission <= 0) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Commission amount is zero'
                ], 400);
            }


            $lastWallet = AgentWallet::where('user_id', $agentId)
                ->orderBy('id', 'desc')
                ->first();

            $previousBalance = $lastWallet ? $lastWallet->balance : 0;
            $newBalance     = $previousBalance + $totalCommission;

            AgentWallet::insert([
                'transaction_id'   => 'CR' . now()->format('YmdHis') . rand(1000, 9999),
                'amount'           => $totalCommission,
                'transaction_type' => 'c',
                'type'             => 'Commission Redeem',
                'balance'          => $newBalance,
                'user_id'          => $agentId,
                'created_by'       => $agentId,
                'status'           => 1,
                'created_at'       => now(),
                'updated_at'       => now()
            ]);


            Booking::whereIn('pnr', $bookings->pluck('pnr'))
                ->update([
                    'redeem_status' => 1,
                    'updated_at'    => now()
                ]);






            $fromDate = $bookings->min('journey_dt');
            $toDate   = $bookings->max('journey_dt');

            DB::table('agent_redeem')->insert([
                'agent_id'         => $agentId,
                'from_date'        => $fromDate,
                'to_date'          => $toDate,
                'booking_ids'      => implode(',', $bookings->pluck('pnr')->toArray()),
                'previous_balance' => $previousBalance,
                'redeem_amount'    => $totalCommission,
                'current_balance'  => $newBalance,
                'created_by'       => $agentId,
                'created_on'       => now()
            ]);


            DB::commit();

            return response()->json([
                'status'           => true,
                'message'          => 'Commission redeemed successfully',
                'credited_amount' => $totalCommission,
                'wallet_balance'  => $newBalance,
                'redeemed_pnrs'   => $bookings->pluck('pnr')
            ], 200);
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('REDEEM FAILED', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }
}
