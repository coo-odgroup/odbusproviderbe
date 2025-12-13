    <?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use InvalidArgumentException;
    use App\Traits\ApiResponser;
    use Illuminate\Support\Facades\Config;
    use Exception;
    use Symfony\Component\HttpFoundation\Response;
    use App\AppValidator\TicketFareSlabValidator;
    use Illuminate\Support\Facades\Log;
    use App\Repositories\TicketFareSlabRepository;
    use App\Repositories\LocationRepository;
    use Illuminate\Support\Facades\DB;
    use App\AppValidator\LocationValidator;


    class TicketFareSlabController extends Controller
    {
        use ApiResponser;
        /**
         * @var LocationService
         */

        protected $ticketFareSlabValidator;
        protected $ticketFareSlabRepository;
        protected $locationRepository;
        protected $locationValidator;

        /**
         * PostController Constructor
         *
         * @param LocationService $busTypeService
         *
         */
        public function __construct(
            LocationRepository $locationRepository,
            TicketFareSlabRepository $ticketFareSlabRepository,
            TicketFareSlabValidator $ticketFareSlabValidator,
            LocationValidator $locationValidator
        ) {

            $this->ticketFareSlabValidator = $ticketFareSlabValidator;
            $this->ticketFareSlabRepository = $ticketFareSlabRepository;
            $this->locationRepository = $locationRepository;
            $this->locationValidator = $locationValidator;
        }


        public function ticketFareSlabData(Request $request)
        {


            $ticketFare = $this->ticketFareSlabRepository->ticketFareSlabData($request);
            return $this->successResponse($ticketFare, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        }
        public function changeStatusticketFareSlab($id)
        {

            try {

                $response = $this->ticketFareSlabRepository->changeStatusticketFareSlab($id);
                return $this->successResponse($response, "Ticket Fare Slab Status Updated", Response::HTTP_ACCEPTED);
            } catch (Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
            }
            return $this->successResponse(null, "Ticket Fare Slab Status Updated", Response::HTTP_ACCEPTED);
        }
        public function deleteticketFareSlab($id)
        {

            try {

                $this->ticketFareSlabRepository->deleteticketFareSlab($id);
            } catch (Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
            }
            return $this->successResponse(null, "Ticket Fare Slab Deleted", Response::HTTP_ACCEPTED);
        }

        public function createslab(Request $request)

        {

            $data = $request->all();



            $response =  $this->ticketFareSlabRepository->createslab($data);


            if ($response == 'Operator Already Exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } else {
                return $this->successResponse($response, "Ticket fare Slab Added Successfully", Response::HTTP_CREATED);
            }
        }


        public function editLocation(Request $request, $id)
        {
            $data = $request->only([
                'name',
                'synonym',
                'created_by'
            ]);


            $locationValidation = $this->locationValidator->validate($data);
            if ($locationValidation->fails()) {
                $errors = $locationValidation->errors();
                return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
            }

            try {

                $location = $this->locationRepository->update($data, $id);

                if (!$location) {

                    return $this->errorResponse('Location Already Exist', Response::HTTP_PARTIAL_CONTENT);
                }

                return $this->successResponse($location, "Location Updated", Response::HTTP_CREATED);
            } catch (Exception $e) {
                DB::rollBack();
                Log::error("Error updating location: " . $e->getMessage());

                return $this->errorResponse('Record not found or unable to update', Response::HTTP_BAD_REQUEST);
            }
        }



        public function filterLocation(request $request)
        {

            $prod = $this->locationRepository->filter($request);
            $output['status'] = 1;
            $output['message'] = 'All Data Fetched Successfully';
            $output['result'] = $prod;
            return $this->successResponse($prod, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
        }
    }
