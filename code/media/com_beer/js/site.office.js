window.addEvent('domready', function() {
	var myLatlng = new google.maps.LatLng($("map_canvas").getAttribute('rel'));
	var myOptions = {
		zoom: 8,
		center: myLatlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
});