
var Files = {};

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