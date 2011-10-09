
if(!Files) var Files = {};

Files.Tree = new Class({
	Extends: MooTreeControl,
	Implements: [Options],
	options: {
		mode: 'folders',
		title: '',
		grid: true,
		onClick: $empty,
		onAdopt: $empty,
		adopt: null,
		root: {
			open: true
		}
	},
	initialize: function(options) {
		this.setOptions(options);

		this.onAdopt = this.options.onAdopt;

		this.parent(this.options, this.options.root);

		if (options.adopt) {
			this.adopt(options.adopt);
		}
		
		if (this.options.title) {
			this.setTitle(this.options.title);
		}
	},
	setTitle: function(title) {
		if (!this.title_element) {
			this.title_element = new Element('h3').inject(document.id(this.options.div), 'top');
		}
		this.title = title;
		this.title_element.set('text', title);
	},
	/**
	 * We need to duplicate this because in the latest Mootree noClick argument is removed.
	 */
	select: function(node, noClick) {
		if (!$chk(noClick)) {
			this.onClick(node); node.onClick(); // fire click events
		}
		if (this.selected === node) return; // already selected
		if (this.selected) {
			// deselect previously selected node:
			this.selected.select(false);
			this.onSelect(this.selected, false);
		}
		// select new node:
		this.selected = node;
		node.select(true);
		this.onSelect(node, true);
		
		while (true) {
			if (!node.parent || node.parent.id == null) {
				break;
			}
			node.parent.toggle(false, true);
			
			node = node.parent;
		}		
	},
	adopt: function(id, parentNode) {
		this.parent(id, parentNode);

		this.onAdopt(id, parentNode);
	},
	fromUrl: function(url) {
		var that = this,
			root = this.root,
			insertNode = function(item, parent) {
				var node = parent.insert({
					text: item.name,
					id: item.path,
					data: {
						url: '#/'+item.path,
						type: 'folder'
					}
				});
				if (item.children) {
					$each(item.children, function(item) {
						insertNode(item, node);
					});
				}
				
				return node;
			};
		
		new Request.JSON({
			url: url,
			method: 'get',
			onSuccess: function(response) {
				if (response.total) {
					$each(response.items, function(item) {
						insertNode(item, that.root);
					});
				}
				if (Files.app && Files.app.active) {
					that.selectPath(Files.app.active);
				}
				
			}
		}).send();
	},
	selectPath: function(path) {
		if (path) {
			var node = this.get(path.substr(1));
			if (node) {
				this.select(node, true);
			}
			else {
				this.select(this.root, true);
			}
		}
	}
});