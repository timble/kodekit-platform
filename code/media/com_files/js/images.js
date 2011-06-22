
var Files = Files || {};
Files.Images = {};

Files.Images.App = new Class({
	Implements: [Events, Options],

	_tmpl_cache: {},
	active: null,
	options: {
		editor: null,
		preview: 'image-details',
		tree: {
			theme: '',
			div: '',
			adopt: ''
		},
		container: {
			parent_button: false,
			element: 'images-canvas',
			cookie: false,
			layout: 'image'
		}
	},

	initialize: function(options) {
		this.setOptions(options);

		this.editor = this.options.editor;
		this.preview = document.id(this.options.preview);

		this.setContainer();
		this.setTree();

	},
	setContainer: function() {
		var opts = this.options.container;
		var that = this;
		$extend(opts, {
			'onClickParent': function(e) {
				if (that.tree.selected.parent) {
					that.tree.select(that.tree.selected.parent);
				}
			},
			'onClickImage': function(e) {
				var target = document.id(e.target).getParent('.files-node');
				target.getParent().getChildren().removeClass('active');
				target.addClass('active');
				var row = target.retrieve('row');
				var copy = $extend({}, row);
				copy.template = 'details';

				document.id('url').set('value',  Files.path+'/'+row.path);

				that.preview.empty();

				copy.render().inject(that.preview);
			}
		});
		this.container = new Files.Container(this.options.container.element, opts);
	},
	setTree: function() {
		var tree_opts = this.options.tree;
		$extend(tree_opts, {
			onClick: function(node) {
				this.navigate('/'+ (node && node.id ? node.id : ''));
			}.bind(this),
			onAdopt: function(id) {
				var hash = window.location.hash;
				var selected = null;
				if (hash.substr(1, 2) == '/' && hash.substr(2) != '/') {
					selected = this.get(hash.substr(3));
				}
				selected = selected || this.root;

				this.select(selected);
			},
			root: {
				text: '/',
				data: {
					url: '#/'
				}
			}
		});
		this.tree = new Files.Tree(tree_opts);
	},
	navigate: function(path) {
		this.active = path;

		var is_root = path === '/';

		document.id('path-active').set('text', is_root ? '' : this.active);
		$$('.file-basepath').set('value', is_root ? '' : this.active);

		this.preview.empty();
		this.container.reset(is_root);

		var that = this;
		this.folder = new Files.Folder({'path': path}, null, {type: 'image'});
		this.folder.getChildren(function(resp) {
			$each(resp, function(el) {
				var cls = Files[el.type.capitalize()];
				var row = new cls(el);
				that.container.insert(row, 'last');
			});
		},
		null,
		{'type': 'image'});

		window.location.hash = '#'+path;
	},
	getPath: function() {
		return this.active;
	},
	getImageString: function() {
		var src = document.id('url').getValue();
		var attrs = {};
		['align', 'alt', 'title'].each(function(id) {
			var value = document.id(id).getValue();
			if (value) {
				attrs[id] = value;
			}
		});
		if (document.id('caption').getValue()) {
			attrs['class'] = 'caption';
		}

		var str = '<img src="'+src+'" ';
		var parts = [];
		$each(attrs, function(value, key) {
			parts.push(key+'="'+value+'"');
		});
		str += parts.join(' ')+' />';

		return str;
	},
	insertImage: function() {
		var image = this.getImageString();
		window.parent.jInsertEditorText(image, this.editor);
	}
});