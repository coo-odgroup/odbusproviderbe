<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BusSittingController;
use App\Http\Controllers\BusTypeController;
use App\Http\Controllers\SafetyController;
use App\Http\Controllers\AmenitiesController;
use App\Http\Controllers\AppDownloadController;
use App\Http\Controllers\BusSeatLayoutController;
use App\Http\Controllers\DummyController;
use App\Http\Controllers\CustomerQueryController;
use App\Http\Controllers\CustomerQueryCategoryController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\BusGalleryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BusAmenitiesController;
use App\Http\Controllers\BusContactsController;
use App\Http\Controllers\BusExtraFareController;
use App\Http\Controllers\BusStoppageController;
use App\Http\Controllers\BusSeatsController;
use App\Http\Controllers\BusSeatsExtraController;
use App\Http\Controllers\BusStoppageTimingController;
use App\Http\Controllers\BusCancelledController;
use App\Http\Controllers\BusScheduleController;
use App\Http\Controllers\BusSlotsController;
use App\Http\Controllers\CityClosingController;
use App\Http\Controllers\CityClosingExtendedController;
use App\Http\Controllers\BusOperatorController;
use App\Http\Controllers\BookingSeizedController;
use App\Http\Controllers\SeatOpenController;
use App\Http\Controllers\SeatBlockController;
use App\Http\Controllers\OwnerPaymentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AppVersionController;
use App\Http\Controllers\SiteMasterController;
use App\Http\Controllers\BoardingDropingController;
use App\Http\Controllers\CustomPagesController;
use App\Http\Controllers\ReasonController;
use App\Http\Controllers\TicketCancelationController;
use App\Http\Controllers\BusStoppageAdditionalFareController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CouponAssignedBusController;
use App\Http\Controllers\PreBookingController;
use App\Http\Controllers\PreBookingDetailController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingDetailController;
use App\Http\Controllers\CancellationSlabController;
use App\Http\Controllers\ExtendedBusClosingHoursController;
use App\Http\Controllers\BusClosingHoursController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BusSpecialFareController;
use App\Http\Controllers\BusOwnerFareController;
use App\Http\Controllers\FestivalFareController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\OdbusChargesController;

use App\Http\Controllers\OffersController;


use App\Http\Controllers\DashboardController;

use App\Http\Middleware\LogRoute;
use Laravel\Passport\Passport;
use App\Http\Controllers\AgentController;

// ReportController
use App\Http\Controllers\SeatOpenReportController;
use App\Http\Controllers\SeatBlockReportController;
use App\Http\Controllers\ExtraSeatOpenReportController;
use App\Http\Controllers\CompleteReportController;
use App\Http\Controllers\ClearTransactionReportController;
use App\Http\Controllers\OwnerPaymentReportController;
use App\Http\Controllers\BusCancellationReportController;
use App\Http\Controllers\FailledTransactionReportController;
use App\Http\Controllers\CouponUsedUserReportController;
use App\Http\Controllers\CancelTicketReportController;
use App\Http\Controllers\ContactReportController;

// SettingController 
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\PageContentController;
use App\Http\Controllers\UserContentController;
use App\Http\Controllers\SeoSettingController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\BannerController;


//Agent
use App\Http\Controllers\AgentWalletController;
use App\Http\Controllers\AgentNotificationController;
use App\Http\Controllers\AgentCompleteReportController;
use App\Http\Controllers\AgentCommissionReportController;
use App\Http\Controllers\AgentCancelTicketReportController;
use App\Http\Controllers\AgentWalletReportController;
use App\Http\Controllers\AgentCommissionSlabController;
use App\Http\Controllers\AgentComissionController;
use App\Http\Controllers\AgentFeeController;
use App\Http\Controllers\AgentBookingController;
use App\Http\Controllers\TestEmailController;



Route::post('/emailtest', [TestEmailController::class,'emailtest']);

	Route::middleware('auth:api')->group( function () {
    Route::get('/userAuth', [UserController::class, 'userDetail']);
    Route::post('/busAuth', [BusController::class, 'createBuses']);
});

///////////////////////////Agent Start////////////////////////////////////////
// Agent commission slab
Route::post('/AgentCommission', [AgentComissionController::class, 'createAgentCommission']);
Route::post('/AgentCommissionData', [AgentComissionController::class, 'getAllAgentCommissionData']);
Route::get('/AgentCommission', [AgentComissionController::class, 'getAllAgentCommission']);
Route::put('/AgentCommission/{id}', [AgentComissionController::class, 'updateAgentCommission']);
Route::delete('/AgentCommission/{id}', [AgentComissionController::class, 'deleteAgentCommission']);
Route::get('/AgentCommission/{id}', [AgentComissionController::class, 'getAgentCommission']);


Route::post('/AgentFee', [AgentFeeController::class, 'createAgentFee']);
Route::post('/AgentFeeData', [AgentFeeController::class, 'getAllAgentFeeData']);
Route::get('/AgentFee', [AgentFeeController::class, 'getAllAgentFee']);
Route::put('/AgentFee/{id}', [AgentFeeController::class, 'updateAgentFee']);
Route::delete('/AgentFee/{id}', [AgentFeeController::class, 'deleteAgentFee']);
Route::get('/AgentFee/{id}', [AgentFeeController::class, 'getAgentFee']); 
// Agent wallet
Route::post('/agentWallet',[AgentWalletController::class,'addAgentWallet']);
Route::post('/agentWalletData',[AgentWalletController::class,'getData']);
Route::post('/agentWalletAllData',[AgentWalletController::class,'getAllData']);
Route::put('/changeAgentWalletStatus/{id}',[AgentWalletController::class,'changeStatus']);

//Agent Notification
Route::post('/agentnotification',[AgentNotificationController::class,'getData']);
Route::post('/addPushNotification',[AgentNotificationController::class,'addNotification']);
Route::post('/allPushNotification',[AgentNotificationController::class,'allPushNotification']);
Route::delete('/deletePushNotification/{id}',[AgentNotificationController::class,'deleteNotification']);

// Agent Report 
	
Route::post('/agentcompletereport',[AgentCompleteReportController::class,'getalldata']);
Route::post('/agentcommissionreport',[AgentCommissionReportController::class,'getalldata']);
Route::post('/agentcancelticketreport',[AgentCancelTicketReportController::class,'getalldata']);
Route::post('/agentwalletreport',[AgentWalletReportController::class,'getalldata']);
Route::get('/agentcommissionslab',[AgentCommissionSlabController::class,'agentcommissionslab']);
Route::get('/customercommissionslab',[AgentCommissionSlabController::class,'customercommissionslab']);


////////////////////////////Agent End//////////////////////////////////////////

Route::post('socialmediaData',[SocialMediaController::class,'getAllsocialmedia']);
Route::post('socialmedia',[SocialMediaController::class,'addsocialmedia']);
Route::put('socialmedia/{id}',[SocialMediaController::class,'updatesocialmedia']);
Route::delete('socialmedia/{id}', [SocialMediaController::class, 'deletesocialmedia']);
Route::put('/changeStatussocialmedia/{id}', [SocialMediaController::class, 'changeStatus']);

//Seo Setting
Route::get('/seosetting',[SeoSettingController::class,'getAllseosetting']);
Route::post('seosetting',[SeoSettingController::class,'addseosetting']);
Route::put('seosetting/{id}',[SeoSettingController::class,'updateseosetting']);
Route::put('changeStatusseosetting/{id}',[SeoSettingController::class,'changeStatusseosetting']);
Route::delete('seosetting/{id}', [SeoSettingController::class, 'deleteseosetting']);
Route::post('seosettingData',[SeoSettingController::class,'seosettingData']);


Route::post('/BusData', [BusController::class, 'BusData']);

Route::post('/dashboarddata',[DashboardController::class,'getAll']);
Route::get('/toproutedata',[DashboardController::class,'getRoute']);
Route::get('/operatordata',[DashboardController::class,'getOperator']);
Route::get('/ticketstaticsdata',[DashboardController::class,'getticketstatics']);
Route::get('/bookingbydevicedata',[DashboardController::class,'getbookingbydevice']);
Route::get('/pnrstaticsdata',[DashboardController::class,'getpnrstatics']);

//Route::middleware(['api'])->group(function ($router) {

    //Route::get('me', 'AuthController@me')->middleware('log.route');
    
Route::post('/seatsBus',[BusController::class,'seatsBus']);
// Route::get('/user/getAllUser', [UserController::class, 'getAllUser']);
Route::get('/user', [UserController::class, 'getAllUser'])->middleware('log.route');
Route::post('/user', [UserController::class, 'createUser'])->middleware('log.route');
Route::get('/user/{id}', [UserController::class, 'getUserbyID']);

Route::get('/customer/{id}', [UserController::class, 'getCustomerInformation']);
Route::get('/allAgent', [UserController::class, 'getallAgent']);
//Route::post('/login', [UserController::class, 'Login']);

Route::post('/BusSitting', [BusSittingController::class, 'createBusSitting']);
Route::post('/BusSittingData', [BusSittingController::class, 'BusSittingData']);
Route::get('/BusSitting', [BusSittingController::class, 'getAllBusSitting']);
Route::put('/BusSitting/{id}', [BusSittingController::class, 'updateBusSitting']);
Route::delete('/BusSitting/{id}', [BusSittingController::class, 'deleteBusSitting']);
Route::get('/BusSitting/{id}', [BusSittingController::class, 'getBusSitting']);
Route::post('/BusSittingDT', [BusSittingController::class, 'getBusSittingDT']);
Route::put('/changeStatusBusSitting/{id}', [BusSittingController::class, 'changeStatus']);

//Booking Seized

Route::get('/bookingseized',[BookingSeizedController::class,'getAllseized']);
Route::post('/bookingseized',[BookingSeizedController::class,'updateSeized']);
Route::post('/bookingseizedData',[BookingSeizedController::class,'bookingseizedData']);
Route::put('/changebookingseizedStatus/{id}', [BookingSeizedController::class, 'changeStatus']);



///SEATOPEN///

Route::get('/seatopen',[SeatOpenController::class,'getAllseatopen']);
Route::post('/seatopen',[SeatOpenController::class,'addseatopen']);
Route::put('/seatopen/{id}',[SeatOpenController::class,'updateseatopen']);
Route::delete('/seatopen/{id}', [SeatOpenController::class, 'deleteseatopen']);
Route::post('/getseatopenDT', [SeatOpenController::class, 'getseatopenDT']);
Route::post('/seatopenData', [SeatOpenController::class, 'seatopenData']);
Route::put('/changeseatopenStatus/{id}', [SeatOpenController::class, 'changeStatus']);

////////////SEAT BLOCK//////
Route::get('/seatblock',[SeatBlockController::class,'getAllseatblock']);
Route::post('/seatblock',[SeatBlockController::class,'addseatblock']);
Route::put('/seatblock/{id}',[SeatBlockController::class,'updateseatblock']);
Route::delete('/seatblock/{id}', [SeatBlockController::class, 'deleteseatblock']);
Route::post('/getseatblockDT', [SeatBlockController::class, 'getseatblockDT']);
Route::post('/seatblockData', [SeatBlockController::class, 'seatblockData']);
Route::put('/changeseatblockStatus/{id}', [SeatBlockController::class, 'changeStatus']);



Route::post('/BusType', [BusTypeController::class, 'createBusType']);
Route::post('/BusTypeData', [BusTypeController::class, 'getAllBusTypeData']);
Route::get('/BusType', [BusTypeController::class, 'getAllBusType']);
Route::put('/BusType/{id}', [BusTypeController::class, 'updateBusType']);
Route::delete('/BusType/{id}', [BusTypeController::class, 'deleteBusType']);
Route::get('/BusType/{id}', [BusTypeController::class, 'getBusType']);
Route::post('/BusTypeDT', [BusTypeController::class, 'getBusTypeDT']);
Route::put('/changeStatusBusType/{id}', [BusTypeController::class, 'changeStatus']);


Route::post('/Amenities', [AmenitiesController::class, 'createAmenities']);
Route::post('/AmenitiesData', [AmenitiesController::class, 'AmenitiesData']);
Route::post('/AmenitiesDT', [AmenitiesController::class, 'getAllAmenitiesDT']);
Route::get('/Amenities', [AmenitiesController::class, 'getAll']);
Route::post('/AmenitiesUpdate', [AmenitiesController::class, 'updateAmenities']);
//Route::put('/Amenities/{id}', [AmenitiesController::class, 'updateAmenities']);
Route::delete('/Amenities/{id}', [AmenitiesController::class, 'deleteAmenities']);
Route::get('/Amenities/{id}', [AmenitiesController::class, 'getAmenities']);
Route::put('/changeStatusAmenities/{id}', [AmenitiesController::class, 'changeStatus']);


Route::post('/Safety', [SafetyController::class, 'save']);
Route::post('/getAllData', [SafetyController::class, 'getAllData']);
Route::post('/SafetyDT', [SafetyController::class, 'getSafetyDT']);
Route::get('/Safety', [SafetyController::class, 'getAll']);
Route::put('/Safety/{id}', [SafetyController::class, 'update']);
Route::delete('/Safety/{id}', [SafetyController::class, 'delete']);
Route::get('/Safety/{id}', [SafetyController::class, 'getById']);
Route::get('/BusSafety/{id}', [SafetyController::class, 'getByBusId']);
Route::put('/changeStatusSafety/{id}', [SafetyController::class, 'changeStatus']);



Route::post('/appdownload', [AppDownloadController::class, 'createAppDownload']);
Route::get('/appdownload', [AppDownloadController::class, 'getAllAppDownload']);
Route::put('/appdownload/{id}', [AppDownloadController::class, 'updateAppDownload']);
Route::delete('/appdownload/{id}', [AppDownloadController::class, 'deleteAppDownload']);
Route::get('/appdownload/{id}', [AppDownloadController::class, 'getAppDownload']);

Route::get('/BusSeatLayoutRecord/{id}', [BusSeatLayoutController::class, 'getSeatLayoutRecord']);
Route::post('/BusSeatLayout', [BusSeatLayoutController::class, 'save']);
Route::post('/BusSeatLayoutData', [BusSeatLayoutController::class, 'BusSeatLayoutData']);
Route::get('/BusSeatLayout', [BusSeatLayoutController::class, 'getAll']);
Route::put('BusSeatLayout/{id}', [BusSeatLayoutController::class, 'update']);
Route::delete('/BusSeatLayout/{id}', [BusSeatLayoutController::class, 'deleteById']);
Route::get('/BusSeatLayout/{id}', [BusSeatLayoutController::class, 'getById']);
Route::get('/getSeatLayout/{bus_id}', [BusSeatLayoutController::class, 'getSeatLayoutByBusId']);
Route::post('/BusSeatLayoutDT', [BusSeatLayoutController::class, 'getBusSeatLayoutDT']);
Route::put('/changeStatusBusSeatLayout/{id}', [BusSeatLayoutController::class, 'changeStatus']);
Route::get('/BusSeatLayoutRC/{id}/{type}', [BusSeatLayoutController::class, 'getRowCol']);

Route::post('/slider', [SliderController::class, 'createSlider']);
Route::get('/slider', [SliderController::class, 'getAllSlider']);
Route::put('/slider/{id}', [SliderController::class, 'updateSlider']);
Route::delete('/slider/{id}', [SliderController::class, 'deleteSlider']);
Route::get('/slider/{id}', [SliderController::class, 'getSlider']);
Route::post('sliderDataTable',[SliderController::class,'getData']);
Route::put('/changeStatusSlider/{id}', [SliderController::class, 'changeStatus']);

Route::post('/banner', [BannerController::class, 'createBanner']);
Route::get('/banner', [BannerController::class, 'getAllBanner']);
Route::put('/banner/{id}', [BannerController::class, 'updateBanner']);
Route::delete('/banner/{id}', [BannerController::class, 'deleteBanner']);
Route::get('/banner/{id}', [BannerController::class, 'getBanner']);
Route::post('bannerDataTable',[BannerController::class,'getData']);
Route::put('/changeStatusBanner/{id}', [BannerController::class, 'changeStatus']);

Route::post('/test/Dummy/{dummy}', [DummyController::class, 'save']);

Route::get('api/test/{dummy}', function (App\Dummy $dummy) {
//calling the UserController@functionUser
// DummyController@save
});

Route::post('/customerQuery', [CustomerQueryController::class, 'createCustomerQuery']);
Route::get('/customerQuery', [CustomerQueryController::class, 'getAllCustomerQuery']);
Route::put('/customerQuery/{id}', [CustomerQueryController::class, 'updateCustomerQuery']);
Route::delete('/customerQuery/{id}', [CustomerQueryController::class, 'deleteCustomerQuery']);
Route::get('/customerQuery/{id}', [CustomerQueryController::class, 'getCustomerQuery']);

Route::post('/busGallery', [BusGalleryController::class, 'addBusGallery']);
Route::post('/busGalleryData', [BusGalleryController::class, 'viewBusGallery']);

Route::get('/busGallery', [BusGalleryController::class, 'getAllBusGallery']);
Route::delete('/busGallery/{id}', [BusGalleryController::class, 'deleteBusGallery']);
Route::get('/busGallery/{id}', [BusGalleryController::class, 'getBusGallery']);
Route::get('/busGalleryByBusId/{bid}', [BusGalleryController::class, 'getBusGalleryBus']);


Route::get('/review', [ReviewController::class, 'getAll']);
Route::post('/review', [ReviewController::class, 'getData']);
Route::put('/review/{id}', [ReviewController::class, 'changeStatus']);
Route::delete('/review/{id}', [ReviewController::class, 'deleteData']);


////SeatOpenReport/////
Route::post('seatopenreport',[SeatOpenReportController::class,'getData']);
////SeatBlockReport/////
Route::post('seatblockreport',[SeatBlockReportController::class,'getData']);
///ExtraSeatOpenReport////
Route::post('extraseatopenreport',[ExtraSeatOpenReportController::class,'getAllextraseatopen']);
///CompleteReport////
Route::post('completereport',[CompleteReportController::class,'getData']);
///FailledTransactionReport////
Route::post('failledtransactionreport',[FailledTransactionReportController::class,'getData']);
///BusCancellationReport////
Route::post('buscancellationreport',[BusCancellationReportController::class,'getData']);
////OwnerPaymentReport/////
Route::post('ownerpaymentreport',[OwnerPaymentReportController::class,'getData']);
//CancelTicketReport
Route::post('cancelticketreport',[CancelTicketReportController::class,'getData']);
//ContactReport
Route::post('contactreport',[ContactReportController::class,'getData']);	
Route::delete('contactreport/{id}',[ContactReportController::class,'deleteData']);


// ClearTransactionReport //
Route::get('cleartransactionreport',[ClearTransactionReportController::class,'getAll']);
//CouponUsedUserReportController//
Route::post('couponuseduserreport',[CouponUsedUserReportController::class,'getData']);




//Page Content
Route::get('/pagecontent',[PageContentController::class,'getAllpagecontent']);
Route::post('pagecontentData',[PageContentController::class,'getAllData']);
Route::post('pagecontent',[PageContentController::class,'addpagecontent']);
Route::put('pagecontent/{id}',[PageContentController::class,'updatepagecontent']);
Route::delete('pagecontent/{id}', [PageContentController::class, 'deletepagecontent']);


//user Content
// Route::get('/user',[UserContentController::class,'getAlluser']);
Route::post('userContentData',[UserContentController::class,'getAllData']);
Route::post('userContent',[UserContentController::class,'adduser']);
Route::put('userContent/{id}',[UserContentController::class,'updateuser']);
Route::delete('userContent/{id}', [UserContentController::class, 'deleteuser']);
Route::put('changePassword/{id}', [UserContentController::class, 'changePassword']);
Route::put('/changeuserStatus/{id}', [UserContentController::class, 'changeStatus']);

//Testimonial

Route::post('/gettestimonial',[TestimonialController::class,'getAlltestimonial']);
Route::post('testimonial',[TestimonialController::class,'addtestimonial']);
Route::put('testimonial/{id}',[TestimonialController::class,'updatetestimonial']);
Route::delete('testimonial/{id}', [TestimonialController::class, 'deletetestimonial']);
Route::put('/changetestimonialStatus/{id}', [TestimonialController::class, 'changeStatus']);








Route::get('/GetLocations/{search_query}', [LocationController::class, 'GetLocations']);
Route::get('/locations', [locationController::class, 'getAllLocations']);
Route::get('/locations/{id}', [locationController::class, 'getlocationbyID']);
Route::post('/locations/', [locationController::class, 'createLocation'])->middleware('log.route');
Route::put('locations/{id}', [locationController::class, 'updateLocation']);
Route::put('locationcodes/{id}', [locationController::class, 'updateLocationcode']);
Route::delete('/locations/{id}', [locationController::class, 'deletelocation']);
Route::post('/LocationFilter', [locationController::class, 'filterLocation']);
Route::post('/locationsDT', [locationController::class, 'getLocationDT']);
Route::post('/addlocation', [locationController::class, 'addLocation']);
Route::put('editlocation/{id}', [locationController::class, 'editLocation']);
Route::put('/changeStatusLocations/{id}', [locationController::class, 'changeStatus']);

Route::get('/appversion', [AppVersionController::class, 'getAllAppVersion']);
Route::post('/appversion', [AppVersionController::class, 'createAppVersion']);
Route::put('/appversion/{id}', [AppVersionController::class, 'updateAppVersion']);
Route::delete('/appversion/{id}', [AppVersionController::class, 'deleteAppVersion']);
Route::get('/appversion/{id}', [AppVersionController::class, 'getAppVersion']);

Route::get('/sitemaster', [SiteMasterController::class, 'getAllSiteMaster']);
Route::post('/sitemaster', [SiteMasterController::class, 'createSiteMaster']);
Route::put('/sitemaster/{id}', [SiteMasterController::class, 'updateSiteMaster']);
Route::delete('/sitemaster/{id}', [SiteMasterController::class, 'deleteSiteMaster']);
Route::get('/sitemaster/{id}', [SiteMasterController::class, 'getSiteMaster']);

Route::get('/boarding', [BoardingDropingController::class, 'getAllBoardingDroping']);
Route::post('/boarding', [BoardingDropingController::class, 'createBoardingDroping']);
Route::put('/boarding/{id}', [BoardingDropingController::class, 'updateBoardingDroping']);
Route::delete('/boarding/{id}', [BoardingDropingController::class, 'deleteBoardingDroping']);
Route::get('/boarding/{id}', [BoardingDropingController::class, 'getBoardingDroping']);
Route::post('/boardingDT', [BoardingDropingController::class, 'getBoardingDropingDT']);
Route::post('/boardingDroping', [BoardingDropingController::class, 'createBoardingDroping']);
Route::get('/boardingLocationId/{id}', [BoardingDropingController::class, 'getBoardingDropingbyLoacationId']);
Route::put('/changeStatusBoardingDroping/{id}', [BoardingDropingController::class, 'changeStatus']);


Route::get('/custompages', [CustomPagesController::class, 'getAllcustomPages']);
Route::post('/custompages', [CustomPagesController::class, 'createcustomPages']);
Route::put('/custompages/{id}', [CustomPagesController::class, 'updatecustomPages']);
Route::delete('/custompages/{id}', [CustomPagesController::class, 'deletecustomPages']);
Route::get('/custompages/{id}', [CustomPagesController::class, 'getcustomPages']);

Route::get('/reasons', [ReasonController::class, 'getAllReason']);
Route::post('/reasons', [ReasonController::class, 'createReason']);
Route::put('/reasons/{id}', [ReasonController::class, 'updateReason']);
Route::delete('/reasons/{id}', [ReasonController::class, 'deleteReason']);
Route::get('/reasons/{id}', [ReasonController::class, 'getReason']);

Route::get('/tickets', [TicketCancelationController::class, 'getAllTicketCancelations']);
Route::post('/tickets', [TicketCancelationController::class, 'createTicketCancelations']);
//Route::put('/tickets/{id}', [TicketCancelationController::class, 'updateReason']);
//Route::delete('/tickets/{id}', [TicketCancelationController::class, 'deleteReason']);
Route::get('/tickets/{id}', [TicketCancelationController::class, 'getTicketCancelationsbyID']);

Route::get('/busStoppageAdditionalFare', [BusStoppageAdditionalFareController::class, 'getAllBusStoppageAdditionalFare']);
Route::post('/busStoppageAdditionalFare', [BusStoppageAdditionalFareController::class, 'createBusStoppageAdditionalFare']);
Route::put('/busStoppageAdditionalFare/{id}', [BusStoppageAdditionalFareController::class, 'updateBusStoppageAdditionalFare']);
Route::delete('/busStoppageAdditionalFare/{id}', [BusStoppageAdditionalFareController::class, 'deleteBusStoppageAdditionalFare']);
Route::get('/busStoppageAdditionalFare/{id}', [BusStoppageAdditionalFareController::class, 'getBusStoppageAdditionalFare']);



Route::get('/cqc', [CustomerQueryCategoryController::class, 'getAllCustomerQueryCategory']);
Route::post('/cqc', [CustomerQueryCategoryController::class, 'createCustomerQueryCategory']);
Route::get('/cqc/{id}', [CustomerQueryCategoryController::class, 'getCustomerQueryCategorybyID']);



Route::get('/bus', [BusController::class, 'getAll']);
Route::get('/locationBus/{source_id}/{destination_id}', [BusController::class, 'getLocationBus']);

Route::post('/bus', [BusController::class, 'save']);
Route::put('/bus/{id}', [BusController::class, 'update']);
Route::put('/busContactInfo/{id}', [BusController::class, 'busContactInfo']);

Route::delete('/bus/{id}', [BusController::class, 'deleteById']);
Route::get('/bus/{id}', [BusController::class, 'getById']);
Route::get('/operatorBus/{id}', [BusController::class, 'getByOperaor']);
Route::get('/getBusListing', [BusController::class, 'getAllBusListing']);
Route::get('/getValidateBookingData', [BusController::class, 'validateBookingData']);
Route::post('/busDT', [BusController::class, 'getBusDT']);
Route::put('/changeStatusBus/{id}', [BusController::class, 'changeStatus']);
Route::get('/getBusbyBuschedule/{id}', [BusController::class, 'getBusbyBuschedule']);
Route::get('/getBusScheduleEntryDates/{busId}', [BusController::class, 'getBusScheduleEntryDates']);
Route::post('/getBusScheduleEntryDatesFilter', [BusController::class, 'getBusScheduleEntryDatesFilter']);

Route::get('/BusListingPageInformation', [BusController::class, 'getAllBusListingPageInformation']);
Route::get('/BusListingReplica', [BusController::class, 'getAllBusListingReplica']);
//Route::get('/BusDT', [BusController::class, 'getBusDT']);

Route::put('/busupdatesequence/{id}', [BusController::class, 'updateBusSequence']);

Route::get('/busAmenities', [BusAmenitiesController::class, 'getAllBusAmenities']);
Route::post('/busAmenities', [BusAmenitiesController::class, 'createBusAmenities']);
Route::put('/busAmenities/{id}', [BusAmenitiesController::class, 'updateBusAmenities']);
Route::delete('/busAmenities/{id}', [BusAmenitiesController::class, 'deleteBusAmenities']);
Route::get('/busAmenities/{id}', [BusAmenitiesController::class, 'getBusAmenities']);
Route::post('/busAmenitiesDT', [BusAmenitiesController::class, 'getBusAmenitiesDT']);

Route::get('/cityClosing', [CityClosingController::class, 'getAllCityClosing']);
Route::post('/cityClosing', [CityClosingController::class, 'createCityClosing']);
Route::put('/cityClosing/{id}', [CityClosingController::class, 'updateCityClosing']);
Route::delete('/cityClosing/{id}', [CityClosingController::class, 'deleteCityClosing']);
Route::get('/cityClosing/{id}', [CityClosingController::class, 'getCityClosing']);

Route::get('/cityClosingExtended', [CityClosingExtendedController::class, 'getAllCityClosingExtended']);
Route::post('/cityClosingExtended', [CityClosingExtendedController::class, 'createCityClosingExtended']);
Route::put('/cityClosingExtended/{id}', [CityClosingExtendedController::class, 'updateCityClosingExtended']);
Route::delete('/cityClosingExtended/{id}', [CityClosingExtendedController::class, 'deleteCityClosingExtended']);
Route::get('/cityClosingExtended/{id}', [CityClosingExtendedController::class, 'getCityClosingExtended']);

Route::get('/busContactsByBusId/{id}', [BusContactsController::class, 'busContactsByBusId']);
Route::get('/busContacts', [BusContactsController::class, 'getAllBusContacts']);
Route::post('/busContacts', [BusContactsController::class, 'createBusContacts']);
Route::put('/busContacts/{id}', [BusContactsController::class, 'updateBusContacts']);
Route::delete('/busContacts/{id}', [BusContactsController::class, 'deleteBusContacts']);
Route::get('/busContacts/{id}', [BusContactsController::class, 'getBusContacts']);

Route::get('/busExtraFare', [BusExtraFareController::class, 'getAllBusExtraFare']);
Route::post('/busExtraFare', [BusExtraFareController::class, 'createBusExtraFare']);
Route::put('/busExtraFare/{id}', [BusExtraFareController::class, 'updateBusExtraFare']);
Route::delete('/busExtraFare/{id}', [BusExtraFareController::class, 'deleteBusExtraFare']);
Route::get('/busExtraFare/{id}', [BusExtraFareController::class, 'getBusExtraFare']);

Route::get('/busStoppage', [BusStoppageController::class, 'getAllBusStoppage']);
Route::post('/busStoppage', [BusStoppageController::class, 'createBusStoppage']);
Route::put('/busStoppage/{id}', [BusStoppageController::class, 'updateBusStoppage']);
Route::delete('/busStoppage/{id}', [BusStoppageController::class, 'deleteBusStoppage']);
Route::get('/busStoppage/{id}', [BusStoppageController::class, 'getBusStoppage']);
Route::get('/busStoppagebyBusId/{busid}', [BusStoppageController::class, 'getBusStoppagebyBusId']);
Route::get('/busStoppageByOperator/{id}', [BusStoppageController::class, 'getBusByOperator']);
Route::get('/busStoppagebyRoutes/{sourceId}/{destinationId}', [BusStoppageController::class, 'getBusStoppagebyRoutes']);

Route::get('/busSeats', [BusSeatsController::class, 'getAllBusSeats']);
Route::post('/busSeats', [BusSeatsController::class, 'createBusSeats']);
Route::put('/busSeats/{id}', [BusSeatsController::class, 'updateBusSeats']);
Route::delete('/busSeats/{id}', [BusSeatsController::class, 'deleteBusSeats']);
Route::get('/busSeats/{id}', [BusSeatsController::class, 'getBusSeats']);
Route::get('/busSeatsByBus/{id}', [BusSeatsController::class, 'getByBusId']);
Route::put('/updateBusSeatsExtras/{id}', [BusSeatsController::class, 'updateBusSeatsExtras']);

Route::get('/busSeatsFare/{id}', [BusSeatsController::class, 'getAllBusSeatsFare']);
Route::put('/updateNewFare',[BusSeatsController::class,'updateNewFare']);

Route::get('/busSeatsExtra', [BusSeatsExtraController::class, 'getAllBusSeatsExtra']);
Route::post('/busSeatsExtra', [BusSeatsExtraController::class, 'createBusSeatsExtra']);
Route::put('/busSeatsExtra/{id}', [BusSeatsExtraController::class, 'updateBusSeatsExtra']);
Route::delete('/busSeatsExtra/{id}', [BusSeatsExtraController::class, 'deleteBusSeatsExtra']);
Route::get('/busSeatsExtra/{id}', [BusSeatsExtraController::class, 'getBusSeatsExtra']);

Route::get('/busStoppageTiming', [BusStoppageTimingController::class, 'getAllBusStoppageTiming']);
Route::get('/busStoppageTimingbyBusId/{busid}', [BusStoppageTimingController::class, 'busStoppageTimingbyBusId']);
Route::post('/busStoppageTiming', [BusStoppageTimingController::class, 'createBusStoppageTiming']);
Route::put('/busStoppageTiming/{id}', [BusStoppageTimingController::class, 'updateBusStoppageTiming']);
Route::delete('/busStoppageTiming/{id}', [BusStoppageTimingController::class, 'deleteBusStoppageTiming']);
Route::get('/busStoppageTiming/{id}', [BusStoppageTimingController::class, 'getBusStoppageTiming']);

Route::get('/busCancelled', [BusCancelledController::class, 'getAllBusCancelled']);
Route::post('/busCancelled', [BusCancelledController::class, 'createBusCancelled']);
Route::put('/busCancelled/{id}', [BusCancelledController::class, 'updateBusCancelled']);
Route::delete('/busCancelled/{id}', [BusCancelledController::class, 'deleteBusCancelled']);
Route::get('/busCancelled/{id}', [BusCancelledController::class, 'getBusCancelled']);
Route::post('/busCancelledDT', [BusCancelledController::class, 'getBusCancelledDT']);
Route::put('/changeStatusBusCancelled/{id}', [BusCancelledController::class, 'changeStatus']);

Route::post('/busScheduleDT', [BusScheduleController::class, 'getAllBusScheduleDT']);
Route::post('/busSchedulerData', [BusScheduleController::class, 'busSchedulerData']);
Route::get('/busSchedule', [BusScheduleController::class, 'getAllBusSchedule']);
Route::post('/busSchedule', [BusScheduleController::class, 'createBusSchedule']);
Route::put('/busSchedule/{id}', [BusScheduleController::class, 'updateBusSchedule']);
Route::delete('/busSchedule/{id}', [BusScheduleController::class, 'deleteBusSchedule']);
Route::get('/busSchedule/{id}', [BusScheduleController::class, 'getBusSchedule']);
Route::put('/changeStatusBusSchedule/{id}', [BusScheduleController::class, 'changeStatus']);

Route::get('/busSlots', [BusSlotsController::class, 'getAllBusSlots']);
Route::post('/busSlots', [BusSlotsController::class, 'createBusSlots']);
Route::put('/busSlots/{id}', [BusSlotsController::class, 'updateBusSlots']);
Route::delete('/busSlots/{id}', [BusSlotsController::class, 'deleteBusSlots']);
Route::get('/busSlots/{id}', [BusSlotsController::class, 'getBusBusSlots']);

Route::get('/coupon', [CouponController::class, 'getAllCoupon']);
Route::post('/coupon', [CouponController::class, 'createCoupon']);
Route::put('/coupon/{id}', [CouponController::class, 'updateCoupon']);
Route::delete('/coupon/{id}', [CouponController::class, 'deleteCoupon']);
Route::get('/coupon/{id}', [CouponController::class, 'getBusCoupon']);
Route::post('/getData', [CouponController::class, 'getData']);

//COUPON FOR BUS, ROUTE AND OPERATOR 
Route::post('/couponBus', [CouponController::class, 'createCouponBus']);
Route::post('/couponRoute', [CouponController::class, 'createCouponRoute']);
Route::post('/couponOperator', [CouponController::class, 'createCouponOperator']);


// Route::get('/couponAssignedBus', [CouponAssignedBusController::class, 'getAllCouponAssignedBus']);
// Route::post('/couponAssignedBus', [CouponAssignedBusController::class, 'createCouponAssignedBus']);
// Route::put('/couponAssignedBus/{id}', [CouponAssignedBusController::class, 'updateCouponAssignedBus']);
// Route::delete('/couponAssignedBus/{id}', [CouponAssignedBusController::class, 'deleteCouponAssignedBus']);
// Route::get('/couponAssignedBus/{id}', [CouponAssignedBusController::class, 'getCouponAssignedBus']);

Route::get('/preBooking', [PreBookingController::class, 'getAllPreBooking']);
Route::post('/preBooking', [PreBookingController::class, 'createPreBooking']);
Route::put('/preBooking/{id}', [PreBookingController::class, 'updatePreBooking']);
Route::delete('/preBooking/{id}', [PreBookingController::class, 'deletePreBooking']);
Route::get('/preBooking/{id}', [PreBookingController::class, 'getPreBooking']);

Route::post('/preBookingPhaseOne', [PreBookingController::class, 'createPreBookingPhaseOne']);
Route::put('/preBookingPhaseTwo/{transaction_id}', [PreBookingController::class, 'updatePreBookingPhaseTwo']);

Route::get('/preBookingDetail', [PreBookingDetailController::class, 'getAllPreBookingDetail']);
Route::post('/preBookingDetail', [PreBookingDetailController::class, 'createPreBookingDetail']);
Route::put('/preBookingDetail/{id}', [PreBookingDetailController::class, 'updatePreBookingDetail']);
Route::delete('/preBookingDetail/{id}', [PreBookingDetailController::class, 'deletePreBookingDetail']);
Route::get('/preBookingDetail/{id}', [PreBookingDetailController::class, 'getPreBookingDetail']);

Route::get('/booking', [BookingController::class, 'getAllBooking']);
Route::post('/booking', [BookingController::class, 'createBooking']);
Route::put('/booking/{id}', [BookingController::class, 'updateBooking']);
Route::delete('/booking/{id}', [BookingController::class, 'deleteBooking']);
Route::get('/booking/{id}', [BookingController::class, 'getBooking']);

Route::get('/bookingDetail', [BookingDetailController::class, 'getAllBookingDetail']);
Route::post('/bookingDetail', [BookingDetailController::class, 'createBookingDetail']);
Route::put('/bookingDetail/{id}', [BookingDetailController::class, 'updateBookingDetail']);
Route::delete('/bookingDetail/{id}', [BookingDetailController::class, 'deleteBookingDetail']);
Route::get('/bookingDetail/{id}', [BookingDetailController::class, 'getBookingDetail']);


Route::post('/cancellationslabsDT', [CancellationSlabController::class, 'getCancellationSlabDT']);
Route::post('/cancellationslab', [CancellationSlabController::class, 'createCancellationSlab']);

Route::post('/cancellationslabData', [CancellationSlabController::class, 'cancellationslabData']);
Route::put('/cancellationslab/{id}', [CancellationSlabController::class, 'updateCancellationSlab']);
Route::get('/cancellationslabs', [CancellationSlabController::class, 'getAllCancellationSlab']);
Route::delete('/cancellationslab/{id}', [CancellationSlabController::class, 'deleteCancellationSlab']);
Route::get('/cancellationslab/{id}', [CancellationSlabController::class, 'getCancellationSlab']);
Route::put('/changeStatusCancellationslab/{id}', [CancellationSlabController::class, 'changeStatus']);

Route::post('/closinghourdt', [BusClosingHoursController::class, 'getAllClosingHoursDataTable']);
Route::post('/closinghour', [BusClosingHoursController::class, 'createClosingHours']);
Route::put('/closinghour/{id}', [BusClosingHoursController::class, 'updateClosingHours']);
Route::get('/closinghour', [BusClosingHoursController::class, 'getAllClosingHours']);
Route::delete('/closinghour/{id}', [BusClosingHoursController::class, 'deleteClosingHours']);
Route::get('/closinghour/{id}', [BusClosingHoursController::class, 'getClosingHours']);

Route::post('/extclosinghourdt', [ExtendedBusClosingHoursController::class, 'getAllExtendedClosingHoursDataTable']);
Route::post('/extclosinghour', [ExtendedBusClosingHoursController::class, 'createExtendedClosingHours']);
Route::put('/extclosinghour/{id}', [ExtendedBusClosingHoursController::class, 'updateClosingHours']);
Route::get('/extclosinghour', [ExtendedBusClosingHoursController::class, 'getAllExtendedClosingHours']);
Route::delete('/extclosinghour/{id}', [ExtendedBusClosingHoursController::class, 'deleteExtendedClosingHours']);
Route::get('/extclosinghour/{id}', [ExtendedBusClosingHoursController::class, 'getExtendedClosingHours']);



Route::post('/busoperatorsDT', [BusOperatorController::class, 'getAllBusOperatorsDT']);
Route::post('/busoperator', [BusOperatorController::class, 'createBusOperator']);
Route::post('/BusbyOperatorData', [BusOperatorController::class, 'BusbyOperatorData']);
Route::put('/busoperator/{id}', [BusOperatorController::class, 'updateBusOperator']);
Route::get('/busoperator', [BusOperatorController::class, 'getAllBusOperators']);
Route::delete('/busoperator/{id}', [BusOperatorController::class, 'deleteBusOperator']);
Route::get('/busoperator/{id}', [BusOperatorController::class, 'getBusOperator']);
Route::put('/changeStatusBusOperator/{id}', [BusOperatorController::class, 'changeStatus']);
Route::get('/getBusbyOperator/{id}', [BusOperatorController::class, 'getBusbyOperator']);
Route::post('/getOperatorEmail', [BusOperatorController::class, 'getOperatorEmail']);
Route::post('/getOperatorPhone', [BusOperatorController::class, 'getOperatorPhone']);

Route::get('/busSpecialFare', [BusSpecialFareController::class, 'getAllBusSpecialFare']);
Route::post('/busSpecialFare', [BusSpecialFareController::class, 'createBusSpecialFare']);
Route::post('/busSpecialFareData', [BusSpecialFareController::class, 'busSpecialFareData']);
Route::put('/busSpecialFare/{id}', [BusSpecialFareController::class, 'updateBusSpecialFare']);
Route::delete('/busSpecialFare/{id}', [BusSpecialFareController::class, 'deleteBusSpecialFare']);
Route::get('/busSpecialFare/{id}', [BusSpecialFareController::class, 'getBusSpecialFare']);
Route::post('/busSpecialFareDT', [BusSpecialFareController::class, 'getBusSpecialFareDT']);
Route::put('/changeStatusBusSpecialFare/{id}', [BusSpecialFareController::class, 'changeStatus']);
Route::get('/relations/{id}', [BusSpecialFareController::class, 'getPivotData']);

Route::get('/busOwnerFare', [BusOwnerFareController::class, 'getAllBusOwnerFare']);
Route::post('/busOwnerFare', [BusOwnerFareController::class, 'createBusOwnerFare']);
Route::post('/busOwnerFareData', [BusOwnerFareController::class, 'busOwnerFareData']);
Route::put('/busOwnerFare/{id}', [BusOwnerFareController::class, 'updateBusOwnerFare']);
Route::delete('/busOwnerFare/{id}', [BusOwnerFareController::class, 'deleteBusOwnerFare']);
Route::get('/busOwnerFare/{id}', [BusOwnerFareController::class, 'getBusOwnerFare']);
Route::post('/busOwnerFareDT', [BusOwnerFareController::class, 'getBusOwnerFareDT']);
Route::put('/changeStatusbusOwnerFare/{id}', [BusOwnerFareController::class, 'changeStatus']);
//Route::get('/relations/{id}', [BusOwnerFareController::class, 'getPivotData']);


Route::get('/festivalFare', [FestivalFareController::class, 'getAllFestivalFare']);
Route::post('/festivalFare', [FestivalFareController::class, 'createFestivalFare']);
Route::post('/festivalFareData', [FestivalFareController::class, 'festivalFareData']);
Route::put('/festivalFare/{id}', [FestivalFareController::class, 'updateFestivalFare']);
Route::delete('/festivalFare/{id}', [FestivalFareController::class, 'deleteFestivalFare']);
Route::get('/festivalFare/{id}', [FestivalFareController::class, 'getFestivalFare']);
Route::post('/festivalFareDT', [FestivalFareController::class, 'getFestivalFareDT']);
Route::put('/changeStatusfestivalFare/{id}', [FestivalFareController::class, 'changeStatus']);




Route::get('/listing', [ListingController::class, 'getAllListing']);


Route::get('/article', [ArticleController::class, 'getArticle']);
Route::get('/comments', [ArticleController::class, 'getComments']);
Route::get('/articles', [ArticleController::class, 'getAll']);
Route::get('/getBusFareANDBus', [ArticleController::class, 'getBusFareANDBus']);
Route::get('/paginated', [ArticleController::class, 'paginated']);
Route::get('/getTestData', [ArticleController::class, 'getTestData']);
Route::get('/manytomany', [ArticleController::class, 'manytomany']);
Route::get('/insertManyTomany', [ArticleController::class, 'insertManyTomany']);
Route::post('/insertOneToMany', [ArticleController::class, 'insertOneToMany']);
Route::get('/cancelbusDT', [ArticleController::class, 'cancelbusDT']);
Route::get('/operators', [ArticleController::class, 'getOperatorData']);
Route::post('/saveBuscancel', [ArticleController::class, 'saveBuscancel']);
Route::post('/saveBusSchedule', [ArticleController::class, 'saveBusSchedule']);
Route::post('/testMe', [ArticleController::class, 'testMe']);
Route::put('/updateOneToMany', [ArticleController::class, 'updateOneToMany']);


Route::get('/odbusCharges',[OdbusChargesController::class,'getAll']);
Route::get('/odbusCharges/{id}',[OdbusChargesController::class,'getById']);
Route::post('/odbusCharges', [OdbusChargesController::class, 'save']);
Route::put('/odbusCharges/{id}',[OdbusChargesController::class,'update']);
Route::delete('/odbusCharges/{id}', [OdbusChargesController::class, 'delete']);
Route::post('odbusChargesData',[OdbusChargesController::class,'getData']);
Route::put('/changeStatus/{id}', [OdbusChargesController::class, 'changeStatus']);

Route::post('/offersDT', [OffersController::class, 'getOffersDT']);

////////////Owner Payment//////
Route::get('/ownerpayment',[OwnerPaymentController::class,'getAllOwnerPayment']);
Route::post('/ownerpayment',[OwnerPaymentController::class,'createOwnerPayment']);
Route::post('/getownerpaymentDT', [OwnerPaymentController::class, 'getOwnerPaymentDT']);




Route::post('/locationsData', [locationController::class, 'locationsData']);
Route::post('/boardingData', [BoardingDropingController::class, 'boardingData']);
Route::post('/busSeatsFareData', [BusController::class, 'busSeatsFareData']);
Route::post('/busupdatesequenceData', [BusController::class, 'busupdatesequenceData']);
Route::post('/busCancelledData', [BusCancelledController::class, 'busCancelledData']);
Route::post('/ownerpaymentData', [OwnerPaymentController::class, 'ownerpaymentData']);


Route::post('/Register', [UserController::class, 'Register']);
Route::post('/VerifyOtp', [UserController::class, 'verifyOtp']);
Route::post('/Login', [UserController::class, 'login']);
Route::get('/GetAllRoles', [UserController::class, 'getRoles']);
Route::post('/AgentRgestration', [UserController::class, 'agentRegister']);

Route::post('/AgentBooking', [AgentBookingController::class, 'agentBooking']);


Route::post('/login', [UserController::class, 'login']);


//});
Route::post('/Agent', [AgentController::class, 'createAgent']);
Route::post('/AgentData', [AgentController::class, 'getAllAgentData']);
Route::get('/Agent', [AgentController::class, 'getAllAgent']);
Route::put('/Agent/{id}', [AgentController::class, 'updateAgent']);
Route::delete('/Agent/{id}', [AgentController::class, 'deleteAgent']);
Route::get('/Agent/{id}', [AgentController::class, 'getAgent']);