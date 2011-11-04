
if (!Files) var Files = {};

Files.State = new Class({
	Implements: Options,
	data: {},
	defaults: {},
	options: {
		defaults: {}
	},
	initialize: function(options) {
		this.setOptions(options);
		
		if (this.options.data) {
			$extend(this.data, this.options.data);
		}
		if (this.options.defaults) {
			$extend(this.defaults, this.options.defaults);
			$extend(this.data, this.defaults);
		}
	},
	getData: function() {
		return this.data;
	},
	setDefaults: function() {
		this.set(this.defaults);
		
		return this;
	},
	set: function(key, value) {
		if ($type(key) == 'object') {
			$extend(this.data, key);
		} else {
			this.data[key] = value;
		}
		
		return this;
	},
	get: function(key, def) {
		return this.data[key] || def;
	},
	unset: function(key) {
		delete this.data[key];
	}
});