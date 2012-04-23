<?php
	session_start();
	//session_unset();
	require_once('inc/globals.php');
	require_once('inc/oauth.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" /> 
		<title>DIG4503 - Devin de la Parte - Semester Project</title> 
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript"  src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
<?php if(isset($_SESSION['token'])) { ?>
		<script src="js/semester_project.js"></script>
<?php } ?>
		<style type="text/css">
            @import url('http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css');
            @import url('css/styles.css');
        </style>
	</head> 
	<body> 

	<div id="primary_container">
	<div id="jquery_mobile_content">
		<div data-role="page" id="main_page" class="page">
			<div data-role="header"  data-position="inline" class="header">
				
			</div>
			<div data-role="content" class="content">	
<?php if(!isset($_SESSION['token'])) { ?>
				<a id="fs_login" href="https://foursquare.com/oauth2/authenticate?client_id=<?php echo FS_CLIENT_ID ?>&response_type=code&redirect_uri=<?php echo FS_REDIRECT_URI ?>" data-role="button"><img src="img/fs_logo.png" alt="foursquare login" /> Login</a> 
<?php } else { ?>
				<div data-role="fieldcontain">
					<input type="text" name="zip" id="zip" value="Zip Code" />
				</div>					
				<h2>Venues Nearby</h2>
				<div class="list_container">
					<ul id="venue_list" data-role="listview" data-inset="true" class="list">
						<li></li>
					</ul>
				</div>
<?php } ?>
			</div>
		</div>
		
		<div data-role="page" id="venue_page" class="page">
			<div data-role="header" data-position="inline" class="header">
				<a href="#" data-role="button" data-rel="back" data-icon="arrow-l">Back</a>
					<h1>Check In</h1>
				<a id="check_in_btn" href="#" data-role="button" data-icon="check">Check In</a>
			</div>
			<div data-role="content">
				<div id="venue_info_container">
					<span id="venue_name"></span>
				</div>
				<div id="comment_container" data-role="fieldcontain">
					<input id="venue_id" type="hidden" value="" />
					<label for="textarea">Comment:</label><br />
					<textarea name="textarea" id="comments"></textarea>
				</div>
			</div>
		</div>
		
		<div data-role="page" id="deals_page">
			<div data-role="header"  class="header">

			</div>
			<div data-role="content">
				<div id="deal_info_container">
					<div id="fs_special_text"></div>
					<div id="groupon_deal_text"></div>
				</div>
			</div>
		</div>
		
		</div>
		</div>

	</body>
</html>