<?php
	session_start();

	/*
	if (isset($_POST['VENUE_ID'])) {
		$params = array(
			'venueId' => $_POST['VENUE_ID'],
			'venue' => $_POST['venue'],
			'll' => $_POST['lat'].','.$_POST['lon'],
			'llAcc' => '1',
			'broadcast' => 'public',
			'oauth_token' => $_SESSION['token']
		);
		
		if($_POST['comments'] != '')
			$params['shout'] = $_POST['comments'];

		$encoded_params = array();
 
		foreach ($params as $k => $v)
			$encoded_params[] = urlencode($k).'='.urlencode($v);
			
		$param_string = implode('&', $encoded_params);

		$url = 'https://api.foursquare.com/v2/checkins/add';
		
		$ch = curl_init();
	 	curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 
		$data = curl_exec($ch);

		curl_close($ch);
	}
	*/

?>