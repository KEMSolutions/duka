<?php namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
//		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
//		return view('home');
		$this->getLayouts();
	}

	/**
	 * VERY TEMPORARY : guzzle request to https://kemsolutions.com/CloudServices/index.php/api/1/layouts
	 */
	private function getLayouts()
	{
		$expiresAt  = Carbon::now()->addWeek();
		$client = new Client();
		$data   = '' . 'hLEQPVB9OduNPC5zd3ErIRs4e1wap0Dn9SEzUXeaMyovxJbowhC6TOSY4ySRel8';
		$sig    = base64_encode(hash('sha512', $data, true));

		$response = $client->get('https://kemsolutions.com/CloudServices/index.php/api/1/layouts', [
			'headers' => ['X-Kem-User' => '1', 'X-Kem-Signature' => $sig]
		]);

		$layouts = json_decode($response->getBody()->getContents());
		dd($layouts);
	}

}
