window.addEvent('domready', function() 
{ 
	$('country').addEvent('change', function() 
	{
		new Ajax('index.php?option=com_profiles&view=states&region='+this.value, {
			method: 'get',
			update: 'statecontainer'
		}).request();
	});

	var myLatlng = new google.maps.LatLng(coordinate_lat, coordinate_lng);
    var myOptions = {
      zoom: 2,
      center: myLatlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    
    var map    = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    var marker = new google.maps.Marker({
        position: myLatlng, 
        map: map
    });
    
    google.maps.event.addListener(marker, 'click', function() {
        map.setZoom(12);
    });
});