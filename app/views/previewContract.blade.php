<!DOCTYPE html>
<header>
	<link rel="stylesheet" href="../foundation-5.4.0/css/foundation.css">
	<link rel="stylesheet" href="../foundation-5.4.0/css/css_self.css">
	<link rel="stylesheet" href="../foundation-5.4.0/css/css_validate.css">
	<link rel="stylesheet" href="../foundation-5.4.0/css/css_contract.css">
	<style>
		.form-error {
			color:red;
		}
		.errorMsg {
			color:red;

			visibility: hidden;
			text-align: left;
		}
		#main_tab input[type=text],
		#main_tab input[type=password],
		#main_tab select,
		#main_tab textarea {
			width:90%;
		}
		.hint {
			display:block;
			float: right;
		}
	</style>
</header>

<body>
<!-- navigation bar -->
<div class="remove_for_print">
	<nav class="top-bar" data-topbar role="navigation">
		<ul class="title-area">
			<li class="name">
				<a href="mytransaction"><img id="logo" src="../foundation-5.4.0/img/logos.png"></a>
				<a href="mytransaction"><img id="font" src="../foundation-5.4.0/img/font.png"></a>
			</li>
			<!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
			<li class="toggle-topbar menu-icon"><a href="index.html"><span>Menu</span></a></li>
		</ul>
		<section class="top-bar-section">
			<!-- Right Nav Section -->
			<ul class="right">
				<li><a href="profile" id="welcome">Welcome {{ Session::get('usermail') }}</a></li>
				<li><a href="logout"><img id="logout" src="../foundation-5.4.0/img/logout_w.png"></a></li>

			</ul>
		</section>
	</nav>
</div>

<div class="row">
	<div class="large-12 columns">
		<!-- tab titles -->
		<div class="remove_for_print">
			<ul class="tabs" data-tab role="tablist">
				<li class="tab-title active" role="presentational">
					<a href="#panel2-1" role="tab" tabindex="0" aria-selected="true" controls="panel2-1">Preview Contract</a>
				</li>
			</ul>
		</div>

		<div class="tabs-content"  id="main_tab">
			<!-- Create Contract Tab -->
			<section role="tabpanel" aria-hidden="false" class="content active" id="panel2-1">
				<div class="large-12 columns">

					@if(empty($success))
					@foreach($errors as $message)
					<div data-alert class="alert-box alert radius">{{ $message }}</div>
					@endforeach
					@else
					<div data-alert class="alert-box success radius">{{ $success }}</div>
					@endif



					<div class="panel callout">

						<section class="printContract">
							<h1>Loan Agreement</h1>
							<article id="participants">
								<p>This loan agreement is made between:</p>
								<p class="printIndent">{{ $profile->fname ." ". $profile->lname }}, who resides at {{ $profile->streetno ." ". $profile->street }}, {{ $profile->suburb ." ". $profile->state ." ". $profile->postcode }}</p>
								<p>and the following party or parties:</p>
								<ul>
									@foreach($lenders as $lender)
									<li>{{ $lender->fname ." ". $lender->lname }}, who resides at {{ $lender->streetno ." ". $lender->street .", ". $lender->suburb ." ". $lender->state ." ". $lender->postcode }}</li>
									@endforeach
								</ul>
							</article>
							<article id="promise_to_pay">
								<h2>1. Promise to Pay</h2>
								<p>The borrower of this loan, {{ $profile->fname ." ". $profile->lname }}, promises to pay to the lender(s), @foreach($lenders as $lender){{ $lender->fname ." ". $lender->lname }}, @endforeach the sum of ${{ $loan->amount }} and interest and other charges as stated below.</p>
							</article>
							<article id="loan_details">
								<h2>2. Loan Details</h2>
								<p><span class="contractLabel">Total Loan Ammount:</span>  ${{ $loan->amount }}</p>
								<p><span class="contractLabel">Term of the Loan:</span>  {{ $loan->term }} months</p>
								<p><span class="contractLabel">Interest Rate:</span>  {{ $loan->pref_rate }}% {{ $contract->interest_type }} interest</p>
								<p>The contributions of each lenders is as detailed below.</p>
								<table>
									<thead>
									<tr>
										<th>Name</th>
										<th>Contribution</th>
									</tr>
									</thead>
									<tbody>
									@foreach($lenders as $lender)
									<tr>
										<td>{{ $lender->fname ." ". $lender->lname }}</td>
										<td>{{ $lender->bid_amount }}</td>
									</tr>
									@endforeach
									</tbody>
								</table>
							</article>
							<article id="repayment">
								<h2>3. Repayment</h2>
								<p>The borrower will repay the amount due in {{ $contract->num_install }} equal {{ $contract->install_freq }} installments of ${{ $contract->val_install }}.  If these installments are not daily, they will be paid on the {{ $contract->day_install }}.</p>
								<p>The payments will start on {{ $contract->start_date }} and end on {{ $contract->end_date }}.</p>
							</article>
							<article id="prepayment">
								<h2>4. Prepayment</h2>
								<p>The borrower has the right to pay the whole outstanding value of the loan amount at any time.  If the borrower chooses to do so, the following rule will be applied: {{ $contract->prepayment_rule }} </p>
							</article>
							<article id="late_charge">
								<h2>5. Late Charge</h2>
								<p>If any single installment is not paid on the date it is due, the first day thereafter it will incur a late charge of {{ $contract->late_percent }}% of the installment value, every {{ $contract->days_late }} days thereafter it will incure an additional late charge of another {{ $contract->late_percent }}% of the original installment value.  The late charges cannot exceed more than ${{ $contract->late_max }}.</p>
							</article>
							<article id="security">
								<h2>6. Security</h2>
								@if(!empty($contract->security_tf))
								<p>There is no security for this loan.</p>
								@else
								<p>The security for this loan has an estimated value of ${{ $contract->security_value }}.  In the event that the lender defaults on the loan the security item(s) will be sold to cover the cost of the loan. The borrower must not sell or otherwise part with the item(s) they put up as security until the loan is complete. A description of the security item(s) follows.</p>
								<p>{{ $contract->security }}</p>
								@endif
							</article>
							<article id="default">
								<h2>7. Default</h2>
								<p>If, for any reason, the borrower is unable to make any payment on time they are in default, and the lender may request that the loan then be paid in fall. If the borrower is unable to pay the remaining balance, then an additional charge will be added to the loan, this charge will be {{ $contract->default_extra }}% of the original total loan value.</p>
							</article>
							<article id="collection_fees">
								<h2>8. Collection Fees</h2>
								<p>If the lender(s) have to seek legal aid in reclaiming their funds the borrower will be required to pay the legal fees up to {{ $contract->default_legal }}% of the unpaid balance.</p>
							</article>
							<article id="squiggles">
								<h2>9. Signatures</h2>
								<h3>Borrower</h3>
								<p class="signature">{{ $profile->fname ." ". $profile->lname }}</p>
								<h3>Lender(s)</h3>
								@foreach($lenders as $lender)
								<p class="signature">{{ $lender->fname ." ". $lender->lname }}</p>
								@endforeach
							</article>
						</section>


					</div>


					{{ Form::open(array('url'=>'previewContract/'.$loan->loan_id,'id'=>'previewContractForm')) }}

					<div class="remove_for_print">
						@if($contract->status === 'complete')
						<div class="row">
							<div class="large-12 column">
								<div class="right" id="submit_button">
									<div class="contractButton">
										<input type="button" value="Print this Page" onclick="window.print();">
									</div>
								</div>
							</div>
						</div>

						@else
						<div class="row" id="confirm-checkbox">
							<div class="large-12 column">
								<div class="contractCheck">
									<label><input type="checkbox" name="i_agree" /> I want to finalise this contract.</label>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="right" id="submit_button">
								<div class="contractButton">
									{{ Form::submit('Finalise',null,array('id'=>'submit_button','class'=>'secondary small button')) }}
								</div>
							</div>
							<div class="right" id="cancel_button">
								<a href="{{ $backurl }}" class="secondary small button">Back</a>
							</div>
						</div>
						@endif
					</div>

					{{ Form::close() }}
				</div>

			</section>


		</div>
	</div>
</div>


<footer class="remove_for_print">
	<div class="row">
		<div class="large-3 columns">
			<p><img id="logo" src="../foundation-5.4.0/img/logos.png">
				<img id="font" src="../foundation-5.4.0/img/font.png"></p>
		</div>
		<div class="large-9 columns">
			<ul class="inline-list right">
				<li><a href="#" id="bottom_links">Contact Us</a></li>
				<li><a href="#" id="bottom_links">About MoneyLink</a></li>
				<li><a href="#" id="bottom_links">How MoneyLink works</a></li>
				<li><a href="#" id="bottom_links">Privacy Policy</a></li>
				<li><a href="#" id="bottom_links">Term of Use</a></li>
			</ul>
		</div>
	</div>
</footer>
</div>


<script src="../foundation-5.4.0/js/vendor/jquery.js"></script>
<script src="../foundation-5.4.0/js/foundation/foundation.js"></script>
<script src="../foundation-5.4.0/js/foundation/foundation.topbar.js"></script>
<script src="../foundation-5.4.0/js/foundation/foundation.tab.js"></script>
<script>
	$(document).foundation();
</script>
</body>
</html>
