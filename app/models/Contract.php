<?php

class Contract extends Eloquent {

	protected $table = 'contracts';
	protected $primaryKey = 'contract_id';
	protected $fillable = array('contract_id', 'offer_id', 'status', 'interest_type', 'install_freq', 'num_install',
								'val_install', 'day_install', 'start_date', 'end_date', 'prepayment_rule', 'days_late',
								'late_percent', 'late_max', 'security_tf', 'security', 'security_value', 'default_extra',
								'default_legal');

	public $timestamps = false;

	private $rules = array(
		'offer_id'        => array('required', 'integer'),
		'interest_type'   => array('required', 'in:flat,compound'),
		'num_install'     => array('required', 'integer'),
		'install_freq'    => array('required', 'in:daily,weekly,fortnightly,monthly'),
		'val_install'     => array('required', 'integer'),
		'day_install'     => array('required', 'alpha_dash'),
		'start_date'      => array('required', 'date'),
		'end_date'        => array('required', 'date'),
		'prepayment_rule' => array('required'),
		'days_late'       => array('required', 'integer'),
		'late_percent'    => array('required', 'integer'),
		'late_max'        => array('required', 'integer'),
		'security_tf'     => array('required', 'integer'),
		'security'        => array('required_if:security_tf,1', 'alpha_dash'),
		'security_value'  => array('required_if:security_tf,1', 'integer'),
		'default_extra'   => array('required', 'integer'),
		'default_legal'   => array('required', 'integer'),
	);

	public static function boot() {
		parent::boot();

		// event bindings
		self::saving(function ($contract) {
			/* @var $contract Contract */
			return $contract->isValid();
		});
	}

	public function isValid() {
		$validator = Validator::make($this->attributesToArray(), $this->rules);

		if ($validator->fails()) {
			Session::remove('messages');

			foreach ($validator->getMessageBag()->getMessages() as $message) {
				Session::push('messages', $message[0]);
			}

//			debug(Session::get('messages')); exit;
			return false;
		}


		return true;
	}

}
