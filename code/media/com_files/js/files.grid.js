
if(!Files) var Files = {};

Files.Grid = new Class({
	Implements: [Events, Options],

	options: {
		onClickFolder: $empty,
		onClickFile: $empty,
		onClickImage: $empty,
		onDeleteNode: $empty,
		onSwitchLayout: $empty,
		switcher: '.files-layout-controls',
		layout: false,
		batch_delete: false,
		icon_size: 200,
		types: null // null for all or array to filter for folder, file and image
	},

	initialize: function(container, options) {
		this.setOptions(options);

		this.nodes = new Hash();
		this.container = document.id(container);

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
			var box = e.target.getParent('.files-node-shadow');
			that.checkNode(box.retrieve('row'));
		}; 
		this.container.addEvent('click:relay(div.imgOutline)', fireCheck.bind(this));
        this.container.addEvent('click:relay(input.files-select)', fireCheck.bind(this));

		/*
		 * Delete events
		 */
		var deleteEvt = function(e) {
			if (e.stop) {
				e.stop();
			}

			var path = e.target.getParent('.files-node').retrieve('path');
			this.erase(path);
		}.bind(this);

		this.container.addEvent('click:relay(.delete-node)', deleteEvt);		
		
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
					folder_count = 0,
					checkboxes = this.container.getElements('input[type=checkbox]:checked.files-select')
					.filter(function(el) {
						if (el.checked) {
							if (el.getParent('.files-node').hasClass('files-folder')) {
								folder_count++;
							} else {
								file_count++;
							}
							return true;
						}
					});
				
				var str = [];
				if (folder_count) {
					str.push(folder_count+' folder'+(folder_count > 1 ? 's' : ''));
				}

				if (file_count) {
					str.push(file_count+' file'+(file_count > 1 ? 's' : ''));
				}

				str = str.join(' and ');
				
				if (!checkboxes.length || !confirm('There '+(checkboxes.length > 1 ? 'are' : 'is')+' '+str+' to be deleted. Are you sure?')) {
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
		    node = row.element.getElement('.files-node'),
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
				failure = function() {
					this.fireEvent('afterDeleteNodeFail', {node: node});
				}.bind(this);
			node['delete'](success, failure);
		}
	},
	render: function() {
		this.fireEvent('beforeRender');
		
		this.container.empty();
		this.root = new Files.Grid.Root();
		this.root.element.injectInside(this.container);

		this.renew();
		
		this.fireEvent('afterRender');
	},
	renderObject: function(object, position) {
		var position = position || 'alphabetical';

		this.fireEvent('beforeRenderObject', {object: object, position: position});
		
		object.element = object.render();
		object.element.store('path', object.path);
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
			
			if (this.options.icon_size) {
				this.setIconSize(this.options.icon_size);
			}

			this.fireEvent('afterInsertNode', {node: object, position: position});
		}
	},
	/**
	 * Insert multiple rows, possibly coming from a JSON request
	 */
	insertRows: function(rows) {
		this.fireEvent('beforeInsertRows', {rows: rows});
		
		$each(rows, function(row) {
			var cls = Files[row.type.capitalize()];
			var item = new cls(row);
			this.insert(item, 'last');
		}.bind(this));
		
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
			
			Files.Template.layout = layout;
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
		
		if (this.nodes.getKeys().length && Files.Template.layout == 'icons') {	
			this.container.getElements('.imgTotal').setStyles({
	            width: size + 'px',
	            height: (size * 0.75) + 'px'
	        });
	        this.container.getElements('.imgOutline .ellipsis').setStyle('width', size + 'px');
		}
		
    	this.fireEvent('afterSetIconSize', {size: size});
	}
});

Files.Grid.Root = new Class({
	Implements: Files.Template,
	template: 'container',
	initialize: function() {
		this.element = this.render();
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