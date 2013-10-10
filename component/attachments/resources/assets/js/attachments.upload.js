if(!Attachments) var Attachments = {};

Attachments.Upload = new Class({
	Implements: Options,
	
	initialize: function(options){
		this.setOptions({
			list: document.id(options.container).getElement('ul.attachments'),
			extensions : options.extensions
		});
		
		var fileElement = this.options.list.getChildren()[0].getChildren()[0];
		if(fileElement)
		{
			fileElement.addEvent('change', this.onFileSelect.bind(this));
			fileElement.getParent('form').setProperties({
					'enctype': 'multipart/form-data',
					'encoding': 'multipart/form-data'// IE
				}
			);
		}
	},
	
	onFileSelect: function(event){
		if(!this.isValid(event.target)) 
		{
			this.reset(event.target);
			return false;
		}
		
		this.add(event.target);
	},
	
	add: function(target){
		target.setStyle('display', 'none').getParent().adopt(
			new Element('span', {text: target.value.replace(/c:\\fakepath\\/i, '')}),
			new Element('a', {text: 'x', class: 'btn btn-mini btn-danger'}).addEvent('click', function(event){
				event.stop();
				
				this.remove(event.target);
			}.bind(this))
		);
		
		this.options.list.grab(
			new Element('li').grab(this.clone(target))
		);
	},
	
	isValid: function(target){
		if(this.options.extensions == null || !this.options.extensions.length) {
			return true;
		}
		
		var matches = /(?:\.([^.]+))?$/.exec(target.value);
		var extension = matches.length ? matches[1] : '';

        extension = extension.toLowerCase();
		
		if(this.options.extensions.contains(extension)) {
			return true;
		}
		else
		{
			alert("The file of type '" + extension + "' is not allowed!\n"
					+ "Please add another file.");
			return false;
		}
	},
	
	remove: function(target){
		target.getParent().destroy();
	},
	
	reset: function(target) {
		this.clone(target).replaces(target);
	},
	
	clone: function(target) {
		return new Element('input', {
				type: 'file', 
				name: 'attachments[]', 
				accept: target.get('accept')
			}).addEvent('change', this.onFileSelect.bind(this));
	}
});