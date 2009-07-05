window.addEvent('domready', function() { 
	$('country').addEvent('change', function() {
		new Ajax('index.php?option=com_beer&view=states&format=ajax&region='+this.value, {
			method: 'get',
			update: 'statecontainer'
		}).request();
	});
});