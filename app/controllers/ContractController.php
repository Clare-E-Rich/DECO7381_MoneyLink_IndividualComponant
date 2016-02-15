<?php

function debug() {
	echo "<pre>";
	foreach (func_get_args() as $var) {
		var_dump($var);
	}
	echo "</pre>";
}

class ContractController extends BaseController {

	private $errors = array();

	public function createContract($loan_id) {
		// Find the profile of the person who's id matches the id of the person currently logged in.  Find it in DB.
		$profile =  UserProfile::where('id', '=', Auth::user()->id)->first();

		try {
			$loan = LoanApp::findOrFail($loan_id);
		} catch (Exception $e) {
			return Redirect::route('mytransaction')->with('message', "Could not load loan: " . $e->getMessage());
		}

		$lenders = $this->getLenders($loan_id);
		if (empty($lenders)) {
			return Redirect::route('mytransaction')->with('message', 'Could not find lenders for this loan request');
		}

		$contract = Contract::firstOrCreate(array('offer_id' => Input::get('offer_id', $loan_id)));

		if ($contract->getAttribute('status') === 'complete') {
			return Redirect::to('previewContract/' . $loan_id);
		}

		// todo: set these using the form
		$contract->setAttribute('start_date', date('d/m/Y'));
		$contract->setAttribute('end_date', date('d/m/Y'));

		if (Request::isMethod('post')) { // Check if actually posting data.
			$save = $contract->fill(Input::all())->save();

			$errors = Session::pull('messages', array());

			if ($save === false && empty($errors)) {
				$errors[] = 'Failed to save contract';
			}

			if (empty($errors)) {
				return Redirect::to('previewContract/' . $loan_id);
			}
		}

		$prepayment_rules = array(
			"There are no penalties for paying off the loan early.",
			"Borrower must pay 5% of the original loan amount.",
			"Borrower must pay the complete interest of the loan.",
			"Borrower must pay \$100",
		);

		return View::make('createContract', compact('loan', 'lenders', 'profile', 'errors', 'contract', 'prepayment_rules'));
	}

/*	public function createContract($loan_id) {
		// Find the id of person currently logged in.
		$id = Auth::user()-> id;

		// Find their email address.
		$usermail = Auth::user()-> email;

		// Find the profile of the person who's id matches the id of the person currently logged in.  Find it in DB.
		$profile =  UserProfile::where('id','=', $id)-> first();

		// Retrieve from the DB details about loan, where $loan_id = input for function and user_id = id of person logged in.
		// This should return object.
		$loan = DB::table('loan_app')
				  ->select('amount', 'term', 'pref_rate', 'purpose', 'progress', 'loan_id', 'match_date')
					// Where the value for amount == value for progress, i.e. the loan is completely financed.
				  ->where(DB::raw('loan_app.amount'), '=', DB::raw('loan_app.progress'))
				  ->where('user_id', '=', $id)
				  ->where('loan_id', '=', $loan_id)
				  ->orderBy('match_date', 'DESC')
				  ->first();

		// Run the getLenders function and store the returned value as $lenders.
		$lenders = $this->getLenders($loan_id);

		// If there is no value for lenders...
		if (empty($lenders)) {
			// fail and redirect somewhere else
			return Redirect::route('mytransaction')->with('message', 'Could not find lenders for this loan request');
		}

		$loan->Lenders = $lenders;

		$contract = Contract::firstOrCreate(array('offer_id' => Input::get('offer_id', $loan_id)));
		$errors = array();

		// Form Submmitting
		if (Request::isMethod('post')) { // Check if actually posting data.
			$input_data = Input::all();

			$contract->fill($input_data);

			// todo: add transaction (if possible)
			foreach ($loan->Lenders as $lender) {
				$contract->setAttribute('offer_id', $lender->bid_id);

				if ($contract->save() === false) {
					$errors[] = "Failed to save for lender " . $lender->fname . " " . $lender->lname . ": " . $contract->;
					break;
				}
			}

			if (empty($errors)) {
				// Redirect to next page
				return Redirect::to('previewContract/' . $loan_id);
			}
		}

		// Return a view using the information just retrieved from DB.
		return View::make('createContract', compact('loan', 'profile', 'errors', 'contract'));
	}
*/

	public function previewContract($loan_id) {  //  Is what I did last night?  I hope so, else I'm lost.
		$id = Auth::user()-> id;
		$usermail = Auth::user()-> email;
		$profile =  UserProfile::where('id','=', $id)-> first();

		// Retreive from the DB details about loan, where loanid = input for function and user_id = id of person logged in.
		// This should return object.
		$loan = DB::table('loan_app')
				  -> select('amount', 'term', 'pref_rate', 'purpose', 'progress', 'loan_id', 'match_date')
				  -> where('user_id', '=', $id)
				  -> where('loan_id', '=', $loan_id)
				  -> orderBy('match_date', 'DESC')
				  -> first();

		// Run the getLenders function and store the returned value as $lenders.
		$lenders = $this->getLenders($loan_id);

		// If there is no value for lenders...
		if (empty($lenders)) {
			// fail and redirect somewhere else
			return Redirect::route('mytransaction')->with('message', 'Could not find lenders for this loan request');
		}

		$contract = Contract::firstOrNew(array('offer_id' => Input::get('offer_id', $loan_id)));
		if (!$contract->getAttribute('offer_id')) {
			return Redirect::to('mytransaction')->with('message', 'No dice!');
		}

		$success = null;
		$errors = array();

		if (Request::isMethod('post')) {
			// update contract record with new status
			if (Input::get('i_agree') !== null) {
				$contract->setAttribute('status', 'complete');

				if ($contract->save()) {
					$success = 'Your contract was finalised';
				} else {
					$errors[] = 'Your contract was not finalised';
				}
			}
		}

		$backurl = route('createContract', array($loan_id));

		return View::make('previewContract', compact('loan', 'profile', 'success', 'errors', 'contract', 'lenders', 'backurl'));
	}

	/**
	 * Gets a list of lenders for the given load
	 *
	 * @param int $loan_id The ID of the loan
	 * @return array Array of lender details
	 */
	private function getLenders($loan_id) {
		// Retrieve from DB details about bids, join with profile and return info about each lender.
		return DB::table('bids')
					 -> join ('profile', 'bids.user_id', '=', 'profile.id')
					 -> select ('bids.bid_id', 'profile.fname', 'profile.lname', 'profile.streetno', 'profile.street', 'profile.suburb', 'profile.state', 'profile.postcode', 'bids.bid_amount')
					 -> where ('bids.loan_id', '=', $loan_id)
					 -> orderBy ('profile.lname', 'ASC')
					 -> get(); // Returns array.
	}

	/** private methods for CREATE **/

	/**
	 * Validates and saves submitted "createContract" data
	 *
	 * @param array $data The data to save
	 * @return boolean Success?
	 */
	private function submitCreateContract(array $data) {
		// 1. Filter the input data
		$filtered_data = $this->filterData($data, function($k, $v) {
			return array_key_exists($k, $this->createRules);
		});

		// 2. Create validation object and check against it
		$validator = Validator::make($filtered_data, $this->createRules);

		if ($validator->fails()) {
			var_dump("validation failed");
			$this->setErrors($validator);
			return false;
		}

		// 3. Submit to database
		$table = DB::table('contracts');

		// check if the row exists
		if ($table->where('offer_id', '=', $data['offer_id'])->count('*') > 0) {
			var_dump("row exists");
			return $table->where('offer_id', '=', $data['offer_id'])->update($filtered_data);
		}

		var_dump("row not exists");

		return $table->insert($filtered_data);
	}

	/**
	 * Filters data to only the fields we want
	 *
	 * @param array $data The data to filter
	 * @return array Filtered data array
	 */
	private function filterData(array $data, callable $function) {
		$output = array();

		foreach ($data as $key => $value) {
			if ($function($key, $value)) {
				$output[$key]=$value;
			}
		}

		return $output;
	}

	/**
	 * Sets error messages from validation object
	 *
	 * @param \Illuminate\Validation\Validator $validator The validator object to get errors from
	 */
	private function setErrors(\Illuminate\Validation\Validator $validator) {
		$messages = $validator->messages()->all();

		foreach ($messages as $message) {
			$this->errors[] = $message;
		}
	}

	/** private methods for FINALISE **/

	/**
	 * Checks that the user has ticked the "I wish to finalise" checkbox
	 *
	 * @param array $data
	 * @return bool
	 */
	private function checkFinaliseInput(array $data) {
		if (!array_key_exists('i_agree', $data)) {
			$this->errors[] = "You must agree to finalise the contract before continuing";
			return false;
		}

		return true;
	}

	/**
	 * Updates the contract records to be "complete"
	 *
	 * @param int   $loan_id
	 * @param array $lenders
	 * @return bool
	 */
	private function finaliseContract(array $lenders) {
		$bids = array_map(function($el) {
			return $el->bid_id;
		}, $lenders);

		$updates = DB::table('contracts')->whereRaw("offer_id IN (" . implode(',', $bids) . ")")->update(array('status' => 'complete'));
		if ($updates < 1) {
			echo "no rows updated";
		}
		return $updates > 0;
	}

}
