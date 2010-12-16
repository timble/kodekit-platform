jQuery.noConflict();

/* MooTools version of jQuery.fn.tipTip */
var TipTip = Tips.extend({

	options: {
		onShow: function(){
		    this.fx.start(1);
		},
		onHide: function(tip){
		    this.fx.start(0);
		},
		className: 'tiptip',
		offsets: {
			x: 0,
			y: 6
		},
		fixed: true
	},
	
	initialize: function(elements, options){
		this.parent(elements, options);

		this.toolTip.setProperty('class', 'tip_bottom')
					.setProperty('id', 'tiptip_holder');
					
		this.arrow = new Element('div', {'id': 'tiptip_arrow'})
			.adopt(new Element('div', {'id': 'tiptip_arrow_inner'}))
			.injectTop(this.toolTip);

		this.fx = this.toolTip.effect('opacity', {
			duration: 300,
			wait: false
		}).set(0);
	},

	start: function(el){
	    this.wrapper.empty();
	    if (el.$tmp.myText){
	        this.text = new Element('div', {id: 'tiptip_content', 'class': this.options.className + '-text'}).inject(this.wrapper).setHTML(el.$tmp.myText);
	    }
	    $clear(this.timer);
	    this.timer = this.show.delay(this.options.showDelay, this);
	},

	position: function(element){
		var coordinates = {tooltip: this.toolTip.getCoordinates(), element: element.getCoordinates()},
			offset = (coordinates.element.width + coordinates.tooltip.width) / 2,
			left = coordinates.element.right - offset;

		this.toolTip.setStyles({
			'left': left + this.options.offsets.x,
			'top': coordinates.element.bottom + this.options.offsets.y
		});
	}
});


window.addEvent('domready', function(){
	var blocks	= $$('.equalize'),
		height	= blocks.map(function(block){
			var size = block.getSize();
			//For compat on both mt 1.1 and mt 1.2
			return size.size ? size.size.y : size.y;
		}).sort(function(a, b){
			return b-a;
		})[0];
	
	blocks.setStyle('height', height);

	$(document.body).removeClass('js-disabled').addClass('js-enabled');
	
	new TipTip('.tooltip');

	new SmoothScroll({links: '#skipto a'});

	var skipToFx = $('skipto').effect('opacity', {duration: 600, onComplete: function(){
		this.element.setStyle('visibility', 'visible');
	}});
	$('skipto').setStyles({
		opacity: 0,
		visibility: 'visible',
		backgroundColor: '#000'
	}).addEvents({
		mouseenter: function(){
			skipToFx.start(1);
		},
		mouseleave: function(){
			skipToFx.start('0');
		}
	});
	
	var scrollToTop = new Fx.Scroll(window, {duration: 600});
	$$('[href=#top]', '[href=#primary-content]').addEvent('click', function(event){
		scrollToTop.toTop();
		event.stop();
	}.bindWithEvent());
	
	$$('ul', 'ol').each(function(item){
		item.getFirst().addClass('first-child');
		item.getLast().addClass('last-child');
	});
});