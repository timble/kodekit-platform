/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

if(!Files) var Files = {};

Files.Grid = new Class({
	Implements: [Events, Options],
	layout: 'icons',
	options: {
		onClickFolder: $empty,
		onClickFile: $empty,
		onClickImage: $empty,
		onDeleteNode: $empty,
		onSwitchLayout: $empty,
		switcher: '.files-layout-controls',
		layout: false,
		batch_delete: false,
		icon_size: 150,
		types: null // null for all or array to filter for folder, file and image
	},

	initialize: function(container, options) {
		this.setOptions(options);

		this.nodes = new Hash();
		this.container = document.id(container);

        // Attach spinner events
        this.addEvents({
            afterReset: function(){
                this.spin();
            },
            afterInsertRows: function(){
                this.unspin();
            }
        });

		if (this.options.switcher) {
			this.options.switcher = document.getElement(this.options.switcher);
		}

		if (this.options.batch_delete) {
			this.options.batch_delete = document.getElement(this.options.batch_delete);
		}

		if (this.options.layout) {
			this.setLayout(this.options.layout);
		}
		this.render();
		this.attachEvents();
	},
	attachEvents: function() {

		var that = this,
			createEvent = function(selector, event_name) {
				that.container.addEvent(selector, function(e) {
					e.stop();
					that.fireEvent(event_name, arguments);
				});
			};
		createEvent('click:relay(.files-folder a.navigate)', 'clickFolder');
		createEvent('click:relay(.files-file a.navigate)', 'clickFile');
		createEvent('click:relay(.files-image a.navigate)', 'clickImage');

		/*
		 * Checkbox events
		 */
		var fireCheck = function(e) {
		    if(e.target.match('a.navigate')) {
		        return;
		    }
			if (e.target.get('tag') == 'input') {
				e.target.setProperty('checked', !e.target.getProperty('checked'));
			};
			box = e.target.match('.files-node') ? e.target :  e.target.getParent('.files-node');


			that.checkNode(box.retrieve('row'));
		};
		this.container.addEvent('click:relay(div.files-node)', fireCheck.bind(this));
        this.container.addEvent('click:relay(input.files-select)', fireCheck.bind(this));

		/*
		 * Delete events
		 */
		var deleteEvt = function(e) {
			if (e.stop) {
				e.stop();
			}

			box = e.target.match('.files-node') ? e.target :  e.target.getParent('.files-node');

			this.erase(box.retrieve('row').path);
		}.bind(this);

		this.container.addEvent('click:relay(.delete-node)', deleteEvt);

		that.addEvent('afterDeleteNodeFail', function(context) {
			var xhr = context.xhr,
				response = JSON.decode(xhr.responseText, true),
                error = Files.getResponseError(response);

			if (error) {
				alert(error);
			}
		});

		if (this.options.batch_delete) {
			var chain = new Chain(),
				chain_call = function() {
					chain.callChain();
				},
				that = this;

			this.addEvent('afterCheckNode', function() {
				var checked = this.container.getElements('input[type=checkbox]:checked');
				this.options.batch_delete.setProperty('disabled', !checked.length);
			}.bind(this));

			this.options.batch_delete.addEvent('click', function(e) {
				e.stop();

				var file_count = 0,
					files = []
					folder_count = 0,
					folders = [],
					checkboxes = this.container.getElements('input[type=checkbox]:checked.files-select')
					.filter(function(el) {
						if (el.checked) {
							var box = el.getParent('.files-node'),
								name = box.retrieve('row').name;
							
							if (el.getParent('.files-node').hasClass('files-folder')) {
								folder_count++;
								folders.push(name)
							} else {
								file_count++;
								files.push(name);
							}
							return true;
						}
					});

				var message = Files._("You selected following folders and files to be deleted. Are you sure?");
				$each(folders, function(folder) {
					message += '\n'+folder;
				});
				$each(files, function(file) {
					message += '\n'+file;
				});

				if (!checkboxes.length || !confirm(message)) {
					return false;
				}

				that.addEvent('afterDeleteNode', chain_call);
				that.addEvent('afterDeleteNodeFail', chain_call);

				checkboxes.each(function(el) {
					if (!el.checked) {
						return;
					}
					chain.chain(function() {
						deleteEvt({target: el});
					});
				});
				chain.chain(function() {
					that.removeEvent('afterDeleteNode', chain_call);
					that.removeEvent('afterDeleteNodeFail', chain_call);
					chain.clearChain();
				});
				chain.callChain();
			}.bind(this));
		}

		if (this.options.switcher) {
			var that = this;
			this.options.switcher.addEvent('change', function(e) {
				e.stop();

				var value = this.get('value');
				that.setLayout(value);
			});
		}

		if (this.options.icon_size) {
			var size = this.options.icon_size;
			this.addEvent('beforeRenderObject', function(context) {
				context.object.icon_size = size;
			});
		}
	},
	/**
	 * fire_events is used when switching layouts so that client events to
	 * catch the user interactions don't get messed up
	 */
	checkNode: function(row, fire_events) {
		var box = row.element,
		    node = row.element.match('.files-node') ? row.element : row.element.getElement('.files-node'),
			checkbox = box.getElement('input[type=checkbox]')
			;
		if (fire_events !== false) {
			this.fireEvent('beforeCheckNode', {row: row, checkbox: checkbox});
		}

		var old = checkbox.getProperty('checked');
        !old ? node.addClass('selected') : node.removeClass('selected');
		row.checked = !old;
		checkbox.setProperty('checked', !old);

		if (fire_events !== false) {
			this.fireEvent('afterCheckNode', {row: row, checkbox: checkbox});
		}

	},
	erase: function(node) {
		if (typeof node === 'string') {
			node = this.nodes.get(node);
		}
		if (node) {
			this.fireEvent('beforeDeleteNode', {node: node});
			var success = function() {
				if (node.element) {
					node.element.dispose();
				}

				this.nodes.erase(node.path);

				this.fireEvent('afterDeleteNode', {node: node});
			}.bind(this),
				failure = function(xhr) {
					this.fireEvent('afterDeleteNodeFail', {node: node, xhr: xhr});
				}.bind(this);
			node['delete'](success, failure);
		}
	},
	render: function() {
		this.fireEvent('beforeRender');

		this.container.empty();
		this.root = new Files.Grid.Root(this.layout);
		this.root.element.injectInside(this.container);

		this.renew();

		this.fireEvent('afterRender');
	},
	renderObject: function(object, position) {
		var position = position || 'alphabetical';

		this.fireEvent('beforeRenderObject', {object: object, position: position});

		object.element = object.render(this.layout);
		object.element.store('row', object);

		if (position == 'last') {
			this.root.adopt(object.element, 'bottom');
		}
		else if (position == 'first') {
			this.root.adopt(object.element);
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
						this.root.adopt(object.element, 'bottom');
					}
				}
				else {
					this.root.adopt(object.element, 'bottom');
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

		this.fireEvent('afterRenderObject', {object: object, position: position});

		return object.element;
	},
	reset: function() {
		this.fireEvent('beforeReset');

		this.nodes.each(function(node) {
			if (node.element) {
				node.element.dispose();
			}
			this.nodes.erase(node.path);
		}.bind(this));

		this.fireEvent('afterReset');
	},
	insert: function(object, position) {
		this.fireEvent('beforeInsertNode', {object: object, position: position});

		if (!this.options.types || this.options.types.contains(object.type)) {
			this.renderObject(object, position);

			this.nodes.set(object.path, object);

			this.fireEvent('afterInsertNode', {node: object, position: position});
		}
	},
	/**
	 * Insert multiple rows, possibly coming from a JSON request
	 */
	insertRows: function(rows) {
		this.fireEvent('beforeInsertRows', {rows: rows});

		$each(rows, function(row) {
			if (row.data) {
				row = row.data;
			}
			var cls = Files[row.type.capitalize()];
			var item = new cls(row);
			this.insert(item, 'last');
		}.bind(this));

		if (this.options.icon_size) {
			this.setIconSize(this.options.icon_size);
		}

		this.fireEvent('afterInsertRows', {rows: rows});
	},
	renew: function() {
		this.fireEvent('beforeRenew');

		var folders = this.getFolders(),
			files = this.getFiles(),
			that = this,
			renew = function(node) {
				var node = that.nodes.get(node);

				if (node.element) {
					node.element.dispose();
				}
				that.renderObject(node, 'last');

				if (node.checked) {
					that.checkNode(node, false);
				}
			};
		folders.each(renew);
		files.each(renew);

		this.fireEvent('afterRenew');
	},
	setLayout: function(layout) {
		if (layout) {
			this.fireEvent('beforeSetLayout', {layout: layout});

			this.layout = layout;
			if (this.options.switcher) {
				this.options.switcher.set('value', layout);
			}

			this.fireEvent('afterSetLayout', {layout: layout});

			this.render();
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
	},
	setIconSize: function(size) {
		this.fireEvent('beforeSetIconSize', {size: size});

		this.options.icon_size = size;

		if (this.nodes.getKeys().length && this.layout == 'icons') {
			this.container.getElements('.files-node-thumbnail').setStyles({
	            width: size + 'px',
	            height: (size * 0.75) + 'px'
	        });
	        this.container.getElements('.files-node .ellipsis').setStyle('width', size + 'px');
		}

    	this.fireEvent('afterSetIconSize', {size: size});
	},
    spin: function(){
        document.id('files-grid').getElements('div').addClass('spinner');
    },
    unspin: function(){
        document.id('files-grid').getElements('div').removeClass('spinner');
    }
});

Files.Grid.Root = new Class({
	Implements: Files.Template,
	template: 'container',
	initialize: function(layout) {
		this.element = this.render(layout);
	},
	adopt: function(element, position) {
		position = position || 'top';
		var parent = this.element;
		if (this.element.get('tag') == 'table') {
			parent = this.element.getElement('tbody');
		}
		element.injectInside(parent, position);
	}
});
