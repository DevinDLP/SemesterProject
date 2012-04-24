<?php
	session_start();
	require_once('inc/globals.php');
	require_once('inc/oauth.php');
?>
<!DOCTYPE html>
<html>
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
		    @import url('css/styles.css');
            @import url('http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css');
            @import url('css/styles.css');
        </style>
	</head> 
	<body> 

		<div id="primary_container">
			<div id="jquery_mobile_content">
			
				<div data-role="page" id="main_page" class="page">
					<div data-role="header" data-position="inline" class="header" data-theme="b">
						<img src="img/fs_deals_logo.png" alt="Foursquare Deals" />
					</div>
					<div data-role="content" class="content">	
<?php if(!isset($_SESSION['token'])) { ?>
						<a id="fs_login" href="https://foursquare.com/oauth2/authenticate?client_id=<?php echo FS_CLIENT_ID ?>&amp;response_type=code&amp;redirect_uri=<?php echo FS_REDIRECT_URI ?>" data-role="button" data-theme="b">Log Into<br /><img src="img/fs_logo.png" alt="foursquare login" /></a> 
<?php } else { ?>
						<a id="refresh_list" href="#" data-role="button" data-icon="refresh" data-theme="b">Refresh Local Venue List</a>
						<div id="no_list">
							<ul data-role="listview" data-inset="true">
								<li><h3>No venues found, or your location services have been disabled.</h3><br/>Enable your browser's location services and activate your GPS for more accurate results.</li>
							</ul>
						</div>
						<div id="list_container">
							<ul id="venue_list" data-role="listview" data-inset="true">
								<li></li>
							</ul>
						</div>
<?php } ?>
					</div>
				</div>
				
				<div data-role="page" id="venue_page" class="page">
					<div data-role="header" data-position="inline" data-theme="b">
						<a href="#" data-role="button" data-rel="back" data-icon="arrow-l" data-theme="b">Back</a>
							<h3>Check In</h3>
						<a id="check_in_btn" href="#" data-role="button" data-icon="check" data-theme="b">Check In</a>
					</div>
					<div data-role="content" class="content">
						<div id="venue_info_container">
							<div id="venue_info_header">
								<img id="venue_icon" src="https://foursquare.com/img/categories/building/default_64.png" alt="icon" />
								<h2 id="venue_name"></h2>
							</div>
							<fieldset class="ui-grid-a">
								<div class="ui-block-a">Address:</div>
								<div class="ui-block-b" id="vi_address"></div>	   
								<div class="ui-block-a">Phone Number:</div>
								<div class="ui-block-b" id="vi_phone"></div>	  
							</fieldset>
						</div>
						<hr class="divider" />
						<fieldset data-role="fieldcontain">
							<input id="venue_id" type="hidden" value="" />
							<label for="comments">Comment:</label><br />
							<textarea cols="40" rows="8" name="textarea" id="comments"></textarea>
						</fieldset>
					</div>
				</div>
				
				<div data-role="page" id="deals_page" class="page">
					<div data-role="header" data-position="inline" data-theme="b">
						<h3>Available Deals</h3>
						<a id="done_btn" href="#" data-role="button" data-icon="check" data-theme="b" class="ui-btn-right">Done</a>
					</div>
					<div data-role="content">
						<div id="deal_info_container">
							<ul id="fs_special_text" data-role="listview" data-inset="true">
								<li></li>
							</ul>
							<ul id="groupon_deal_text" data-role="listview" data-inset="true">
								<li></li>
							</ul>
						</div>
					</div>
				</div>
				
			</div>
		</div>

	</body>
</html>