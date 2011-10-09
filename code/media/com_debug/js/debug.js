window.addEvent('domready', function(){	
	var cookie = new Hash.Cookie('debug');
	//Make resizable vertically
	if(!cookie.has('position')) cookie.set('position', 80);
	var y = cookie.get('position').toFloat();
	$('container').setStyle('height', Math.max(10, Math.min(90, y))+'%');
	$('debug').setStyle('height', (100-y) + '%');

    var bodyHeight = document.body.clientHeight, content = $('content-box'), maxHeight = content && content.getPosition().y, minLimit = parseInt((maxHeight || bodyHeight*0.2) + 130, 10), maxLimit = parseInt(bodyHeight*0.8, 10), cursor;
    
	new Element('div', {
		'id': 'debug-handle',
		'class': 'resize-handle-ns',
		'styles': {
			'top': '80%',
			'cursor': 'ns-resize',
			'height': '6px',
			'margin-top': '-2px',
			'position': 'absolute',
			'width': '100%',
			'z-index': '2'
		}
	}).inject($('debug'), 'before');    

	$('debug-handle').makeDraggable({
		//style: false,
		
		limit: {
		    x: false,
		    y: [minLimit, maxLimit]
		},
		modifiers: {
			x: false,
			y: 'top'
		},
		onBeforeStart: function(element){
			var coor = element.getCoordinates(); 
			bodyHeight = document.body.clientHeight;
			maxHeight = content && content.getPosition().y;
			this.options.limit.y = [(maxHeight || bodyHeight*0.2) + 130, bodyHeight*0.8];
			element.setStyle('top', coor.top+2);
		},
		onDrag: function(element){
			var height = this.element.getStyle(this.options.modifiers.y).toFloat(), offset = height+3, percent = (height/bodyHeight)*100;
			$('container').setStyle('height', percent + '%');
			$('debug').setStyle('height', (100-percent) + '%');
			cookie.set('position', percent);
			
			//Reached minimum height, change cursor
			if(this.limit.y[1] == this.value.now.y) {
			    element.setStyle('cursor', 'n-resize');
			    document.body.setStyle('cursor', 'n-resize');
			//Reached maximum height, change cursor again
			} else if(this.limit.y[0] == this.value.now.y) {
			    element.setStyle('cursor', 's-resize');
			    document.body.setStyle('cursor', 's-resize');
			//No limit reached, set cursor to standard
			} else {
			    element.setStyle('cursor', 'ns-resize');
			    document.body.setStyle('cursor', 'ns-resize');
			}
			
			window.fireEvent('resize');
		},
		onComplete: function(element){
			var height = $('container').getSize().y, percent = (height/bodyHeight)*100;
			element.setStyle('top', percent + '%');
			document.body.setStyle('cursor', '');
		}
	});
	$('debug-handle').setStyle('top', ( (($('container').getSize().y)/bodyHeight)*100 ) + '%');
	
	//Set cursor depending on boundaries
	var barY = $('debug-handle').getCoordinates().top + 2;
	if(maxLimit == barY) {
	    cursor = 'n-resize';
	} else if(minLimit == barY) {
	    cursor = 's-resize';
	} else {
	    cursor = 'ns-resize';
	}
	$('debug-handle').setStyle('cursor', cursor);
	
	if(cookie.get('hidden')) {
		$$('#debug', '#debug-handle').setStyle('display', 'none');
		$('container').setStyle('height', '');
	}
	
	//Height fix for scrollbars and such
	var doubletap;
	window.addEvent('resize', function(){
	    var element = $('debug').getElement('.current');
	    if(element) {
	        element.setStyle('height', document.body.clientHeight - element.getPosition().y);
	        // we may need to double tap
	        clearTimeout(doubletap);
	        doubletap = setTimeout(function(){
	            element.setStyle('height', document.body.clientHeight - element.getPosition().y);
	        }, 10);
	    }
	});
	//Another fix for chromatable
	$('tabs-debug').getElements('dt').addEvent('click', function(){
	    window.fireEvent('resize', [], 0.1);
	});
	
	window.fireEvent('resize');
	//To prevent flash of faulty height
	document.head.grab(new Element('style', {text: '#debug .current {height: '+(document.body.clientHeight-$('container').getSize().y-43)+'px}'}));
	
	var toggle = function(){
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
	};
	window.addEvent('keypress', function(event){
	    if(event.target !== document.body || event.key !== 'd') return;

	    toggle();
	});
	$('debug').getElement('.close').addEvent('click', function(event){
	    event.stop();

	    toggle();
	});
});