
Files.Pathway = new Class({
	Implements: [Options, Events],
	initialize: function(element, options) {
		this.setOptions(options);

		this.element = document.id(element);

		this.element.addEvent('click:relay(a)', function(e) {
			e.stop();
			this.fireEvent('clickItem', e.target);
		}.bind(this));

	},
	setPath: function(path) {
		var parts = path.substr(1) ? path.substr(1).split('/') : [],
			selected = parts[parts.length-1],
			el = this.element.empty(),
			current_path = '';
		
		if (parts.length == 0) {
			new Element('span', {
				'class': 'files-pathway-element',
				'text': 'Root'
			}).inject(el);
			return;
		}

		new Element('a', {
			'href': '#',
			'class': 'files-pathway-element',
			'text': 'Root',
			'data-path': '/'
		}).inject(el);
		
		var i = 0,
			separator = new Element('span', {
				'class': 'files-pathway-separator',
				'text': ''
			});
		
		$each(parts, function(value) {
			current_path += '/'+value;

			separator.clone().inject(el);
			if (i != parts.length-1) {
				new Element('a', {
					'class': 'files-pathway-element',
					'href': '#',
					'text': value,
					'data-path': current_path
				}).inject(el);				
			}
			else {
				new Element('span', {
					'class': 'files-pathway-element',
					'text': value
				}).inject(el);
			}
			i++;
		});
	}
});