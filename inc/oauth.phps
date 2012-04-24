<?php
	if(isset($_GET['code'])) {
		$params = array(
			'client_id' => FS_CLIENT_ID,
			'client_secret' => FS_CLIENT_SECRET,
			'grant_type' => 'authorization_code',
			'redirect_uri' => FS_REDIRECT_URI,
			'code' => $_GET['code']
		);

		$encoded_params = array();
 
		foreach ($params as $k => $v)
			$encoded_params[] = urlencode($k).'='.urlencode($v);

		$url = 'https://foursquare.com/oauth2/access_token?'.implode('&', $encoded_params);

		$ch = curl_init();
	 	curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.X.Y.Z Safari/525.13.');
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 
		$data = json_decode(curl_exec($ch));
		
		curl_close($ch);
		
		$_SESSION['token'] = $data->access_token;
	}
?>