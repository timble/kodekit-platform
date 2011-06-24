

Files.Container = new Class({
	Implements: [Events, Options],

	options: {
		onClickParent: $lambda,
		onClickFolder: $lambda,
		onClickFile: $lambda,
		onClickImage: $lambda,
		onDeleteNode: $lambda,
		switcher: 'files-layout-switcher',
		cookie: 'com.files.view.files.switcher',
		layout: false,
		batch_delete: false,
		parent_button: true
	},

	initialize: function(container, options) {
		this.setOptions(options);

		this.nodes = new Hash();
		this.container = document.id(container);

		if (this.options.switcher) {
			this.options.switcher = document.id(this.options.switcher);
		}

		if (this.options.batch_delete) {
			this.options.batch_delete = document.getElement(this.options.batch_delete);
		}

		if (this.options.cookie) {
			this.setLayout(Cookie.read(this.options.cookie));
		}
		else if (this.options.layout) {
			this.setLayout(this.options.layout);
		}
		this.render();
		this.attachEvents();
	},
	render: function() {
		this.container.empty();
		this.root = new Files.Container.Root();
		this.root.element.injectInside(this.container);

		if (this.options.parent_button) {
			var style = this.parent_button ? this.parent_button.element.getStyle('display') : 'block';
			this.renderParent(style);
		}

		this.renew();
	},
	attachEvents: function() {
		this.createEvent('click:relay(.files-folder a.navigate)', 'clickFolder');
		this.createEvent('click:relay(.files-file a.navigate)', 'clickFile');
		this.createEvent('click:relay(.files-image a.navigate)', 'clickImage');

		var deleteEvt = function(e) {
			if (e.stop) {
				e.stop();
			}

			var path = e.target.getParent('.files-node').retrieve('path');
			this.erase(path);
		}.bind(this);

		this.container.addEvent('click:relay(.delete-node)', deleteEvt);

		this.container.addEvent('click:relay(input[type=checkbox])', function(e) {
			var row = e.target.getParent('.files-node').retrieve('row');
			row.checked = e.target.getProperty('checked');
		});

		if (this.options.batch_delete) {
			this.options.batch_delete.addEvent('click', function(e) {
				e.stop();
				var checkboxes = this.container.getElements('input[type=checkbox].files-select');
				checkboxes.each(function(el) {
					if (!el.checked) {
						return;
					}
					deleteEvt({target: el});
				}.bind(this));
			}.bind(this));
		}


		if (this.options.switcher) {
			var that = this;
			this.options.switcher.addEvent('change', function(e) {
				e.stop();
				var value = this.get('value');
				that.setLayout(value);
				that.render();
				Cookie.write(that.options.cookie, value);
			});
		}
	},
	createEvent: function(selector, event_name) {
		this.container.addEvent(selector, function(e) {
			e.stop();
			this.fireEvent(event_name, arguments);
		}.bind(this));
	},
	erase: function(node) {
		if (typeof node === 'string') {
			node = this.nodes.get(node);
		}
		if (node) {
			var success = function() {
				if (node.element) {
					node.element.dispose();
				}

				this.fireEvent('deleteNode', [node]);
				this.nodes.erase(node.path);
			}.bind(this);
			node['delete'](success);
		}
	},
	renderParent: function(style) {
		this.parent_button = new Files.Container.Parent();
		this.renderObject(this.parent_button, 'first');

		if (style) {
			this.parent_button.element.setStyle('display', style);
		}

		this.parent_button.element.getElements('a').addEvent('click', function() {
			this.fireEvent('clickParent', arguments);
		}.bind(this));
	},
	renderObject: function(object, position) {
		var position = position || 'alphabetical';

		object.element = object.render();
		object.element.store('path', object.path);
		object.element.store('row', object);

		if (position == 'last') {
			object.element.inject(this.root.element, 'bottom');
		}
		else if (position == 'first') {
			object.element.inject(this.root.element, 'top');
		}
		else {
			var index = this.nodes.filter(function(node){
				return node.type == object.type;
			}).getKeys();

			if (index.length === 0) {
				if (object.type === 'folder') {
					var keys = this.nodes.getKeys();
					if (keys.length) {
						// there are files so append it before the first file
						var target = this.nodes.get(keys[0]);
						object.element.inject(target.element, 'before');
					}
					else {
						object.element.inject(this.root.element, 'bottom');
					}
				}
				else {
					object.element.inject(this.root.element, 'bottom');
				}

			}
			else {
				index.push(object.path);
				index = index.sort();

				var obj_index = index.indexOf(object.path);
				var length = index.length;
				if (obj_index === 0) {
					var target = this.nodes.get(index[1]);
					object.element.inject(target.element, 'before');
				}
				else {
					var target = obj_index+1 === length ? index[length-2] : index[obj_index-1];
					target = this.nodes.get(target);
					object.element.inject(target.element, 'after');
				}
			}
		}

		return object.element;
	},
	reset: function(hide_parent) {
		this.nodes.each(function(node) {
			if (node.element) {
				node.element.dispose();
			}
			this.nodes.erase(node.path);
		}.bind(this));

		if (this.options.parent_button) {
			this.parent_button.element.setStyle('display', hide_parent === true ? 'none' : 'block');
		}
	},
	insert: function(object, position) {
		this.renderObject(object, position);
		this.nodes.set(object.path, object);

		this.fireEvent('insertNode', [object]);
	},
	renew: function() {
		var folders = this.getFolders(),
			files = this.getFiles();

		folders.each(function(folder) {
			var node = this.nodes.get(folder);
			if (node.element) {
				node.element.dispose();
			}
			this.renderObject(node, 'last');
		}.bind(this));
		files.each(function(file) {
			var node = this.nodes.get(file);
			if (node.element) {
				node.element.dispose();
			}
			this.renderObject(node, 'last');
			if (node.checked) {
				node.element.getElement('input[type=checkbox]').setProperty('checked', node.checked);
			}
		}.bind(this));
	},
	setLayout: function(layout) {
		if (['icons', 'details', 'image'].contains(layout)) {
			Files.Template.layout = layout;
			if (this.options.switcher) {
				this.options.switcher.set('value', layout);
			}
		}
	},
	getFolders: function() {
		return this.nodes.filter(function(node) {
			return node.type === 'folder';
		}).getKeys().sort();
	},
	getFiles: function() {
		return this.nodes.filter(function(node) {
			return node.type === 'file' || node.type == 'image';
		}).getKeys().sort();
	}
});

Files.Container.Parent = new Class({
	Implements: Files.Template,
	template: 'parent'
});

Files.Container.Root = new Class({
	Implements: Files.Template,
	template: 'container',
	initialize: function() {
		this.element = this.render();
	},
	adopt: function(element) {
		element.injectInside(this.element);
	}
});