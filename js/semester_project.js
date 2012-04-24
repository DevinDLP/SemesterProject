var loc;
var myMap;
var geocoder;
var marker;

$(document).ready(function() {
	navigator.geolocation.getCurrentPosition(setLocation);
	geocoder = new google.maps.Geocoder();
	
	$('#check_in_btn').click(function(event) {
		event.preventDefault();
		checkIn();
	});
	
	$('#refresh_list').click(function(event) {
		event.preventDefault();
		location.reload();
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
				$('#venue_list').empty().append('<li data-role="list-divider"><h1>Nearby Venues</h1></li>').append(rsp).listview('refresh');
				$('.venue_link').each(function(index) {
					$(this).click(function(event) {
						event.preventDefault();
						getVenueInfo($(this).attr('alt'));
					});
				});
			} else {
				$('#venue_list').empty().append('<li>No Venues Nearby</li>').listview('refresh');
			}
		}
	});
}

function getVenueInfo(venue_id) {
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
		var address = '';
		var lat = loc.latitude;
		var lon = loc.longitude;
		
		$('#venue_name').empty();
		$('#vi_address').empty();
		$('#vi_phone').empty();
		
		if(venue_obj.response.venue.location.address)
			address += venue_obj.response.venue.location.address;
		if(venue_obj.response.venue.location.city)
			address += ' ' + venue_obj.response.venue.location.city;
		if(venue_obj.response.venue.location.state)
			address += ', ' + venue_obj.response.venue.location.state;
		if(venue_obj.response.venue.location.postalCode)
			address += ' ' + venue_obj.response.venue.location.postalCode;
		
		$('#venue_id').val(venue_obj.response.venue.id + '');
		$('#venue_name').append(venue_obj.response.venue.name + '');

		if(venue_obj.response.venue.categories[0])
			$('#venue_icon').attr('src', venue_obj.response.venue.categories[0].icon.prefix + venue_obj.response.venue.categories[0].icon.sizes[2] + venue_obj.response.venue.categories[0].icon.name);
		if(address != '')
			$('#vi_address').append(address + '');
		if(venue_obj.response.venue.contact.formattedPhone)
			$('#vi_phone').append(venue_obj.response.venue.contact.formattedPhone + '');
		else
			$('#vi_phone').append('N/A');

		if(venue_obj.response.venue.location.lng && venue_obj.response.venue.location.lat) {
			lat = venue_obj.response.venue.location.lat;
			lon = venue_obj.response.venue.location.lng;
		} else if(venue_obj.response.venue.location.address) {
			geocoder.geocode( { address: address }, function(results, status) {
				if(status == google.maps.GeocoderStatus.OK) {
					lat = results[0].geometry.location.latitude;
					lon = results[0].geometry.location.longitude;
				}
			});
		}
		
		if(!myMap) {
			myMap = new google.maps.Map(document.getElementById('map_canvas'), {
				zoom: 18,
				center: new google.maps.LatLng(lat, lon),
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});
			
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(lat, lon),
				map: myMap
			});
		} else {
			myMap.setCenter(new google.maps.LatLng(lat, lon));
			marker.setPosition(new google.maps.LatLng(lat, lon));
		}
		
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
		$('#groupon_deal_text').empty().append('<li data-role="list-divider"><h1>Groupon Deals</h1></li>').listview('refresh');
		var groupon_obj = $.parseJSON(rsp);
		
		if(groupon_obj.announcementTitle)
			$('#groupon_deal_text').append('<li><a href="' + rsp.dealURL + '">' + rsp.announcementTitle + '</a></li>').listview('refresh');
		else
			$('#groupon_deal_text').append('<li>No Groupon deals for this location.</li>').listview('refresh');
	} else if(deal_type == 'fs_special') {
		$('#fs_special_text').empty().append('<li data-role="list-divider"><h1>Foursquare Specials</h1></li>').listview('refresh');
		var fs_obj = $.parseJSON(rsp);
		
		if(fs_obj.response.specials.count > 0) {
			for(special in fs_obj.response.specials.items)
				$('#fs_special_text').append(special.message).listview('refresh');
		} else {
			$('#fs_special_text').append('<li>No Foursquare specials for this location.</li>').listview('refresh');
		}
	}
}