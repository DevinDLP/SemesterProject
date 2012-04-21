<?php
	session_start();
	
	if (isset($_GET['VENUE_ID'])) {
		$params = array(
			'oauth_token' => $_SESSION['token'],
			'v' => date('Ymd')
		);

		$encoded_params = array();
 
		foreach ($params as $k => $v)
			$encoded_params[] = urlencode($k).'='.urlencode($v);

		$url = 'https://api.foursquare.com/v2/venues/'.$_GET['VENUE_ID'].'?'.implode('&', $encoded_params);
		
		$ch = curl_init();
	 	curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.X.Y.Z Safari/525.13.');
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 
		$data = curl_exec($ch);
		
		curl_close($ch);
		
		echo $data;
	}
?>