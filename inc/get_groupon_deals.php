<?php
	session_start();
	
	if (isset($_GET['lat']) && isset($_GET['lon'])) {
		$params = array(
			'client_id' => 'bb7aee1becd6daeb4d16e256e680b39e4b04afbf',
			'lat' => $_GET['lat'],
			'lng' => $_GET['lon']
		);
		
		$encoded_params = array();
 
		foreach ($params as $k => $v)
			$encoded_params[] = urlencode($k).'='.urlencode($v);

		$url = 'http://api.groupon.com/v2/deals.json?'.implode('&', $encoded_params);
		
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