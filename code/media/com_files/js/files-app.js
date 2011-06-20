
Files.App = new Class({
	Implements: [Events, Options],

	_tmpl_cache: {},
	active: null,
	options: {
		tree: {
			theme: '',
			div: '',
			adopt: ''
		},
		container: {
			element: 'files-container',
			batch_delete: '#toolbar-files-delete a'
		}
	},

	initialize: function(options) {
		this.setOptions(options);
		this.setContainer();
		this.setTree();
	},
	setContainer: function() {
		var opts = this.options.container;
		$extend(opts, {
			'onClickParent': function(e) {
				if (this.tree.selected.parent) {
					this.tree.select(this.tree.selected.parent);
				}
			}.bind(this),
			'onClickFolder': function(e) {
				var target = document.id(e.target);
				var path = target.getParent('.files-node').retrieve('path');
				var node = path ? this.tree.get(path) : false;

				if (path) {
					this.tree.select(node);
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
			}.bind(this)
		});
		this.container = new Files.Container(this.options.container.element, opts);
	},
	setTree: function() {
		var tree_opts = this.options.tree;
		this.tree = new Files.Tree({
			div: tree_opts.div,
			theme: tree_opts.theme,
			adopt: tree_opts.adopt,
			onClick: function(node) {
				this.navigate('/'+ (node && node.id ? node.id : ''));
			}.bind(this),
			onAdopt: function(id) {
				var hash = window.location.hash;
				var selected = null;
				if (hash.substr(1, 2) == '!/' && hash.substr(2) != '/') {
					selected = this.get(hash.substr(3));
				}
				selected = selected || this.root;

				this.select(selected);
			},
			root: {
				text: '/',
				data: {
					url: '#!/'
				}
			}
		});
	},
	navigate: function(path) {
		this.active = path;

		var is_root = path === '/';

		document.id('path-active').set('text', is_root ? '' : this.active);
		$$('.file-basepath').set('value', is_root ? '' : this.active);

		this.container.reset(is_root);

		var that = this;
		this.folder = new Files.Folder({'path': path});
		this.folder.getChildren(function(resp) {
			$each(resp, function(el) {
				var cls = Files[el.type.capitalize()];
				var row = new cls(el);
				that.container.insert(row, 'last');
			});
		});

		window.location.hash = '#!'+path;
	},
	getPath: function() {
		return this.active;
	}
});