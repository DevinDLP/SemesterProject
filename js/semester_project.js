var loc;

jQuery(document).ready(function($) {
	navigator.geolocation.getCurrentPosition(setLocation);
	
	$('#check_in_btn').click(function(event) {
		event.preventDefault();
		checkIn();
	});
	
	$('.unselected_input').focus(function(event) {
		$(this).val('');
		$(this).addClass('selected_input');
	});
	
	$('.unselected_input').blur(function(event) {
		if($(this).val() == '') {
			$(this).val($(this).attr('alt'));
			$(this).removeClass('selected_input');
		}
	});
	
	$('#refresh_list').click(function(event) {
		event.preventDefault();
		location.reload();
	});
	
	$('#done_btn').click(function(event) {
		event.preventDefault();
		$.mobile.changePage('#main_page', { transition: 'slide' });
	});
});

function setLocation(pos) {
	loc = pos.coords;
	$('#no_list').hide();
	$('#list_container').show();
	getVenues();
}

function getVenues(query) {
	$.ajax({
		type: 'GET',
		url: 'inc/get_venues.php',
		data: 'lat=' + loc.latitude + '&lon=' + loc.longitude + '&query=' + query,
		dataType: 'html',
		cache: false,
		success: function(rsp) { 
			if(rsp) {
				$('#venue_list').empty().append(rsp).listview('refresh');
			} else {
				$('#venue_list').empty().append('<li>No Venues Nearby</li>').listview('refresh');
			}
		}
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
		var venue_obj = $.parseJSON(rsp);
		$('#venue_id').val(venue_obj.response.venue.id);
		$('#venue_name').empty();
		$('#vi_address').empty();
		$('#vi_phone').empty();
		if(venue_obj.response.venue.categories[0])
			$('#venue_icon').attr('src', venue_obj.response.venue.categories[0].icon.prefix + venue_obj.response.venue.categories[0].icon.sizes[2] + venue_obj.response.venue.categories[0].icon.name);
		if(venue_obj.response.venue.location.address)
			$('#vi_address').append(venue_obj.response.venue.location.address + '<br/>');
		if(venue_obj.response.venue.location.city)
			$('#vi_address').append(venue_obj.response.venue.location.city);
		if(venue_obj.response.venue.location.state)
			$('#vi_address').append(', ' + venue_obj.response.venue.location.state);
		if(venue_obj.response.venue.location.postalCode)
			$('#vi_address').append(' ' + venue_obj.response.venue.location.postalCode);
		if(venue_obj.response.venue.contact.formattedPhone)
			$('#vi_phone').append(venue_obj.response.venue.contact.formattedPhone);
		$('#venue_name').append(venue_obj.response.venue.name);
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
		success: function(rsp) { displayDeals('fs_special', rsp); }
	})
	$.ajax({
		type: 'GET',
		url: 'inc/get_groupon_deals.php',
		data: 'lat=' + loc.latitude + '&lon=' + loc.longitude + '&venue=' + $('#venue_name').text(), 
		dataType: 'html',
		cache: false,
		success: function(rsp) { displayDeals('groupon_deal', rsp); }
	});
	$.mobile.changePage('#deals_page', { transition: 'slide' });
}

function displayDeals(deal_type, rsp) {
	if(deal_type == 'groupon_deal') {
		var groupon_obj = $.parseJSON(rsp);
		if(groupon_obj.announcementTitle) { 
			$('#groupon_deal_text').empty().append(rsp.announcementTitle);
		} else {
			$('#groupon_deal_text').empty().append('<li>No Groupon deals for this location.</li>');
		}
	} else if(deal_type == 'fs_special') {
		$('#fs_special_text').empty().append(rsp);
		var fs_obj = $.parseJSON(rsp);
		if(fs_obj.response.specials.count > 0) {
			for(special in fs_obj.response.specials.items) {
				$('#fs_special_text').empty().append(special.message);
			}
		} else {
			$('#fs_special_text').empty().append('<li>No Foursquare specials for this location.</li>');
		}
	}
}