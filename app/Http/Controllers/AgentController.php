<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use Exception;
use App\AppValidator\AgentValidator;
use App\Repositories\AgentRepository;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Models\AgentSlider;

class AgentController extends Controller
{
  use ApiResponser;

  protected $agentValidator;

  public function __construct(AgentValidator $agentValidator, AgentRepository $agentRepository)
  {
    $this->agentValidator = $agentValidator;
    $this->agentRepository = $agentRepository;
  }


  public function agentprofile(Request $request)
  {
    try {
      $agents = $this->agentRepository->agentprofile($request);
      return $this->successResponse($agents, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
    }
  }

  public function updateAgentProfile(Request $request)
  {
    try {
      $agents = $this->agentRepository->updateAgentProfile($request);
      return $this->successResponse($agents, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
    }
  }

  public function getAllAgent(Request $request)
  {
    try {
      $agents = $this->agentRepository->getAll($request);
      return $this->successResponse($agents, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
    }
  }


  public function getAllAgentData(Request $request)
  {
    try {
      $agents = $this->agentRepository->getAllAgentData($request);
      return $this->successResponse($agents, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
    }
  }

  public function ourAgentData(Request $request)
  {
    try {
      $agents = $this->agentRepository->ourAgentData($request);
      return $this->successResponse($agents, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
    }
  }

  public function createAgent(Request $request)
  {
    DB::beginTransaction();
    try {
      $data = $request->only([
        'name',
        'email',
        'phone',
        'password',
        'user_type',
        'otp',
        'city',
        'street',
        'location',
        'adhar_no',
        'pancard_no',
        'organization_name',
        'address',
        'landmark',
        'pincode',
        'name_on_bank_account',
        'bank_name',
        'ifsc_code',
        'bank_account_no',
        'agentType',
        'created_by'
      ]);
      $agentValidation = $this->agentValidator->validate($data);

      if ($agentValidation->fails()) {
        $errors = $agentValidation->errors();
        return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
      }

      $response =  $this->agentRepository->savePostData($request);

      if (in_array($response, [
        'Email Already Exist',
        'Phone Already Exist',
        'Pan Card Already Exist',
        'Aadhaar Card Already Exist'
      ])) {
        DB::rollBack();
        return $this->errorResponse($response, \Symfony\Component\HttpFoundation\Response::HTTP_PARTIAL_CONTENT);
      }

      DB::commit();
      return $this->successResponse($response, "Agent Create", Response::HTTP_CREATED);
    } catch (Exception $e) {
      DB::rollBack();
      return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
    }
  }

  public function updateAgent(Request $request, $id)
  {
    DB::beginTransaction();

    try {
      $data = $request->only([
        'name',
        'email',
        'phone',
        'password',
        'user_type',
        'otp',
        'location',
        'adhar_no',
        'pancard_no',
        'organization_name',
        'address',
        'landmark',
        'pincode',
        'name_on_bank_account',
        'bank_name',
        'ifsc_code',
        'bank_account_no',
        'agentType',
        'created_by'
      ]);

      $response = $this->agentRepository->update($data, $id);

      if (in_array($response, [
        'Email Already Exist',
        'Phone Already Exist',
        'Pan Card Already Exist',
        'Aadhaar Card Already Exist'
      ])) {
        DB::rollBack();
        return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
      }

      DB::commit();
      return $this->successResponse($response, "Agent Updated", Response::HTTP_CREATED);
    } catch (Exception $e) {
      DB::rollBack();
      return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
    }
  }

  public function deleteAgent($id)
  {
    DB::beginTransaction();
    try {
      $this->agentRepository->delete($id);

      DB::commit();
      return $this->successResponse('Null', "Agent has been deleted Successfully", Response::HTTP_ACCEPTED);
    } catch (Exception $e) {
      DB::rollBack();
      return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
    }
  }

  public function getAgent($id)
  {
    try {
      $agentID = $this->agentRepository->getById($id);
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
    }
    return $this->successResponse($agentID, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
  }

  public function changeStatus(Request $request)
  {
    DB::beginTransaction();
    try {
      $this->agentRepository->changeStatus($request);

      DB::commit();
      return $this->successResponse(null, "Agent Status Updated", Response::HTTP_ACCEPTED);
    } catch (Exception $e) {
      DB::rollBack();
      return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
    }
  }

  public function blockAgent(Request $request)
  {
    DB::beginTransaction();
    try {
      $this->agentRepository->blockAgent($request);

      DB::commit();
      return $this->successResponse(null, "Agent Status Updated", Response::HTTP_ACCEPTED);
    } catch (Exception $e) {
      DB::rollBack();
      return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
    }
  }

  public function agentCouponSlider(Request $request)
  {
    try {
      $today = now();

      $agentSlider = DB::table('agent_slider')
        ->where('status', 1)
        ->where('start_date', '<=', $today)
        ->where('end_date', '>=', $today)
        ->orderBy('sequence', 'asc')
        ->get();
      return $this->successResponse($agentSlider, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
    }
  }

  public function agentAlerts(Request $request)
  {
    try {
      date_default_timezone_set('Asia/Kolkata');
      $now = date('Y-m-d H:i:s');

      $before6Min = date(
        'Y-m-d H:i:s',
        strtotime('-60 minutes')
      );

      // return $before6Min;

      $data = DB::table('booking')
        ->where('status', 4)
        ->where('updated_at', '>=', $before6Min)
        ->where('updated_at', '<=', $now)
        ->get();

      // return $data;

      foreach ($data as $booking) {

        // Get user details
        $user = DB::table('users')
          ->where('id', $booking->users_id)
          ->first();

          return $user;

        if (!$user) {
          continue;
        }

        $mobile = $user->mobile_no;
        $email  = $user->email;

        // Notification message
        $message = 'Your bus booking is incomplete. Please complete your booking to confirm your seat.';

        // Send SMS
        // $this->sendSms($mobile, $message);

        // Send Email
        // $this->sendEmail($email, 'Complete Your Booking', $message);

        // Example for checking
        Log::info('Incomplete Booking Notification', [
          'booking_id' => $booking->id,
          'user_id'    => $booking->user_id,
          'mobile'     => $mobile,
          'email'      => $email,
        ]);
      }

      return $data;
    } catch (\Exception $e) {
      $this->error($e->getMessage());
    }

    return;
    try {
      $agentAlerts = DB::table('assigned_comm_slab_agent')
        ->where('status', 1)
        ->where('agent_id', '=', $request->agent_id)
        ->get();
      return $this->successResponse($agentAlerts, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
    }
  }

  public function agentDashboard(Request $request)
  {
    try {
      $agentBookings = DB::table('booking')
        ->where('user_id', '!=', 0)
        ->where('app_type', 'AGENT')
        ->where('status', 1);

      $agents = DB::table('user')
        ->where('role_id', 3)
        ->where('user_type', 'AGENT')
        ->where('status', 1)
        ->get();

      $walletBalance = 0;
      $firstBookingPending = 0;

      foreach ($agents as $agent) {

        $wallet = DB::table('agent_wallet')
          ->where('user_id', $agent->id)
          ->where('status', 1)
          ->orderBy('id', 'DESC')
          ->limit(1)
          ->first();

        if ($wallet) {
          $walletBalance += $wallet->balance;
        }
      }

      // return $firstBooking;

      $walletBalance = round($walletBalance, 2);

      $userRec = DB::table('user')
        ->where('role_id', 3)
        ->where('user_type', 'AGENT')
        ->where('status', 1);

      $newToday = (clone $userRec)
        ->whereDate('created_at', now()->toDateString())
        ->count();

      $newThisMonth = (clone $userRec)
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

      $kycPending = (clone $userRec)
        ->where(function ($query) {
          $query->where('is_mobile_verified', 0)
            ->orWhere('is_email_verified', 0)
            ->orWhere('is_pan_verified', 0)
            ->orWhere('is_aadhaar_verified', 0);
        })
        ->count();

      $activatedAgents = (clone $userRec)
        ->where('is_mobile_verified', 1)
        ->where('is_email_verified', 1)
        ->where('is_pan_verified', 1)
        ->where('is_aadhaar_verified', 1)
        ->count();

      $agentIds = $agents->pluck('id');

      // return $agentIds;

      $bookingAgentIds = DB::table('booking')
        ->whereIn('user_id', $agentIds)
        ->where('status', 1)
        ->distinct()
        ->pluck('user_id');

      $firstBookingPending = $agents
        ->whereNotIn('id', $bookingAgentIds)
        ->count();

      $activeAgentIds = DB::table('booking')
        ->where('status', 1)
        ->where('created_at', '>=', now()->subMonth())
        ->distinct()
        ->pluck('user_id');

      $activeAgents = (clone $userRec)
        ->whereIn('id', $activeAgentIds)
        ->count();

      $dormantAgentIds = DB::table('booking')
        ->whereIn('user_id', $agentIds)
        ->where('status', 1)
        ->whereBetween('created_at', [
          now()->subDays(90),
          now()->subDays(31)
        ])
        ->distinct()
        ->pluck('user_id');

      $dormantAgents = $agents
        ->whereIn('id', $dormantAgentIds)
        ->count();

      $inactiveAgentIds = DB::table('booking')
        ->whereIn('user_id', $agentIds)
        ->where('status', 1)
        ->where('created_at', '<', now()->subDays(90))
        ->distinct()
        ->pluck('user_id');

      $inactiveAgents = $agents
        ->whereIn('id', $inactiveAgentIds)
        ->count();

      $topAgents = DB::table('booking as b')
        ->join('user as u', 'u.id', '=', 'b.user_id')
        ->join('bus_operator as bo', 'bo.id', '=', 'b.user_id')
        ->select(
          'b.user_id as agent_id',
          'u.name as agent_name',
          'u.unique_id as agent_unique_id',
          'bo.location_name',
          DB::raw('SUM(b.total_fare) as total_fare'),
          DB::raw('SUM(b.agent_commission) as total_commission'),
          DB::raw('COUNT(b.id) as total_bookings'),
          'b.status'
        )
        ->whereIn('b.user_id', $agentIds)
        ->where('b.status', 1)
        ->where('u.role_id', 3)
        ->groupBy('b.user_id', 'u.name', 'bo.location_name')
        ->orderByDesc('total_fare')
        ->limit(10)
        ->get();

      $topOperators = DB::table('booking as b')
        ->join('user as u', 'u.id', '=', 'b.user_id')
        ->join('bus_operator as o', 'o.id', '=', 'b.user_id')
        ->join('bus as bo', 'bo.bus_operator_id', '=', 'o.id')
        ->select(
          'o.id as operator_id',
          'o.operator_name',
          'bo.name as bus_name',
          DB::raw('SUM(b.total_fare) as total_fare'),
          DB::raw('COUNT(b.id) as total_bookings')
        )
        ->whereIn('b.user_id', $agentIds)
        ->where('b.status', 1)
        ->groupBy('o.id', 'o.operator_name', 'bo.name')
        ->orderByDesc('total_fare')
        ->limit(3)
        ->get();

      $topAgentRoutes = DB::table('booking as b')
        ->join('location as source', 'source.id', '=', 'b.source_id')
        ->join('location as destination', 'destination.id', '=', 'b.destination_id')
        ->select(
          'source.name as source_name',
          'destination.name as destination_name',
          DB::raw('COUNT(b.id) as total_bookings'),
          DB::raw('SUM(b.total_fare) as total_fare'),
          DB::raw('COUNT(DISTINCT b.user_id) as total_agents')
        )
        ->whereIn('b.user_id', $agentIds)
        ->where('b.status', 1)
        ->groupBy('b.source_id', 'b.destination_id')
        ->orderByDesc('total_bookings')
        ->limit(10)
        ->get();

      $data = [
        'grossbookings' => number_format((clone $agentBookings)->sum('total_fare'), 2, '.', ''),
        'totalpnrs' => (clone $agentBookings)->count(),
        'commissions' => number_format((clone $agentBookings)->sum('agent_commission'), 2, '.', ''),
        'walletbalance' => number_format($walletBalance, 2, '.', ''),
        'newtoday' => $newToday,
        'newthismonth' => $newThisMonth,
        'kycpending' => $kycPending,
        'activatedagents' => $activatedAgents,
        'firstbookingpending' => $firstBookingPending,
        'activeagents' => $activeAgents,
        'dormantagents' => $dormantAgents,
        'inactiveagents' => $inactiveAgents,
        'totalagents' => count($agents),
        'topagents' => $topAgents,
        'topoperators' => $topOperators,
        'topagentroutes' => $topAgentRoutes
      ];

      return $this->successResponse($data, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
    }
  }

  public function agentReports(Request $request)
  {
    try {
      $userRec = DB::table('user')
        ->where('role_id', 3)
        ->where('user_type', 'AGENT')
        ->where('status', 1);

      $newToday = (clone $userRec)
        ->whereDate('created_at', now()->toDateString())
        ->get();

      $data = [
        'newtoday' => $newToday,
      ];

      return $this->successResponse($data, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
    }
  }
}
