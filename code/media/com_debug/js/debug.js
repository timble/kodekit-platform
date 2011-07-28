window.addEvent('domready', function(){	
	var cookie = new Hash.Cookie('debug');
	//Make resizable vertically
	if(!cookie.has('position')) cookie.set('position', 80);
	var y = cookie.get('position').toFloat();
	$('container').setStyle('height', Math.max(10, Math.min(90, y))+'%');
	$('debug').setStyle('height', (100-y) + '%');

	new Element('div', {
		'id': 'debug-handle',
		'class': 'sidebar-resizer-horizontal',
		'styles': {
			'top': '80%'
		}
	}).inject($('debug'), 'before');
	return;
	$('debug-handle').makeDraggable({
		//style: false,
		
		modifiers: {
			x: false,
			y: 'top'
		},
		onBeforeStart: function(element){
			var coor = element.getCoordinates();
			element.setStyle('top', coor.top+3);
		},
		onDrag: function(element){
			var height = this.element.getStyle(this.options.modifiers.y).toFloat(), offset = height+3, total = document.body.getSize().y, percent = (height/total)*100;
			$('container').setStyle('height', percent + '%');
			$('debug').setStyle('height', (100-percent) + '%');
			cookie.set('position', percent);
			window.fireEvent('resize');
		},
		onComplete: function(element){
			var height = $('container').getSize().y+3, total = document.body.getSize().y, percent = (height/total)*100;
			element.setStyle('top', percent + '%');
		}
	});
	$('debug-handle').setStyle('top', ( (($('container').getSize().y+3)/document.body.getSize().y)*100 ) + '%');
	
	if(cookie.get('hidden')) {
		$$('#debug', '#debug-handle').setStyle('display', 'none');
		$('container').setStyle('height', '');
	}
	return;
	window.fireEvent('resize');
	
	var myKeyboardEvents = new Keyboard({
		defaultEventType: 'keyup', 
		events: { 
			'ctrl+i': function(){
				if(cookie.get('hidden')) {
					$$('#debug', '#debug-handle').setStyle('display', '');
					$('container').setStyle('height', cookie.get('position').toFloat() + '%');
					cookie.erase('hidden');
				} else {
					$$('#debug', '#debug-handle').setStyle('display', 'none');
					$('container').setStyle('height', '');
					cookie.set('hidden', true);
				}
				window.fireEvent('resize');
			}
		}
	});
});