
Files.App = new Class({
	Implements: [Events, Options],

	_tmpl_cache: {},
	active: null,
	options: {
		thumbnails: true,
		types: null,
		tree: {
			div: 'files-tree',
			theme: ''
		},
		grid: {
			element: 'files-grid',
			batch_delete: '#files-batch-delete'
		},
		paginator: {
			element: 'files-paginator'
		},
		pathway: {
			element: 'files-pathway'
		}
	},

	initialize: function(options) {
		this.setOptions(options);
		
		if (this.options.types !== null) {
			this.options.grid.types = this.options.types;
			Files.state.types = this.options.types; 
		}

		this.setPathway();
		this.setGrid();
		this.setTree();
		this.setPaginator();
		
		this.setInitialFolder();
		
		if (this.options.thumbnails) {
			this.addEvent('afterSelect', function(resp) {
				this.setThumbnails();
			});
		}
	},
	setInitialFolder: function() {
		var hash = window.location.hash.substr(1);
		this.navigate(hash ? hash : '/');
	},
	setPathway: function() {
		var opts = this.options.pathway;
		var that = this;
		$extend(opts, {
			'onClickItem': function(el) {
				var path = el.get('data-path');
				that.navigate(path);
			}
		});
		this.pathway = new Files.Pathway(opts.element, opts);
		
		this.addEvent('afterNavigate', function(path) {
			if (path) {
				this.pathway.setPath(path);
			}
		}.bind(this));
	},
	setPaginator: function() {
		var opts = this.options.paginator;
		$extend(opts, {
			'state' : Files.state,
			'defaults' : Files.state_default,
			'onClickPage': function(el) {
				Files.state.limit = el.get('data-limit');
				Files.state.offset = el.get('data-offset');
				this.navigate();
			}.bind(this),
			'onChangeLimit': function(limit) {
				Files.state.limit = limit;
				Files.state.offset = 0;
				this.navigate();
			}.bind(this)
		});
		this.paginator = new Files.Paginator(opts.element, opts);
		
		var that = this;
		that.addEvent('afterSelect', function(response) {
			that.paginator.setData({
				limit: response.limit,
				offset: response.offset,
				total: response.total
			});
			that.paginator.setValues();
		});
	},
	setGrid: function() {
		var opts = this.options.grid;
		$extend(opts, {
			'onClickFolder': function(e) {
				var target = document.id(e.target);
				var path = target.getParent('.files-node').retrieve('path');
				if (path) {
					this.navigate('/'+path);
				}
			}.bind(this),
			'onClickImage': function(e) {
				var target = document.id(e.target);
				var img = target.getParent('.files-node').retrieve('row').image;
				if (img) {
					SqueezeBox.open(img, {handler: 'image'});
				}
			},
			'onDeleteNode': function(node) {
				if (node.type == 'folder') {
					var item = this.tree.get(node.path);
					if (item) {
						item.remove();
					}
				}
			}.bind(this),
			'onSwitchLayout': function(layout) {
				if (layout === 'icons' && this.options.thumbnails) {
					this.setThumbnails();
				}
			}.bind(this)
		});
		this.grid = new Files.Grid(this.options.grid.element, opts);
	},
	setTree: function() {
		var opts = this.options.tree,
			that = this;
		$extend(opts, {
			onClick: function(node) {
				if (node.id || node.data.url) {
					that.navigate('/'+ (node && node.id ? node.id : ''));
				}
			},
			root: {
				text: Files.container.title,
				data: {
					url: '#/'
				}
			}
		});
		this.tree = new Files.Tree(opts);
		this.tree.fromUrl(Files.getUrl({view: 'folders', 'tree': '1', 'limit': '0'}));
		
		this.addEvent('afterNavigate', function(path) {
			that.tree.selectPath(path);
		});
		
		var ContainerTree = new Class({
			Extends: Files.Tree,
			addItem: function(item) {
				if (item.id == Files.container.id) {
					return;
				}

				this.root.insert({
					text: item.title,
					data: {
						id: item.slug,
						type: 'container'
					}
				});
			}
		});
		this.containertree = new ContainerTree({
			div: 'files-containertree',
			theme: this.options.tree.theme,
			mode: 'files',
			root: {
				text: 'Other Containers'
			},
			onClick: function(node) {
				if (node.data && node.data.type === 'container') {
					window.location =  window.location.pathname+Files.getUrl({format: 'html', container: node.data.id});
					return;
				}
			}
		});
		
		new Request.JSON({
			url: Files.getUrl({view: 'containers', limit: 0, sort: 'title'}),
			onSuccess: function(response) {
				$each(response.items, this.containertree.addItem.bind(this.containertree));
			}.bind(this)
		}).get();
	},
	navigate: function(path) {
		this.fireEvent('beforeNavigate', path);
		if (path) {
			if (this.active) {
				// Reset states if we are changing folders
				Files.state.setDefaults();
			}
			this.active = path;
		}

		var is_root = this.active === '/';

		$$('.file-basepath').set('value', is_root ? '' : this.active);
		this.grid.reset();

		var that = this;
		this.folder = new Files.Folder({'path': this.active});
		this.folder.getChildren(function(resp) {
			that.response = resp;
			that.grid.insertRows(resp.items);
			
			that.fireEvent('afterSelect', resp);

		}, null, Files.state);

		window.location.hash = '#'+this.active;
	
		this.fireEvent('afterNavigate', path);
	},
	getPath: function() {
		return this.active;
	},
	setThumbnails: function() {
		var nodes = this.grid.nodes;
		if (Files.Template.layout === 'icons' && nodes.getLength()) {
			var url = Files.getUrl({
				view: 'thumbnails',
				offset: Files.state.offset, 
				limit: Files.state.limit,
				folder: this.active
			});
			new Request.JSON({
				url: url,
				method: 'get',
				onSuccess: function(response, responseText) {
					var thumbs = response.items;
					nodes.each(function(node) {
						if (node.type !== 'image') {
							return;
						}
						var name = node.name;

						var img = node.element.getElement('img.image-thumbnail');
						img.set('src', thumbs[name] ? thumbs[name].thumbnail : Files.blank_image);
					});
				}
			}).send();
		}
	}
});