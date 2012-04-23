<?php
	session_start();
	
	if (isset($_GET['lat']) && isset($_GET['lon'])) {
		$params = array(
			'll' => $_GET['lat'].','.$_GET['lon'],
			'oauth_token' => $_SESSION['token'],
			'v' => date('Ymd')
		);
		
		if($_GET['query'] != 'undefined')
			$params['query'] = $_GET['query'];

		$encoded_params = array();
 
		foreach ($params as $k => $v)
			$encoded_params[] = urlencode($k).'='.urlencode($v);

		$url = 'https://api.foursquare.com/v2/venues/search?'.implode('&', $encoded_params);
		
		$ch = curl_init();
	 	curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.X.Y.Z Safari/525.13.');
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 
		$data = json_decode(curl_exec($ch));
		
		curl_close($ch);

		foreach ($data->response->venues as $venue) {
			echo '<li class="venue_li">';
			echo '<a href="#" onclick="getVenueInfo(event, \''.$venue->id.'\');">';
			if($venue->categories)
				echo '<img src="'.$venue->categories[0]->icon->prefix.$venue->categories[0]->icon->sizes[2].$venue->categories[0]->icon->name.'" alt="icon" />';
			else
				echo '<img src="https://foursquare.com/img/categories/building/default_64.png" alt="icon" />';
			echo '<h3>'.$venue->name.'</h3>';
			echo '<p> ';
			if(isset($venue->location->address)) echo $venue->location->address;
			echo '</p>';
			echo '</a></li>';
		}
	}
?>