
if(!Files) var Files = {};

Files.App = new Class({
	Implements: [Events, Options],

	_tmpl_cache: {},
	active: null,
	title: '',
	cookie: null,
	options: {
		persistent: true,
		thumbnails: true,
		types: null,
		container: null,
		active: null,
		title: 'files-title',
		state: {
			data: {
				limit: 0,
				offset: 0
			},
			defaults: {}
		},
		tree: {
			div: 'files-tree',
			theme: ''
		},
		grid: {
			element: 'files-grid',
			batch_delete: '#files-batch-delete',
			icon_size_slider: 'files-thumbs-size'
		},
		paginator: {
			element: 'files-paginator'
		}
	},

	initialize: function(options) {
		this.setOptions(options);
		
		if (this.options.persistent && this.options.container) {
			this.cookie = 'com.files.container.'+this.options.container;
		}
		
		//this.setContainerTree();
		this.setState();
		this.setGrid();
		this.setPaginator();
		
		var hash = window.location.hash.substr(2);
		if (window.location.hash.substr(1, 1) == '!' && hash) {
			pieces = hash.split(':', 2);
			this.options.container = pieces[0];
			this.options.active = pieces[1] || '/';
		}
		
		if (this.options.title) {
			this.options.title = document.id(this.options.title);
		}
		
		if (this.options.container) {
			this.setContainer(this.options.container);
		}

		if (this.options.thumbnails) {
			this.addEvent('afterSelect', function(resp) {
				this.setThumbnails();
			});
		}
	},
	setState: function() {
		this.fireEvent('beforeSetState');
		
		var opts = this.options.state;
		this.state = new Files.State(opts);
	
		this.fireEvent('afterSetState');
	},
	setHash: function() {
		this.fireEvent('beforeSetHash');
		
		var hash = '!';
		if (Files.container) {
			hash += Files.container.slug+':';
		}
		if (this.active) {
			hash += this.active;
		}
		window.location.hash = hash;
		
		this.fireEvent('afterSetHash', {hash: hash});
	},
	setContainer: function(container) {
		new Request.JSON({
			url: Files.getUrl({view: 'container', slug: container, container: false}),
			method: 'get',
			onSuccess: function(response) {
				var item = response.item;
				
				this.fireEvent('beforeSetContainer', {container: item});
				
				Files.container = item;
				Files.path = item.relative_path;
				Files.baseurl = Files.sitebase + '/' + Files.path;

				this.active = '';
				window.location.hash = '';
				
				if (Files.container.parameters.upload_extensions) {
					this.uploader.settings.filters = [
					     {title: 'All Files', extensions: Files.container.parameters.upload_extensions.join(',')}
	    			];
				}
				if (Files.container.parameters.upload_maxsize) {
					this.uploader.settings.max_file_size = Files.container.parameters.upload_maxsize;
					document.id('upload-max-size').set('html', new Files.Filesize(Files.container.parameters.upload_maxsize).humanize());
				}
				
				if (this.options.types !== null) {
					this.options.grid.types = this.options.types;
					this.state.set('types', this.options.types); 
				}
				
				this.fireEvent('afterSetContainer', {container: item});

				this.grid.reset();
				
				this.setTree();
				
				this.active = this.options.active || '/';
				this.options.active = '';
				this.navigate(this.active);
			}.bind(this)
		}).send();
	},
	setContainerTree: function() {
		var ContainerTree = new Class({
			Extends: Files.Tree,
			addItem: function(item) {
				/*if (item.id == Files.container.id) {
					return;
				}*/

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
					this.setContainer(node.data.id);return;
					window.location =  window.location.pathname+Files.getUrl({format: 'html', container: node.data.id});
					return;
				}
			}.bind(this)
		});
		
		new Request.JSON({
			url: Files.getUrl({view: 'containers', limit: 0, sort: 'title'}),
			onSuccess: function(response) {
				$each(response.items, this.containertree.addItem.bind(this.containertree));
			}.bind(this)
		}).get();
	},
	setPaginator: function() {
		this.fireEvent('beforeSetPaginator');
		
		var key = this.cookie ? this.cookie+'.paginator.state' : null;

		if (key) {
			var cookie = JSON.decode(Cookie.read(key), true);
			
			if (cookie && cookie.limit) {
				this.state.set('limit', cookie.limit);
			}
			if (cookie && cookie.offset) {
				this.state.set('offset', cookie.offset);
			}
		}
		
		var opts = this.options.paginator,
			state = this.state;
		
		$extend(opts, {
			'state' : state,
			'onClickPage': function(el) {
				this.state.set('limit', el.get('data-limit'));
				this.state.set('offset', el.get('data-offset'));
				this.navigate();
			}.bind(this),
			'onChangeLimit': function(limit) {
				this.state.set('limit', limit);
				this.state.set('offset', 0);
				if (key) {
					Cookie.write(key, JSON.encode({'limit': limit}));
				}
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
		
		this.fireEvent('afterSetPaginator');
	},
	setGrid: function() {
		this.fireEvent('beforeSetGrid');
		
		var opts = this.options.grid,
			key = this.cookie+'.grid.layout';

		if (this.cookie) {
			opts.layout = Cookie.read(key);
			var size_key = this.cookie+'.grid.icon.size',
				size = Cookie.read(size_key);
			if (size) {
				opts.icon_size = size;
			}
			opts.onAfterSetIconSize = function(context) {
				Cookie.write(size_key, context.size); 
			};
		}
		
		$extend(opts, {
			'onAfterInsertRows': function() {
				if (Files.Template.layout == 'icons') {
					this.setIconSize(this.options.icon_size);
				}
				
				if (opts.icon_size_slider) {
					document.id(opts.icon_size_slider).set('value', this.options.icon_size);
				}
				
		    },
			'onClickFolder': function(e) {
				var target = document.id(e.target),
					path = target.getParent('.files-node').retrieve('path');
				if (path) {
					this.navigate('/'+path);
				}
			}.bind(this),
			'onClickImage': function(e) {
				var target = document.id(e.target),
					img = target.getParent('.files-node').retrieve('row').image;
				
				if (img) {
					SqueezeBox.open(img, {handler: 'image'});
				}
			},
			'onClickFile': function(e) {
				var target = document.id(e.target),
					row = target.getParent('.files-node').retrieve('row'),
					copy = $extend({}, row);
				
				copy.template = 'file_preview';

				SqueezeBox.open(copy.render(false), {
					handler: 'adopt',
					size: {x: 300, y: 200}
				});
			},
			'onAfterDeleteNode': function(context) {
				var node = context.node;
				if (node.type == 'folder') {
					var item = this.tree.get(node.path);
					if (item) {
						item.remove();
					}
				}
			}.bind(this),
			'onAfterSetLayout': function(context) {
				var layout = context.layout;
				if (layout === 'icons' && this.grid && this.options.thumbnails) {
					this.setThumbnails();
				}
				if (key) {
					Cookie.write(key, layout);
				}
			}.bind(this)
		});
		this.grid = new Files.Grid(this.options.grid.element, opts);
		
		this.fireEvent('afterSetGrid');
	},
	setTree: function() {
		this.fireEvent('beforeSetTree');
		
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

		this.fireEvent('afterSetTree');
	},
	navigate: function(path) {
		this.fireEvent('beforeNavigate', path);
		if (path) {
			if (this.active) {
				// Reset offset if we are changing folders
				this.state.set('offset', 0);
			}
			this.active = path;
		}

		var is_root = this.active === '/';

		this.grid.reset();

		var that = this;
		this.folder = new Files.Folder({'path': this.active});
		this.folder.getChildren(function(resp) {
			that.response = resp;
			that.grid.insertRows(resp.items);
			
			that.fireEvent('afterSelect', resp);

		}, null, this.state.getData());

		this.setHash();
	
		this.fireEvent('afterNavigate', path);
	},
	getPath: function() {
		return this.active;
	},
	setThumbnails: function() {
		var nodes = this.grid.nodes,
			that = this;
		if (Files.Template.layout === 'icons' && nodes.getLength()) {
			var url = Files.getUrl({
				view: 'thumbnails',
				offset: this.state.get('offset'), 
				limit: this.state.get('limit'),
				folder: this.active
			});
			new Request.JSON({
				url: url,
				method: 'get',
				onSuccess: function(response, responseText) {
					var thumbs = response.items;
					
					that.fireEvent('beforeSetThumbnails', {thumbnails: thumbs, response: response});
					
					nodes.each(function(node) {
						if (node.type !== 'image') {
							return;
						}
						var name = node.name;

						var img = node.element.getElement('img.image-thumbnail');
						img.set('src', thumbs[name] ? thumbs[name].thumbnail : Files.blank_image);
					});

					that.fireEvent('afterSetThumbnails', {thumbnails: thumbs, response: response});
				}
			}).send();
		}
		
	},
	setTitle: function(title) {
		this.fireEvent('beforeSetTitle', {title: title});
		
		this.title = title;
		
		if (this.options.title) {
			this.options.title.set('html', title);
		}
		
		this.fireEvent('afterSetTitle', {title: title});
	}
});