var Page = new Class({

	Implements: Options,

	options: {
		
		sidebar: 'components-inner',
		panel: 'types',
		active: '[name=type_option]'
		
	},

	initialize: function(options){

		this.setOptions(options);

		this.togglers	= $(this.options.sidebar).getElements('a');
		this.types		= $(this.options.panel).getChildren();
		this.active		= document.getElement(this.options.active);

		if(this.active) this.active = this.active.get('value');

		this.togglers.each(function(toggle){
			
			var component_name = toggle.getProperty('data-component'), type = this.types.filter(function(type){
				return type.getProperty('data-component') == this;
			}, component_name)[0];
			
			if(type.getProperty('data-component') != this.active) {
				type.setStyle('display', 'none');
			} else {
				toggle.addClass('active selected');
			}

			toggle.addEvent('click', function(event){

				new Event(event).stop();

				this.types.setStyle('display', 'none');
				type.setStyle('display', 'block');
				toggle.getParent().getParent().getElements('a.active').removeClass('active');
				toggle.addClass('active');

			}.bindWithEvent(this));

		}, this);

	}

});