var loc;

jQuery(document).ready(function($) {
	navigator.geolocation.getCurrentPosition(setLocation);
	
	$('#check_in_btn').click(function(event) {
		event.preventDefault();
		checkIn();
	});
});

function setLocation(pos) {
	loc = pos.coords;
	getVenues();
}

function getVenues(query) {
	$.ajax({
		type: 'GET',
		url: 'inc/get_venues.php',
		data: 'lat=' + loc.latitude + '&lon=' + loc.longitude + '&query=' + query,
		dataType: 'html',
		cache: false,
		success: function(rsp) { $('#venue_list').empty().append(rsp).listview('refresh'); }
	});
}

function getVenueInfo(event, venue_id) {
	event.preventDefault();
	$.ajax({
		type: 'GET',
		url: 'inc/get_venue_info.php',
		data: 'VENUE_ID=' + venue_id,
		dataType: 'html',
		cache: false,
		success: function(rsp) { displayVenueInfo(rsp) }
	});
}

function displayVenueInfo(rsp) {
	if(rsp) {
		var venue_obj = jQuery.parseJSON(rsp);
		$('#venue_id').val(venue_obj.response.venue.id);
		$('#venue_name').empty().append(venue_obj.response.venue.name);
		$.mobile.changePage('#venue_page', { transition: 'slide' });
	}
}

function checkIn() {
	$.ajax({
		type: 'POST',
		url: 'inc/check_in.php',
		data: 'lat=' + loc.latitude + '&lon=' + loc.longitude + '&VENUE_ID=' + $('#venue_id').val() + '&comments=' + $('#comments').val() + '&venue=' + $('#venue_name').text(), 
		dataType: 'html',
		cache: false,
		success: function() { getDeals(); }
	});
}

function getDeals() {
	$.ajax({
		type: 'GET',
		url: 'inc/get_fs_specials.php',
		data: 'VENUE_ID=' + $('#venue_id').val(), 
		dataType: 'html',
		cache: false,
		success: function(rsp) { displayDeals(false, rsp); }
	})
	$.ajax({
		type: 'GET',
		url: 'inc/get_groupon_deals.php',
		data: 'lat=' + loc.latitude + '&lon=' + loc.longitude + '&venue=' + $('#venue_name').text(), 
		dataType: 'html',
		cache: false,
		success: function(rsp) { displayDeals(true, rsp); }
	});
	$.mobile.changePage('#deals_page', { transition: 'slide' });
}

function displayDeals(groupon_deal, rsp) {
	if(groupon_deal) {
		$('#groupon_deal_text').empty().append(rsp);
	} else {
		$('#fs_special_text').empty().append(rsp);
	}
}