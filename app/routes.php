<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


//route to homepage
Route::get('/', array('as' => 'index', 'uses'=>'HomeController@welcome'));
//route to register page
Route::get('register', function(){
	return View::make('register');
});
Route::get('about',function(){
	return View::make('infopages.about');
});
Route::get('hworks',function(){
	return View::make('infopages.hworks');
});

//route to thank for registration page
Route::get('thankyou',function(){
	return View::make('thankyou');
});

Route::get('register_comf', function() {
	return View::make('register_comf');
});
//route to do regsiter controller
Route::post('doRegister', array('uses'=>'HomeController@doRegister'));
//route to process email verfication
Route::get('email_verify/{code}', 'HomeController@verify_email');
//route to process login
Route::post('login', array('uses'=>'HomeController@doLogin'));
//rout to execute logout
Route::get('logout','HomeController@logout');
//Route to verify email
Route::get('email_verify','HomeController@verify_email');
//Route to process password reminder
Route::get('password/reminder', array('uses'=>'RemindersController@getRemind'));
//Rout to process password reset
Route::get('password/reset/{token}', array('uses'=>'RemindersController@getReset'));
//Route to process password reminder form
Route::post('post',array('uses'=>'RemindersController@postRemind'));
//Route to porcess password reset form
Route::post('reset',array('uses'=>'RemindersController@postReset'));
Route::get('password/notify', array('uses'=> 'RemindersController@afterSend'));
Route::post('password/change', array('uses'=> 'HomeController@changePwd'));
//route to myprofile page
//Route::get('profile', array('before'=>'guest', 'as'=>'myprofile', 'uses' => 'UserProfileController@profile'));


Route::post('upload', array('uses'=> 'ImageController@postUploads'));
//Route::post('/upload/image','ImageController@postUpload');


//Route to open editprofile page
Route::get('editprofile', array('brfore'=>'guest', 'as'=>'editProfile', 'uses'=> 'UserProfileController@editProfile'));

//route to process save profile
Route::post('saveProfile', 'UserProfileController@saveProfile');

Route::get('editLender', function() {
	return View::make('editLender');
});

//Route::get('makebid/{loan_id}',array('before'=>'guest', 'as'=>'makebid', 'uses'=>'BidController@makeBid'));
//route to update myprofile form  FOR TEST PURPOSE
Route::get('myprofile_form', function(){
	return View::make('myprofile_form');
});

Route::post('mkbid/{loan_id}',array('before'=>'guest','as'=>'makebid','uses'=>'BidController@mkBid'));
//route to process save Loan
Route::post('saveLoan', 'LoanAppController@saveLoanApp');
//route to process save Lend Pref
Route::post('saveLendPref', 'LenderPrefController@saveLendPref');
Route::post('lend/saveLend', array('before'=>'guest', 'uses'=>'LenderPrefController@search'));

//route to process save financile profilef
Route::post('saveFinancialProfile','UserProfileController@saveFinancialProfile');

//Route to prcess the Ajax request to finish applying loan
Route::post('submitLoan','LoanAppController@applyLoans');
Route::post('cancelLoan','LoanAppController@cancelLoans');
Route::post('confirmLoan','LoanAppController@confirmLoans');


/***** after seperate pages ******/
Route::get('mytransaction',array('before'=>'guest','as'=>'mytransaction', 'uses'=>'UserProfileController@mytransaction'));
Route::get('myprofile',array('before'=>'guest','as'=>'profile', 'uses'=>'UserProfileController@profile'));
Route::get('borrow', array('before'=>'guest','as'=>'borrow', 'uses'=>'UserProfileController@borrow'));
Route::get('lend',array('before'=>'guest', 'uses'=>'LenderPrefController@rawLend'));
Route::get('confirm',array('before'=>'guest', 'uses'=>'UserProfileController@confirm'));
Route::get('contact',function() {
	return View::make('infopages.contact_us');
});
Route::get('policy', function() {
	return View::make('infopages.policy');
});
Route::get('term', function() {
	return View::make('infopages.terms_of_use');
});
Route::get('logContact',array('before'=>'guest', 'uses'=>'UserProfileController@logContact'));
Route::get('logPolicy', array('before'=>'guest', 'uses'=>'UserProfileController@logPolicy'));
Route::get('logTerm',array('before'=>'guest', 'uses'=>'UserProfileController@logTerm'));
Route::get('logAbout',array('before'=>'guest', 'uses'=>'UserProfileController@logAbout'));
Route::get('logHworks',array('before'=>'guest', 'uses'=>'UserProfileController@logHwork'));

/* Joel's loan offers */
Route::get('loanoffers',array('before'=>'guest', 'uses'=>'LoanOfferController@loanoffer'));
Route::get('cancelOffer/{offer_id}', array('before'=>'guest', 'uses'=>'LoanOfferController@deleteOffer'));
Route::get('cancelObid/{obid_id}', array('before'=>'guest', 'uses'=>'LoanOfferController@deleteObid'));
Route::get('offerBids/{offer_id}', array('before'=>'guest', 'uses'=>'LoanOfferController@offerBids'));
Route::get('acceptBid/{obid_id}/{offer_id}', array('before'=>'guest', 'uses'=>'LoanOfferController@acceptBid'));
Route::post('makeOffer', 'LoanOfferController@makeOffer');
Route::post('makeBid', 'LoanOfferController@makeBid');



/* Contracts by Clare */
/*
	GET = send request, receive page
	POST = send data with request, receive page
*/
Route::get('createContract/{loan_id}', array('as' => 'createContract', 'before' => 'guest', 'uses' => 'ContractController@createContract'));
Route::post( // match POST request
	'createContract/{loan_id}', // on this url
	array(
	 	'before'=>'guest', // before calling action, check auth status
	 	'uses'=>'ContractController@createContract' // use ContractController::createContract()  (Class::Method)
	)
);
Route::get('previewContract/{loan_id}', array('before' => 'guest', 'uses' => 'ContractController@previewContract'));
Route::post('previewContract/{loan_id}', array('before' => 'quest', 'uses' => 'ContractController@previewContract'));


/* TempStuff by Nhi */
Route::get('getLanguage', array('uses'=>'languageController@getLanguage'));
Route::post('saveLanguage', array('uses'=>'languageController@saveLanguage'));

/* Search Loans */
Route::post('lend/search', array('before'=>'guest', 'uses'=>'LenderPrefController@reload'));
Route::post('lend/allLoans', array('before'=>'guest', 'uses'=>'LenderPrefController@allLoans'));
Route::post('lend/sort', array('before'=> 'guest', 'uses'=> 'LenderPrefController@sort'));
Route::get('mb',function(){
	return View::make('mkbid');
});

Route::get('cancelLoan/{loan_id}', array('before'=>'guest', 'uses'=>'TransactionController@deleteLoan'));
Route::get('cancelBid/{bid_id}', array('before'=>'guest', 'uses'=>'TransactionController@deleteBid'));
Route::get('claimLoan/{loan_id}', array('before'=>'guest', 'uses'=>'TransactionController@claimLoan'));
Route::get('detailedLoan/{loan_id}', array('before'=>'guest', 'uses'=>'UserProfileController@detailedLoan'));
Route::get('matchedLoan/{loan_id}', array('before'=>'guest', 'uses'=>'UserProfileController@matchedLoan'));
Route::get('otherUsers/{user_id}', array('before'=>'guest', 'as'=>'otherUsers','uses'=>'UserProfileController@otherUsers'));
Route::post('addComment', array('uses'=>'UserProfileController@addComment'));
Route::get('deleteComment/{comment_id}/{commenter_id}', array('before'=>'guest', 'uses'=>'UserProfileController@deleteComment'));

/* New user edit profile*/
Route::get('newUserProfile', array('as'=>'newUserProfile', 'uses'=>'UserProfileController@newUserProfile'));
Route::post('saveNewProfile', array('as'=>'saveNewProfile','uses'=>'UserProfileController@saveNewProfile'));

/* Cindy  Individual feature */
Route::get('calculator' ,array('before'=>'guest','uses'=>'caluController@evaluate'));

/* Peipei Individual feature */
Route::get('vis' ,array('before'=>'guest','uses'=>'visController@load'));

/* Hongzhe Li Individual feature */
Route::get('transfer', array('before'=>'guest', 'uses'=>'transferController@loadPage'));
Route::get('transferPanel/{bid_id}',array('before'=>'guest', 'uses'=>'transferController@seeRequest'));
Route::post('transferPanel/sell', array('before'=>'guest', 'uses'=>'transferController@requestTransfer'));
Route::post('transferPanel/cancel', array('before'=>'guest', 'uses'=>'transferController@cancelTransfer'));
Route::post('transfer/all', array('before'=>'guest', 'uses'=>'transferController@allTransfer'));
Route::post('transfer/search', array('before'=>'guest', 'uses'=>'transferController@searchTransfer'));
Route::post('transfer/offer',array('before'=>'guest', 'uses'=>'transferController@wantAccept'));
Route::get('transfer/accepted/{bidId}/{newId}', array('before'=>'guest', 'uses'=>'transferController@acceptTransfer'));
