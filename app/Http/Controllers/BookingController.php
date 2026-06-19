<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingDetail;
use App\Models\Bus;
use App\Models\Location;
use App\Models\User;
use App\Models\BusSeats;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Booking;
use Illuminate\Support\Facades\Validator;
use App\Services\BookingService;
use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    protected $bookingService;


    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }


    public function getAllBooking()
    {

        $bookings = $this->bookingService->getAll();
        $output['status'] = 1;
        $output['message'] = 'All Data Fetched Successfully';
        $output['result'] = $bookings;
        return response($output, 200);
    }

    public function createBooking(Request $request)
    {
        $data = $request->only([

            'transaction_id',
            'pnr',
            'customer_id',
            'user_id',
            'bus_id',
            'source_id',
            'destination_id',
            'j_day',
            'journey_dt',
            'boarding_id',
            'dropping_id',
            'boarding_time',
            'dropping_time',
            'bus_info',
            'customer_info',
            'total_fare',
            'ownr_fare',
            'is_coupon',
            'coupon_code',
            'coupon_discount',
            'discounted_fare',
            'origin',
            'app_type',
            'typ_id',
            'created_by'

        ]);

        $BookingRules = [
            'transaction_id' => 'required',
            //'pnr' => 'required',
            'customer_id' => 'required',
            'user_id' => 'required',
            'bus_id' => 'required',
            'source_id' => 'required',
            'destination_id' => 'required',
            'j_day' => 'required',
            'journey_dt' => 'required',
            'boarding_id' => 'required',
            'dropping_id' => 'required',
            'boarding_time' => 'required',
            'dropping_time' => 'required',
            'bus_info' => 'required',
            'customer_info' => 'required',
            'total_fare' => 'required',
            'ownr_fare' => 'required',
            'is_coupon' => 'required',
            'coupon_code' => 'required',
            'coupon_discount' => 'required',
            'discounted_fare' => 'required',
            'origin' => 'required',
            'app_type' => 'required',
            'typ_id' => 'required',
            'created_by' => 'required',

        ];

        $BookingValidation = Validator::make($data, $BookingRules);


        if ($BookingValidation->fails()) {
            $errors = $BookingValidation->errors();
            return $errors->toJson();
        }
        $result = ['status' => 200];

        try {
            $result['data'] = $this->bookingService->saveBookingData($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function updateBooking(Request $request, $id)
    {
        $data = $request->only([
            'transaction_id',
            'pnr',
            'customer_id',
            'user_id',
            'bus_id',
            'source_id',
            'destination_id',
            'j_day',
            'journey_dt',
            'boarding_id',
            'dropping_id',
            'boarding_time',
            'dropping_time',
            'bus_info',
            'customer_info',
            'total_fare',
            'ownr_fare',
            'is_coupon',
            'coupon_code',
            'coupon_discount',
            'discounted_fare',
            'origin',
            'app_type',
            'typ_id',
            'created_by'

        ]);
        $BookingRules = [

            'transaction_id' => 'required',
            'pnr' => 'required',
            'customer_id' => 'required',
            'user_id' => 'required',
            'bus_id' => 'required',
            'source_id' => 'required',
            'destination_id' => 'required',
            'j_day' => 'required',
            'journey_dt' => 'required',
            'boarding_id' => 'required',
            'dropping_id' => 'required',
            'boarding_time' => 'required',
            'dropping_time' => 'required',
            'bus_info' => 'required',
            'customer_info' => 'required',
            'total_fare' => 'required',
            'ownr_fare' => 'required',
            'is_coupon' => 'required',
            'coupon_code' => 'required',
            'coupon_discount' => 'required',
            'discounted_fare' => 'required',
            'origin' => 'required',
            'app_type' => 'required',
            'typ_id' => 'required',
            'created_by' => 'required',

        ];

        $preBookingValidation = Validator::make($data, $BookingRules);


        if ($preBookingValidation->fails()) {
            $errors = $preBookingValidation->errors();
            return $errors->toJson();
        }

        $result = ['status' => 200];

        try {
            $result['data'] = $this->bookingService->updatePost($data, $id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function deleteBooking($id)
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $this->bookingService->deleteById($id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }

    public function getBooking($id)
    {
        $bookings = $this->bookingService->getById($id);
        $output['status'] = 1;
        $output['message'] = 'Single Data Fetched Successfully';
        $output['result'] = $bookings;
        return response($output, 200);
    }

    public function PrintTicket($pnr)
    {
        $booking = Booking::where('pnr', $pnr)
            ->where('status', 1)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found'
            ], 404);
        }

        $passengers = BookingDetail::select(
            'booking_detail.*',
            'seats.seatText as seat_name'
        )
            ->leftJoin(
                'bus_seats',
                'bus_seats.id',
                '=',
                'booking_detail.bus_seats_id'
            )
            ->leftJoin(
                'seats',
                'seats.id',
                '=',
                'bus_seats.seats_id'
            )
            ->where(
                'booking_detail.booking_id',
                $booking->id
            )
            ->get();

        $bus = Bus::find($booking->bus_id);
        $conductor = DB::table('bus_contacts')
            ->where('bus_id', $booking->bus_id)
            ->where('type', 2)
            ->first();
        $source = Location::find($booking->source_id);
        $destination = Location::find($booking->destination_id);
        $agent = User::find($booking->user_id);

        return response()->json([
            'success' => true,
            'data' => [
                'booking' => $booking,
                'bus' => $bus,
                'source' => $source,
                'destination' => $destination,
                'agent' => $agent,
                'passengers' => $passengers,
                'conductor' => $conductor
            ]
        ], Response::HTTP_OK);
    }
}
