<!DOCTYPE html>
<header>
	<link rel="stylesheet" href="../foundation-5.4.0/css/foundation.css">
	<link rel="stylesheet" href="../foundation-5.4.0/css/css_self.css">

</header>
<body>


	<! navigation bar -->

	<nav class="top-bar" data-topbar role="navigation">
		<ul class="title-area">
			<li class="name">
       <a href="mytransaction"><img id="logo" src="../foundation-5.4.0/img/logos.png"></a>
       <a href="mytransaction"><img id="font" src="../foundation-5.4.0/img/font.png"></a>
			</li>
			<!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
			<!-- <li class="toggle-topbar menu-icon"><a href="home"><span>Menu</span></a></li> -->
		</ul>
		<section class="top-bar-section">
			<!-- Right Nav Section -->
			<ul class="right">
				<li><a href="/myprofile" id="welcome">Welcome {{{ $pdata['userfname'] or "" }}}</a></li>
				<li><a href="/logout"><img id="logout" src="../foundation-5.4.0/img/logout_w.png"></a></li>

			</ul>
		</section>
	</nav>


	<div id="confirmClaim" class="reveal-modal small" data-reveal>
		<div class="row">
			<p> Are you sure you want to claim this loan? </p>
			<p>You will be matched with all existing offers on the loan and your loan request will be removed.</p>
			<a class='small button claminBtn'>Yes</a>
			<a class='small button no'>No</a>
		</div>
		<a class="close-reveal-modal close_form">&#215;</a>
		</div>
	<div id="confirmCancel" class="reveal-modal small" data-reveal>
		<div class="row">
			<p> Are you sure you want to cancel this loan request? </p>
			<a class='small button cancelBtn'>Yes</a>
			<a class='small button no'>No</a>
		</div>
		<a class="close-reveal-modal close_form">&#215;</a>
	</div>

	<div id="confirmCancelBid" class="reveal-modal small" data-reveal>
		<div class="row">
			<p> Are you sure you want to cancel this offer?</p>
			<a class='small button cancelBidBtn'>Yes</a>
			<a class='small button no'>No</a>
		</div>
		<a class="close-reveal-modal close_form">&#215;</a>
	</div>

	<div id="confirmCancelOffer" class="reveal-modal small" data-reveal>
		<div class="row">
			<p> Are you sure you want to cancel this loan offer? </p>
			<a class='small button cancelOfferBtn'>Yes</a>
			<a class='small button no'>No</a>
		</div>
		<a class="close-reveal-modal close_form">&#215;</a>
	</div>

	<div id="confirmCancelObid" class="reveal-modal small" data-reveal>
		<div class="row">
			<p> Are you sure you want to cancel this bid on loan offer? </p>
			<a class='small button cancelObidBtn'>Yes</a>
			<a class='small button no'>No</a>
		</div>
		<a class="close-reveal-modal close_form">&#215;</a>
	</div>

	<div class="row">
		<div class="large-12 small-12 columns">
				<ul class="inline-list" id="tab-area">
					<!-- <div class="large-3 columns" id="active_tab_bg"> -->
					<li id="active_title"><a href="mytransaction">My Transaction</a></li>
				<!-- </div>
				<div class="large-3 columns" id="tab_bg"> -->
					<li id="title"><a href="myprofile">My Profile</a></li>
					<!-- </div>
					<div class="large-3 columns" id="tab_bg"> -->
					<li id="title"><a href="borrow">Borrow Money</a></li>
					<!-- </div>
					<div class="large-3 columns" id="tab_bg"> -->
					<li id="title"><a href="lend">Lend Money</a></li>
					<!-- </div> -->
					<!-- <li id="title"><a href='loanoffers'>Loan Offers</a></li> -->
					<li id="title"><a href="/transfer">Transfer Bid</a></li>
					<li id="title"><a href="loanoffers">Loan Offers</a></li>
				</ul>
			</div>
		</div>
				<div class="row">
					<div class="large-12 small-12 columns">
						<div class="tabs-content" id="main_tab">

						<h5 id="title_bg">Your Loan Requests</h5>
						<table>
							<thead>
								<tr>
									<th>Amount ({{$pdata['userCurrency']}})</th>
									<th>Purpose</th>
									<th>Loan Duration</th>
									<th>Interest Rate</th>
									<th>Progress</th>
									<th width="40"></th>
									<th width="40"></th>
								</tr>
							</thead>
							<tbody>
								@if(isset($loan_requests))
								@if(empty($loan_requests))
								<tr><td colspan="3">You currently have no loan requests</td></tr>
								@else
								@foreach($loan_requests as $loan)
								<tr>
									<td>${{round( $loan-> amount * $pdata['userXrate'],2)}}</td>
									<td>{{ $loan-> purpose }}</td>
									<td>{{ $loan-> term }} Months</td>
									<td>{{ $loan-> pref_rate, 2 }}%</td>
									<td><a href= {{ 'detailedLoan/'.$loan-> loan_id }}>${{ round($loan-> progress * $pdata['userXrate'],2) }} ({{ round($loan->progress / $loan->amount * 100, 2) }}%)</a></td>
									<td><a class ='claimLink' data = {{ $loan->loan_id }} data-reveal-id ='confirmClaim' ><img id="logout" src="../foundation-5.4.0/img/dollar_icon.png"></a></td>
									<td><a class = 'cancleLink' data = {{ $loan->loan_id }} data-reveal-id = 'confirmCancel'><img id="logout" src="../foundation-5.4.0/img/cancel_icon.png"></a></td>
								</tr>
								<!--  href= {{ 'claimLoan/'.$loan-> loan_id }}  -->
								<!-- href= {{ 'cancelLoan/'.$loan-> loan_id }} -->
								@endforeach
								@endif
								@endif

						</tbody>
					</table>

					<h5 id="title_bg">Your Offers on Loans</h5>
					<table>
						<thead>
							<tr>
								<th>Applicant</th>
								<th>Offer Ammount ({{$pdata['userCurrency']}})</th>
								<th width = "80">Purpose</th>
								<th>Loan Duration</th>
								<th>Interest Rate</th>
								<th>Loan Progress</th>
								<th width= "80"></th>
								<th width="40"></th>
							</tr>
						</thead>
						<tbody>
							@if(isset($bids))
							@if(empty($bids))
								<tr><td colspan="3">You currently have no offers on loans</td></tr>
							@else
							@foreach($bids as $bid)
							<tr>
								<td><a class="capital" href= {{ 'otherUsers/'.$bid-> user_id }}>{{ $bid->fname }} &nbsp; {{ $bid-> lname }}</a></td>
								<td>${{ round($bid-> bid_amount * $pdata['userXrate'],2)}}</td>
								<td>{{ $bid-> purpose }}</td>
								<td>{{ $bid-> term }} Months</td>
								<td>{{ round($bid-> pref_rate, 2) }}%</td>
								<td>${{ round($bid-> progress * $pdata['userXrate'],2) }}({{ round($bid->progress / $bid->amount * 100, 2) }}%)</td>
								<td><a class = 'transferPanel' href = 'transferPanel/{{ $bid->bid_id }}'> transfer </a></td>
								<td><a class = 'cBidLink' data-reveal-id = 'confirmCancelBid' data = {{ $bid-> bid_id }} ><img id="logout" src="../foundation-5.4.0/img/cancel_icon.png"></a></td>
							</tr>
							@endforeach
							@endif
							@endif
						</tbody>
					</table>

					<h5 id="title_bg">Matched Offers</h5>
					<table>
						<thead>
							<tr>
								<th>Applicant</th>
								<th width="250">Applicant's Email</th>
								<th>Offer Ammount ({{$pdata['userCurrency']}})</th>
								<th>Purpose</th>
								<th>Loan Duration</th>
								<th>Interest Rate</th>
								<th>Match Date</th>
							</tr>
						</thead>

						<tbody>
							@if(isset($accepted_loan))
							@if(empty($accepted_loan))
								<tr><td colspan="3">You currently have no matched offers</td></tr>
							@else
							@foreach($accepted_loan as $acc)
							<tr>
								<td><a class="capital" href= {{ 'otherUsers/'.$acc-> user_id }}>{{ $acc-> fname }} {{ $acc-> lname }}</a></td>
								<td>{{ $acc-> email }}</td>
								<td>${{ $acc-> bid_amount }}</td>
								<td>{{ $acc-> purpose }}</td>
								<td>{{ $acc-> term }} Months</td>
								<td>{{ round($acc-> pref_rate,2) }}%</td>
								<td>{{ date( "d/m/Y", strtotime ($acc -> match_date)) }}</td>
							</tr>
							@endforeach
							@endif
							@endif
						</tbody>
					</table>

					<h5 id="title_bg">Matched Loans</h5>
					<table>
						<thead>
							<tr>
								<th>Loan Amount ({{$pdata['userCurrency']}})</th>
								<th>Purpose</th>
								<th>Loan Duration</th>
								<th>Interest Rate</th>
								<th>Match Date</th>
								<th></th>
								<th id="createContract"></th>
							</tr>
						</thead>
						<tbody>
							@if(isset($active_loan))
							@if(empty($active_loan))
								<tr><td colspan="3">You currently have no matched loans</td></tr>
							@else
							@foreach($active_loan as $act)
							<tr>
								<td>${{ round($act -> amount * $pdata['userXrate'],2)}}</td>
								<td>{{ $act -> purpose }}</td>
								<td>{{ $act -> term }} Months</td>
								<td>{{ round($act -> pref_rate,2) }}%</td>
								<td>{{ date( "d/m/Y", strtotime ($act -> match_date)) }}</td>
								<td><a href= {{ 'matchedLoan/'.$act-> loan_id }}>Lender Details</a></td>
								<td><a href= {{ 'createContract/'.$act-> loan_id }}>Contract</a></td>
							</tr>
							@endforeach
							@endif
							@endif
						</tbody>
					</table>

					<h5 id="title_bg">Your Loan Offers</h5>
					<table>
						<thead>
							<tr>
								<th>Amount</th>
								<th>Requested Interest Rate</th>
								<th>Requested Term</th>
								<th width="90"></th>
								<th width="40"></th>
							</tr>
						</thead>

						<tbody>
							@if(isset($active_offers))
							@if(empty($active_offers))
								<tr><td colspan="3">You currently have no loan offers</td></tr>
							@else
							@foreach($active_offers as $offer)
							<tr>
								<td>${{ $offer-> amount }}</td>
								<td>{{ $offer-> rate}}%</td>
								<td>{{ $offer-> term }} Months</td>
								<td><a href= {{ 'offerBids/'.$offer-> offer_id }}>View Bids</a></td>
								<td><a class = 'cancelOffer' data = {{ $offer->offer_id }} data-reveal-id = 'confirmCancelOffer'><img id="logout" src="../foundation-5.4.0/img/cancel_icon.png"></a></td>
							</tr>
							@endforeach
							@endif
							@endif
						</tbody>
					</table>

					<h5 id="title_bg">Your Bids on Loan Offers</h5>
					<table>
						<thead>
							<tr>
								<th>Offerer</th>
								<th>Amount</th>
								<th>Proposed Interest Rate</th>
								<th>Proposed Term</th>
								<th width="40"></th>
							</tr>
						</thead>

						<tbody>
							@if(isset($active_obids))
							@if(empty($active_obids))
								<tr><td colspan="3">You currently have no bids on loan offers</td></tr>
							@else
							@foreach($active_obids as $obid)
							<tr>
								<td><a class="capital" href= {{ 'otherUsers/'.$obid-> user_id }}>{{ $obid-> fname}} {{ $obid-> lname}}</a></td>
								<td>${{ $obid-> amount }}</td>
								<td>{{ $obid-> rate}}%</td>
								<td>{{ $obid-> term }} Months</td>
								<td><a class = 'cancelObid' data = {{ $obid->obid_id }} data-reveal-id = 'confirmCancelObid'><img id="logout" src="../foundation-5.4.0/img/cancel_icon.png"></a></td>
							</tr>
							@endforeach
							@endif
							@endif
						</tbody>
					</table>

					<h5 id="title_bg">Matched Loan Offers</h5>
					<table>
						<thead>
							<tr>
								<th>Applicant</th>
								<th width="250">Email</th>
								<th>Amount</th>
								<th>Interest Rate</th>
								<th>Term</th>
							</tr>
						</thead>

						<tbody>
							@if(isset($matched_offers))
							@if(empty($matched_offers))
								<tr><td colspan="3">You currently have no matched loan offers</td></tr>
							@else
							@foreach($matched_offers as $offer)
							<tr>
								<td><a class="capital" href= {{ 'otherUsers/'.$offer-> user_id }}>{{ $offer-> fname}} {{ $offer-> lname}}</a></td>
								<td>{{ $offer-> email}}</td>
								<td>${{ $offer-> amount }}</td>
								<td>{{ $offer-> rate}}%</td>
								<td>{{ $offer-> term }} Months</td>
							</tr>
							@endforeach
							@endif
							@endif
						</tbody>
					</table>

					<h5 id="title_bg">Matched Bids on Loan Offers</h5>
					<table>
						<thead>
							<tr>
								<th>Offerer</th>
								<th width="250">Email</th>
								<th>Amount</th>
								<th>Interest Rate</th>
								<th>Term</th>
							</tr>
						</thead>

						<tbody>
							@if(isset($matched_obids))
							@if(empty($matched_obids))
								<tr><td colspan="3">You currently have no matched loan offers</td></tr>
							@else
							@foreach($matched_obids as $obid)
							<tr>
								<td><a class="capital" href= {{ 'otherUsers/'.$obid-> user_id }}>{{ $obid-> fname}} {{ $obid-> lname}}</a></td>
								<td>{{ $obid-> email}}</td>
								<td>${{ $obid-> amount }}</td>
								<td>{{ $obid-> rate}}%</td>
								<td>{{ $obid-> term }} Months</td>
							</tr>
							@endforeach
							@endif
							@endif
						</tbody>
					</table>


				</div>
			</div>

</div>

 <?php include '/var/www/htdocs/MoneyLink/app/views/template/footer.php'; ?>

	<script src="../foundation-5.4.0/js/vendor/jquery.js"></script>
	<script src="../foundation-5.4.0/js/foundation/foundation.js"></script>
	<script src="../foundation-5.4.0/js/loanApp.js"></script>
	<script src="../foundation-5.4.0/js/foundation/foundation.topbar.js"></script>
	<script src="../foundation-5.4.0/js/foundation/foundation.tab.js"></script>
	<script src="../foundation-5.4.0/js/foundation/foundation.reveal.js"></script>
	<script>
	$(document).foundation();
	getStep();
	enableBtns();
	</script>
	<script>
		$('.claimLink').on('click', function(event){
			event.preventDefault();
			var loan_id = $(this).attr('data');
			$('.claminBtn').attr('href', 'claimLoan/'+loan_id);
		})
		$('.cancleLink').on('click', function(event) {
			event.preventDefault();
			var loan_id = $(this).attr('data');
			$('.cancelBtn').attr('href', 'cancelLoan/'+loan_id);
		})
		$('.cBidLink').on('click', function(event) {
			event.preventDefault();
			var bid_id = $(this).attr('data');
			$('.cancelBidBtn').attr('href','cancelBid/'+bid_id);
		})
		$('.cancelOffer').on('click', function(event) {
			event.preventDefault();
			var offer_id = $(this).attr('data');
			$('.cancelOfferBtn').attr('href','cancelOffer/'+offer_id);
		})
		$('.cancelObid').on('click', function(event) {
			event.preventDefault();
			var obid_id = $(this).attr('data');
			$('.cancelObidBtn').attr('href','cancelObid/'+obid_id);
		})
		$('.no').on('click', function(){
			$('.close_form').click();
		})

	</script>
</body>
</html>

