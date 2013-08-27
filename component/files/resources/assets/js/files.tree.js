/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

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
				var path = parent.data.path ? parent.data.path+'/' : '';
				path += item.name;

				var node = parent.insert({
					text: item.name,
					id: path,
					data: {
						path: path,
						url: '#'+item.path,
						type: 'folder'
					}
				});

                node.div.main.setAttribute('title', node.div.text.innerText);

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
						if (item.data) {
							insertNode(item.data, that.root);
						}
					});
				}
				if (Files.app && Files.app.active) {
					that.selectPath(Files.app.active);
				}
                that.onAdopt(that.options.div, that.root);
			}
		}).send();
	},
	selectPath: function(path) {
		if (path !== undefined) {
			var node = this.get(path);
			if (node) {
				this.select(node, true);
			}
			else {
				this.select(this.root, true);
			}
		}
	}
});
