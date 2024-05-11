
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="{{url('public/images/favicon.png')}}" rel="icon" />
<title>ODBUS</title>
<meta name="author" content="harnishdesign.net">


<!-- Stylesheet
======================= -->
<link rel="stylesheet" type="text/css" href="{{url('public/css/bootstrap.min.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{url('public/css/stylesheet.css')}}"/>
</head>
<body>
<!-- Container -->
<div class="container-fluid invoice-container">
  <!-- Main Content -->
  <header class="mb-2">
    <div class="row align-items-center gy-3">
      <div class="col-sm-7 text-center text-sm-start"> <img id="logo" src="{{url('public/images/logo.png')}}" title="ODBUS" alt="ODBUS"> </div>
      <div class="col-sm-5 text-center text-sm-end">
        <h4 class="mb-0">Invoice</h4>
        <p class="mb-0">Invoice Number - #{{$gst_name}}</p>
      </div>
    </div>
    
  </header>
  <main>
	  <div class="table-responsive">
		<table class="table table-bordered border border-secondary mb-0">
			<tbody>
				<tr>
				  <td colspan="2" class="bg-light text-center"><h3 class="mb-0">OD TOURS AND TRAVELS PVT LTD</h3></td>
				</tr>
				
				<tr>
				  <td colspan="2" class="py-1">
					<div class="row">
						<div class="col fw-600">CONSIGNOR</div>
						<div class="col text-center fw-600 text-3 text-uppercase"></div>
						<div class="col fw-600 text-end">CONSIGNEE</div>
					</div>
				  </td>
				</tr>
				<tr>
				  <td colspan="2" class="p-0">
					<div class="row p-3">
						
						<div class="col-6">
							<address>
								<strong>OD Tours and Travels Pvt Ltd</strong><br />
								3rd Floor, Hotel Rajdhani, Cuttack Puri Road,
								Bhubaneswar - 751002 (Odisha), India<br />
                <strong>PAN:-</strong> AADCO0411F<br />
                <strong>GSTIN:-</strong>21AADCO0411F1ZC<br />

							</address>
						</div>
					
						<div class="col-6 text-end">
							<address>
								<strong>{{$customer_gst_business_name}}</strong><br />
								{{$customer_gst_business_address}}<br />
								<strong>Email Id:-</strong>{{$customer_gst_business_email}}<br />
                <strong>GSTIN:-</strong>{{$customer_gst_number}}<br />

							</address>
						</div>
					</div>
				  </td>
				</tr>
				<tr>
					<td colspan="2" class="p-0">
						<table class="table table-bordered text-2 table-sm table-striped ">
        <thead>
          <tr>
            <td colspan="4" class="pt-3"><h4 class="text-4">Travel Information</h4></td>
          </tr>
        </thead>
        <tbody>
        	<tr>
            <td class="fw-600 col-3 text-1">PNR No</td>
            <td class="col-3">{{$pnr}} </td>
            <td class="fw-600 col-2 text-1">Bus Name & No</td>
            <td class="col-4">{{$busname}}/{{$busNumber}}</td>
          </tr>
          <tr>
            <td class="fw-600 col-3 text-1">No of Seat / Sleeper</td>
            <td class="col-3">{{$total_seats}}</td>
            <td class="fw-600 col-2 text-1">DOJ</td>
            <td class="col-4">{{$journeydate}}</td>
          </tr>
           <tr>
            <td class="fw-600 col-3 text-1" >From</td>
            <td class="col-3">{{$source}}</td>
            <td class="fw-600 col-2 text-1">To</td>
            <td class="col-4">{{$destination}}</td>
          </tr>
         
          <tr>
            <td class="fw-600 text-1">Name</td>
            <td>{{$name}}</td>
            <td class="fw-600 text-1">Mob No.</td>
            <td>{{$customer_number}}</td>
          </tr>
          
          <tr>
            <td class="fw-600 text-1">HSN/SAC Code</td>
            <td>998551</td>
            <td class="fw-600 text-1">Place of Supply</td>
            <td>Bhubaneswar</td>
          </tr>
          
        </tbody>
      </table>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="p-0">
						<table class="table table-sm mb-0 text-center">
							<thead>
							  <tr class="bg-light text-1">
								<td class="col-3 text-center" valign="middle"><strong>Particulars</strong></td>
								<td class="col-1 text-center" valign="middle"><strong>Base Fare</strong></td>
								<td class="col-1 text-center" valign="middle"><strong>Discount<br/>(If Any)</strong></td>
								<td class="col-2 text-center" valign="middle"><strong>Other Chargers<br/>(If Any)</strong></td>
								<td class="col-3 text-center" valign="middle"><strong>GST 5% On Base Fare<br/>(CGST 2.5% + SGST 2.5%) </strong></td>
								<td class="col-1 text-end" valign="middle"><strong>Amount</strong></td>
							  </tr>
							</thead>
							<tbody>
								<tr>
								  <td class="col-3 text-center">Sale of Services-Bus ticket</td>
								  <td class="col-1 text-center">{{$owner_fare + $odbus_charges + $customer_comission}}</td>
								  <td class="col-1 text-center">{{$coupon_discount}}</td>
								  <td class="col-2 text-center">
                                    @if($add_festival_fare > 0 || $add_special_fare > 0)
                                    {{$add_festival_fare + $add_special_fare + $transactionFee}}
                                    @else {{ $transactionFee }}
                                    @endif
                                  </td>
								  <td class="col-3 text-center">{{$customer_gst_amount}}</td>
								  <td class="col-1 text-end">{{$payable_amount + $customer_comission}}</td>
								</tr>
								
								
							</tbody>
						</table>
					</td>
				</tr>
				<tr class="bg-light fw-600">
					<td class="col-7 py-1">GSTIN No.: {{$customer_gst_number}}</td>
					<td class="col-5 py-1 pe-1">Total Invoice Amount: <span class="float-end">{{$payable_amount + $customer_comission}}</span></td>
				</tr>
								
				<tr>
					<td class="col-7 text-1">
						<div class="fw-600">Declaration :</div>
						We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.
					</td>
					<td class="col-5 pe-1 text-end">
						For, OD TOURS AND TRAVELS PVT LTD
						<div class="text-1 fst-italic mt-5">(Authorised Signatory)</div>
					</td>
				</tr>
			</tbody>
		</table>
		</div>
		<p class="text-center">This is a computer generated Invoice and does not require Signature/Stamp.</p>
  </main>
  <footer class="text-center mt-4">
	<div class="btn-group btn-group-sm d-print-none"> <a href="javascript:window.print()" class="btn btn-light border text-black-30 shadow-none"><i class="fa fa-print"></i> Print & Download</a> </div>
  </footer>
</div>
</body>
</html>