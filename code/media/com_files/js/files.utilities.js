
if(!Files) var Files = {};

Files.Filesize = new Class({
	Implements: Options,
	options: {
		units: ['Bytes', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb']
	},
	initialize: function(size, options) {
		this.setOptions(options);
		this.size = size;
	},
	humanize: function() {
		var i = 0, size = this.size;
		while (size >= 1024) {
			size /= 1024;
			i++;
		}
		return (i === 0 ? size : size.toFixed(2)) + ' ' + this.options.units[i];
	}
});

Files.getUrl = function(dict) {
	dict = dict || {};

	dict.option = dict.option || 'com_files';
	dict.view = dict.view || 'files';
	dict.format = dict.format || 'json';
	if (dict.container !== false && !dict.container && Files.container) {
		dict.container = Files.container.slug;
	} else {
		delete dict.container;
	}

	if (dict.format == 'html') {
		delete dict.format;
	}

	return '?'+new Hash(dict).filter(function(value, key) {
		return typeof value !== 'function';
	}).toQueryString();
};