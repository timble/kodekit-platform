
Files.Uploader = new Class({
	Extends: FancyUpload2,
	options: {
		method: 'POST',
		fieldName: 'file',
		fileSizeMin: 0
	}
});

Files.Uploader.File = new Class({
	Extends: FancyUpload2.File,
	render: function() {
		if (this.invalid) {
			if (this.validationError) {
				var msg = MooTools.lang.get('FancyUpload', 'validationErrors')[this.validationError] || this.validationError;
				this.validationErrorMessage = msg.substitute({
					name: this.name,
					size: Swiff.Uploader.formatUnit(this.size, 'b'),
					fileSizeMin: Swiff.Uploader.formatUnit(this.base.options.fileSizeMin || 0, 'b'),
					fileSizeMax: Swiff.Uploader.formatUnit(this.base.options.fileSizeMax || 0, 'b'),
					fileListMax: this.base.options.fileListMax || 0,
					fileListSizeMax: Swiff.Uploader.formatUnit(this.base.options.fileListSizeMax || 0, 'b')
				});
			}
			this.remove();
			return;
		}

		this.addEvents({
			'start': this.onStart,
			'progress': this.onProgress,
			'complete': this.onComplete,
			'error': this.onError,
			'remove': this.onRemove
		});

		this.info = new Element('td', {'class': 'file-info'});
		var remove = new Element('a', {
			//'class': 'file-remove',
			href: '#',
			html: MooTools.lang.get('FancyUpload', 'remove'),
			title: MooTools.lang.get('FancyUpload', 'removeTitle'),
			events: {
				click: function() {
					this.remove();
					return false;
				}.bind(this)
			}
		});
		var row = new Element('tr', {'class': 'file'});
		row.adopt(
			new Element('td', {'class': 'file-size', 'html': Swiff.Uploader.formatUnit(this.size, 'b')}),
			new Element('td', {'class': 'file-remove'}).adopt(remove),
			new Element('td', {'class': 'file-name', 'html': MooTools.lang.get('FancyUpload', 'fileName').substitute(this)}),
			this.info
		);
		this.element = row.inject(this.base.list);
	}
});