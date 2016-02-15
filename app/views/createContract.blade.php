<!DOCTYPE html>
<header>
	<link rel="stylesheet" href="../foundation-5.4.0/css/foundation.css">
	<link rel="stylesheet" href="../foundation-5.4.0/css/css_self.css">
	<link rel="stylesheet" href="../foundation-5.4.0/css/css_contract.css">
	<link rel="stylesheet" href="../foundation-5.4.0/css/css_validate.css">
	<link rel="stylesheet" href="../foundation-5.4.0/css/jqueryui-css.css">
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

<section class="createContract">
	<div class="row">
		<div class="large-12 columns">
			<!-- tab titles -->
			<ul class="tabs" data-tab role="tablist">
				<li class="tab-title active" role="presentational">
					<a href="#panel2-1" role="tab" tabindex="0" aria-selected="true" controls="panel2-1">Create Contract</a>
				</li>
			</ul>
			<div class="tabs-content"  id="main_tab">
				<!-- Create Contract Tab -->
				<section role="tabpanel" aria-hidden="false" class="content active" id="panel2-1">
					<div class="large-12 columns">
						@foreach($errors as $message)
						<div data-alert class="alert-box alert radius">{{ $message }}</div>
						@endforeach

						{{ Form::open(array('url'=>'createContract/'.$loan->loan_id,'id'=>'createContractForm')) }}
						<fieldset class="createContract">
							<legend>Participant Details</legend>
							<div class="row">
								<div class="large-12 column"><h2>Borrower</h2></div>
							</div>
							<div class="row">
								<div class="large-4 column"><label>Borrower Name:</label></div>
								<div class="large-8 column"><p>{{ $profile->fname ." ". $profile->lname }}</p></div>
							</div>
							<div class="row">
								<div class="large-4 column"><label>Borrower Address:</label></div>
								<div class="large-8 column"><p>{{ $profile->streetno ." ". $profile->street }}, {{ $profile->suburb ." ". $profile->state ." ". $profile->postcode }}</p></div>
							</div>
							<div class="row">
								<div class="large-12 column"><h2>Lender(s)</h2></div>
							</div>
							<div class="row">
								<div class="large-12 column">
									<table>
										<thead>
										<tr>
											<th>Name</th>
											<th>Address</th>
										</tr>
										</thead>
										<tbody>  @foreach($lenders as $lender)
										<tr>
											<td>{{ $lender->fname ." ". $lender->lname }}</td>
											<td>{{ $lender->streetno ." ". $lender->street .", ". $lender->suburb ." ". $lender->state ." ". $lender->postcode }}</td>
										</tr> @endforeach
										</tbody>
									</table>
								</div>
							</div>
						</fieldset> <!-- End Participant Details Fieldset -->
						<fieldset class="createContract">
							<legend>Breakdown of Loan</legend>
							<div class="row">
								<div class="large-4 column"><label>Amount:</label></div>
								<div class="large-8 column"><p>${{ $loan->amount }}</p></div>
							</div>
							<div class="row">
								<div class="large-4 column"><label>Term:</label></div>
								<div class="large-8 column"><p>{{ $loan->term }} months</p></div>
							</div>
							<div class="row">
								<div class="large-4 column"><label>Interest Rate:</label></div>
								<div class="large-8 column"><p>{{ $loan->pref_rate }}%</p></div>
							</div>
							<div class="row">
								<div class="large-4 column"><label>Type of Interest Rate:</label></div>
								<div class="large-8 column">
									<div class="contractRadio">
										<label for="interest_compound"><input type="radio" name="interest_type" value="compound" id="interest_compound" {{ $contract->interest_type ? "checked" : "" }}> Compound Interest</label>
										<label for="interest_flat"><input type="radio" name="interest_type" value="flat" id="interest_flat"  {{ $contract->interest_type===false ? "checked" : "" }}> Flat Interest</label>
									</div>
								</div>
							</div>
						</fieldset> <!-- End Breakdown of Loan fieldset -->

						<fieldset class="createContract">
							<legend>Repayment</legend>
							<div class="row">
								<div class="large-4 columns"><label for="num_install">Number of Installments:</label></div>
								<div class="large-8 column"><input type="text" name="num_install" id="num_install" value="{{ $contract->num_install }}" /></div>
							</div>
							<div class="row">
								<div class="large-4 columns"><label for="install_freq">Installment Frequency:</label></div>
								<div class="large-8 column">
									<select name="install_freq" id="install_freq">
										<option value="daily" {{ $contract->install_freq=='daily' ? "selected" : "" }}>Daily</option>
										<option value="weekly" {{ $contract->install_freq=='weekly' ? "selected" : "" }}>Weekly</option>
										<option value="fortnightly" {{ $contract->install_freq=='fortnightly' ? "selected" : "" }}>Fortnightly</option>
										<option value="monthly" {{ $contract->install_freq=='monthly' ? "selected" : "" }}>Monthly</option>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="large-4 columns"><label for="val_install">Value of Each Installment:</label></div>
								<div class="large-8 column"><input type="text" name="val_install" id="val_install" value="{{ $contract->val_install }}" /></div>
							</div>
							<div class="row">
								<div class="large-4 column"><label for="day_install">Day of Installment:</label></div>
								<div class="large-8 column"><input type="text" name="day_install" id="day_install" value="{{ $contract->day_install }}" /></div>
							</div>
							<!-- DatePicker -->
							<div class="row">
								<div class="large-4 column"><label for="start_date">Start Date:</label></div>
								<div class="large-8 column"><input type="text" name="start_date" id="datepicker" value="{{ $contract->start_date }}"></div>
							</div>
							<div class="row">
								<div class="large-4 column"><label for="end_date">End Date:</label></div>
								<div class="large-8 column"><input type="text" name="end_date" id="datepicker" value="{{ $contract->end_date }}"></div>
							</div>

						</fieldset> <!-- End Repayment fieldset -->

						<fieldset class="createContract">
							<legend>Prepayment</legend>
							<div class="row">
								<div class="large-12 column">
									<p>Prepayment refers to the financial penalties incurred by paying off the loan early.  Because the interest rate is accrued monthly if the borrower pays off the loan early then they don’t have to pay as much interest.  The lender(s) want as much interest as possible so a financial penalty is often attached to a loan to dissuade the borrower from paying off the loan early. </p>
								</div>
							</div>
							<div class="row">
								<div class="large-4 column"><label for="prepayment_rule">Prepayment Options:</label></div>
								<div class="large-8 column">
									<select name="prepayment_rule" id="prepayment_rule" >
										@foreach($prepayment_rules as $rule)
										<option {{$contract->prepayment_rule == $rule ? "selected" : "" }}>{{ $rule }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</fieldset>	<!-- End Prepayment fieldset -->

						<fieldset class="createContract">
							<legend>Late Charge</legend>
							<div class="row">
								<div class="large-12 column">
									<p>A late charge is applied to a single instalment when it is late.  A late charge is applied to a single instalment when it is late.  The initial late charge is applied the day after the instalment was due.  The borrower then has a set number of days to make the payment or the late charge increases, this will continue until the installment and late charges are paid.  The late charges will max out at a set value. </p>
								</div>
							</div>
							<div class="row">
								<div class="large-8 column"><label for="days_late">Number of Days to Pay Before Additional Late Charges Applied:</label></div>
								<div class="large-4 column"><input type="text" name="days_late" id="days_late" value="{{ $contract->days_late }}" /></div>
							</div>
							<div class="row">
								<div class="large-8 column"><label for="late_percent">Percentage of Installment Charged as Late Fee:</label></div>
								<div class="large-4 column"><input type="text" name="late_percent" id="late_percent" value="{{ $contract->late_percent }}" /></div>
							</div>
							<div class="row">
								<div class="large-8 column"><label for="late_max">Max Value of Late Fee:</label></div>
								<div class="large-4 column"><input type="text" name="late_max" id="late_max" value="{{ $contract->late_max }}" /></div>
							</div>
						</fieldset> <!-- End Late Charge fieldset -->

						<fieldset class="createContract">
							<legend>Security</legend>
							<div class="row">
								<div class="large-12 column">
									<p>Security refers to the asset(s) the borrower is going to use as collateral for the loan.  In typical loans this is the item which will become the property of the lender in the event of the borrower defaulting on the loan.  In the case of a MoneyLink loan, however, the item(s) will be sold and the funds from the sale used to pay back the money owed.  The borrower may not sell or otherwise part with the asset(s) until the loan is complete. </p>
								</div>
							</div>
							<div class="row">
								<div class="large-4 column"><label for="security_tf">Is There Security on this Loan?</label></div>
								<div class="large-8 column">
									<div class="contractRadio">
										<input type="radio" name="security_tf" value="1" id="security_true" {{ $contract->security_tf ? "checked" : "" }}><label for="security_true">Yes</label>
										<input type="radio" name="security_tf" value="0" id="security_false" {{ $contract->security_tf==0 ? "checked" : "" }}><label for="security_false">No</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="large-4 column"><label for="security">Description of Security:</label></div>
								<div class="large-8 column"><input type="text" name="security" id="security" value="{{ $contract->security }}" /></div>
							</div>
							<div class="row">
								<div class="large-4 column"><label for="security_value">Estimated Value of Security:</label></div>
								<div class="large-8 column"><input type="text" name="security_value" id="security_value" value="{{ $contract->security_value }}" /></div>
							</div>
						</fieldset> <!-- End of Security fieldset -->

						<fieldset class="createContract">
							<legend>Default</legend>
							<div class="row">
								<div class="large-12 column">
									<p>If, for any reason, the borrower is unable to make any payment on time they are in default, and the lender may request that the loan then be paid in fall.  If the borrower is unable to pay the remaining balance, then an additional charge will be added to the loan, based on the percentage specified. </p>
								</div>
							</div>
							<div class="row">
								<div class="large-8 column"><label for="default_extra">Percentage of Loan Paid as Penalty if Default on Loan:</label></div>
								<div class="large-4 column"><input type="text" name="default_extra" id="default_extra" value="{{ $contract->default_extra }}" /></div>
							</div>
						</fieldset> <!-- End of Default fieldset -->

						<fieldset class="createContract">
							<legend>Collection Fees</legend>
							<div class="row">
								<div class="large-12 column">
									<p>If the lender(s) have to seek legal aid in reclaiming their funds the borrower will be required to pay the legal fees up to a specified percentage of the unpaid balance.</p>
								</div>
							</div>
							<div class="row">
								<div class="large-8 column"><label for="default_legal">Percentage of Unpaid Balance to Pay in Legal Fees:</label></div>
								<div class="large-4 column"><input type="text" name="default_legal" id="default_legal" value="{{ $contract->default_legal }}" /></div>
							</div>
						</fieldset> <!-- End of Collection Fees fieldset -->

						<div class="right" id="submit_button">
							<div class="contractButton">
								{{ Form::submit('Preview',null,array('id'=>'submit_button','class'=>'contractButton')) }}
							</div>
						</div>
						<div class="right" id="cancel_button">
							<a href="#" class="secondary small button">Cancel</a>
						</div>
						{{ Form::close() }}
					</div>
				</section>


			</div>
		</div>
	</div>
</section>


<footer>
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

<script src="../foundation-5.4.0/js/vendor/jquery.js"></script>
<script src="../foundation-5.4.0/js/jquery_ui.js"></script>
<script src="../foundation-5.4.0/js/foundation/foundation.js"></script>
<script src="../foundation-5.4.0/js/foundation/foundation.topbar.js"></script>
<script src="../foundation-5.4.0/js/foundation/foundation.tab.js"></script>
<script>
	$(document).foundation();
	$(function() {
		$( "#datepicker" ).datepicker();
	});
</script>
</body>
</html>
